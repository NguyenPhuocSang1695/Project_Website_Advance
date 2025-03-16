
<?php
include 'connect.php';
$start_date = isset($_POST['start_date']) ? $_POST['start_date'] : '';
$end_date = isset($_POST['end_date']) ? $_POST['end_date'] : '';
$customers = [];

if ($start_date && $end_date) {
    $sql = "SELECT c.id, c.name, SUM(o.total) as total_spent, 
            GROUP_CONCAT(CONCAT('<a href=\"orderDetail2.html?id=', o.id, '\" class=\"order-link\">Đơn ', o.id, '</a> - ', o.total, ' VND (', DATE_FORMAT(o.order_date, '%d/%m/%Y'), ')') SEPARATOR '<br>') as orders
            FROM customers c
            JOIN orders o ON c.id = o.customer_id
            WHERE o.order_date BETWEEN ? AND ? 
            -- đặt đièu kiện ngày Order phải ở khoảng giữa ngày bắt đầu và ngày kết thúc
            GROUP BY c.id, c.name
            ORDER BY total_spent DESC
            LIMIT 5";
    $stmt= $myconn->prepare($sql);
    $stmt->bind_param("ss",$start_date,$end_date);
    // dùng bind_param để gán giá trị cho biến khi điều kiện đúng
    //ss : dùng để biến đổi ngày thành String và xóa các ký tự đặc biệt
    $stmt->execute();
    $result= $stmt->get_result();
  while($row=mysqli_fetch_assoc($result)) {
    $customers[] = $row;
  }
$stmt->close();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Thống Kê Kinh Doanh</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../style/header.css">
  <link rel="stylesheet" href="../style/sidebar.css">
  <link rel="stylesheet" href="../icon/css/all.css">
  <link rel="stylesheet" href="../style/generall.css">
  <link rel="stylesheet" href="../style/analyzeStyle.css">
  <link rel="stylesheet" href="../style/main.css">
  <link rel="stylesheet" href="../icon/css/fontawesome.min.css">
  <!-- Add reponsive -->
  <link rel="stylesheet" href="../style/reponsiveAnalyze.css">
  <!-- Add bootstrap -->
  <link rel="stylesheet" href="./asset/bootstrap/css/bootstrap.min.css">
  <!-- Add login function -->
  <link rel="stylesheet" href="../style/LogInfo.css">
  <!-- Add charts -->
  <script src="https://www.gstatic.com/charts/loader.js"></script>
  <script src="./asset/bootstrap/js/bootstrap.bundle.min.js"></script>
</head>
<style>
  body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 20px;
    background-color: #f5f5f5;
  }

  .container {
    max-width: 1120px;
    margin-left: 15rem;
    margin-top: 6rem;
    background: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  }

  h1 {
    color: #2c3e50;
    text-align: center;
    margin-bottom: 30px;
  }

  .filter-section {
    margin-bottom: 30px;
    padding: 20px;
    background: #ecf0f1;
    border-radius: 5px;
  }

  .filter-section label {
    margin-right: 10px;
    font-weight: bold;
  }

  .filter-section input {
    padding: 8px;
    margin-right: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
  }

  .filter-section button {
    padding: 8px 20px;
    background: #3498db;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
  }

  .filter-section button:hover {
    background: #2980b9;
  }

  table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
  }

  th,
  td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #ddd;
  }

  th {
    background: #1c8e2e;
    color: white;
  }

  tr:hover {
    background: #f8f9fa;
  }

  .order-link {
    color: #1c8e2e;
    text-decoration: none;
  }

  .order-link:hover {
    text-decoration: underline;
  }

  .total {
    font-weight: bold;
    color: #e74c3c;
  }
</style>

