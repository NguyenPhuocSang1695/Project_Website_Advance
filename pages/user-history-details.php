<?php
require_once('../src/php/token.php');
require_once('../src/php/check_token_v2.php');
require_once('../src/php/connect.php');

$orderID = $_GET['orderid'] ?? 0;

// Lấy tổng thanh toán
$totalAmount = 0;
$stmt = $conn->prepare("SELECT TotalAmount FROM orders WHERE OrderID = ?");
$stmt->bind_param("i", $orderID);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
  $totalAmount = $row['TotalAmount'];
}

// Lấy danh sách sản phẩm trong đơn hàng
$stmt = $conn->prepare("
    SELECT p.ProductName, p.ImageURL, od.Quantity, od.TotalPrice
    FROM orderdetails od
    JOIN products p ON od.ProductID = p.ProductID
    WHERE od.OrderID = ?
");
$stmt->bind_param("i", $orderID);
$stmt->execute();
$productResult = $stmt->get_result();
?>

<?php
$orderID = $_GET['orderid']; // hoặc lấy từ session nếu cần

$sql = "SELECT o.CustomerName, o.Phone, o.Address,
               p.Name AS ProvinceName, d.Name AS DistrictName, w.Name AS WardName
        FROM orders o
        JOIN province p ON o.Province = p.province_id
        JOIN district d ON o.District = d.district_id
        JOIN wards w ON o.Ward = w.wards_id
        WHERE o.OrderID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $orderID);
$stmt->execute();
$result = $stmt->get_result();
$orderInfo = $result->fetch_assoc(); // ⚠️ sửa lại tên biến thành orderInfo cho khớp

?>



<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <!-- CSS  -->
  <link rel="stylesheet" type="text/css" href="../src/css/user-history-details.css" />
  <link rel="stylesheet" href="../assets/libs/bootstrap-5.3.3-dist/css/bootstrap.min.css" />
  <link rel="stylesheet" href="../assets/icon/fontawesome-free-6.7.2-web/css/all.min.css" />
  <link rel="stylesheet" href="../src/css/search-styles.css" />
  <link rel="stylesheet" href="../src/css/searchAdvanceMobile.css" />
  <link rel="stylesheet" href="../src/css/footer.css">
  <!-- JS  -->
  <script src="../src/js/search-common.js"></script>
  <script src="../assets/libs/bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
  <script src="../src/js/Trang_chu.js"></script>
  <link rel="stylesheet" href="../assets/libs/fontawesome-free-6.6.0-web/css/all.min.css" />
  <!-- <script src="../src/js/main.js"></script> -->
  <script src="../src/js/onOffSeacrhAdvance.js"></script>
  <script src="../src/js/search-index.js"></script>
  <title>Lịch sử người dùng</title>
  <style>
    .section.products table tbody {
      max-height: 400px;
      overflow-y: auto;
      display: block;
    }

    .section.products table thead,
    .section.products table tbody tr {
      display: table;
      width: 100%;
      table-layout: fixed;
    }
  </style>
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
                window.location.href = "./search-result.php?q=" + encodeURIComponent(searchInput);
              });
            </script>

            <div class="cart-icon">
              <a href="./gio-hang.php"><img src="../assets/images/cart.svg" alt="cart" /></a>
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
  <div class="section1">
    <div class="img-21">
      <img src="../assets/images/CAY21.jpg" alt="CAY21" />
    </div>
  </div>

  <section>
    <div class="information-client">
      <h2>Thông tin người nhận</h2>
      <hr>
      <div class="thongtin">
        <h5>Họ tên: <?= htmlspecialchars($orderInfo['CustomerName']) ?></h5>
        <h5>Số điện thoại: <?= htmlspecialchars($orderInfo['Phone']) ?></h5>
        <h5>Địa chỉ:
          <?= isset($orderInfo['Address']) ? htmlspecialchars($orderInfo['Address']) . ', ' : '' ?>
          <?= htmlspecialchars($orderInfo['WardName']) ?>,
          <?= htmlspecialchars($orderInfo['DistrictName']) ?>,
          <?= htmlspecialchars($orderInfo['ProvinceName']) ?>
        </h5>
      </div>
    </div>


    <div class="history">
      <div class="main-content">
        <!-- Left Section -->
        <div class="left-section">
          <div class="section products">
            <div class="section-header">
              <span style="color:#21923c;">
                <i class="fa-regular fa-circle" style="margin-right: 5px;"></i>Chi tiết đơn hàng
              </span>
              <button class="more-btn">...</button>
            </div>
            <table>
              <thead>
                <tr>
                  <th></th>
                  <th>SỐ LƯỢNG</th>
                  <th>THÀNH TIỀN (đ)</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $totalQuantity = 0; // <== Thêm dòng này
                while ($row = $productResult->fetch_assoc()):
                  $totalQuantity += $row['Quantity']; // <== Thêm dòng này
                ?>
                  <tr>
                    <td>
                      <img src="..<?= htmlspecialchars($row['ImageURL']) ?>" alt="Product Image">
                      <div class="product-info">
                        <span class="product-name"><?= htmlspecialchars($row['ProductName']) ?></span><br>
                      </div>
                    </td>
                    <td><?= $row['Quantity'] ?></td>
                    <td><?= number_format($row['TotalPrice'], 0, ',', '.') ?> đ</td>
                  </tr>
                <?php endwhile; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="section payment">
        <div class="section-header">
          <span>Thanh Toán: </span>
        </div>
        <div class="payment-details">
          <div class="payment-row">
            <span>Số lượng sản phẩm: </span>
            <span><?= $totalQuantity ?></span> <!-- Đổi chỗ này -->
          </div>
          <div class="payment-row paid">
            <span>Tổng thanh toán</span>
            <span><?= number_format($totalAmount, 0, ',', '.') ?> đ</span>
          </div>
        </div>
      </div>
    </div>

  </section>

  <script>
    $("#menu-btn").click(function() {
      $("#menu").toggleClass("active");
    });
  </script>

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