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
  <link href="../style/main.css" rel="stylesheet">
  <link href="../style/orderStyle.css" rel="stylesheet">
  <link href="../style/LogInfo.css" rel="stylesheet">
  <!-- Add bootstrap -->
  <link href="asset/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <!-- Add reponsive -->
  <link rel="stylesheet" href="../style/reponsiveOrder.css">
  <style>
    .filter-section table {
      border-spacing: 10px;
      border-collapse: separate;
    }

    .filter-section td {
      padding: 5px;
    }

    .filter-section select,
    .filter-section input {
      width: 100%;
    }

    .select_list {
      display: flex;
      justify-content: center;
      align-items: center;
      margin: 20px 0;
    }

    .select_list {
      display: flex;
      justify-content: center;
      align-items: center;
      margin: 20px 0;
    }

    .select_list button {
      padding: 10px 20px;
      font-size: 16px;
      margin: 0 5px;
      border: 1px solid #ddd;
      background-color: #f9f9f9;
      cursor: pointer;
      border-radius: 4px;
    }

    .select_list button:hover {
      background-color: #e0e0e0;
    }

    .select_list span {
      margin: 0 5px;
    }
  </style>
</head>

<body>
  <div class="header">
    <div class="header-left-section">
      <p class="header-left-title">Đơn Hàng</p>
    </div>
    <div class="header-middle-section">
      <img class="logo-store" src="../image/logo.webp">
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
        <img class="avatar" class src="../image/chuong-avatar.jpg" alt="" data-bs-toggle="offcanvas"
          data-bs-target="#offcanvasWithBothOptions" aria-controls="offcanvasWithBothOptions">
      </div>
      <div class="offcanvas offcanvas-end" data-bs-scroll="true" tabindex="-1" id="offcanvasWithBothOptions"
        aria-labelledby="offcanvasWithBothOptionsLabel">
        <div style=" 
            border-bottom-width: 1px;
            border-bottom-style: solid;
            border-bottom-color: rgb(176, 176, 176);" class="offcanvas-header">
          <img class="avatar" class src="../image/chuong-avatar.jpg" alt="">
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
  <div class="index-menu">
    <i class="fa-solid fa-bars" data-bs-toggle="offcanvas" href="#offcanvasExample" role="button"
      aria-controls="offcanvasExample">
    </i>
    <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
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
            <button class="button-function-selection" style="background-color: #6aa173; ">
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
          <button class="button-function-selection" style="background-color: #6aa173;">
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
    <div class="main-content">
      <div class="container-order-management">
        <div class="search-container-order">
          <input class="search-bar-order" type="text" placeholder="Tìm kiếm">
          <a href="orderDetail1.html">
            <button class="search-icon-order">
              <i class="fa-solid fa-magnifying-glass"></i>
            </button>
          </a>
        </div>
        <div class="container-bar-operation">
          <p style="font-size: 30px;
                    font-weight:700px;">
            Quản lý đơn hàng</p>
        </div>
        <!-- Phím lọc -->
        <div class="filter-option">
          <button class="btn btn-success dropdown-toggle" type="button" data-bs-toggle="dropdown"
            aria-expanded="false"><i class="fa-solid fa-filter">
              <p style="font-family:Arial, sans-serif;margin-left: 3px; ">
                Bộ lọc</p>
            </i></button>
          <ul class="dropdown-menu">
            <li>
              <label for="date-from">
                <p style="margin-left: 15px; font-weight: bold;">Từ ngày:</p>
              </label>
              <input type="date" id="date-from" name="date-from">
            </li> <br>
            <li>
              <label style="margin-left: 10px;font-weight: bold;" for="end-date">Đến ngày:</label>
              <input type="date" id="end-date" name="end-date" name="date-end">
            </li><br>
            <li><label style="margin-left: 10px;font-weight: bold;" for="order-status"> Đơn hàng:</label>
              <select id="order-status">
                <option value="all">Tất cả</option>
                <option value="pending">Chưa xử lý</option>
                <option value="confirmed">Đã xác nhận</option>
                <option value="delivered">Đã giao</option>
                <option value="cancelled">Đã hủy</option>
              </select>
            </li> <br>
            <li>
              <label style="margin-left: 10px;font-weight: bold;" for="district">Huyện/Quận:</label>
              <select id="district">
                <option value="all">Tất cả</option>
                <option value="quan-1">Quận 1</option>
                <option value="quan-2">Quận 2</option>
                <option value="quan-3">Quận 3</option>
                <option value="quan-4">Quận 4</option>
                <option value="quan-5">Quận 5</option>
                <option value="quan-6">Quận 6</option>
                <option value="quan-7">Quận 7</option>
                <option value="quan-12">Quận 12</option>
                <option value="huyen-hoc-mon">Hóc Môn</option>
                <option value="huyen-thu-duc">Thủ Đức</option>
                <option value="quan-binh-chanh">Bình Chánh</option>
                <option value="quan-binh-thanh">Bình Thạnh</option>
              </select>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>
            <li style="margin-left:50px ;"> <button type="button" class="filter-button">Lọc</button></li>
          </ul>
        </div>
        <!-- Bộ lọc theo thời gian -->
        <div class="filter-section">
          <form action="locdon.php" method="post">
            <table style="width: 100%;">
              <tr>
                <td>
                  <label for="date-from1" style="font-weight: bold;">Từ ngày:</label>
                  <input type="date" id="date-from1" name="date-from1">
                </td>
                <td>
                  <label for="end-date1" style="font-weight: bold;">Đến ngày:</label>
                  <input type="date" id="end-date1" name="end-date1">
                </td>
                <td>
                  <label for="order-status" style="font-weight: bold;">Tình trạng đơn hàng:</label>
                  <select id="order-status" name="order-status">
                    <option value="all" >Tất cả</option>
                    <option value="pending">Chưa xử lý</option>
                    <option value="confirmed">Đã xác nhận</option>
                    <option value="delivered">Đã giao</option>
                    <option value="cancelled">Đã hủy</option>
                  </select>
                </td>
                <td>
                  <label for="district" style="font-weight: bold;">Huyện/Quận:</label>
                  <select id="district" name="district">
                    <option value="all">Tất cả</option>
                    <option value="quan-1">Quận 1</option>
                    <option value="quan-2">Quận 2</option>
                    <option value="quan-3">Quận 3</option>
                    <option value="quan-4">Quận 4</option>
                    <option value="quan-5">Quận 5</option>
                    <option value="quan-6">Quận 6</option>
                    <option value="quan-7">Quận 7</option>
                    <option value="quan-12">Quận 12</option>
                    <option value="huyen-hoc-mon">Hóc Môn</option>
                    <option value="huyen-thu-duc">Thủ Đức</option>
                    <option value="quan-binh-chanh">Bình Chánh</option>
                    <option value="quan-binh-thanh">Bình Thạnh</option>
                  </select>
                </td>
                <td>
                  <button type="submit" class="filter-button" name="btn">Lọc</button>
                </td>
              </tr>
            </table>
          </form>
        </div>
        <!-- Danh sách đơn hàng -->
        <div class="statistic-section">
          <table class="statistic-table">
            <thead>
              <tr>
                <th>Mã đơn hàng</th>
                <th class="hide-index-tablet">Tên khách hàng</th>
                <th >Ngày tạo</th>
                <th class="hide-index-mobile">Giá tiền (VND)</th>
                <th>Trạng thái</th>
                <th >Địa chỉ</th>
                <th>Xem chi tiết</th>
              </tr>
            </thead>
            <tbody>
              <?php
              include "connect.php";
              $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
              $items_per_page = 5; 
              $offset = ($current_page - 1) * $items_per_page; 
              $total_sql = "SELECT COUNT(*) as total FROM donhang";
              $total_result = mysqli_query($myconn, $total_sql);
              $total_row = mysqli_fetch_assoc($total_result);
              $total_orders = $total_row['total'];
              $total_pages = ceil($total_orders / $items_per_page); 
              $sql = "SELECT * FROM donhang LIMIT $offset, $items_per_page";
              $result = mysqli_query($myconn, $sql);
              if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_array($result)) {
              ?>
                  <tr>
                    <td><?php echo $row["madonhang"] . " "; ?></td>
                    <td class="hide-index-tablet"><?php echo $row["tenkhachhang"]; ?></td>
                    <td ><?php echo $row["ngaytao"]; ?></td>
                    <td class="hide-index-mobile"><?php echo $row["giatien"]; ?> </td>
                    <td>
                      <?php
                      $statusColor = '';
                      switch ($row["trangthai"]) {
                        case 'Đang xử lý':
                          $statusClass = 'status pending';
                          break;
                        case 'Đã xác nhận':
                          $statusClass = 'status pending4';
                          break;
                        case 'Đã giao':
                          $statusClass = 'status pending2';
                          break;
                        case 'Đã hủy':
                          $statusClass = 'status pending3';
                          break;
                        default:
                          $statusClass = 'status pending2';
                      }
                      ?>
                      <button class="<?php echo $statusClass; ?>"><?php echo $row["trangthai"]; ?></button>
                    </td>
                    <td ><?php echo $row["diachi"]; ?></td>
                    <td><a class="detail-info" href="<?php echo "orderDetail" . $row["xemchitiet"] . ".html"; ?>"><span>Xem chi tiết </span><i
                          class="fa-solid fa-circle-info"></i></a></td>

                  <?php } ?>
                <?php } ?>
                  </tr>
            </tbody>
          </table>
        </div>
      </div>
      <div class="select_list">
        <?php if ($current_page > 1): ?>
          <a href="?page=<?php echo $current_page - 1; ?>" id="prevPage" ><button style=" font-style:bold">&laquo;</button></a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
          <button style="margin: 0 5px; <?php echo $current_page == $i ? 'font-weight: bold;' : ''; ?>">
            <a href="?page=<?php echo $i; ?>" style="text-decoration: none; color: inherit;"><?php echo $i; ?></a>
          </button>
        <?php endfor; ?>

        <?php if ($current_page < $total_pages): ?>
          <a href="?page=<?php echo $current_page + 1; ?>" id="nextPage" ><button style=" font-style:bold ">&raquo;</button></a>
        <?php endif; ?>
      </div>
    </div>
  </div>

  
  <script src="./asset/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../js/chuyentrang.js"></script>
</body>

</html>