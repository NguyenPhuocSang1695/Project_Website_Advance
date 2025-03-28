<!DOCTYPE html>
<html lang="vi">

<head>
  <title>Đơn Hàng Số 3</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../style/header.css">
  <link rel="stylesheet" href="../style/order.css">
  <link rel="stylesheet" href="../style/sidebar.css">
  <link href="../icon/css/all.css" rel="stylesheet">
  <link href="../style/generall.css" rel="stylesheet">
  <link href="../style/main.css" rel="stylesheet">
  <link href="../style/orderDetail1.css" rel="stylesheet">
  <link href="asset/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="../style/LogInfo.css" rel="stylesheet">
  <link rel="stylesheet" href="../style/reponsiveOrder-detail.css">

  <link rel="stylesheet" href="styles.css">
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
          <a href="orderPage1.php" style="text-decoration: none; color: black;">
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
    <div class="header-left-section">
      <p style="
       font-size: 30px;
       font-weight: bold; position: relative;
       left: -25px;">Đơn số 3 </p>
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

  <div class="content-wrapper">
    <div class="order-container">
      <div class="order-header">
        <div class="breadcrumb">
          <a href="orderPage1.php">Đơn hàng</a> > <span>DH003</span>
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
              <td>2</td>
              <td>01/04/2025</td>
              <td>30/04/2025</td>
              <td class="status delivered">Đã giao hàng</td>
              <td class="status paid">Online-Banking <i class="fa-solid fa-money-check-dollar"
                  style="font-size: 1rem;"></i></td>
            </tr>
          </tbody>
        </table>
      </div>
      <!-- Main Content -->
      <div class="main-content">
        <div class="left-section">
          <div class="section products">
            <div class="section-header">
              <span style="color:#21923c;"><i class="fa-regular fa-circle" style="  margin-right: 5px;"></i>Chi tiết đơn
                hàng</span>
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
                <tr>
                  <td>
                    <img src="../../assets/images/CAY2.PNG" alt="Product Image">
                    <div class="product-info">
                      <span class="product-name">Cay j ko bt ten</span><br>
                      <span class="sku">SKU: QJ-0001</span><br>
                    </div>
                  </td>
                  <td>1</td>
                  <td>1,100,000 đ</td>
                  <td class="hide-display">1,100,000 đ</td>
                </tr>
                <tr>
                  <td>
                    <img src="../../assets/images/CAY1.PNG" alt="Product Image">
                    <div class="product-info">
                      <span class="product-name">Chac cay xuong rong</span><br>
                      <span class="sku">SKU: JJ-0001</span><br>
                    </div>
                  </td>
                  <td>1</td>
                  <td>550,000 đ</td>
                  <td class="hide-display">550,000 đ</td>
                </tr>
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
                <span>2</span>
              </div>
              <div class="payment-row">
                <span>Tổng tiền hàng</span>
                <span>1,650,000 đ</span>
              </div>
              <div class="payment-row">
                <span>Giảm giá</span>
                <span>0 đ</span>
              </div>
              <div class="payment-row">
                <span>Vận chuyển</span>
                <span>20,000 đ</span>
              </div>
              <div class="payment-row">
                <span>Ghi Chú Đơn Hàng</span>
                <input type="text" placeholder="a" value="An dep zai :))">
              </div>
              <div class="payment-row total">
                <span>Tổng giá trị đơn hàng</span>
                <span>1,505,000 đ</span>
              </div>
              <div class="payment-row paid">
                <span>Đã thanh toán</span>
                <span>1,505,000 đ</span>
              </div>
            </div>
          </div>
        </div>
        <!-- Right Section -->
        <div class="right-section">

          <div class="section source">
            <div class="section-header">
              <span>Thông Tin Người Mua</span>

            </div>
            <div class="section-header">

              <p style="color:#007bff">Hiếu Thứ Hai</p>
            </div>

            <div class="source-details">
              <div>
                <span style=" font-size: 16px; font-weight: bold; padding-bottom: 15px; padding-right: 4rem; display:flex;">Người Liên
                  Hệ</span>
                <p style="color:#007bff;font-weight: bold;padding-bottom:10px">Hiếu Thứ Hai</p>
              </div>
              <span>SĐT:</span>
              <span class="highlight">012345678910JQKA</span>
            </div>
          </div>
          <div class="section shipping">
            <div class="section-header">
              <span>Địa Chỉ Giao Hàng</span>
            </div>
            <div class="shipping-details">
              <span>Đường An Dương Vương </span><br>
              <span> Quận 5, Hồ Chí Minh, Vietnam</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <scriptt src="script.js"></scriptt>
</body>

</html>