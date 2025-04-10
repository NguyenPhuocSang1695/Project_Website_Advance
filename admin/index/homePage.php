<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Quản lý bán hàng - Trang chủ Admin</title>

  <link rel="stylesheet" href="../style/header.css">
  <link rel="stylesheet" href="../style/sidebar.css">
  <link href="../icon/css/all.css" rel="stylesheet">
  <link href="../style/generall.css" rel="stylesheet">
  <link href="../style/main1.css" rel="stylesheet">
  <link href="../style/LogInfo.css" rel="stylesheet">
  <link href="asset/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../style/responsiveHomePage.css">

</head>

<body>
  <div class="header">
    <div class="index-menu">
      <i class="fa-solid fa-bars" data-bs-toggle="offcanvas" href="#offcanvasExample" role="button"
        aria-controls="offcanvasExample"></i>
      <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample"
        aria-labelledby="offcanvasExampleLabel">
        <div style="border-bottom: 1px solid rgb(176, 176, 176);" class="offcanvas-header">
          <h5 class="offcanvas-title" id="offcanvasExampleLabel">Mục lục</h5>
          <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
          <a href="homePage.php" style="text-decoration: none; color: black;">
            <div class="container-function-selection">
              <button class="button-function-selection" style="background-color: #6aa173;">
                <i class="fa-solid fa-house" style="font-size: 20px; color: #FAD4AE;"></i>
              </button>
              <p>Trang chủ</p>
            </div>
          </a>
          <a href="wareHouse.php" style="text-decoration: none; color: black;">
            <div class="container-function-selection">
              <button class="button-function-selection">
                <i class="fa-solid fa-warehouse" style="font-size: 20px; color: #FAD4AE;"></i>
              </button>
              <p>Kho hàng</p>
            </div>
          </a>
          <a href="customer.php" style="text-decoration: none; color: black;">
            <div class="container-function-selection">
              <button class="button-function-selection">
                <i class="fa-solid fa-users" style="font-size: 20px; color: #FAD4AE;"></i>
              </button>
              <p style="color: black; text-align: center; font-size: 10x;">Khách hàng</p>
            </div>
          </a>
          <a href="orderPage.php" style="text-decoration: none; color: black;">
            <div class="container-function-selection">
              <button class="button-function-selection">
                <i class="fa-solid fa-list-check" style="font-size: 18px; color: #FAD4AE;"></i>
              </button>
              <p style="color:black">Đơn hàng</p>
            </div>
          </a>
          <a href="analyzePage.php" style="text-decoration: none; color: black;">
            <div class="container-function-selection">
              <button class="button-function-selection">
                <i class="fa-solid fa-chart-simple" style="font-size: 20px; color: #FAD4AE;"></i>
              </button>
              <p>Thống kê</p>
            </div>
          </a>
          <a href="accountPage.php" style="text-decoration: none; color: black;">
            <div class="container-function-selection">
              <button class="button-function-selection">
                <i class="fa-solid fa-circle-user" style="font-size: 20px; color: #FAD4AE;"></i>
              </button>
              <p style="color:black">Tài khoản</p>
            </div>
          </a>
        </div>
      </div>
    </div>
    <div class="header-left-section">
      <p class="header-left-title">Trang chủ</p>
    </div>
    <div class="header-middle-section">
      <img class="logo-store" src="../../assets/images/LOGO-2.jpg">
    </div>
    <div class="header-right-section">
      <div class="bell-notification">
        <i class="fa-regular fa-bell" style="color: #64792c; font-size: 45px; width:100%;"></i>
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
        <img class="avatar" src="../../assets/images/admin.jpg" alt="" data-bs-toggle="offcanvas"
          data-bs-target="#offcanvasWithBothOptions" aria-controls="offcanvasWithBothOptions">
      </div>
      <div class="offcanvas offcanvas-end" data-bs-scroll="true" tabindex="-1" id="offcanvasWithBothOptions"
        aria-labelledby="offcanvasWithBothOptionsLabel">
        <div style="border-bottom: 1px solid rgb(176, 176, 176);" class="offcanvas-header">
          <img class="avatar" src="../../assets/images/admin.jpg" alt="">
          <div class="admin">
            <h4 class="offcanvas-title" id="offcanvasWithBothOptionsLabel">Username</h4>
            <h5 id="employee-displayname">Họ tên</h5>
          </div>
          <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
          <a href="accountPage.php" class="navbar_user">
            <i class="fa-solid fa-user"></i>
            <p>Thông tin cá nhân</p>
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

  <div class="side-bar">
    <div class="backToHome">
      <a href="homePage.php" style="text-decoration: none; color: black;">
        <div class="container-function-selection">
          <button class="button-function-selection" style="background-color: #6aa173; margin-top: 35px;">
            <i class="fa-solid fa-house" style="font-size: 20px; color: #FAD4AE;"></i>
          </button>
          <p>Trang chủ</p>
        </div>
      </a>
    </div>
    <a href="wareHouse.php" style="text-decoration: none; color: black;">
      <div class="container-function-selection">
        <button class="button-function-selection">
          <i class="fa-solid fa-warehouse" style="font-size: 20px; color: #FAD4AE;"></i>
        </button>
        <p>Kho hàng</p>
      </div>
    </a>
    <a href="customer.php" style="text-decoration: none; color: black;">
      <div class="container-function-selection">
        <button class="button-function-selection">
          <i class="fa-solid fa-users" style="font-size: 20px; color: #FAD4AE;"></i>
        </button>
        <p>Khách hàng</p>
      </div>
    </a>
    <a href="orderPage.php" style="text-decoration: none; color: black;">
      <div class="container-function-selection">
        <button class="button-function-selection">
          <i class="fa-solid fa-list-check" style="font-size: 20px; color: #FAD4AE;"></i>
        </button>
        <p>Đơn hàng</p>
      </div>
    </a>
    <a href="analyzePage.php" style="text-decoration: none; color: black;">
      <div class="container-function-selection">
        <button class="button-function-selection">
          <i class="fa-solid fa-chart-simple" style="font-size: 20px; color: #FAD4AE;"></i>
        </button>
        <p>Thống kê</p>
      </div>
    </a>
    <a href="accountPage.php" style="text-decoration: none; color: black;">
      <div class="container-function-selection">
        <button class="button-function-selection">
          <i class="fa-solid fa-circle-user" style="font-size: 20px; color: #FAD4AE;"></i>
        </button>
        <p>Tài khoản</p>
      </div>
    </a>
  </div>

  <div class="container-main">
    <div class="dashboard-overview">
      <div class="overview-card">
        <h3>50</h3>
        <p>Đơn hàng chưa xử lý</p>
      </div>
      <div class="overview-card">
        <h3>120</h3>
        <p>Sản phẩm trong kho</p>
      </div>
      <div class="overview-card">
        <h3>30</h3>
        <p>Khách hàng mới</p>
      </div>
      <div class="overview-card">
        <h3>5</h3>
        <p>Sản phẩm sắp hết hạn</p>
      </div>
    </div>

    <!-- Phần đơn hàng chưa xử lý -->
    <div class="order-section">

      <p class="section-title">Đơn hàng chưa xử lý</p>
      <a class="more-detail" href="orderPage.php"> Xem thêm</a>
      <div class="overview-order">
        <div><img class="avatar-customer" src="../image/sontung.webp" alt="Customer"></div>
        <div class="info-overview-order">
          <p>Sơn Tùng <span class="label customer">Customer</span></p>
          <p>Ngày đặt hàng: 15/03/2025</p>
        </div>
        <div><a href="customer.php" style="text-decoration: none; color: black;"><button class="button-handle">Xử
              lý</button></a></div>
      </div>
      <div class="overview-order">
        <div><img class="avatar-customer" src="../image/baolam.jpg" alt="Customer"></div>
        <div class="info-overview-order">
          <p>Diễm <span class="label customer">Customer</span></p>
          <p>Ngày đặt hàng: 16/03/2025</p>
        </div>
        <div><a href="customer3.php" style="text-decoration: none; color: black;"><button class="button-handle">Xử
              lý</button></a></div>
      </div>
      <div class="overview-order">
        <div><img class="avatar-customer" src="../image/hth.webp" alt="Customer"></div>
        <div class="info-overview-order">
          <p>HieuThuHai <span class="label customer">Customer</span></p>
          <p>Ngày đặt hàng: 17/03/2025</p>
        </div>
        <div><a href="customer2.php" style="text-decoration: none; color: black;"><button class="button-handle">Xử
              lý</button></a></div>
      </div>
    </div>

    <!-- Phần hàng cần chú ý -->
    <div class="inventory-section">
      <p class="section-title">Hàng cần chú ý</p>
      <a class="more-detail" href="wareHouse.php"> Xem thêm</a>
      <div class="overview-order">
        <div><img class="avatar-customer" src="../image/product1.webp" alt="Product"></div>
        <div class="info-overview-order">
          <p>Bắp cải hữu cơ <span class="label product">Product</span></p>
          <p>Ngày hết hạn: 20/03/2025</p>
          <p>Kho: 34</p>
        </div>
        <div><a href="wareHouse.php" style="text-decoration: none; color: black;"><button class="button-handle">Xử
              lý</button></a></div>
      </div>
      <div class="overview-order">
        <div><img class="avatar-customer" src="../image/product2.webp" alt="Product"></div>
        <div class="info-overview-order">
          <p>Bó xôi hữu cơ <span class="label product">Product</span></p>
          <p>Ngày hết hạn: 21/03/2025</p>
          <p>Kho: 40</p>
        </div>
        <div><a href="wareHouse.php" style="text-decoration: none; color: black;"><button class="button-handle">Xử
              lý</button></a></div>
      </div>
      <div class="overview-order">
        <div><img class="avatar-customer" src="../image/product3.webp" alt="Product"></div>
        <div class="info-overview-order">
          <p>Cà chua hữu cơ <span class="label product">Product</span></p>
          <p>Ngày hết hạn: 22/03/2025</p>
          <p>Kho: 3</p>
        </div>
        <div><a href="wareHouse.php" style="text-decoration: none; color: black;"><button class="button-handle">Xử lý</button></a></div>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="./asset/bootstrap/js/bootstrap.bundle.min.js"></script>
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