<!DOCTYPE html>
<?php
session_start();
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


$cart_count =  0;

if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $cart_count += $item['Quantity'];
    }
}
// Kiểm tra giỏ hàng
$cart_items = isset($_SESSION['cart']) && is_array($_SESSION['cart']) ? $_SESSION['cart'] : [];
// Tính tổng
$total_amount = 0;
foreach ($cart_items as $item) {
    $total_amount += $item['Price'] * $item['Quantity'];
}
$total_price_formatted = number_format($total_amount, 0, ',', '.') . " VNĐ";

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
  <link rel="stylesheet" href="../src/css/user-sanpham.css" />

  <link rel="stylesheet" href="../assets/libs/bootstrap-5.3.3-dist/css/bootstrap.min.css" />
  <link rel="stylesheet" href="../assets/libs/fontawesome-free-6.6.0-web/fontawesome-free-6.6.0-web/css/all.min.css" />
  <link rel="stylesheet" href="../src/css/footer.css">
  <!-- JS  -->
  <script src="../src/js/search-common.js"></script>
  <script src="../src/js/Trang_chu.js"></script>
  <script src="../assets/libs/bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
  <!-- <script src="../src/js/main.js"></script> -->
  <script src="../src/js/onOffSeacrhAdvance.js"></script>
  <script src="../src/js/search-index.js"></script>
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
          <div class="cart-wrapper">
              <div class="cart-icon">
                <a href="gio-hang.php"><img src="../assets/images/cart.svg" alt="cart" />
                <span class="cart-count" id = "mni-cart-count" style="position: absolute; margin-top: -10px; background-color: red; color: white; border-radius: 50%; padding: 2px 5px; font-size: 12px;">
                  <?php 
                    echo $cart_count;
                  ?>
                </span>
                </a>
              </div>
              <div class="cart-dropdown">
                    <?php if (count($cart_items) >0): ?>
                        <?php foreach ($cart_items as $item): ?>
                            <div class="cart-item">
                                <img src="<?php echo ".." . $item['ImageURL']; ?>" alt="<?php echo $item['ProductName']; ?>"  class="cart-thumb"/>                                
                                <div class="cart-item-details">
                                    <h5><?php echo $item['ProductName']; ?></h5>
                                    <p>Giá: <?php echo number_format($item['Price'], 0, ',', '.') . " VNĐ"; ?></p>
                                    <p><?php echo $item['Quantity']; ?> × <?php echo number_format($item['Price'], 0, ',', '.'); ?>VNĐ</p>
                                  </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>Giỏ hàng của bạn đang trống.</p>
                    <?php endif; ?>
                </div>
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
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
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
      </div> 
      <script src ="../src/js/san-pham.js"></script>
      <!-- Form thêm vào giỏ hàng -->
      <form id = "add-to-cart-form">
        <input type="hidden" name="product_id" value="<?php echo $product['ProductID']; ?>">
        <input type="hidden" name="quantity" id="quantity" value="1">
        <button type="submit" class="btn btn-primary btn-lg add-to-cart" 
          data-id="<?= $product['ProductID'] ?>" 
          data-name="<?= $product['ProductName'] ?>"
          data-price="<?= $product['Price'] ?>"
          data-image="<?= $product['ImageURL'] ?>"
        >
        Thêm vào giỏ hàng
      </button>
      </form>
      <script>
        document.addEventListener('DOMContentLoaded', function () {
          document.querySelectorAll('.add-to-cart').forEach(button => {
            button.addEventListener('click', function () {
              const productId = this.dataset.id;
              const productName = this.dataset.name;
              const price = this.dataset.price;
              const image = this.dataset.image;

              fetch('cart.php', {
                method: 'POST',
                headers: {
                  'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                  id: productId,
                  name: productName,
                  price: price,
                  image: image,
                })
              })
              .then(res => res.json())
              .then(data => {
                if (data.success) {
                  // 1. Hiện thông báo thành công
                  alert('🛒 Đã thêm vào giỏ hàng thành công!');

                  // 2. Cập nhật dropdown & số lượng
                  document.getElementById('mni-cart-count').innerText = data.cart_count;
                  document.querySelector('.cart-dropdown').innerHTML = data.cart_html;

                  
                  // auto ẩn sau 3 giây
                }
              });
            });
          });
        });
        </script>

      <script src="../src/js/load-sanpham.js"></script>
    </div>
  </div>
</div>
<div class="description">
  <hr style="height: 3px; border-width: 0; background-color: #1c8e2e; width: 20%;" />
  <h3>MÔ TẢ</h3>
  <p><?php echo ($product['Description'] !== 'None') ? $product['Description'] : 'Không có mô tả'; ?></p>
</div>
</div>




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
<script src="../assets/libs/bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>



</body>

</html>