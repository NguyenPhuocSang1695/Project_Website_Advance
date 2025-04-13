<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('../src/php/connect.php');
require_once('../src/php/token.php');
require __DIR__ . '/../src/Jwt/vendor/autoload.php';
date_default_timezone_set('Asia/Ho_Chi_Minh');

use Firebase\JWT\JWT;
use Firebase\JWT\Key;


// Kiểm tra xem cookie 'token' có tồn tại không
if (!isset($_COOKIE['token'])) {
    header("Location: login.php");
    exit;
}

try {
    // Giải mã token
    $decoded = JWT::decode($_COOKIE['token'], new Key($key, 'HS256'));
    $username = $decoded->data->Username;
} catch (Exception $e) {
    // Nếu token không hợp lệ, hết hạn, hoặc bị chỉnh sửa => chuyển hướng login
    header("Location: login.php");
    exit;
}

if (isset($_SESSION['username'])) {
  $username = $_SESSION['username'];

  $stmt = $conn->prepare("SELECT * FROM users WHERE Username = ?");
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $result = $stmt->get_result();
  $user = $result->fetch_assoc();
  $stmt->close();
};

// Kiểm tra giỏ hàng
$cart_items = isset($_SESSION['cart']) && is_array($_SESSION['cart']) ? $_SESSION['cart'] : [];
//tính tổng
$total_amount = 0;
foreach ($cart_items as $item) {
    $total_amount += $item['Price'] * $item['Quantity'];
}
$total_price_formatted = number_format($total_amount, 0, ',', '.') . " VNĐ";
    // Lấy thông tin người dùng
    $stmt = $conn->prepare("SELECT * FROM users WHERE Username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $userResult = $stmt->get_result();
    $user = $userResult->fetch_assoc();
    $stmt->close();
  // Lấy ngày giờ hiện tại
  $dateNow = date('Y-m-d H:i:s');
  // echo "<pre>";
  // var_dump($user);
  // echo "</pre>";

  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_checkout'])) {
          // Tạo đơn hàng
      $paymentMethod = $_POST['paymentMethod'] ?? 'COD';
      $stmt = $conn->prepare("INSERT INTO orders (Username, PaymentMethod, CustomerName, Phone, Province, District, Ward, DateGeneration, TotalAmount, Address)
                              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
      $stmt->bind_param("ssssssssss", 
          $username,
          $paymentMethod,
          $user['FullName'],
          $user['Phone'],
          $user['Province'],
          $user['District'],
          $user['Ward'],
          $dateNow,
          $total_amount,
          $user['Address']
      );
      $stmt->execute();
      $orderID = $stmt->insert_id;
      $_SESSION['order_id'] = $orderID; // ✅ đặt ở đây, ngay sau khi có orderID

      $stmt->close();
      // thêm chi tiết đơn hàng
      $stmt = $conn->prepare("INSERT INTO orderdetails (OrderID, ProductID, Quantity, UnitPrice, TotalPrice) VALUES (?, ?, ?, ?, ?)");
      foreach ($cart_items as $item) {
          $productID = $item['ProductID'];
          $quantity = $item['Quantity'];
          $unitPrice = $item['Price'];
          $totalPrice = $quantity * $unitPrice;

          $stmt->bind_param("iiidd", $orderID, $productID, $quantity, $unitPrice, $totalPrice);
          $stmt->execute();
      }
      $stmt->close();

      

  }
    if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_order_info'])) {
            // Debug: kiểm tra dữ liệu nhận được
        // echo "<pre>";
        // print_r($_POST);
        // echo "</pre>";
        // Lấy thông tin từ form
        $orderID = $_POST['order_id']; // lấy từ input hidden
        $newName = $_POST['new_name'];
        $newSdt = $_POST['new_sdt'];
        $newDiachi = $_POST['new_diachi'];
        $province = $_POST['province'];
        $district = $_POST['district'];
        $ward = $_POST['wards'];
        //     // Debug: kiểm tra giá trị orderID và các trường
        // echo "Order ID nhận được: " . $orderID . "<br>";
        // 🆕 Lấy thêm phương thức thanh toán nếu có
        $paymentMethod = isset($_POST['paymentMethod']) ? $_POST['paymentMethod'] : 'COD';

       // Cập nhật thông tin người nhận trong bảng orders
        $stmt = $conn->prepare("UPDATE orders 
        SET CustomerName = ?, Phone = ?, Address = ?, Province = ?, District = ?, Ward = ?,PaymentMethod = ?
        WHERE OrderID = ?");
          $stmt->bind_param("sssssssi", $newName, $newSdt, $newDiachi, $province, $district, $ward, $paymentMethod,$orderID,);
          echo "SQL Query: " . "UPDATE orders SET CustomerName = '$newName', Phone = '$newSdt', Address = '$newDiachi', Province = '$province', District = '$district', Ward = '$ward', PaymentMethod = '$paymentMethod' WHERE OrderID = '$orderID'";

          if ($stmt->execute()) {
          // echo "<script>alert('Cập nhật thông tin giao hàng thành công!');</script>";
          } else {
          // echo "<script>alert('Cập nhật thông tin thất bại!');</script>";
          }
             // Debug: kiểm tra số dòng bị ảnh hưởng
          // echo "Số dòng được cập nhật: " . $stmt->affected_rows;
          $stmt->close();
    } 

?>
<!DOCTYPE html>
<html>
<!-- Sửa infor-for-banking ở dòng 584  -->

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <!-- CSS  -->
  <link rel="stylesheet" href="../src/css/thanh-toan-php.css" />
  <link rel="stylesheet" href="../src/css/thanh-toan.css" />
  <link rel="stylesheet" href="../assets/icon/fontawesome-free-6.7.2-web/css/all.min.css" />
  <link rel="stylesheet" href="../src/css/search-styles.css" />
  <link rel="stylesheet" href="../assets/libs/bootstrap-5.3.3-dist/css/bootstrap.min.css" />
  <link rel="stylesheet" href="../src/css/searchAdvanceMobile.css" />
  <link rel="stylesheet" href="../src/css/footer.css">
  <!-- JS  -->
  <script src="../assets/libs/bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
  <!-- <script src="../src/js/main.js"></script> -->
  <script src="../src/js/search-common.js"></script>
  <script src="../src/js/onOffSeacrhAdvance.js"></script>
  <script src="../src/js/thanh-toan.js"></script>
  <script src="../src/js/search-index.js"></script>
  <script src="../src/js/gio-hang.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>



  <title>Hoàn tất thanh toán</title>
</head>

<body>
  <div class="Sticky">
    <div class="container-fluid" style="padding: 0 !important">
      <!-- HEADER  -->
      <div class="header">
        <!-- MENU  -->
        <div class="grid">
          <div class="aaa"></div>
          <div class="item-header">
            <div class="search-group">
              <form id="searchForm" method="get">
                <div class="search-container">
                  <div class="search-input-wrapper">
                    <input type="search" placeholder="Tìm kiếm sản phẩm..." id="searchInput" name="search"
                      class="search-input" />
                    <button type="button" class="advanced-search-toggle" id="advanced-search-toggle"
                      onclick="toggleAdvancedSearch()" title="Tìm kiếm nâng cao">
                      <i class="fas fa-sliders-h"></i>
                    </button>
                    <button type="submit" class="search-button" onclick="performSearch()" title="Tìm kiếm">
                      <i class="fas fa-search"></i>
                    </button>
                  </div>
                </div>

                <!-- Form tìm kiếm nâng cao được thiết kế lại -->
                <div id="advancedSearchForm" class="advanced-search-panel" style="display: none">
                  <div class="advanced-search-header">
                    <h5>Tìm kiếm nâng cao</h5>
                    <button type="button" class="close-advanced-search" onclick="toggleAdvancedSearch()">
                      <i class="fas fa-times"></i>
                    </button>
                  </div>

                  <!-- Panel tìm kiếm nâng cao  -->
                  <div class="search-filter-container" id="search-filter-container">
                    <div class="filter-group">
                      <label for="categoryFilter">
                        <i class="fas fa-leaf"></i> Phân loại sản phẩm
                      </label>
                      <select id="categoryFilter" name="category" class="form-select">
                        <option value="Chọn phân loại">Chọn phân loại</option>
                        <option value="Cây dễ chăm">Cây dễ chăm</option>
                        <option value="Cây văn phòng">Cây văn phòng</option>
                        <option value="Cây để bàn">Cây để bàn</option>
                        <option value="Cây dưới nước">Cây dưới nước</option>
                      </select>
                    </div>

                    <div class="filter-group">
                      <label for="priceRange">
                        <i class="fas fa-tag"></i> Khoảng giá
                      </label>
                      <div class="price-range-slider">
                        <div class="price-input-group">
                          <input type="number" id="minPrice" name="minPrice" placeholder="Từ" min="0" />
                          <span class="price-separator">-</span>
                          <input type="number" id="maxPrice" name="maxPrice" placeholder="Đến" min="0" />
                        </div>
                        <!-- <div class="price-ranges">
                          <button type="button" class="price-preset" onclick="setPrice(0, 200000)">
                            Dưới 200k
                          </button>
                          <button type="button" class="price-preset" onclick="setPrice(200000, 500000)">
                            200k - 500k
                          </button>
                          <button type="button" class="price-preset" onclick="setPrice(500000, 1000000)">
                            500k - 1tr
                          </button>
                          <button type="button" class="price-preset" onclick="setPrice(1000000, 0)">
                            Trên 1tr
                          </button>
                        </div> -->
                      </div>
                    </div>

                    <div class="filter-actions">
                      <button type="submit" class="btn-search" onclick="performSearch()">
                        <i class="fas fa-search"></i> Tìm kiếm
                      </button>
                      <button type="button" class="btn-reset" onclick="resetFilters()">
                        <i class="fas fa-redo-alt"></i> Đặt lại
                      </button>
                    </div>
                  </div>

                  <div class="search-tips">
                    <p>
                      <i class="fas fa-lightbulb"></i> Mẹo: Kết hợp nhiều điều
                      kiện để tìm kiếm chính xác hơn
                    </p>
                  </div>
                </div>
              </form>
            </div>

            <script>
              document.getElementById("searchForm").addEventListener("submit", function(e) {
                e.preventDefault(); // Ngăn chặn reload trang
                let searchInput = document.getElementById("searchInput").value;
                window.location.href = "./search-result.php?q=" + encodeURIComponent(searchInput);
              });
            </script>



            <div class="cart-icon">
              <a href="gio-hang.php"><img src="../assets/images/cart.svg" alt="cart" /></a>
            </div>
            <div class="user-icon">
            <label for="tick" style="cursor: pointer">
              <img src="../assets/images/user.svg" alt="" />
            </label>
            <input id="tick" hidden type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample"
              aria-controls="offcanvasExample" />
            <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasExample"
              aria-labelledby="offcanvasExampleLabel">
              <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="offcanvasExampleLabel">
                  <?= $loggedInUsername ? "Xin chào, " . htmlspecialchars($loggedInUsername) : "Xin vui lòng đăng nhập" ?>
                </h5>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                  aria-label="Close"></button>
              </div>
              <div class="offcanvas-body">
                <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                  <?php if (!$loggedInUsername): ?>
                    <li class="nav-item">
                      <a class="nav-link login-logout" href="user-register.php">Đăng kí</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link login-logout" href="user-login.php">Đăng nhập</a>
                    </li>
                  <?php else: ?>
                    <li class="nav-item">
                      <a class="nav-link hs-ls-dx" href="ho-so.php">Hồ sơ</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link hs-ls-dx" href="user-History.php">Lịch sử mua hàng</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link hs-ls-dx" href="../src/php/logout.php">Đăng xuất</a>
                    </li>
                  <?php endif; ?>
                </ul>
              </div>
            </div>
          </div>
          </div>
        </div>

        <!-- BAR  -->
        <nav class="navbar position-absolute">
          <div class="a">
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar"
              aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>
            <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasNavbar"
              aria-labelledby="offcanvasNavbarLabel">
              <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="offcanvasNavbarLabel">
                  THEE TREE
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
              </div>
              <div class="offcanvas-body offcanvas-fullscreen mt-20">
                <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                  <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="../index.php">Trang chủ</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="#">Giới thiệu</a>
                  </li>
                  <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                      aria-expanded="false">
                      Sản phẩm
                    </a>
                    <ul class="dropdown-menu">
                      <li>
                        <a class="dropdown-item" href="./phan-loai.php?category_id=3">Cây dễ chăm</a>
                      </li>
                      <li>
                        <a class="dropdown-item" href="./phan-loai.php?category_id=1">Cây văn phòng</a>
                      </li>
                      <li>
                        <a class="dropdown-item" href="./phan-loai.php?category_id=4">Cây để bàn</a>
                      </li>
                      <li>
                        <a class="dropdown-item" href="./phan-loai.php?category_id=2">Cây dưới nước</a>
                      </li>
                    </ul>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="#">Tin tức</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="#">Liên hệ</a>
                  </li>
                </ul>
                <form class="searchFormMobile mt-3" role="search" id="searchFormMobile">
                  <div class="d-flex">
                    <input class="form-control me-2" type="search" placeholder="Tìm kiếm" aria-label="Search"
                      style="height: 37.6px;" />
                    <!-- Nút tìm kiếm nâng cao trên mobile  -->
                    <button type="button" class="advanced-search-toggle" onclick="toggleMobileSearch()"
                      title="Tìm kiếm nâng cao">
                      <i class="fas fa-sliders-h"></i>
                    </button>

                    <button class="btn btn-outline-success" type="submit"
                      style="width: 76.3px;display: flex;justify-content: center;align-items: center;height: 37.6px;">
                      Tìm
                    </button>
                  </div>
                  <div id="search-filter-container-mobile" class="search-filter-container-mobile">
                    <div class="filter-group">
                      <label for="categoryFilter-mobile">
                        <i class="fas fa-leaf"></i> Phân loại sản phẩm
                      </label>
                      <select id="categoryFilter-mobile" name="category" class="form-select">
                        <option value="">Tất cả phân loại</option>
                        <option value="Cây dễ chăm">Cây dễ chăm</option>
                        <option value="Cây văn phòng">Cây văn phòng</option>
                        <option value="Cây để bàn">Cây để bàn</option>
                        <option value="Cây dưới nước">Cây dưới nước</option>
                      </select>
                    </div>

                    <div class="filter-group">
                      <label for="priceRange">
                        <i class="fas fa-tag"></i> Khoảng giá
                      </label>
                      <div class="price-range-slider">
                        <div class="price-input-group">
                          <input type="number" id="minPriceMobile" name="minPrice" placeholder="Từ" min="0" />
                          <span class="price-separator">-</span>
                          <input type="number" id="maxPriceMobile" name="maxPrice" placeholder="Đến" min="0" />
                        </div>
                        <!-- <div class="price-ranges">
                          <button type="button" class="price-preset" onclick="setPriceMobile(0, 200000)">
                            Dưới 200k
                          </button>
                          <button type="button" class="price-preset" onclick="setPriceMobile(200000, 500000)">
                            200k - 500k
                          </button>
                          <button type="button" class="price-preset" onclick="setPriceMobile(500000, 1000000)">
                            500k - 1tr
                          </button>
                          <button type="button" class="price-preset" onclick="setPriceMobile(1000000, 0)">
                            Trên 1tr
                          </button>
                        </div> -->
                      </div>
                    </div>

                    <div class="filter-actions">
                      <button type="submit" class="btn-search" onclick="performSearchMobile()">
                        <i class="fas fa-search"></i> Tìm kiếm
                      </button>
                      <button type="button" class="btn-reset" onclick="resetMobileFilters()">
                        <i class="fas fa-redo-alt"></i> Đặt lại
                      </button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </nav>
      </div>
    </div>

    <!-- NAV  -->
    <div class="nav">
      <div class="brand">
        <div class="brand-logo">
          <!-- Quay về trang chủ  -->
          <a href="../index.php"><img class="img-fluid" src="../assets/images/LOGO-2.jpg" alt="LOGO" /></a>
        </div>
        <div class="brand-name">THE TREE</div>
      </div>
      <div class="choose">
        <ul>
          <li>
            <a href="../index.php" style="font-weight: bold">Trang chủ</a>
          </li>
          <li><a href="#">Giới thiệu</a></li>
          <li>
            <div class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                aria-expanded="false">
                Sản phẩm
              </a>
              <ul class="dropdown-menu">
                <li>
                  <a class="dropdown-item" href="./phan-loai.php?category_id=3">Cây dễ chăm</a>
                </li>
                <li>
                  <a class="dropdown-item" href="./phan-loai.php?category_id=1">Cây văn phòng</a>
                </li>
                <li>
                  <a class="dropdown-item" href="./phan-loai.php?category_id=4">Cây để bàn</a>
                </li>
                <li>
                  <a class="dropdown-item" href="./phan-loai.php?category_id=2">Cây dưới nước</a>
                </li>
              </ul>
            </div>
          </li>
          <li><a href="">Tin tức</a></li>
          <li><a href="">Liên hệ</a></li>
        </ul>
      </div>
    </div>
  </div>

  <!-- SECTION  -->
  <div class="section">
    <div class="img-21">
      <img src="../assets/images/CAY21.jpg" alt="CAY21" />
    </div>
  </div>

  <main>
    <div class="container-payment">
      <h2>THANH TOÁN</h2>
      <div class="content">
        <div class="status-order">
          <i class="fa-solid fa-cart-shopping"></i>
          <hr style="border: 1px dashed black; width: 21%;">
          <i style="color: green;" class="fa-solid fa-id-card"></i>
          <hr style="border: 1px dashed black; width: 21%;">
          <i class="fa-solid fa-circle-check"></i>
        </div>

        <div class="option-address">
          <label for="">
            <input type="radio" name="chon" id="default-information" checked> <span>Sử dụng thông tin mặc
              định</span>
          </label>
          <label for="">
            <input type="radio" name="chon" id="new-information"> <span>Nhập thông tin mới</span>
          </label>
        </div>
        <script>
          document.addEventListener('DOMContentLoaded', function() {
                // Lấy các phần tử radio button
                const defaultInformationRadio = document.getElementById('default-information');
                const newInformationRadio = document.getElementById('new-information');
                const defaultInformationForm = document.getElementById('default-information-form');
                const newInformationForm = document.getElementById('new-information-form');

                // Hàm để ẩn/hiện form
                function toggleForms() {
                    if (defaultInformationRadio.checked) {
                        defaultInformationForm.style.display = 'block';
                        newInformationForm.style.display = 'none';
                    } else if (newInformationRadio.checked) {
                        defaultInformationForm.style.display = 'none';
                        newInformationForm.style.display = 'block';
                    }
                }

                // Khi người dùng thay đổi lựa chọn radio
                defaultInformationRadio.addEventListener('change', toggleForms);
                newInformationRadio.addEventListener('change', toggleForms);

                // Gọi hàm toggleForms khi trang được tải lên để xác định trạng thái form ban đầu
                toggleForms();
            });
        </script>
       
       
          

          <div id="default-information-form" >
              <label><strong>Họ và tên</strong></label>
              <input type="text" value="<?= htmlspecialchars($user['FullName']) ?>" disabled>
              <input type="hidden" name="FullName" value="<?= htmlspecialchars($user['FullName']) ?>">

              <label><strong>Email</strong></label>
              <input type="email" value="<?= htmlspecialchars($user['Email']) ?>" disabled>
              <input type="hidden" name="Email" value="<?= htmlspecialchars($user['Email']) ?>">

              <label><strong>Số điện thoại</strong></label>
              <input type="text" value="<?= htmlspecialchars($user['Phone']) ?>" disabled>
              <input type="hidden" name="Phone" value="<?= htmlspecialchars($user['Phone']) ?>">

              <label><strong>Địa chỉ</strong></label>
              <input type="text" value="<?= htmlspecialchars($user['Address'] . ', ' . $user['Ward'] . ', ' . $user['District'] . ', ' . $user['Province']) ?>" disabled>

              <input type="hidden" name="Address" value="<?= htmlspecialchars($user['Address']) ?>">
              <input type="hidden" name="Ward" value="<?= htmlspecialchars($user['Ward']) ?>">
              <input type="hidden" name="District" value="<?= htmlspecialchars($user['District']) ?>">
              <input type="hidden" name="Province" value="<?= htmlspecialchars($user['Province']) ?>">
          </div>


        <form action="thanh-toan.php" id="new-information-form" method="POST">
        <input type="hidden" name="order_id" value="<?php echo $_SESSION['order_id'] ?? ''; ?>">

          <label for=""><strong>Họ và tên</strong></label>
          <input type="text" name="new_name" id="new-name" placeholder="Họ và tên">
          <label for=""><strong>Số điện thoại</strong></label>
          <input type="text" name="new_sdt" id="new-sdt" placeholder="Số điện thoại">
          <label for=""><strong>Địa chỉ</strong></label>
          <input type="text" name="new_diachi" id="new-diachi" placeholder="Nhập địa chỉ cụ thể" >

          <label for=""><strong>Tỉnh/Thành phố</strong></label>
          <select name="province" id="province" class="form-select">
            <option value="">Chọn tỉnh/thành phố</option>
            <?php
            // Lấy danh sách tỉnh từ cơ sở dữ liệu
            $stmt = $conn->prepare("SELECT province_id, name FROM province");
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                echo '<option value="' . $row['province_id'] . '">' . htmlspecialchars($row['name']) . '</option>';
            }
            $stmt->close();
            ?>
          </select>

          <label for=""><strong>Quận/Huyện</strong></label>
          <select name="district" id="district" class="form-select">
            <option value="">Chọn quận/huyện</option>

          </select>

          <label for=""><strong>Phường/Xã</strong></label>
          <select name="wards" id="wards" class="form-select">
            <option value="">Chọn phường/xã</option>
          </select>
          <button type="submit" name="submit_order_info">Xác nhận thông tin mới này</button>
          <script src="../src/js/DiaChi.js"></script>
        </form>
        

      
        <div class="infor-goods">
          <hr style="border: 3px dashed green; width: 100%" />
          <?php if (count($cart_items) > 0): ?>
            <?php foreach ($cart_items as $item): ?>
              <div class="order">
                  <div class="order-img">
                    <img src="<?php echo ".." . $item['ImageURL']; ?>" alt="<?php echo $product['ProductName']; ?>" />
                  </div>
                  <div class="frame">
                    <div class="name-price">
                    <p><strong><?php echo htmlspecialchars($item['ProductName']); ?></strong></p>
                    <p class="price" data-price="<?php echo $item['Price']; ?>">
                            <strong><?php echo number_format($item['Price'], 0, ',', '.') . " VNĐ"; ?></strong>
                    </p>
                  </div>
                    <div class="function">
                      <!-- Button trigger modal -->
                      <form action="gio-hang.php" method="POST">
                          <input type="hidden" name="remove_product_id" value="<?php echo $item['ProductID']; ?>">
                          <button type="button" class="btn" onclick="this.form.submit();"
                            style="width: 53px; height: 33px;">
                            <i class="fa-solid fa-trash" style="font-size: 25px;"></i>
                          </button>
                      </form>
                      <!-- Nútxóa và thêm số lượng sản phẩm  -->
                      <div class="add-del">
                          <div class="oder">
                            <div class="wrapper" >
                              <form action="gio-hang.php" method="POST" class="update-form">
                                <!-- Truyền ProductID để xác định sản phẩm cần cập nhật -->
                                <input type="hidden" name="update_product_id" value="<?php echo $item['ProductID']; ?>">                       
                                <!-- Nút giảm số lượng -->
                                <!-- <button type="button" class="quantity-btn" onclick="changeQuantity(this, -1)">-</button>                       -->
                                <!-- Trường số lượng, gán thuộc tính data-price để JS dùng cho tính toán nếu cần -->
                                <span class="quantity-display" ><?php echo "x".$item['Quantity']; ?></span>
                        
                                <!-- Nút tăng số lượng -->
                                <!-- <button type="button" class="quantity-btn" onclick="changeQuantity(this, 1)">+</button> -->
                              </form>
                            </div>
                          </div>
                      </div>
                  </div>
                </div>
              </div>
          <?php endforeach; ?>
            <?php else:  ?>
            <p>Giỏ hàng của bạn đang trống</p>
            <?php endif; ?>
          <div class="frame-2">
            <div class="thanh-tien">
              Tổng : <span id="total-price"><?php echo $total_price_formatted; ?></span>
            </div>
          </div>

          <form action="thanh-toan.php"> 
              <div class="payment-method">
                <label>
                  <input type="radio" name="paymentMethod" value="COD" id="cod-button"  checked>
                  <span>Thanh toán khi nhận hàng</span>
                </label>
                <label>
                  <input type="radio" name="paymentMethod" value="Banking" id="banking-button">
                  <span>Chuyển khoản</span>
                </label>
              </div>
          </form>

        <form action="hoan-tat.php" method="POST">
          <div class="payment-button">
            <button type="submit" class="btn btn-success" id="payment-button" style="width: 185px; height: 50px;">
              THANH TOÁN
            </button>
          </div>
        </form>

          <a href="../index.html" style="text-decoration: none;
          margin-bottom: 10px;">Tiếp tục mua hàng</a>
        </div>
    </div>

    </div>
  </main>
  <!-- FOOTER  -->
  <footer class="footer">
    <div class="footer-column">
      <h3>Thee Tree</h3>
      <ul>
        <li><a href="#">Cây dễ chăm</a></li>
        <li><a href="#">Cây văn phòng</a></li>
        <li><a href="#">Cây dưới nước</a></li>
        <li><a href="#">Cây để bàn</a></li>
      </ul>
    </div>

    <div class="footer-column">
      <h3>Learn</h3>
      <ul>
        <li><a href="#">Cách chăm sóc cây</a></li>
        <li><a href="#">Lợi ích của cây xanh</a></li>
        <li><a href="#">Cây phong thủy</a></li>
      </ul>
    </div>

    <div class="footer-column">
      <h3>More from The Tree</h3>
      <ul>
        <li><a href="#">Blog</a></li>
        <li><a href="#">Affiliate</a></li>
        <li><a href="#">Liên hệ</a></li>
        <li><a href="#">Faq's</a></li>
        <li><a href="#">Sign In</a></li>
      </ul>
    </div>

    <div class="footer-column newsletter">


      <h3>Theo dõi chúng tôi</h3>
      <div class="social-icons">
        <a href="#" aria-label="Pinterest">
          <i class="fa-brands fa-pinterest"></i>
        </a>
        <a href="#" aria-label="Facebook">
          <i class="fa-brands fa-facebook"></i>
        </a>
        <a href="#" aria-label="Instagram">
          <i class="fa-brands fa-instagram"></i>
        </a>
        <a href="#" aria-label="Twitter">
          <i class="fa-brands fa-x-twitter"></i>
        </a>
      </div>
    </div>

    <div class="copyright">
      © 2021 tenzotea.co

      <div class="policies">
        <a href="#">Điều khoản dịch vụ</a>
        <span>|</span>
        <a href="#">Chính sách bảo mật</a>
        <span>|</span>
        <a href="#">Chính sách hoàn tiền</a>
        <span>|</span>
        <a href="#">Chính sách trợ năng</a>
      </div>
    </div>
    <!-- xong footer  -->
  </footer>
  <script src="../src/js/thanh-toan.js"></script>

  
  
</body>


</html>