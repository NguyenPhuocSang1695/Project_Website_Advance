<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kho hàng</title>

  <link href="../style/main_warehouse1.css" rel="stylesheet">
  <link rel="stylesheet" href="../style/header.css">
  <link rel="stylesheet" href="../style/sidebar.css">
  <link href="../icon/css/all.css" rel="stylesheet">
  <link href="../style/generall.css" rel="stylesheet">
  <link href="../style/main.css" rel="stylesheet">
  <link href="../style/LogInfo.css" rel="stylesheet">
  <link href="asset/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../style/responsiveWarehouse.css">
  <style>
    /* Popup overlay cho thêm sản phẩm */
    .add-product-overlay {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.5);
      /* Nền mờ */
      z-index: 1000;
      justify-content: center;
      align-items: center;
      margin: auto;
    }

    .add-product-content {
      background: #fff;
      border-radius: 10px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
      width: 400px;
      max-height: 80vh;
      overflow-y: auto;
      position: relative;
    }

    /* Popup overlay */
    .product-details-overlay {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.5);
      /* Nền mờ */
      z-index: 1000;
      justify-content: center;
      align-items: center;
    }

    /* .product-details-overlay.active {
      display: flex;
    } */

    .product-details-content {
      background: #fff;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
      width: 400px;
      max-height: 80vh;
      overflow-y: auto;
      position: relative;
    }

    /* Đảm bảo nội dung trong popup không bị tràn */
    .details-grid p,
    .form-group label,
    .form-group input {
      font-size: 14px;
    }

    .form-grid {
      grid-template-columns: 1fr 2fr;
      gap: 15px;
    }

    .image-preview,
    .edit-image-preview {
      max-width: 150px;
    }

    /* Responsive */
    @media only screen and (max-width: 29.9375em) {

      .product-details-content,
      .add-product-content {
        width: 90%;
        padding: 15px;
      }

      .form-grid {
        grid-template-columns: 1fr;
      }

      .details-grid p,
      .form-group label,
      .form-group input {
        font-size: 12px;
      }

      .image-preview,
      .edit-image-preview {
        max-width: 100px;
      }
    }

    @media only screen and (min-width: 30em) and (max-width: 63.9375em) {
      .product-details-content {
        width: 70%;
      }

      .add-product-content {
        padding: 20px;
        width: 66%;
      }
    }

    @media only screen and (min-width: 64em) {
      .product-details-content {
        width: 40%;
      }

      .add-product-content {
        padding: 25px;
        width: 550px;
      }

      .form-grid {
        grid-template-columns: 1fr 2fr;
        gap: 10px;
      }
    }

    /* Form thêm sản phẩm  */
    #add-product-btn {
      width: 150px;
    }

    .card {
      background: white;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      width: 350px;
      max-width: 100%;
      width: 100%;
    }

    /* Tiêu đề của form */
    .card h2 {
      text-align: center;
      font-size: 24px;
      color: #333;
      margin-bottom: 20px;
    }

    /* Cài đặt khoảng cách cho các trường nhập liệu */
    .form-group {
      margin-bottom: 15px;
    }

    label {
      font-weight: bold;
      font-size: 14px;
      color: #555;
      display: block;
      margin-bottom: 5px;
    }

    input,
    textarea,
    select {
      width: 100%;
      padding: 10px;
      font-size: 14px;
      border-radius: 5px;
      border: 1px solid #ccc;
      background-color: #f9f9f9;
    }

    /* Cải tiến textarea */
    textarea {
      resize: vertical;
      height: 80px;
    }

    /* Hiển thị ảnh trước khi gửi */
    .image-preview {
      max-width: 200px;
      margin-top: 10px;
      display: block;
      margin-left: auto;
      margin-right: auto;
      border-radius: 8px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    /* Nút gửi form */
    .btn {
      width: 100%;

      color: white;
      padding: 12px;
      border: none;
      border-radius: 5px;
      font-size: 16px;
      cursor: pointer;
      text-align: center;
      margin-top: 20px;
    }

    /* Các lỗi hoặc thông báo */
    .alert {
      padding: 10px;
      margin-bottom: 15px;
      background-color: #f44336;
      color: white;
      border-radius: 5px;
      text-align: center;
      font-size: 14px;
    }

    .alert-success {
      background-color: #4CAF50;
    }

    .image-preview {
      max-width: 200px;
      margin-top: 10px;
      display: block;
      margin-left: auto;
      margin-right: auto;
      border-radius: 8px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    /* Mobile responsive */
    @media (max-width: 600px) {
      .card {
        width: 90%;
      }
    }

    .category-note {
      font-size: 12px;
      color: #777;
      margin-top: 5px;
    }
  </style>
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
              <button class="button-function-selection" style="background-color: #6aa173;">
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
      <p class="header-left-title">Kho hàng</p>
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
          <button class="button-function-selection" style="margin-top: 35px;">
            <i class="fa-solid fa-house" style="font-size: 20px; color: #FAD4AE;"></i>
          </button>
          <p>Trang chủ</p>
        </div>
      </a>
    </div>
    <a href="wareHouse.php" style="text-decoration: none; color: black;">
      <div class="container-function-selection">
        <button class="button-function-selection" style="background-color: #6aa173;">
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
  <div class="container-main-warehouse">
    <div class="warehouse-management">
      <div class="search-container">
        <input class="search-input" type="text" placeholder="Tìm kiếm sản phẩm..." onkeyup="searchProducts()">
        <button class="search-btn">
          <i class="fa-solid fa-magnifying-glass"></i>
        </button>
      </div>

      <div class="management-content">
        <div class="products-section">
          <div class="section-header">
            <h2 class="section-title">Quản Lý Kho Hàng</h2>
            <button class="btn btn-success add-product-btn" id="add-product-btn" onclick="showAddProductOverlay()">Thêm
              Sản Phẩm</button>
          </div>

          <table class=" products-table" id="productsTable">
            <thead>
              <tr>
                <th>Ảnh</th>
                <th>Tên sản phẩm</th>
                <th>Danh mục</th>
                <th>Giá</th>
              </tr>
            </thead>
            <tbody id="productsBody">
              <!-- Sản phẩm mốt thế PHP vô đây -->
            </tbody>
          </table>
          <!-- Nút phân trang  -->
          <div class="pagination"></div>
        </div>
      </div>
    </div>

    <!-- Popup overlay cho thông tin sản phẩm -->
    <div class="product-details-overlay" id="productDetailsOverlay">
      <div class="product-details-content" id="productDetailsContent"></div>
    </div>

    <!-- Popup overlay cho add product-->
    <div class="add-product-overlay" id="addProductOverlay">
      <div class="add-product-content">
        <!-- Đóng form  -->
        <button type="button" id="closeButton" class="btn btn-secondary"
          style="margin: 0 0 10px 0;width: 30px; height: 30px; display: flex; justify-content: center; align-items: center;"
          id="closeButton"><i class="fa-solid fa-xmark"></i></button>
        <!-- Form thêm sản phẩm  -->
        <div class="card">
          <h2>Thêm Sản Phẩm</h2>
          <form id="productForm" method="POST" enctype="multipart/form-data">

            <div class="form-group">
              <label for="productName">Tên sản phẩm</label>
              <input type="text" id="productName" name="productName" required placeholder="Nhập tên sản phẩm">
            </div>

            <div class="form-group">
              <label for="categoryID">Danh mục</label>
              <select id="categoryID" name="categoryID" required>
                <?php
                require_once '../../php-api/connectdb.php'; // Kết nối tới CSDL
                $conn = connect_db();

                // Truy vấn lấy danh mục
                $sql = "SELECT CategoryID, CategoryName FROM categories ORDER BY CategoryID ASC";
                $result = $conn->query($sql);

                if ($result && $result->num_rows > 0) {
                  // Lặp qua từng danh mục và hiển thị
                  while ($row = $result->fetch_assoc()) {
                    $categoryID = htmlspecialchars($row['CategoryID']);
                    $categoryName = htmlspecialchars($row['CategoryName']);
                    echo "<option value='$categoryID'>$categoryName</option>";
                  }
                } else {
                  // Nếu không có danh mục
                  echo "<option value=''>Không có danh mục</option>";
                }

                $conn->close();
                ?>
              </select>
            </div>

            <div class="form-group">
              <label for="price">Giá</label>
              <input type="number" id="price" name="price" required placeholder="Nhập giá sản phẩm" min="0">
            </div>

            <div class="form-group">
              <label for="description">Mô tả</label>
              <textarea id="description" name="description" required placeholder="Nhập mô tả sản phẩm"></textarea>
            </div>

            <div class="form-group">
              <label for="imageURL">Ảnh sản phẩm</label>
              <input type="file" id="imageURL" name="imageURL" required accept=".jpg ,.jpeg,.png" multiple>
              <p class="category-note">Chọn ảnh sản phẩm (PNG, JPG, JPEG)</p>
              <p class="category-note">Kích thước tối đa: 2MB</p>
              <p class="category-note">Kích thước tối thiểu: 300x300px</p>
              <p class="category-note">Tối đa 5 ảnh</p>
              <p class="category-note">Chọn ảnh sản phẩm (PNG, JPG, JPEG)</p>
              <p class="category-note">Kích thước tối đa: 2MB</p>
              <p class="category-note">Kích thước tối thiểu: 300x300px</p>
              <img id="imagePreview" class="image-preview" src="#" alt="Preview image" style="display:none;">
            </div>

            <button type="submit" class="btn btn-success">Thêm Sản Phẩm</button>
          </form>


          <script>
            document.getElementById('imageURL').addEventListener('change', function(event) {
              const file = event.target.files[0];

              // Kiểm tra nếu không có file
              if (!file) return;

              // Kiểm tra dung lượng (<= 2MB)
              const maxSize = 2 * 1024 * 1024; // 2MB
              if (file.size > maxSize) {
                alert("Ảnh vượt quá kích thước tối đa 2MB!");
                event.target.value = ""; // Reset input
                document.getElementById('imagePreview').style.display = 'none';
                return;
              }

              // Nếu hợp lệ, hiển thị ảnh preview
              const reader = new FileReader();
              reader.onload = function() {
                const imagePreview = document.getElementById('imagePreview');
                imagePreview.style.display = 'block';
                imagePreview.src = reader.result;
              };
              reader.readAsDataURL(file);
            });
            // Hàm hiển thị overlay
            function showAddProductOverlay() {
              const overlay = document.getElementById("addProductOverlay");
              if (overlay) {
                overlay.style.display = "flex";
              }
            }
            document.getElementById('closeButton').addEventListener('click', function() {
              const overlay = document.getElementById('addProductOverlay');
              if (overlay.style.display === 'flex') {
                overlay.style.display = 'none'; // Ẩn overlay khi nhấn nút đóng
              }
            });
          </script>

        </div>
      </div>
    </div>
  </div>

  <script src="./asset/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../js/add-product.js"></script>
  <script src="../js/checklog.js"></script>
  <!-- <script src="../js/hienthisanpham_khohang.js"></script> -->
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