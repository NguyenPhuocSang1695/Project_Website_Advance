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
  <link rel="stylesheet" href="../style/main1.css">
  <link rel="stylesheet" href="../icon/css/fontawesome.min.css">
  <!-- Add reponsive -->
  <link rel="stylesheet" href="../style/reponsiveAnalyze.css">
  <!-- Add bootstrap -->
  <link rel="stylesheet" href="./asset/bootstrap/css/bootstrap.min.css">
  <!-- Add login function -->
  <link rel="stylesheet" href="../style/LogInfo.css">
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
          <a href="homePage.php" style="text-decoration: none; color: black;">
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
          <a href="wareHouse.php" style="text-decoration: none; color: black;">
            <div class="container-function-selection">
              <button class="button-function-selection">
                <i class="fa-solid fa-warehouse" style="font-size: 20px;
                  color: #FAD4AE;
              "></i></button>
              <p>Kho hàng</p>
            </div>
          </a>
          <a href="customer.php" style="text-decoration: none; color: black;">
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
          <a href="orderPage.php" style="text-decoration: none; color: black;">
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
          <a href="accountPage.php" style="text-decoration: none; color: black;">
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
      <img class="logo-store" src="../../assets/images/LOGO-2.jpg">
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
          <p id="employee-role">Chức vụ</p>
        </div>
        <div class="name-employee">
          <p id="employee-name">Ẩn danh</p>
        </div>
      </div>
      <div>
        <img class="avatar" class src="../../assets/images/admin.jpg" alt="" data-bs-toggle="offcanvas"
          data-bs-target="#offcanvasWithBothOptions" aria-controls="offcanvasWithBothOptions">
      </div>
      <div class="offcanvas offcanvas-end" data-bs-scroll="true" tabindex="-1" id="offcanvasWithBothOptions"
        aria-labelledby="offcanvasWithBothOptionsLabel">
        <div style=" 
            border-bottom-width: 1px;
            border-bottom-style: solid;
            border-bottom-color: rgb(176, 176, 176);" class="offcanvas-header">
          <img class="avatar" src="../../assets/images/admin.jpg" alt="">
          <div style="display: flex; flex-direction: column; height: 95px;">
            <h4 class="offcanvas-title" id="offcanvasWithBothOptionsLabel">Username</h4>
            <h5 id="employee-displayname">Họ tên</h5>
          </div>
          <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
          <a href="accountPage.php" class="navbar_user">
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
                <a href="../php/logout.php" class="btn_2 confirm">Đăng xuất</a>
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
        <a href="homePage.php" style="text-decoration: none; color: black;">
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
      <a href="wareHouse.php" style="text-decoration: none; color: black;">
        <div class="container-function-selection">
          <button class="button-function-selection">
            <i class="fa-solid fa-warehouse" style="font-size: 20px;
            color: #FAD4AE;
        "></i></button>
          <p>Kho hàng</p>
        </div>
      </a>
      <a href="customer.php" style="text-decoration: none; color: black;">
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
      <a href="orderPage.php" style="text-decoration: none; color: black;">
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
      <a href="accountPage.php" style="text-decoration: none; color: black;">
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

    <!-- Container 1: Khách hàng -->
    <div class="container">
      <h1>Thống Kê Khách Hàng Mua Hàng Nhiều Nhất</h1>
      <div class="filter-section">
        <form id="analyze-form" method="POST" action="">
          <label for="start-date">Từ ngày:</label>
          <input type="date" id="start-date" name="start_date" required>
          <label for="end-date">Đến ngày:</label>
          <input type="date" id="end-date" name="end_date" required>
          <button type="submit" name="submit">Lọc <i class="fa-solid fa-filter"></i></button>
        </form>
      </div>

      <table> 
        <thead>
          <tr>
            <th>STT</th>
            <th>Tên khách hàng</th>
            <th>Số lượng đơn đã mua</th>
            <th>Ngày tạo</th>
            <th class="total-amount">Tổng tiền (VNĐ)</th>
            <th>Chi tiết đơn</th>
          </tr>
        </thead>
        <tbody id="customer-table">
          <tr>
            <td colspan="6" style="text-align: center;">Vui lòng chọn khoảng thời gian phù hợp</td>
          </tr>
        </tbody>
      </table>
      <!-- Tổng doanh thu  -->
      <div class="revenue-summary">
        <h2>Tổng quan doanh thu</h2>
        <div class="summary-card revenue-card">
          <i class="fa-solid fa-money-bill-wave"></i>
          <h3>Tổng doanh thu</h3>
          <p id="total-revenue">0 VNĐ</p>
          <span class="period">Trong khoảng thời gian đã chọn</span>
        </div>
      </div>
    </div>
 
    <!-- Container 2: Mặt hàng và các mục bán chạy/ế -->
    <div class="container" style="margin-top: 3rem;">
      <h1>Thống Kê Mặt Hàng Bán Ra</h1>
      <table>
        <thead>
          <tr>
            <th>STT</th>
            <th>Tên mặt hàng</th>
            <th>Số lượng bán</th>
            <th class="total-amount">Tổng tiền (VND)</th>
            <th>Chi tiết đơn</th>
          </tr>
        </thead>
        <tbody id="product-table">
          <tr>
            <td colspan="6" style="text-align: center;">Vui lòng chọn khoảng thời gian phù hợp</td>
          </tr>
        </tbody>
      </table>
      <!-- Mặt hàng bán chạy và ế -->
      <div class="summary-section">
        <div class="summary-card best-selling-card">
          <i class="fa-solid fa-arrow-trend-up"></i>
          <h3>Mặt hàng bán chạy nhất</h3>
          <div class="product-info">
            <p id="best-selling">Chưa có dữ liệu</p>
            <span id="best-selling-quantity" class="product-quantity"></span>
          </div>
        </div>

        <div class="summary-card worst-selling-card">
          <i class="fa-solid fa-arrow-trend-down"></i>
          <h3>Mặt hàng bán ế nhất</h3>
          <div class="product-info">
            <p id="worst-selling">Chưa có dữ liệu</p>
            <span id="worst-selling-quantity" class="product-quantity"></span>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="../js/analyzePage.js"></script>
  <script src ="../js/checklog.js"></script>
  <script>
document.addEventListener('DOMContentLoaded', () => {
    const cachedUserInfo = localStorage.getItem('userInfo');
    if (cachedUserInfo) {
        const userInfo = JSON.parse(cachedUserInfo);
        document.querySelector('.name-employee p').textContent = userInfo.fullname;
        document.querySelector('.position-employee p').textContent = userInfo.role;
        document.querySelectorAll('.avatar').forEach(img => img.src = userInfo.avatar);
    }
});
</script>
</body>

</html>