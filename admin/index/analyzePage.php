<?php include '../pages/thongke.php' ?>
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
  <!-- Add style-->
  <link rel="stylesheet" href="../style/analyzeStyle.css">
  <link rel="stylesheet" href="../style/main.css">
  <link rel="stylesheet" href="../icon/css/fontawesome.min.css">
  <!-- Add reponsive -->
  <link rel="stylesheet" href="../style/reponsiveAnalyze1.css">
  <!-- Add bootstrap -->
  <link rel="stylesheet" href="./asset/bootstrap/css/bootstrap.min.css">
  <!-- Add login function -->
  <link rel="stylesheet" href="../style/LogInfo.css">
  <!-- Add charts -->
  <script src="https://www.gstatic.com/charts/loader.js"></script>
  <script src="./asset/bootstrap/js/bootstrap.bundle.min.js"></script>
</head>

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
                <td data-label="STT"><?php echo $index + 1; ?></td>
                <td data-label="Tên khách hàng"><?php echo htmlspecialchars($customer['name']); ?></td>
                <td data-label="Đơn hàng"><?php echo $customer['orders']; ?></td>
                <td data-label="Tổng tiền (VND)" class="total"><?php echo number_format($customer['total_spent'], 0, ',', '.'); ?></td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
    <div class="container" style="   margin-top: 3rem;">
      <h1>Thống Kê Mặt Hàng Bán Ra</h1>
      <table>
        <thead>
          <tr>
            <th>STT</th>
            <th>Tên mặt hàng</th>
            <th>Số lượng bán</th>
            <th>Tổng tiền (VND)</th>
            <th>Hóa đơn</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($items)): ?>
            <tr>
              <td colspan="5" style="text-align: center;">Vui lòng chọn khoảng thời gian phù hợp</td>
            </tr>
          <?php else: ?>
            <?php foreach ($items as $index => $item): ?>
              <tr>
                <td data-label="STT"><?php echo $index + 1; ?></td>
                <td data-label="Tên mặt hàng"><?php echo htmlspecialchars($item['name']); ?></td>
                <td data-label="Số lượng bán"><?php echo $item['total_quantity']; ?></td>
                <td data-label="Tổng tiền (VND)" class="total"><?php echo number_format($item['total_revenue'], 0, ',', '.'); ?></td>
                <td data-label="Hóa đơn"><?php echo $item['orders']; ?></td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
      <h3>Tổng doanh thu: <?php echo number_format($total_revenue, 0, ',', '.'); ?> VND</h3>
      <?php if ($best_selling_item): ?>
        <p><strong>Mặt hàng bán chạy nhất:</strong> <?php echo htmlspecialchars($best_selling_item['name']); ?> (<?php echo $best_selling_item['total_quantity']; ?> sản phẩm)</p>
      <?php endif; ?>
      <?php if ($least_selling_item): ?>
        <p><strong>Mặt hàng bán ế nhất:</strong> <?php echo htmlspecialchars($least_selling_item['name']); ?> (<?php echo $least_selling_item['total_quantity']; ?> sản phẩm)</p>
      <?php endif; ?>
    </div>
  </div>
</body>

</html>