<body>
  <div class="header">
    <div class="index-menu">
      <i class="fa-solid fa-bars" data-bs-toggle="offcanvas" href="#offcanvasExample" role="button"
        aria-controls="offcanvasExample">
      </i>
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
          <a href="homePage.html" style="text-decoration: none; color: black;">
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
              <p style="color: black;text-align: center; font-size: 10x;">Khách hàng</p>
            </div>
          </a>
          <a href="orderPage1.php" style="text-decoration: none; color: black;">
            <div class="container-function-selection">
              <button class="button-function-selection">
                <i class="fa-solid fa-list-check" style="
                          font-size: 18px;
                          color: #FAD4AE;
                          "></i>
              </button>
              <p style="color:black">Đơn hàng</p>
            </div>
          </a>
          <a href="analyzePage.php" style="text-decoration: none; color: black;">
            <div class="container-function-selection">
              <button class="button-function-selection" style="background-color: #6aa173;">
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
      <p class="header-left-title">Thống kê</p>
    </div>
    <div class="header-middle-section">
      <img class="logo-store" src="../../assets/images/LOGO-2.png">
    </div>
    <div class="header-right-section">
      <div class="bell-notification">
        <i class="fa-regular fa-bell" style="
                        color: #64792c;
                        font-size: 45px;
                        width:100%;
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
        <img class="avatar" class src="../images/image/chuong-avatar.jpg" alt="" data-bs-toggle="offcanvas"
          data-bs-target="#offcanvasWithBothOptions" aria-controls="offcanvasWithBothOptions">
      </div>
      <div class="offcanvas offcanvas-end" data-bs-scroll="true" tabindex="-1" id="offcanvasWithBothOptions"
        aria-labelledby="offcanvasWithBothOptionsLabel">
        <div style=" 
            border-bottom-width: 1px;
            border-bottom-style: solid;
            border-bottom-color: rgb(176, 176, 176);" class="offcanvas-header">
          <img class="avatar" class src="../images/image/chuong-avatar.jpg" alt="">
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
  <div class="main-container">
    <div class="side-bar">
      <div class="backToHome">
        <a href="homePage.html" style="text-decoration: none; color: black;">
          <div class="container-function-selection">
            <button class="button-function-selection" style="margin-top: 35px;">
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
      <a href="orderPage1.php" style="text-decoration: none; color: black;">
        <div class="container-function-selection">
          <button class="button-function-selection">
            <i class="fa-solid fa-list-check" style="
                    font-size: 20px;
                    color: #FAD4AE;
                    "></i>
          </button>
          <p>Đơn hàng</p>
        </div>
      </a>
      <a href="analyzePage.php" style="text-decoration: none; color: black;">
        <div class="container-function-selection">
          <button class="button-function-selection" style="background-color: #6aa173;">
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
    <!-- Phần Body -->
    <div class="container">
      <h1>Thống Kê Khách Hàng Mua Hàng Nhiều Nhất</h1>
      <div class="filter-section">
        <form method="POST" action="">
          <label for="start-date">Từ ngày:</label>
          <input type="date" id="start-date" name="start_date" value="<?php echo $start_date; ?>" required>
          <label for="end-date">Đến ngày:</label>
          <input type="date" id="end-date" name="end_date" value="<?php echo $end_date; ?>" required>
          <button type="submit">Lọc <i class="fa-solid fa-filter"></i></button>
        </form>
      </div>
      <table>
        <thead>
          <tr>
            <th>STT</th>
            <th>Tên khách hàng</th>
            <th>Đơn hàng</th>
            <th>Tổng tiền (VND)</th>
          </tr>
        </thead>
        <tbody id="customer-table">
          <?php if (empty($customers)): ?>
            <tr>
              <td colspan="4" style="text-align: center;">Vui lòng chọn khoảng thời gian phù hợp </td>
            </tr>
          <?php else: ?>
            <?php foreach ($customers as $index => $customer): ?>
              <tr>
                <td><?php echo $index + 1; ?></td>
                <td><?php echo htmlspecialchars($customer['name']); ?></td>
                <td><?php echo $customer['orders']; ?></td>
                <td class="total"><?php echo number_format($customer['total_spent'], 0, ',', '.'); ?></td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</body>

</html>