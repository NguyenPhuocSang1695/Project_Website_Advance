<!DOCTYPE html>
<?php
require_once('../src/php/token.php');
require_once('../src/php/connect.php');

// Lấy ID từ URL
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

// Truy vấn sản phẩm theo ID
$sql = "SELECT * FROM products WHERE ProductID = $id";
$result = $conn->query($sql);

// Nếu có sản phẩm
if ($result && $result->num_rows > 0) {
  $product = $result->fetch_assoc();
} else {
  echo "Không tìm thấy sản phẩm.";
  exit;
}
?>

<html>

<head>
  <title>Chi tiết sản phẩm</title>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <!-- CSS  -->
  <link rel="stylesheet" href="../src/css/san-pham.css" />
  <link rel="stylesheet" href="../assets/icon/fontawesome-free-6.7.2-web/css/all.min.css" />
  <link rel="stylesheet" href="../src/css/search-styles.css" />
  <link rel="stylesheet" href="../src/css/searchAdvanceMobile.css" />
  <link rel="stylesheet" href="../assets/libs/bootstrap-5.3.3-dist/css/bootstrap.min.css" />
  <link rel="stylesheet" href="../assets/libs/fontawesome-free-6.6.0-web/fontawesome-free-6.6.0-web/css/all.min.css" />
  <link rel="stylesheet" href="../src/css/footer.css">
  <!-- JS  -->
  <script src="../src/js/search-common.js"></script>
  <script src="../src/js/Trang_chu.js"></script>
  <script src="../assets/libs/bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
  <!-- <script src="../src/js/main.js"></script> -->
  <script src="../src/js/onOffSeacrhAdvance.js"></script>
</head>

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
                <img src="./assets/images/CAY5.jpg" alt="Cây phát tài" />
                <div class="p-details">
                  <h2>Cây phát tài</h2>
                  <h3>750.000 vnđ</h3>
                </div>
              </div>

              <!-- OK  -->
              <div class="product" data-category="cay-van-phong">
                <img src="./assets/images/CAY6.jpg" alt="Cây kim ngân" />
                <div class="p-details">
                  <h2>Cây kim ngân</h2>
                  <h3>280.000 vnđ</h3>
                </div>
              </div>

              <div class="product" data-category="cay-de-ban">
                <img src="./assets/images/CAY7.jpg" alt="Cây trầu bà" />
                <div class="p-details">
                  <h2>Cây trầu bà</h2>
                  <h3>120.000 vnđ</h3>
                </div>
              </div>

              <div class="product" data-category="cay-duoi-nuoc">
                <img src="./assets/images/CAY8.jpg" alt="Cây lan chi" />
                <div class="p-details">
                  <h2>Cây lan chi</h2>
                  <h3>120.000 vnđ</h3>
                </div>
              </div>

              <div class="product" data-category="cay-de-ban">
                <img src="./assets/images/CAY9.jpg" alt="Cây trầu bà đỏ" />
                <div class="p-details">
                  <h2>Cây trầu bà đỏ</h2>
                  <h3>320.000 vnđ</h3>
                </div>
              </div>

              <div class="product" data-category="cay-de-ban">
                <img src="./assets/images/CAY10.jpg" alt="Cây lưỡi hổ" />
                <div class="p-details">
                  <h2>Cây lưỡi hổ</h2>
                  <h3>750.000 vnđ</h3>
                </div>
              </div>

              <div class="product" data-category="cay-de-ban">
                <img src="./assets/images/CAY11.jpg" alt="Cây lưỡi hổ vàng" />
                <div class="p-details">
                  <h2>Cây lưỡi hổ vàng</h2>
                  <h3>160.000 vnđ</h3>
                </div>
              </div>

              <div class="product" data-category="cay-de-ban">
                <img src="./assets/images/CAY12.jpg" alt="Cây hạnh phúc" />
                <div class="p-details">
                  <h2>Cây hạnh phúc</h2>
                  <h3>1.200.000 vnđ</h3>
                </div>
              </div>

              <div class="product" data-category="cay-de-ban">
                <img src="./assets/images/CAY13.jpg" alt="Cây trầu bà châu lớn" />
                <div class="p-details">
                  <h2>Cây trầu bà châu lớn</h2>
                  <h3>1.100.000 vnđ</h3>
                </div>
              </div>

              <div class="product" data-category="cay-van-phong">
                <img src="./assets/images/CAY14.jpg" alt="Cây phát tài DORADO" />
                <div class="p-details">
                  <h2>Cây phát tài DORADO</h2>
                  <h3>220.000 vnđ</h3>
                </div>
              </div>
              <div class="product" data-category="cay-de-ban">
                <img src="./assets/images/CAY16.jpg" alt="Cây vạn lộc" />
                <div class="p-details">
                  <h2>Cây vạn lộc</h2>
                  <h3>1.150.000 vnđ</h3>
                </div>
              </div>

              <div class="product" data-category="cay-de-ban">
                <img src="./assets/images/CAY17.jpg" alt="Cây ngọc vừng" />
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
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
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


<div class="sanpham">
  <!-- Ảnh sản phẩm -->
  <img src="<?php echo ".." . $product['ImageURL']; ?>" alt="<?php echo $product['ProductName']; ?>"
    class="single-image" />

  <!-- Tên cây, giá, mô tả -->
  <div class="details">
    <div class="nametree">
      <h2><?php echo $product['ProductName']; ?></h2>
      <h3 id="giá"><?php echo number_format($product['Price'], 0, ',', '.') . " VNĐ"; ?></h3>
    </div>

    <!-- Nút tăng chỉnh số lượng -->
    <div class="order">
      <div class="wrapper">
        <span class="minus">-</span>
        <span class="num">01</span>
        <span class="plus">+</span>
        <script src="../src/js/san-pham.js"></script>
      </div>
      <button type="button" class="btn btn-primary btn-lg">
        Thêm vào giỏ hàng
      </button>
    </div>
  </div>
</div>
<div class="description">
  <hr style="height: 3px; border-width: 0; background-color: #1c8e2e; width: 20%;" />
  <h3>MÔ TẢ</h3>
  <p><?php echo ($product['Description'] !== 'None') ? $product['Description'] : 'Không có mô tả'; ?></p>
</div>
<?php $conn->close(); ?>
</div>




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
<script src="../assets/libs/bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
<script>
  function logOut() {
    document.cookie = "token=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
  }
</script>

</body>

</html>