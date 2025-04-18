<!DOCTYPE html>
<html lang="en">

<head>
  <title>Đơn Hàng</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../style/header.css">
  <link rel="stylesheet" href="../style/sidebar.css">
  <link href="../icon/css/all.css" rel="stylesheet">
  <link href="../style/generall.css" rel="stylesheet">
  <link href="../style/main1.css" rel="stylesheet">
  <link href="../style/orderStyle1.css" rel="stylesheet">
  <link href="../style/LogInfo.css" rel="stylesheet">
  <link href="asset/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../style/reponsiveOrder.css">

</head>
<style>
  /* Default display for responsive columns */
  .hide-index-tablet, 
  .hide-index-mobile {
    display: table-cell;
  }

  #filter-button {
    margin-bottom: 20px;
  }

  .modal .modal-header {
    background-color: #6aa173;
    color: white;
  }

  .modal .modal-footer .btn-primary {
    background-color: #6aa173;
    border-color: #6aa173;
  }
  .one-line-paragraph {
            width: 200px; 
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
}
</style>
<body>
  <div class="header">
    <div class="header-left-section">
      <p class="header-left-title">Đơn Hàng</p>
    </div>
    <div class="header-middle-section">
      <img class="logo-store" src="../../assets/images/LOGO-2.jpg" alt="Logo">
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
        <img class="avatar" src="../../assets/images/admin.jpg" alt="Avatar" data-bs-toggle="offcanvas"
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

  <div class="index-menu">
    <i class="fa-solid fa-bars" data-bs-toggle="offcanvas" href="#offcanvasExample" role="button"
      aria-controls="offcanvasExample"></i>
    <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
      <div style="border-bottom-width: 1px; border-bottom-style: solid; border-bottom-color: rgb(176, 176, 176);"
        class="offcanvas-header">
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
          <div class="container-function-selection">
            <button class="button-function-selection">
              <i class="fa-solid fa-users" style="font-size: 20px; color: #FAD4AE;"></i>
            </button>
            <p>Khách hàng</p>
          </div>
        </a>
        <a href="orderPage.php" style="text-decoration: none; color: black;">
          <div class="container-function-selection">
            <button class="button-function-selection" style="background-color: #6aa173;">
              <i class="fa-solid fa-list-check" style="font-size: 18px; color: #FAD4AE;"></i>
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
    </div>
  </div>

  <div class="main-container">
    <div class="side-bar">
      <div class="backToHome">
        <a href="homePage.php" style="text-decoration: none; color: black;">
          <div class="container-function-selection">
            <button class="button-function-selection" style="margin-top: 35px;">
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
          <button class="button-function-selection" style="background-color: #6aa173;">
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
    <div class="main-content">
      <div class="container-order-management">
        <div class="container-bar-operation">
          <p style="font-size: 30px; font-weight: 700;">Quản lý đơn hàng</p>
        </div>
        <div class="filter-section">
          <button type="button" class="btn btn-primary" id="filter-button" data-bs-toggle="modal" data-bs-target="#filterModal">
            <i class="fas fa-filter"></i> Bộ lọc
          </button>
        </div>

        <!-- Modal hiển thị thông tin cần lọc -->
        <div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="filterModalLabel">Bộ lọc đơn hàng</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <form id="filter-form">
                  <div class="mb-3">
                    <label for="date-from" class="form-label">Từ ngày:</label>
                    <input type="date" id="date-from" name="date_from" class="form-control">
                  </div>
                  <div class="mb-3">
                    <label for="date-to" class="form-label">Đến ngày:</label>
                    <input type="date" id="date-to" name="date_to" class="form-control">
                  </div>
                  <div class="mb-3">
                    <label for="order-status" class="form-label">Trạng thái:</label>
                    <select id="order-status" name="order_status" class="form-control">
                      <option value="all">Tất cả</option>
                      <option value="execute">Đang xử lý</option>
                      <option value="ship">Đang giao</option>
                      <option value="success">Thành công</option>
                      <option value="fail">Thất bại</option>
                    </select>
                  </div>
                  <div class="mb-3">
                    <label for="city-select" class="form-label">Tỉnh/Thành phố:</label>
                    <select id="city-select" name="city" class="form-control">
                      <option value="">Chọn thành phố</option>
                    </select>
                  </div>
                  <div class="mb-3">
                    <label for="district-select" class="form-label">Quận/Huyện:</label>
                    <select id="district-select" name="district" class="form-control">
                      <option value="">Chọn quận/huyện</option>
                    </select>
                  </div>
                </form>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                <button type="button" id="reset-filter" class="btn btn-warning">Đặt lại</button>
                <button type="submit" form="filter-form" class="btn btn-primary">Áp dụng</button>
              </div>
            </div>
          </div>
        </div>

        <div class="statistic-section">
          <table class="statistic-table"> 
            <thead>
              <tr>
                <th>Mã đơn hàng</th>
                <th class="hide-index-tablet ">Người mua</th>
                <th>Ngày tạo</th>
                <th class="hide-index-mobile">Giá tiền (VND)</th>
                <th>Trạng thái</th>
                <th>Địa chỉ giao hàng</th>
                <th></th>
              </tr>
            </thead>
            <tbody id="order-table-body">
              <!-- Dynamic content will be inserted here by JavaScript -->
            </tbody>
          </table>
        </div>
        <div id="updateStatusOverlay" class="overlay" style="display: none;">
          <div class="popup">
            <h3>Cập nhật trạng thái đơn hàng</h3>
            <div id="statusOptions" class="status-options"></div>
            <button onclick="closeUpdateStatusPopup()" class="close-btn">Đóng</button>
          </div>
        </div>
      <div class="select_list" id="pagination-container">
        <button id="prevPage"><<</button>
        <div id="pageNumbers"></div>
        <button id="nextPage">>></button>
      </div>
    </div>
  </div>
  <script src ="../js/checklog.js"></script>
  <script src="./asset/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../js/orderPage.js"></script>
</body>
</html>

