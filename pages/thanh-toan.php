<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$servername = "localhost";
$username = "root";
$password = "";
$database = "webdb";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) die("Kết nối thất bại: " . $conn->connect_error);

// Kiểm tra giỏ hàng
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    die("Giỏ hàng trống. Không thể đặt hàng.");
}
$username = 'user1';

// Lấy thông tin người dùng từ bảng users
$stmt = $conn->prepare("SELECT * FROM users WHERE Username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$userResult = $stmt->get_result();
$user = $userResult->fetch_assoc();
$stmt->close();

if (!$user) die("Không tìm thấy người dùng");

// Tính tổng đơn hàng
$cart_items = $_SESSION['cart'];
$total_amount = 0;
foreach ($cart_items as $item) {
    $total_amount += $item['Price'] * $item['Quantity'];
}


$stmt = $conn->prepare("INSERT INTO orders (Username, PaymentMethod, CustomerName, Phone, Province, District, Ward, DateGeneration, TotalAmount, Address)
VALUES (?, ?, ?, ?, ?,  ?, ?, ?, ?, ?)");
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
$stmt->close();

// Chèn dữ liệu chi tiết vào `orderdetails`
$stmt = $conn->prepare("INSERT INTO orderdetails (OrderID, ProductID, Quantity, UnitPrice, TotalPrice) VALUES (?, ?, ?, ?, ?)");
foreach ($cart_items as $item) {
    $productID = $item['ProductID'];
    $quantity = $item['Quantity'];
    $unitPrice = $item['Price'];
    $totalPrice = $quantity * $unitPrice;

    $stmt->bind_param("iiidd", $orderID, $productID, $quantity, $unitPrice, $totalPrice);
    $stmt->execute();
}
// Tính tổng đơn hàng
$cart_items = $_SESSION['cart'];
$total_amount = 0;
foreach ($cart_items as $item) {
    $total_amount += $item['Price'] * $item['Quantity'];
}
$total_price_formatted = number_format($total_amount, 0, ',', '.') . " VNĐ"; // ✅ Thêm dòng này
$stmt->close();

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_order'])) {
  $orderID = $_POST['order_id']; // ID đơn hàng đang cần cập nhập

  // Xử lý thông tin mới từ form
  $newName = $_POST['new_name'];
  $newPhone = $_POST['new_phone'];


  // lấy thông tin địa chỉ 
  $province_id = $_POST['province'];
  $district_id = $_POST['district'];
  $ward_id = $_POST['ward'];
  // Lấy tên tỉnh, quận, huyện từ ID
    // Lấy tên từ ID
    $stmt = $conn->prepare("SELECT name FROM province WHERE province_id = ?");
    $stmt->bind_param("i", $province_id);
    $stmt->execute();
    $stmt->bind_result($province);
    $stmt->fetch();
    $stmt->close();

    $stmt = $conn->prepare("SELECT name FROM districts WHERE district_id = ?");
    $stmt->bind_param("i", $district_id);
    $stmt->execute();
    $stmt->bind_result($district);
    $stmt->fetch();
    $stmt->close();

    $stmt = $conn->prepare("SELECT name FROM wards WHERE wards_id = ?");
    $stmt->bind_param("i", $ward_id);
    $stmt->execute();
    $stmt->bind_result($ward);
    $stmt->fetch();
    $stmt->close();

    // Cập nhật CustomerName trong bảng orders
    $sqlUpdateOrder = "UPDATE orders SET CustomerName = ?, Phone = ?  WHERE OrderID = ?";
    $stmtOrder = $conn->prepare($sqlUpdateOrder);
    $stmtOrder->bind_param("ssi", $newName, $newPhone,  $orderID);
    $stmtOrder->execute();
    $stmtOrder->close();
}

?>


<!DOCTYPE html>
<html>
<!-- Sửa infor-for-banking ở dòng 584  -->
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <!-- CSS  -->
  <link rel="stylesheet" href="../src/css/thanh-toan.css" />
  <link rel="stylesheet" href="../assets/icon/fontawesome-free-6.7.2-web/css/all.min.css" />
  <link rel="stylesheet" href="../src/css/search-styles.css" />
  <link rel="stylesheet" href="../assets/libs/bootstrap-5.3.3-dist/css/bootstrap.min.css" />
  <link rel="stylesheet" href="../src/css/searchAdvanceMobile.css" />
  <link rel="stylesheet" href="../src/css/footer.css">
  <link rel="stylesheet" href="../src/css/gio-hang-php.css">
  <link rel="stylesheet" href="../src/css/thanh-toan-php.css">

  <!-- JS  -->
  <script src="../assets/libs/bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
  <script src="../src/js/DiaChi.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.4.js"></script>
  <script src="../src/js/main.js"></script>
  <script src="../src/js/search-common.js"></script>
  <script src="../src/js/onOffSeacrhAdvance.js"></script>
  <script src="../src/js/thanh-toan.js"></script>
  <script src="../src/js/gio-hang.js"></script>
  <script src="../src/js/jquery-3.7.1.min.js"></script>
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
              <div class="search-container">
                <div class="search-input-wrapper">
                  <input type="search" placeholder="Tìm kiếm sản phẩm..." id="searchInput" class="search-input" />
                  <button class="advanced-search-toggle" onclick="toggleAdvancedSearch()" title="Tìm kiếm nâng cao">
                    <i class="fas fa-sliders-h"></i>
                  </button>
                  <button type="button" class="search-button" onclick="performSearch()" title="Tìm kiếm">
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

                <div class="search-filter-container">
                  <div class="filter-group">
                    <label for="categoryFilter">
                      <i class="fas fa-leaf"></i> Phân loại sản phẩm
                    </label>
                    <select id="categoryFilter" class="form-select">
                      <option value="">Tất cả phân loại</option>
                      <option value="cay-de-cham">Cây dễ chăm</option>
                      <option value="cay-van-phong">Cây văn phòng</option>
                      <option value="cay-de-ban">Cây để bàn</option>
                      <option value="cay-duoi-nuoc">Cây dưới nước</option>
                    </select>
                  </div>

                  <div class="filter-group">
                    <label for="priceRange">
                      <i class="fas fa-tag"></i> Khoảng giá
                    </label>
                    <div class="price-range-slider">
                      <div class="price-input-group">
                        <input type="number" id="minPrice" placeholder="Từ" min="0" />
                        <span class="price-separator">-</span>
                        <input type="number" id="maxPrice" placeholder="Đến" min="0" />
                      </div>
                      <div class="price-ranges">
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
                      </div>
                    </div>
                  </div>

                  <div class="filter-actions">
                    <button type="button" class="btn-search" onclick="performSearch()">
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

              <div id="productList" class="product-list">
                <!-- Thêm div thông báo không tìm thấy sản phẩm -->
                <div id="noResultsMessage" class="no-results-message" style="display: none">
                  <i class="fas fa-search"></i>
                  <p>
                    Không tìm thấy sản phẩm phù hợp với điều kiện tìm kiếm
                  </p>
                  <button type="button" class="btn-reset-search" onclick="resetFilters()">
                    <i class="fas fa-redo-alt"></i> Đặt lại bộ lọc
                  </button>
                </div>

                <div class="product" data-category="cay-de-cham">
                  <img src="../assets/images/CAY5.jpg" alt="Cây phát tài" />
                  <div class="p-details">
                    <h2>Cây phát tài</h2>
                    <h3>750.000 vnđ</h3>
                  </div>
                </div>

                <!-- OK  -->
                <div class="product" data-category="cay-van-phong">
                  <img src="../assets/images/CAY6.jpg" alt="Cây kim ngân" />
                  <div class="p-details">
                    <h2>Cây kim ngân</h2>
                    <h3>280.000 vnđ</h3>
                  </div>
                </div>

                <div class="product" data-category="cay-de-ban">
                  <img src="../assets/images/CAY7.jpg" alt="Cây trầu bà" />
                  <div class="p-details">
                    <h2>Cây trầu bà</h2>
                    <h3>120.000 vnđ</h3>
                  </div>
                </div>

                <div class="product" data-category="cay-duoi-nuoc">
                  <img src="../assets/images/CAY8.jpg" alt="Cây lan chi" />
                  <div class="p-details">
                    <h2>Cây lan chi</h2>
                    <h3>120.000 vnđ</h3>
                  </div>
                </div>

                <div class="product" data-category="cay-de-ban">
                  <img src="../assets/images/CAY9.jpg" alt="Cây trầu bà đỏ" />
                  <div class="p-details">
                    <h2>Cây trầu bà đỏ</h2>
                    <h3>320.000 vnđ</h3>
                  </div>
                </div>

                <div class="product" data-category="cay-de-ban">
                  <img src="../assets/images/CAY10.jpg" alt="Cây lưỡi hổ" />
                  <div class="p-details">
                    <h2>Cây lưỡi hổ</h2>
                    <h3>750.000 vnđ</h3>
                  </div>
                </div>

                <div class="product" data-category="cay-de-ban">
                  <img src="../assets/images/CAY11.jpg" alt="Cây lưỡi hổ vàng" />
                  <div class="p-details">
                    <h2>Cây lưỡi hổ vàng</h2>
                    <h3>160.000 vnđ</h3>
                  </div>
                </div>

                <div class="product" data-category="cay-de-ban">
                  <img src="../assets/images/CAY12.jpg" alt="Cây hạnh phúc" />
                  <div class="p-details">
                    <h2>Cây hạnh phúc</h2>
                    <h3>1.200.000 vnđ</h3>
                  </div>
                </div>

                <div class="product" data-category="cay-de-ban">
                  <img src="../assets/images/CAY13.jpg" alt="Cây trầu bà châu lớn" />
                  <div class="p-details">
                    <h2>Cây trầu bà châu lớn</h2>
                    <h3>1.100.000 vnđ</h3>
                  </div>
                </div>

                <div class="product" data-category="cay-van-phong">
                  <img src="../assets/images/CAY14.jpg" alt="Cây phát tài DORADO" />
                  <div class="p-details">
                    <h2>Cây phát tài DORADO</h2>
                    <h3>220.000 vnđ</h3>
                  </div>
                </div>
                <div class="product" data-category="cay-de-ban">
                  <img src="../assets/images/CAY16.jpg" alt="Cây vạn lộc" />
                  <div class="p-details">
                    <h2>Cây vạn lộc</h2>
                    <h3>1.150.000 vnđ</h3>
                  </div>
                </div>

                <div class="product" data-category="cay-de-ban">
                  <img src="../assets/images/CAY17.jpg" alt="Cây ngọc vừng" />
                  <div class="p-details">
                    <h2>Cây ngọc vừng</h2>
                    <h3>1.750.000 vnđ</h3>
                  </div>
                </div>
              </div>
            </div>

            <div class="cart-icon">
              <a href="gio-hang.php"><img src="../assets/images/cart.svg" alt="cart" /></a>
            </div>
            <div class="user-icon">
              <label for="tick" style="cursor: pointer"><img src="../assets/images/user.svg" alt="" /></label>
              <input id="tick" hidden type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample"
                aria-controls="offcanvasExample" />
              <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasExample"
                aria-labelledby="offcanvasExampleLabel">
                <div class="offcanvas-header">
                  <h5 class="offcanvas-title" id="offcanvasExampleLabel">
                    Nguyễn Phước Sang
                  </h5>
                  <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                    aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                  <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                    <li class="nav-item">
                      <a class="nav-link login-logout" href="../pages/user-register.html">Đăng kí</a>
                    </li>

                    <li class="nav-item">
                      <a class="nav-link login-logout" href="../pages/user-login.html">Đăng nhập</a>
                    </li>

                    <li class="nav-item">
                      <a class="nav-link hs-ls-dx" href="../pages/ho-so.html">Hồ sơ</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link hs-ls-dx" href="../pages/user-History.html">Lịch sử mua hàng</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link hs-ls-dx" href="../index.html" onclick="logOut()">Đăng xuất</a>
                    </li>
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
                    <a class="nav-link active" aria-current="page" href="../index.html">Trang chủ</a>
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
                        <a class="dropdown-item" href="./phan-loai.html?category_id=3">Cây dễ chăm</a>
                      </li>
                      <li>
                        <a class="dropdown-item" href="./phan-loai.html?category_id=1">Cây văn phòng</a>
                      </li>
                      <li>
                        <a class="dropdown-item" href="./phan-loai.html?category_id=4">Cây để bàn</a>
                      </li>
                      <li>
                        <a class="dropdown-item" href="./phan-loai.html?category_id=2">Cây dưới nước</a>
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
                <form class="d-flex mt-3" role="search">
                  <input class="form-control me-2" type="search" placeholder="Tìm kiếm" aria-label="Search" />
                  <!-- Nút tìm kiếm nâng cao trên mobile  -->
                  <button type="button" class="advanced-search-toggle" onclick="toggleMobileSearch()"
                    title="Tìm kiếm nâng cao">
                    <i class="fas fa-sliders-h"></i>
                  </button>

                  <button class="btn btn-outline-success" type="submit">
                    Search
                  </button>
                </form>
                <div id="search-filter-container-mobile" class="search-filter-container">
                  <div class="filter-group">
                    <label for="categoryFilter">
                      <i class="fas fa-leaf"></i> Phân loại sản phẩm
                    </label>
                    <select id="categoryFilter" class="form-select">
                      <option value="">Tất cả phân loại</option>
                      <option value="cay-de-cham">Cây dễ chăm</option>
                      <option value="cay-van-phong">Cây văn phòng</option>
                      <option value="cay-de-ban">Cây để bàn</option>
                      <option value="cay-duoi-nuoc">Cây dưới nước</option>
                    </select>
                  </div>

                  <div class="filter-group">
                    <label for="priceRange">
                      <i class="fas fa-tag"></i> Khoảng giá
                    </label>
                    <div class="price-range-slider">
                      <div class="price-input-group">
                        <input type="number" id="minPriceMobile" placeholder="Từ" min="0" />
                        <span class="price-separator">-</span>
                        <input type="number" id="maxPriceMobile" placeholder="Đến" min="0" />
                      </div>
                      <div class="price-ranges">
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
                      </div>
                    </div>
                  </div>

                  <div class="filter-actions">
                    <button type="button" class="btn-search" onclick="performSearchMobile()">
                      <i class="fas fa-search"></i> Tìm kiếm
                    </button>
                    <button type="button" class="btn-reset" onclick="resetMobileFilters()">
                      <i class="fas fa-redo-alt"></i> Đặt lại
                    </button>
                  </div>
                </div>
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
          <a href="../index.html"><img class="img-fluid" src="../assets/images/LOGO-2.jpg" alt="LOGO" /></a>
        </div>
        <div class="brand-name">THE TREE</div>
      </div>
      <div class="choose">
        <ul>
          <li>
            <a href="../index.html" style="font-weight: bold">Trang chủ</a>
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
                  <a class="dropdown-item" href="./phan-loai.html?category_id=3">Cây dễ chăm</a>
                </li>
                <li>
                  <a class="dropdown-item" href="./phan-loai.html?category_id=1">Cây văn phòng</a>
                </li>
                <li>
                  <a class="dropdown-item" href="./phan-loai.html?category_id=4">Cây để bàn</a>
                </li>
                <li>
                  <a class="dropdown-item" href="./phan-loai.html?category_id=2">Cây dưới nước</a>
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

        <form action="" id="default-information-form">
          <label for=""><strong>Họ và tên</strong></label>
          <input type="text" name="name" id="name" value="<?php  echo htmlspecialchars($user['FullName']) ?>" disabled>
          <label for=""><strong>Email</strong></label>
          <input type="email" name="email" id="email"  value="<?php echo htmlspecialchars($user['Email']); ?>" disabled>
          <label for=""><strong>Số điện thoại</strong></label>
          <input type="text" name="sdt" id="sdt" value="<?php echo htmlspecialchars($user['Phone']);?>" disabled>
          <label for=""><strong>Địa chỉ</strong></label>
          <input type="text" name="diachi" id="diachi" value="<?php echo htmlspecialchars($user['Address'] . ', ' . $user['Ward'] . ', ' . $user['District'] . ', ' . $user['Province']); ?>" 
            disabled>
        </form>

        <form action="" id="new-information-form">
          <label for=""><strong>Họ và tên</strong></label>
          <input type="text" name="name" id="new-name" placeholder="Họ và tên">
          <label for=""><strong>Số điện thoại</strong></label>
          <input type="text" name="sdt" id="new-sdt" placeholder="Số điện thoại">
          <label for=""><strong>Địa chỉ</strong></label>
          <input type="text" name="diachi" id="new-diachi" placeholder="Nhập địa chỉ cụ thể" >

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
          <select name="ward" id="ward" class="form-select">
            <option value="">Chọn phường/xã</option>
          </select>
          <button type="submit" name="submit_order">Xác nhận thông tin mới này</button>
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

        <div class="payment-method">
          <label for="">
            <input type="radio" name="paymentMehtod" id="cod-button" checked> <span>Thanh toán khi nhận hàng</span>
          </label>
          <label for="">
            <input type="radio" name="paymentMehtod" id="banking-button"> <span>Chuyển khoản</span>
          </label>
        </div>


        <div class="card-type" id="card-type">
          <i class="fa-brands fa-cc-visa" alter="thẻ visa" id="visa-card"></i>
          <i class="fa-solid fa-credit-card" alter="thẻ tín dụng"></i>
        </div>
        <!-- Form chuyển khoản  -->
        <form action="" id="banking-form">
          <h2>Liên kết thẻ</h2>
          <label>Thông tin thẻ</label>
          <input type="text" placeholder="1234 1234 1234 1234">
          <input type="text" placeholder="MM / YY">
          <input type="text" placeholder="CVC">
          <label>Tên chủ thẻ</label>
          <input type="text" placeholder="Full name on card">
          <label>Địa chỉ</label>
          <select>
            <option>Vietnam</option>
          </select>
          <input type="text" placeholder="Địa chỉ 1">
          <input type="text" placeholder="Địa chỉ 2">
          <input type="text" placeholder="Thành phố">
          <input type="text" placeholder="Tỉnh">
          <input type="text" placeholder="Mã bưu điện">
          <button class="subscribe-btn">Đăng ký</button>
        </form>
        <div class="payment-button">
          <a href="../pages/hoan-tat.html"><button type="button" class="btn btn-success" id="payment-button" style="width: 185px;
    height: 50px;">THANH TOÁN</button></a>
        </div>
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