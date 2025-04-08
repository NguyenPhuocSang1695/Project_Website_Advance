<?php
include ('connect.php');
if ($myconn->connect_error) {
    die("Connection failed: " . $myconn->connect_error);
}
// session_start();
// if (!isset($_SESSION['username'])) {
//     header("Location: ../index.html"); 
//     exit();
// }
$username= '';
$email= '';
$role= '';
$phone= '';
$address= '';
$FullName= '';
$sql = "SELECT * FROM users WHERE Role = 'admin'
        ";
$result = $myconn->query($sql);
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $username = $row['Username'];
        $FullName = $row['FullName'];
        $email = $row['Email'];
        $role = $row['Role'];
        $phone = $row['Phone'];
        $address = $row['Address'] . ' , ' . $row['District'] . ', ' . $row['Province'];
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Tài khoản</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link rel="stylesheet" href="../style/header.css">
  <link rel="stylesheet" href="../style/sidebar.css">
  <link rel="stylesheet" href="../icon/css/all.css">
  <link rel="stylesheet" href="../style/generall.css">
  <link rel="stylesheet" href="../style/main.css">
  <link rel="stylesheet" href="../style/accountStyle.css">
  <link rel="stylesheet" href="./asset/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="../style/LogInfo.css">
  <link rel="stylesheet" href="../style/reponsiveAccount.css">
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
          <a href="orderPage.html" style="text-decoration: none; color: black;">
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
          <a href="accountPage.php" style="text-decoration: none; color: black;">
            <div class="container-function-selection">
              <button class="button-function-selection" style="background-color: #6aa173;">
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
      <p class="header-left-title">Tài khoản</p>
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
        <div style=" 
            border-bottom-width: 1px;
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
          <a href="" class="navbar_user">
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
      <a href="orderPage.html" style="text-decoration: none; color: black;">
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
      <a href="accountPage.php" style="text-decoration: none; color: black;">
        <div class="container-function-selection">
          <button class="button-function-selection" style="background-color: #6aa173;">
            <i class="fa-solid fa-circle-user" style="
                     font-size: 20px;
                     color: #FAD4AE;
                 "></i>
          </button>
          <p>Tài khoản</p>
        </div>
      </a>
    </div>
    <div class="content-area">
      <!-- Phần tiêu đề và thông tin tài khoản -->
      <div class="header-section">
        <div class="header-left">
          <h1>Thông tin tài khoản</h1>
          <p>Chi tiết thông tin của nhân viên hiện tại</p>
        </div>
        <div class="header-right">
          <div class="user-info">
            <span class="user-icon">NC</span>
            <div style="display: flex; flex-direction: column;">
              <span class="user-name"><?php echo $username ?></span>
              <span class="user-email">📧 <?php echo $email ?></span>
            </div>
          </div>
        </div>
      </div>
      <div class="main-content">
        <div class="personal-info">
          <h1>Thông tin cá nhân</h1>
          <p>Thông tin chi tiết của nhân viên hiện tại</p>
          
          <div class="info-container">
            <div class="info-row">
              <label>Họ và tên:</label>
              <span><?php echo $FullName ?></span>
            </div>
            <div class="info-row">
              <label>Ngày/ tháng / năm sinh:</label>
              <span>29/02/2005</span>
            </div>
            <div class="info-row">
              <label>Số điện thoại:</label>
              <span><?php echo $phone?></span>
            </div>
            <div class="info-row">
              <label>Email:</label>
              <span><?php echo $email?></span>
            </div>
            <div class="info-row">
              <label>Địa chỉ:</label>
              <span><?php echo $address?></span>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php   }
} else {
    echo "0 results";
}
?>
  <script src="./asset/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- Thay thế chỗ dữ liệu tĩnh này bằng dữ liệu database + PHP -->
  <script>
    const userRole = "Chủ cửa hàng"; 

    // Ẩn nút chỉnh sửa nếu không có quyền
    const editBtn = document.getElementById("editInfoBtn");
    if (userRole !== "Chủ cửa hàng") {
      editBtn.style.display = "none"; // Ẩn nút nếu không phải chủ
    }
  </script>
</body>
</html>