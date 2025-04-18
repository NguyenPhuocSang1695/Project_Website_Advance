<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Quản lý người dùng</title>
        <link rel="stylesheet" href="../style/header.css">
        <link rel="stylesheet" href="../style/sidebar.css">
        <link href="../icon/css/all.css" rel="stylesheet">
        <link href="../style/generall.css" rel="stylesheet">
        <link href="../style/main1.css" rel="stylesheet">
        <link href="../style/customer1.css" rel="stylesheet">
        <link href="../style/LogInfo.css" rel="stylesheet">
        <link href="asset/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="../style/responsiveCustomer.css">
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
                  <button class="button-function-selection">
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
                <div class="container-function-selection"  >
                  <button class="button-function-selection" style="background-color: #6aa173;">
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
          <p class="header-left-title">Khách hàng</p>
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
              <div style="display: flex; flex-direction: column; height: 95px;">
                <h4 class="offcanvas-title" id="offcanvasWithBothOptionsLabel">Username</h4>
                <h5 id="employee-displayname">Họ tên</h5>  <!-- Thêm id ở đây -->
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
              <button class="button-function-selection" style=" margin-top: 35px;">
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
          <div class="container-function-selection"  >
            <button class="button-function-selection" style="background-color: #6aa173;">
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
        <!-- Nội dung chính -->
        <div class="container-main">
            <div class="left-section-customer">
                <div class="search-container-customer">
                    <input class="search-bar-customer" type="text" placeholder="Tìm kiếm người dùng..." onkeyup="searchUsers()">
                    <button class="search-icon-customer">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </div>
                <button class="add-customer-btn" onclick="showAddUserPopup()">Thêm Người Dùng</button>
                <table class="user-table" id="userTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tên</th>
                            <th>Số điện thoại</th>
                            <th>Email</th>
                            <th>Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody id="userList">
                    </tbody>
                </table>
                <div class="pagination"> 
                  <button onclick="prevPage()" class="page-btn"><<</button>
                  <span id="pageInfo">Trang 1 / 1</span>
                  <button onclick="nextPage()" class="page-btn">>></button>
              </div>
            </div>
        </div>

        <!-- Popup overlay cho thêm người dùng -->
        <div class="user-overlay" id="addUserOverlay">
            <div class="user-content">
                <h3>Thêm Người Dùng Mới</h3>
                <div class="form-group">
                    <label>Họ và tên:</label>
                    <input type="text" id="addFullName">
                </div>
                <div class="form-group">
                    <label>Số điện thoại:</label>
                    <input type="text" id="addPhone">
                </div>
                <div class="form-group">
                    <label>Email:</label>
                    <input type="email" id="addEmail">
                </div>
                <div class="form-group">
                    <label>Mật khẩu:</label>
                    <input type="password" id="addPassword">
                </div>
                <div class="form-group">
                    <label>Giới tính:</label>
                    <select id="addGender">
                        <option value="Nam">Nam</option>
                        <option value="Nữ">Nữ</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Quê quán:</label>
                    <input type="text" id="addHometown">
                </div>
                <div class="form-actions">
                    <button onclick="confirmAddUser()" class="save-btn">Thêm</button>
                    <button onclick="closeAddUserPopup()" class="cancel-btn">Hủy</button>
                </div>
            </div>
        </div>

        <!-- Popup overlay cho chỉnh sửa -->
        <div class="user-overlay" id="userDetailsOverlay">
            <div class="user-content" id="userDetailsContent">
      
            </div>
        </div>

        <!-- Popup overlay cho chỉnh sửa người dùng -->
        <div class="user-overlay" id="editUserOverlay">
            <div class="user-content" id="editUserContent">
            </div>
        </div>
        <script src="../js/hienthikhachhang.js"></script>
        <script src ="../js/checklog.js"></script>
        <script src="./asset/bootstrap/js/bootstrap.bundle.min.js"></script>
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