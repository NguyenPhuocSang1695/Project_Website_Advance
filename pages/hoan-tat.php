<?php
session_start();
require_once('../src/php/connect.php');
require_once('../src/php/token.php');
require_once('../src/php/check_token_v2.php');
require __DIR__ . '/../src/Jwt/vendor/autoload.php';

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


// Hàm kiểm tra giỏ hàng có trống không
function isCartEmpty() {
  // Kiểm tra session giỏ hàng
  if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
      return true;
  }
  

  return false;
}

if (isCartEmpty()) {
  
  
  // Chuyển hướng về trang giỏ hàng
  header("Location: gio-hang.php");
  exit;
}


// Lấy OrderID từ session
$orderID = $_SESSION['order_id'] ?? 0;
if (!$orderID) die("Không tìm thấy đơn hàng.");

// Lấy thông tin đơn hàng và địa chỉ đầy đủ
$stmt = $conn->prepare("
  SELECT o.OrderID, o.DateGeneration, o.CustomerName, o.Phone, o.Address, 
         w.name AS WardName, d.name AS DistrictName, p.name AS ProvinceName,
         o.TotalAmount
  FROM orders o
  LEFT JOIN wards w ON o.Ward = w.wards_id
  LEFT JOIN district d ON o.District = d.district_id
  LEFT JOIN province p ON o.Province = p.province_id
  WHERE o.OrderID = ?
");
$stmt->bind_param("i", $orderID);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();
$stmt->close();



// Lấy chi tiết sản phẩm từ đơn hàng
$stmt = $conn->prepare("
  SELECT p.ProductName, p.ImageURL, od.Quantity, od.UnitPrice, (od.Quantity * od.UnitPrice) AS TotalPrice
  FROM orderdetails od
  JOIN products p ON od.ProductID = p.ProductID
  WHERE od.OrderID = ?
");
$stmt->bind_param("i", $orderID);
$stmt->execute();
$details = $stmt->get_result();
$stmt->close();



if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Lấy phương thức thanh toán từ form
  $paymentMethod = isset($_POST['paymentMethod']) ? $_POST['paymentMethod'] : 'COD';  
  // Cập nhật phương thức thanh toán vào cơ sở dữ liệu (nếu cần)
  if (isset($_SESSION['order_id'])) {
    $orderID = $_SESSION['order_id'];

    $stmt = $conn->prepare("UPDATE orders SET PaymentMethod = ? WHERE OrderID = ?");
    $stmt->bind_param("si", $paymentMethod, $orderID);
    $stmt->execute();
    $stmt->close();
  }
}


// Cập nhật thông tin người dùng nếu có form cập nhật thông tin
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_order_info'])) {
  // Lấy thông tin cập nhật từ form
  $newName = trim($_POST['new_name']);
  $newSdt = trim($_POST['new_sdt']);
  $newDiachi = trim($_POST['new_diachi']);
  $provinceID = (int) $_POST['province'];
  $districtID = (int) $_POST['district'];
  $wardID = (int) $_POST['wards'];

  // Cập nhật thông tin vào bảng orders nếu thông tin đầy đủ
  if (!empty($newName) && !empty($newSdt) && !empty($newDiachi) && $provinceID > 0 && $districtID > 0 && $wardID > 0) {
    // Lấy tên tỉnh
    $stmt = $conn->prepare("SELECT name FROM province WHERE province_id = ?");
    $stmt->bind_param("i", $provinceID);
    $stmt->execute();
    $stmt->bind_result($provinceName);
    $stmt->fetch();
    $stmt->close();

    // Lấy tên quận
    $stmt = $conn->prepare("SELECT name FROM district WHERE district_id = ?");
    $stmt->bind_param("i", $districtID);
    $stmt->execute();
    $stmt->bind_result($districtName);
    $stmt->fetch();
    $stmt->close();

    // Lấy tên phường
    $stmt = $conn->prepare("SELECT name FROM wards WHERE wards_id = ?");
    $stmt->bind_param("i", $wardID);
    $stmt->execute();
    $stmt->bind_result($wardName);
    $stmt->fetch();
    $stmt->close();

    // Gộp địa chỉ đầy đủ
    $fullAddress = $newDiachi; // Chỉ lưu số nhà và tên đường vào Address

   // Chỉ lưu địa chỉ cụ thể
$fullAddress = $newDiachi; // Không gộp với tên phường, quận, tỉnh

$stmt = $conn->prepare("UPDATE orders 
    SET CustomerName = ?, Phone = ?, Address = ?, Province = ?, District = ?, Ward = ?,
    ProvinceName = ?, DistrictName = ?, WardName = ?
    WHERE OrderID = ?");
$stmt->bind_param("sssiisssi", $newName, $newSdt, $fullAddress, 
                $provinceID, $districtID, $wardID,
                $provinceName, $districtName, $wardName, $orderID);

    
  } 
  
  
}
unset($_SESSION['cart']);
setcookie('cart_quantity', '', time() - 3600, '/'); 

// Hiển thị thông tin chi tiết hóa đơn
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <!-- css  -->
  <link rel="stylesheet" href="../src/css/hoan-tat.css" />
  <link rel="stylesheet" href="../assets/libs/bootstrap-5.3.3-dist/css/bootstrap.min.css" />
  <link rel="stylesheet" href="../assets/icon/fontawesome-free-6.7.2-web/css/all.min.css" />
  <link rel="stylesheet" href="../src/css/search-styles.css" />
  <link rel="stylesheet" href="../src/css/searchAdvanceMobile.css" />
  <link rel="stylesheet" href="../src/css/footer.css">
  <!-- JS  -->
  <script src="../assets/libs/bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
  <script src="../src/js/Trang_chu.js"></script>
  <!-- <script src="../src/js/main.js"></script> -->
  <script src="../src/js/search-common.js"></script>
  <script src="../src/js/onOffSeacrhAdvance.js"></script>
  <script src="../src/js/Hoa-Don.js"></script>
  <script src="../src/js/search-index.js"></script>
  <title>Hoàn tất đặt hàng</title>
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
                        <option value="">Chọn phân loại</option>
                        <?php
                        require_once '../php-api/connectdb.php'; // Đường dẫn đúng tới file kết nối

                        $conn = connect_db();
                        $sql = "SELECT CategoryName FROM categories ORDER BY CategoryName ASC";
                        $result = $conn->query($sql);

                        if ($result && $result->num_rows > 0) {
                          while ($row = $result->fetch_assoc()) {
                            $categoryName = htmlspecialchars($row['CategoryName']);
                            echo "<option value=\"$categoryName\">$categoryName</option>";
                          }
                        } else {
                          echo '<option value="">Không có phân loại</option>';
                        }

                        $conn->close();
                        ?>
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
                window.location.href = "search-result.php?q=" + encodeURIComponent(searchInput);
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
                        <a class="nav-link login-logout" href="user-register.php">Đăng ký</a>
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
                        <?php
                        require_once '../php-api/connectdb.php'; // hoặc đường dẫn đúng đến file connect của bạn
                        $conn = connect_db();

                        $sql = "SELECT CategoryID, CategoryName FROM categories ORDER BY CategoryID ASC";
                        $result = $conn->query($sql);

                        if ($result && $result->num_rows > 0) {
                          while ($row = $result->fetch_assoc()) {
                            $categoryID = htmlspecialchars($row['CategoryID']);
                            $categoryName = htmlspecialchars($row['CategoryName']);
                            echo "<li><a class='dropdown-item' href='./phan-loai.php?category_id=$categoryID'>$categoryName</a></li>";
                          }
                        } else {
                          echo "<li><span class='dropdown-item text-muted'>Không có danh mục</span></li>";
                        }

                        $conn->close();
                        ?>
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
                          <option value="">Chọn phân loại</option>
                          <?php
                          require_once '../php-api/connectdb.php'; // Đường dẫn đúng tới file kết nối

                          $conn = connect_db();
                          $sql = "SELECT CategoryName FROM categories ORDER BY CategoryName ASC";
                          $result = $conn->query($sql);

                          if ($result && $result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                              $categoryName = htmlspecialchars($row['CategoryName']);
                              echo "<option value=\"$categoryName\">$categoryName</option>";
                            }
                          } else {
                            echo '<option value="">Không có phân loại</option>';
                          }

                          $conn->close();
                          ?>
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
                  <?php
                  require_once '../php-api/connectdb.php'; // hoặc đường dẫn đúng đến file connect của bạn
                  $conn = connect_db();

                  $sql = "SELECT CategoryID, CategoryName FROM categories ORDER BY CategoryID ASC";
                  $result = $conn->query($sql);

                  if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                      $categoryID = htmlspecialchars($row['CategoryID']);
                      $categoryName = htmlspecialchars($row['CategoryName']);
                      echo "<li><a class='dropdown-item' href='./phan-loai.php?category_id=$categoryID'>$categoryName</a></li>";
                    }
                  } else {
                    echo "<li><span class='dropdown-item text-muted'>Không có danh mục</span></li>";
                  }

                  $conn->close();
                  ?>
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

    <!-- ARTICLE -->
    <div class="article">
      <div class="title-cart">
        <p class="text-success h1 text-center text-uppercase">Hoàn tất</p>
      </div>

      <div class="infor-order bg-light">
        <div class="status-order">
          <img class="cart-2" src="../assets/images/cart.svg" alt="cart" />
          <hr />
          <img src="../assets/images/id-card.svg" class="id-01" alt="id-card" />
          <hr />
          <img src="../assets/images/circle-check.svg" class="id-02" alt="ccheck" />
        </div>

        <div class="noti-order-success">
          <p class="text-uppercase fw-bold w-100 text-center">
            bạn đã đặt hàng thành công
          </p>
        </div>

        <div class="noti-thanks">
          <p class="fs-4">
            THE TREE xin cảm ơn các bạn đã ủng hộ chúng tôi trong suốt thời gian
            qua.
          </p>
        </div>

        <div class="invoice-container">
          <h2>HÓA ĐƠN MUA HÀNG</h2>
          <p><strong>Mã hóa đơn:</strong> <?= $order['OrderID'] ?> <span id="invoice-id"></span></p>
          <p><strong>Ngày mua:</strong> <?= $order['DateGeneration'] ?><span id="purchase-date"></span></p>
          <p><strong>Tên khách hàng:</strong> <?= $order['CustomerName'] ?> <span id="customer-name"></span></p>
          <p><strong>Số điện thoại:</strong> <?= $order['Phone'] ?> <span id="customer-phone"></span></p>
          <p><strong>Địa chỉ:</strong> <?= $order['Address'] ?>, <?= $order['WardName'] ?>, <?= $order['DistrictName'] ?>, <?= $order['ProvinceName'] ?><span id="customer-address"></span></p>

        <table> 
          <thead>
                  <tr>
                      <th>Sản phẩm</th>
                      <th>Hình ảnh</th>
                      <th>Số lượng</th>
                      <th>Giá</th>
                      <th>Thành tiền</th>
                  </tr>
              </thead>
              <tbody id="invoice-body">
                  <?php while ($row = $details->fetch_assoc()): ?>
                      <tr>
                          <td><?php echo htmlspecialchars($row['ProductName']); ?></td>
                          <td><img src="<?php echo ".." . $row['ImageURL']; ?>" alt="<?php echo htmlspecialchars($row['ProductName']); ?>" width="80"></td>
                          <td><?= $row['Quantity'] ?></td>
                          <td><?= number_format($row['UnitPrice'], 0, ',', '.') ?>đ</td>
                          <td><?= number_format($row['TotalPrice'], 0, ',', '.') ?>đ</td>
                      </tr>
                  <?php endwhile; ?>
              </tbody>
          </table>
          <div class="total" style="color: red;font-size: 23px;" >
              <strong>Tổng cộng: </strong> <span id="total-price"><?= number_format($order['TotalAmount'], 0, ',', '.') ?>đ</span>
          </div>
      </div>


        <div class="continue-shopping">
          <a href="../index.php" class="btn btn-success">Tiếp tục mua sắm</a>
        </div>

      </div>
    </div>

    <!-- FOOTER  -->
    <footer class="footer">
      <div class="footer-column">
        <h3>The Tree</h3>
        <ul>
          <li><a href="#">Cây dễ chăm</a></li>
          <li><a href="#">Cây văn phòng</a></li>
          <li><a href="#">Cây dưới nước</a></li>
          <li><a href="#">Cây để bàn</a></li>
        </ul>
      </div>

      <div class="footer-column">
        <h3>Khám phá</h3>
        <ul>
          <li><a href="#">Cách chăm sóc cây</a></li>
          <li><a href="#">Lợi ích của cây xanh</a></li>
          <li><a href="#">Cây phong thủy</a></li>
        </ul>
      </div>

      <div class="footer-column">
        <h3>Khám phá thêm từ The Tree</h3>
        <ul>
          <li><a href="#">Blog</a></li>
          <li><a href="#">Cộng tác viên</a></li>
          <li><a href="#">Liên hệ</a></li>
          <li><a href="#">Câu hỏi thường gặp</a></li>
          <li><a href="#">Đăng nhập</a></li>
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
        © 2021 c01.nhahodau

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
</body>

</html>