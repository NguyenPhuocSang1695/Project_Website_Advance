<?php
 include('../php/connect.php');

// Lấy mã đơn hàng từ URL
$orderID = isset($_GET['code_Product']) ? $_GET['code_Product'] : null;

if ($orderID) {
    // 1. Lấy thông tin tổng quan đơn hàng
    $sql_order = "SELECT o.OrderID, o.CreatedAt, o.OrderStatus, o.PaymentMethod, s.EstimatedDeliveryDate
                  FROM orders o
                  LEFT JOIN shipments s ON o.OrderID = s.OrderID
                  WHERE o.OrderID = ?";
    $stmt_order = $myconn->prepare($sql_order);
    $stmt_order->bind_param("i", $orderID);
    $stmt_order->execute();
    $result_order = $stmt_order->get_result();
    $orderInfo = $result_order->fetch_assoc();

    if ($orderInfo) {
        $orderDetailID = $orderInfo['OrderID'];
        $orderDate = $orderInfo['CreatedAt'];
        $orderStatus = $orderInfo['OrderStatus'];
        $paymentMethod = $orderInfo['PaymentMethod'];
        $estimatedDeliveryDate = date('d/m/Y', strtotime($orderDate . ' + 4 days'));


        // 2. Lấy chi tiết đơn hàng (có thể có nhiều sản phẩm)
        $sql_details = "SELECT od.OrderDetailID, od.ProductID, od.Quantity, od.UnitPrice, od.TotalPrice, 
                               p.ProductName, p.ImageURL
                        FROM orderdetails od
                        JOIN products p ON od.ProductID = p.ProductID
                        WHERE od.OrderID = ?";
        $stmt_details = $myconn->prepare($sql_details);
        $stmt_details->bind_param("i", $orderID);
        $stmt_details->execute();
        $result_details = $stmt_details->get_result();
        $orderDetails = [];
        while ($row = $result_details->fetch_assoc()) {
            $orderDetails[] = $row;
        }

        // 3. Lấy thông tin thanh toán
        $sql_payment =    "SELECT 
                        SUM(od.Quantity) AS TotalQuantity, o.TotalAmount
                        FROM orderdetails od
                        JOIN orders o ON od.OrderID = o.OrderID
                        JOIN users u ON o.UserID= u.UserID
                        WHERE od.OrderID = ?";
        $stmt_payment = $myconn->prepare($sql_payment);
        $stmt_payment->bind_param("i", $orderID);
        $stmt_payment->execute();
        $result_payment = $stmt_payment->get_result();
        $paymentInfo = $result_payment->fetch_assoc();

        $totalQuantity = $paymentInfo['TotalQuantity'];
        $totalProductAmount = $paymentInfo['TotalAmount'];
        $total = $totalProductAmount;

        // 4. Lấy thông tin người mua và địa chỉ giao hàng
        $sql_user = "SELECT u.FullName, u.Phone, u.Address, u.Ward, u.District, u.Province
                     FROM orders o
                     JOIN users u ON o.UserID = u.UserID
                     WHERE o.OrderID = ?";
        $stmt_user = $myconn->prepare($sql_user);
        $stmt_user->bind_param("i", $orderID);
        $stmt_user->execute();
        $result_user = $stmt_user->get_result();
        $userInfo = $result_user->fetch_assoc();

        if ($userInfo) {
            $fullName = $userInfo['FullName'];
            $phone = $userInfo['Phone'];
            $address = $userInfo['Address'] . ', ' . $userInfo['Ward'] . ', ' . $userInfo['District'] . ', ' . $userInfo['Province'];
        } else {
            echo "Không tìm thấy thông tin người mua";
            exit;
        }
    } 
} else {
    echo "Không có mã đơn hàng";
    exit;
}

