<!DOCTYPE html>
<html lang="en">

<head>
  <title>Đơn Hàng Số 1</title>
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
       font-weight: bold;">Đơn số 1 </p>
    </div>
    <div class="header-middle-section">
      <img class="logo-store" src="../images/image/logo.webp">
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
        <img class="avatar" class src="../image/chuong-avatar.jpg" alt="" data-bs-toggle="offcanvas"
          data-bs-target="#offcanvasWithBothOptions" aria-controls="offcanvasWithBothOptions">

      </div>
      <div class="offcanvas offcanvas-end" data-bs-scroll="true" tabindex="-1" id="offcanvasWithBothOptions"
        aria-labelledby="offcanvasWithBothOptionsLabel">
        <div style=" border-bottom-width: 1px;
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
  <!-- Chi tiết hóa đơn -->
  <div class="container-order-detail-2">
    <div class="container-bar-operation">
      <p style="font-size: 30px; margin-bottom: 50px;">Chi tiết đơn hàng</p>
    </div>

    <div class="order-summary">
      <div class="order-info">
        <h2 class="section-title">Thông tin Đơn hàng</h2>
        <div class="info-item">
          <p><strong>Mã đơn hàng:</strong> <span>1</span></p> <br>
          <p><strong>Ngày lấy dự kiến:</strong> <span>Thứ 5, 14/05/2020</span></p> <br>
          <p><strong>Ngày giao dự kiến:</strong> <span>Thứ 7, 16/05/2020</span></p><br>
          <p><strong>Trạng thái:</strong> <span class="status pending">Đang xử lý</span></p><br>
          <p><strong>Nhân viên trả hàng:</strong> <span>Trần Thị Hương - 097723</span></p><br>
        </div>
      </div>

      <div class="customer-info">
        <h2 class="section-title">Thông tin Khách hàng</h2>
        <div class="info-item">
          <p><strong>Họ tên:</strong> <span>Trần Thị Hương</span></p> <br>
          <p><strong>Địa chỉ giao hàng:</strong> <span>Trần Hưng Đạo, Quận 5, TP.HCM</span></p> <br>
          <p><strong>Số điện thoại:</strong> <span>097723</span></p>
        </div>
      </div>
    </div>

    <!-- Chi tiết của sản phẩm -->
    <h2>Chi tiết Sản phẩm</h2>
    <div class="product-card">
      <div>
        <img class="product-image" src="../images/image/cucai.jpg" style="width: 90px; height: 90px; border-radius: 50%;">
      </div>
      <div class="info-overview-order-product">
        <p><strong>Tên sản phẩm:</strong> <span>Dưa leo</span></p>
        <p><strong>Số lượng:</strong> <span>2</span></p>
        <p><strong>Giá mỗi kg:</strong> <span>20,000 VND</span></p>
      </div>
    </div>
    <div class="product-card">
      <div>
        <img class="product-image" src="../images/image/dualeo.jpg"
          style="margin-top: 0; margin-left: 10px; width: 90px; height: 90px; border-radius: 50%;">
      </div>
      <div class="info-overview-order-product">
        <p style="margin-bottom: 15px"><strong>Tên sản phẩm:</strong><span class="space">Dưa leo</span> </p>
        <p><strong>Số lượng:</strong> <span class="space">2</span></p>
        <p><strong>Khối lượng:</strong> <span class="space">0.5 kg</span></p>
        <p><strong>Giá mỗi kg:</strong> <span class="space">10,000 VND</span></p>
      </div>
    </div>

    <div class="Button">
      <div class="top-buttons">
        <div class="back-button-delete">
          <button class="btn1" onclick="openDeleteModal()">
            <i class="fa-solid fa-trash-can"></i>
            Xóa <span class="hide-on-mobile"> đơn hàng</span>
          </button>
        </div>
        <div class="back-button">
          <a href="orderPage1.php" class="btn_1">
            <i class="fa-solid fa-rotate-left"></i>
            Quay lại
            <span class="hide-on-mobile"> danh sách</span>
          </a>
        </div>
      </div>

      <div class="back-button-edit">
        <button class="btn2" onclick="openEditModal()">
          <i class="fa-solid fa-pen-to-square"></i>Sửa
          <span class="hide-on-mobile"> đơn hàng</span>
        </button>
      </div>
    </div>


    <div id="deleteModal" class="modal">
      <div class="modal-content">
        <span class="close" onclick="closeDeleteModal()">&times;</span>
        <h2>Xác nhận xóa đơn hàng</h2>
        <p>Bạn có chắc chắn muốn xóa đơn hàng này không?</p>
        <div class="modal-buttons">
          <button class="modal-confirm" onclick="confirmDelete()">Đồng ý</button>
          <button class="modal-cancel" onclick="closeDeleteModal()">Hủy</button>
        </div>
      </div>
    </div>


    <div id="editModal" class="modal">
      <div class="modal-content">
        <span class="close" onclick="closeEditModal()">&times;</span>
        <h2>Xác nhận sửa đơn hàng</h2>
        <p>Bạn có chắc chắn muốn sửa đơn hàng này không?</p>
        <div class="modal-buttons">
          <button class="modal-confirm" onclick="confirmEdit()">Đồng ý</button>
          <button class="modal-cancel" onclick="closeEditModal()">Hủy</button>
        </div>
      </div>
    </div>

    <script src="./asset/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script>
      const editModal = document.getElementById("editModal");
      const deleteModal = document.getElementById("deleteModal");

      // Edit functions
      function openEditModal() {
        editModal.style.display = "block";
      }

      function closeEditModal() {
        editModal.style.display = "none";
      }

      function confirmEdit() {
        editModal.style.display = "none";
        alert("Đã sửa đơn hàng thành công!");
      }

      // Delete functions
      function openDeleteModal() {
        deleteModal.style.display = "block";
      }

      function closeDeleteModal() {
        deleteModal.style.display = "none";
      }

      function confirmDelete() {
        deleteModal.style.display = "none";
        alert("Đã xóa đơn hàng thành công!");
      }

      // Close modals when clicking outside
      window.onclick = function (event) {
        if (event.target == editModal) {
          editModal.style.display = "none";
        }
        if (event.target == deleteModal) {
          deleteModal.style.display = "none";
        }
      }
    </script>
  </div>
</body>

</html>