// Hàm để lấy thông tin trạng thái
function getStatusInfo($status) {
    switch ($status) {
        case 'pending':
            return [
                'text' => 'Đang xử lý',
                'class' => 'status-pending',
                'icon' => '<i class="fa-solid fa-spinner"></i>'
            ];
        case 'processing':
            return [
                'text' => 'Đã xác nhận',
                'class' => 'status-processing',
                'icon' => '<i class="fa-solid fa-circle-check"></i>'
            ];
        case 'shipped':
            return [
                'text' => 'Đang giao',
                'class' => 'status-shipping',
                'icon' => '<i class="fa-solid fa-truck"></i>'
            ];
        case 'completed':
            return [
                'text' => 'Đã giao',
                'class' => 'status-completed',
                'icon' => '<i class="fa-solid fa-circle-check"></i>'
            ];
        case 'canceled':
            return [
                'text' => 'Đã hủy',
                'class' => 'status-canceled',
                'icon' => '<i class="fa-solid fa-ban"></i>'
            ];
        default:
            return [
                'text' => 'Không xác định',
                'class' => 'status-unknown',
                'icon' => '<i class="fa-solid fa-question"></i>'
            ];
    }
}

// Thêm hàm này cạnh hàm getStatusInfo
function getPaymentStatusInfo($method) {
    switch ($method) {
        case 'cod':
            return [
                'text' => 'Thanh toán khi nhận hàng',
                'class' => 'payment-cod',
                'icon' => '<i class="fa-solid fa-money-bill"></i>'
            ];
        case 'credit card':
            return [
                'text' => 'Chuyển khoản',
                'class' => 'payment-banking',
                'icon' => '<i class="fa-solid fa-building-columns"></i>'
            ];
        default:
            return [
                'text' => 'Chưa thanh toán',
                'class' => 'payment-pending',
                'icon' => '<i class="fa-solid fa-clock"></i>'
            ];
    }
}

function returnFinishPayment($method, $orderStatus) {
    // Nếu đơn hàng đã hủy
    if ($orderStatus === 'canceled') {
        return [
            'text' => 'Đơn hàng đã hủy',
            'class' => 'payment-status-canceled',
            'icon' => '<i class="fa-solid fa-ban"></i>',
            'showAmount' => false
        ];
    }

    // Xử lý theo phương thức thanh toán
    switch($method) {
        case 'cod':
            if ($orderStatus === 'completed') {
                return [
                    'text' => 'Đã thanh toán COD',
                    'class' => 'payment-status-completed',
                    'icon' => '<i class="fa-solid fa-circle-check"></i>',
                    'showAmount' => true
                ];
            } else {
                return [
                    'text' => 'Chưa thanh toán (COD)',
                    'class' => 'payment-status-pending',
                    'icon' => '<i class="fa-solid fa-clock"></i>',
                    'showAmount' => false
                ];
            }
        
        case 'credit card':
            return [
                'text' => 'Đã thanh toán (Chuyển khoản)',
                'class' => 'payment-status-completed',
                'icon' => '<i class="fa-solid fa-circle-check"></i>',
                'showAmount' => true
            ];
        default:
            return [
                'text' => 'Chưa xác định phương thức thanh toán',
                'class' => 'payment-status-unknown',
                'icon' => '<i class="fa-solid fa-question"></i>',
                'showAmount' => false
            ];
    }
}

$returnFinished = returnFinishPayment($paymentMethod, $orderStatus);
$statusInfo = getStatusInfo($orderStatus);
$paymentStatusInfo = getPaymentStatusInfo($paymentMethod);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <title>Đơn Hàng Số <?php echo $orderDetailID; ?></title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../style/header.css">
  <link rel="stylesheet" href="../style/order.css">
  <link rel="stylesheet" href="../style/sidebar.css">
  <link href="../icon/css/all.css" rel="stylesheet">
  <link href="../style/generall.css" rel="stylesheet">
  <link href="../style/main1.css" rel="stylesheet">
  <link href="../style/orderDetail.css" rel="stylesheet">
  <link href="asset/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="../style/LogInfo.css" rel="stylesheet">
  <link rel="stylesheet" href="../style/reponsiveOrder-detail.css">
</head>

<body>
  <script src="./asset/bootstrap/js/bootstrap.bundle.min.js"></script>
  <div class="header">
    <div class="index-menu">
      <i class="fa-solid fa-bars" data-bs-toggle="offcanvas" href="#offcanvasExample" role="button"
        aria-controls="offcanvasExample"></i>
      <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample"
        aria-labelledby="offcanvasExampleLabel">
        <div style=" 
        border-bottom-width: 1px;
        border-bottom-style: solid;
        border-bottom-color: rgb(176, 176, 176);" class="offcanvas-header">
          <h5 class="offcanvas-title" id="offcanvasExampleLabel">Mục lục</h5>
          <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
          <a href="../index/homePage.html" style="text-decoration: none; color: black;">
            <div class="container-function-selection">
              <button class="button-function-selection">
                <i class="fa-solid fa-house" style="
                  font-size: 20px;
                  color: #FAD4AE;
                  "></i>
              </button>
              <p>Trang chủ</p>
            </div>
          </a>
          <a href="../index/wareHouse.html" style="text-decoration: none; color: black;">
            <div class="container-function-selection">
              <button class="button-function-selection">
                <i class="fa-solid fa-warehouse" style="font-size: 20px;
                  color: #FAD4AE;
              "></i></button>
              <p>Kho hàng</p>
            </div>
          </a>
          <a href="customer.html" style="text-decoration: none; color: black;">
            <div class="container-function-selection">
              <button class="button-function-selection">
                <i class="fa-solid fa-users" style="
                              font-size: 20px;
                              color: #FAD4AE;
                          "></i>
              </button>
              <p style="color: black;text-align: center; font-size: 10x;">Khách hàng</p>
            </div>
          </a>
          <a href="orderPage.html" style="text-decoration: none; color: black;">
            <div class="container-function-selection">
              <button class="button-function-selection" style="background-color: #6aa173; color: black;">
                <i class="fa-solid fa-list-check" style="
                          font-size: 18px;
                          color: #FAD4AE;
                          "></i>
              </button>
              <p style="color:black">Đơn hàng</p>
            </div>
          </a>
          <a href="analyzePage.html" style="text-decoration: none; color: black;">
            <div class="container-function-selection">
              <button class="button-function-selection">
                <i class="fa-solid fa-chart-simple" style="
                          font-size: 20px;
                          color: #FAD4AE;
                      "></i>
              </button>
              <p>Thống kê</p>
            </div>
          </a>
          <a href="accountPage.html" style="text-decoration: none; color: black;">
            <div class="container-function-selection">
              <button class="button-function-selection">
                <i class="fa-solid fa-circle-user" style="
                           font-size: 20px;
                           color: #FAD4AE;
                       "></i>
              </button>
              <p style="color:black">Tài khoản</p>
            </div>
          </a>
        </div>
      </div>
    </div>
    <div class="header-left-section">
      <p style="
       font-size: 30px;
       font-weight: bold; position: relative;
       left: -25px;">Đơn số <?php echo $orderDetailID; ?></p>
    </div>
    <div class="header-middle-section">
      <img class="logo-store" src="../../assets/images/LOGO-2.png">
    </div>
    <div class="header-right-section">
      <div class="bell-notification">
        <i class="fa-regular fa-bell" style="
                        color: #64792c;
                        font-size: 45px;
                        "></i>
      </div>
      <div>
        <div class="position-employee">
          <p>Nhân viên</p>
        </div>
        <div class="name-employee">
          <p>Nguyen Chuong</p>
        </div>
      </div>
      <div>
        <img class="avatar" src="../images/image/chuong-avatar.jpg" alt="" data-bs-toggle="offcanvas"
          data-bs-target="#offcanvasWithBothOptions" aria-controls="offcanvasWithBothOptions">
      </div>
      <div class="offcanvas offcanvas-end" data-bs-scroll="true" tabindex="-1" id="offcanvasWithBothOptions"
        aria-labelledby="offcanvasWithBothOptionsLabel">
        <div style=" border-bottom-width: 1px;
      border-bottom-style: solid;
      border-bottom-color: rgb(176, 176, 176);" class="offcanvas-header">
          <img class="avatar" src="../images/image/chuong-avatar.jpg" alt="">
          <div style="display: flex; flex-direction: column; height: 95px;">
            <h4 class="offcanvas-title" id="offcanvasWithBothOptionsLabel">NgNguyenChuong</h4>
            <h5>Ng_Nguyen_Chuong</h5>
          </div>
          <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
          <a href="accountPage.html" class="navbar_user">
            <i class="fa-solid fa-user"></i>
            <p>Thông tin cá nhân </p>
          </a>
          <a href="#logoutModal" class="navbar_logout">
            <i class="fa-solid fa-right-from-bracket"></i>
            <p>Đăng xuất</p>
          </a>
          <div id="logoutModal" class="modal">
            <div class="modal_content">
              <h2>Xác nhận đăng xuất</h2>
              <p>Bạn có chắc chắn muốn đăng xuất không?</p>
              <div class="modal_actions">
                <a href="../index.html" class="btn_2 confirm">Đăng xuất</a>
                <a href="#" class="btn_2 cancel">Hủy</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Sidebar -->
  <div class="side-bar">
    <div class="backToHome">
      <a href="homePage.html" style="text-decoration: none; color: black;">
        <div class="backToHome">
          <button class="button-function-selection">
            <i class="fa-solid fa-house" style="
            font-size: 20px;
            color: #FAD4AE;
            "></i>
          </button>
          <p>Trang chủ</p>
        </div>
      </a>
    </div>
    <a href="wareHouse.html" style="text-decoration: none; color: black;">
      <div class="container-function-selection">
        <button class="button-function-selection">
          <i class="fa-solid fa-warehouse" style="font-size: 20px;
            color: #FAD4AE;
        "></i></button>
        <p>Kho hàng</p>
      </div>
    </a>
    <a href="customer.html" style="text-decoration: none; color: black;">
      <div class="container-function-selection">
        <button class="button-function-selection">
          <i class="fa-solid fa-users" style="
                        font-size: 20px;
                        color: #FAD4AE;
                    "></i>
        </button>
        <p>Khách hàng</p>
      </div>
    </a>
    <a href="orderPage.html" style="text-decoration: none; color: black;">
      <div class="container-function-selection">
        <button class="button-function-selection" style="background-color: #6aa173;">
          <i class="fa-solid fa-list-check" style="
                    font-size: 20px;
                    color: #FAD4AE;
                    "></i>
        </button>
        <p>Đơn hàng</p>
      </div>
    </a>
    <a href="analyzePage.html" style="text-decoration: none; color: black;">
      <div class="container-function-selection">
        <button class="button-function-selection">
          <i class="fa-solid fa-chart-simple" style="
                    font-size: 20px;
                    color: #FAD4AE;
                "></i>
        </button>
        <p>Thống kê</p>
      </div>
    </a>
    <a href="accountPage.html" style="text-decoration: none; color: black;">
      <div class="container-function-selection">
        <button class="button-function-selection">
          <i class="fa-solid fa-circle-user" style="
                     font-size: 20px;
                     color: #FAD4AE;
                 "></i>
        </button>
        <p>Tài khoản</p>
      </div>
    </a>
  </div>

  <div class="content-wrapper">
    <div class="order-container">
      <div class="order-header">
        <div class="breadcrumb">
          <a href="orderPage.html">Đơn hàng</a> > <span><?php echo $orderDetailID; ?></span>
        </div>
        <table class="status-bar">
          <thead>
            <tr>
              <th>MÃ ĐƠN HÀNG</th>
              <th>NGÀY TẠO</th>
              <th>Ngày giao (dự kiến)</th>
              <th>TRẠNG THÁI</th>
              <th>PHƯƠNG THỨC THANH TOÁN</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td><?php echo $orderDetailID; ?></td>
              <td><?php echo $orderDate; ?></td>
              <td><?php echo $estimatedDeliveryDate; ?></td>
              <td class=" <?php echo $statusInfo['class']; ?>">
                <?php echo $statusInfo['icon'] . ' ' . $statusInfo['text']; ?>
              </td>
              <td class="status paid"><?php echo $paymentStatusInfo['icon'] . ' ' . $paymentStatusInfo['text']; ?></td>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="main-content">
        <div class="left-section">
          <div class="section products">
            <div class="section-header">
              <span style="color:#21923c;"><i class="fa-regular fa-circle" style="margin-right: 5px;"></i>Chi tiết đơn hàng</span>
            </div>
            <table>
              <thead>
                <tr>
                  <th></th>
                  <th>SỐ LƯỢNG</th>
                  <th>GIÁ (đ)</th>
                  <th class="hide-display">THÀNH TIỀN (đ)</th>
                </tr>
              </thead>
              <tbody>
                <?php if (empty($orderDetails)): ?>
                  <tr>
                    <td colspan="4">Không có sản phẩm nào trong đơn hàng này.</td>
                </tr>
                <?php else: ?>
                  <?php foreach ($orderDetails as $detail): ?>
                <tr>
                  <td>
                        <img src="<?php echo '../..'.$detail['ImageURL']; ?>" alt="Product Image" style="width: 50px; height: 50px;">
                    <div class="product-info">
                          <span class="product-name"><?php echo htmlspecialchars($detail['ProductName']); ?></span><br>
                    </div>
                  </td>
                      <td><?php echo $detail['Quantity']; ?></td>
                      <td><?php echo number_format($detail['UnitPrice'], 0, ',', '.') . ' đ'; ?></td>
                      <td class="hide-display"><?php echo number_format($detail['TotalPrice'], 0, ',', '.') . ' đ'; ?></td>
                </tr>
                  <?php endforeach; ?>
                <?php endif; ?>
              </tbody>
            </table>
          </div>

          <div class="section payment">
            <div class="section-header">
              <span>Thanh Toán: </span>
            </div>
            <div class="payment-details">
              <div class="payment-row">
                <span>Số lượng sản phẩm</span>
                <span><?php echo $totalQuantity; ?></span>
              </div>
              <div class="payment-row">
                <span>Tổng tiền hàng</span>
                <span><?php echo number_format($totalProductAmount, 0, ',', '.') . ' đ'; ?></span>
              </div>
              <div class="payment-row total">
                <span>Tổng giá trị đơn hàng</span>
                <span><?php echo number_format($total, 0, ',', '.') . ' đ'; ?></span>
              </div>
              <div class="payment-row ">
                <span>Trạng thái thanh toán:</span>
                <span class="<?php echo $returnFinished['class']; ?>">
                    <?php echo  $returnFinished['text']; ?>
                    <?php if ($returnFinished['showAmount']): ?>
                        (<?php echo number_format($total, 0, ',', '.') . ' đ'; ?>)
                    <?php endif; ?>
                </span>
              </div>
            </div>
          </div>
        </div>

        <div class="right-section">
          <div class="section source">
            <div class="section-header">
              <span>Thông Tin Người Mua</span>
            </div>
            <div class="section-header">
              <p style="color:#007bff"><?php echo $fullName; ?></p>
            </div>
            <div class="source-details">
              <div>
                <span style="font-size: 16px; font-weight: bold;
                 padding-bottom: 15px; padding-right: 4rem; display:flex;">Người Liên Hệ</span>
                <p style="color:#007bff;font-weight: bold;padding-bottom:10px"><?php echo $fullName; ?></p>
              </div>
              <span>SĐT:</span>
              <span class="highlight"><?php echo $phone; ?></span>
            </div>
          </div>
          <div class="section shipping">
            <div class="section-header">
              <span>Địa Chỉ Giao Hàng</span>
            </div>
            <div class="shipping-details">
              <span><?php echo $address; ?></span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>