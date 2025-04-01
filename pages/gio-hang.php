<?php
session_start();

// Kết nối database
$servername = "localhost";
$username = "root";
$password = "";
$database = "webdb";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
  if (isset($_POST['product_id'], $_POST['quantity'])) 
  {
      // 🛒 Xử lý thêm sản phẩm vào giỏ hàng
      $product_id = intval($_POST['product_id']);
      $quantity = intval($_POST['quantity']);

      if ($quantity <= 0) {
          $quantity = 1; // Đảm bảo số lượng hợp lệ
      }

      // Kiểm tra xem sản phẩm đã có trong giỏ hàng chưa
      $sql_check = "SELECT * FROM cart WHERE ProductID = ?";
      $stmt_check = $conn->prepare($sql_check);
      $stmt_check->bind_param("i", $product_id);
      $stmt_check->execute();
      $result_check = $stmt_check->get_result();

      if ($result_check->num_rows > 0) {
          // Nếu đã có trong giỏ hàng, cập nhật số lượng mới
          $sql_update = "UPDATE cart SET Quantity = Quantity + ? WHERE ProductID = ?";
          $stmt_update = $conn->prepare($sql_update);
          $stmt_update->bind_param("ii", $quantity, $product_id);
          $stmt_update->execute();
      } else {
          // Nếu chưa có, thêm mới vào giỏ hàng
          $sql_insert = "INSERT INTO cart (ProductID, Quantity) VALUES (?, ?)";
          $stmt_insert = $conn->prepare($sql_insert);
          $stmt_insert->bind_param("ii", $product_id, $quantity);
          $stmt_insert->execute();
      }

      header("Location: ?id=$product_id"); // Reload lại trang
      exit();
  }

      //cập nhập số lượng sản phẩm
      if (isset($_POST['update_product_id'], $_POST['quantity'])) {
        $product_id = intval($_POST['update_product_id']);
        
        // Kiểm tra số lượng nhập vào có hợp lệ hay không
        if (!isset($_POST['quantity']) || !is_numeric($_POST['quantity']) || intval($_POST['quantity']) < 1) {
            header("Location: gio-hang.php?error=Số lượng không hợp lệ. Vui lòng nhập số lượng lớn hơn hoặc bằng 1.");
            exit();
        }
        $new_quantity = intval($_POST['quantity']);
        
        // Cập nhật số lượng trong database
        $sql_update = "UPDATE cart SET Quantity = ? WHERE ProductID = ?";
        $stmt_update = $conn->prepare($sql_update);
        if (!$stmt_update) {
            die("Lỗi chuẩn bị câu lệnh SQL: " . $conn->error);
        }
        $stmt_update->bind_param("ii", $new_quantity, $product_id);
        if ($stmt_update->execute()) {
            header("Location: gio-hang.php?success=Cập nhật số lượng thành công");
            exit();
        } else {
            die("Lỗi khi cập nhật: " . $stmt_update->error);
        }
    }
    

      // Xử lý xóa sản phẩm khỏi giỏ hàng
      if (isset($_POST['remove_product_id'])) 
      {
        $remove_product_id = intval($_POST['remove_product_id']);

        $sql_delete = "DELETE FROM cart WHERE ProductID = ?";
        $stmt_delete = $conn->prepare($sql_delete);
        $stmt_delete->bind_param("i", $remove_product_id);
        $stmt_delete->execute();

        header("Location: gio-hang.php"); // Reload lại trang sau khi xóa
        exit();
      }
}
/// Lấy danh sách sản phẩm trong giỏ hàng
$sql_cart = "SELECT c.CartID, c.ProductID, c.Quantity, p.ProductName, p.Price, p.ImageURL 
FROM cart c 
JOIN products p ON c.ProductID = p.ProductID";
$result_cart = $conn->query($sql_cart);

$cart_items = [];
$total_price = 0;
while ($item = $result_cart->fetch_assoc()) {
$cart_items[] = $item;
$total_price += $item['Price'] * $item['Quantity'];
}
$total_price = floatval($total_price);
$total_price_formatted = number_format($total_price, 0, ',', '.') . " VNĐ";
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- CSS  -->
  <link rel="stylesheet" href="../src/css/gio-hang.css">
  <link rel="stylesheet" href="../assets/libs/bootstrap-5.3.3-dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="../src/css/search-styles.css">
  <link rel="stylesheet" href="../assets/icon/fontawesome-free-6.7.2-web/css/all.min.css">
  <link rel="stylesheet" href="../src/css/searchAdvanceMobile.css">
  <link rel="stylesheet" href="../src/css/gio-hang-php.css">

  <link rel="stylesheet" href="../src/css/footer.css">
  <!-- JS  -->
  <script src="../assets/libs/bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
  <script src="../src/js/Trang_chu.js"></script>
  <script src="../src/js/main.js"></script>
  <script src="../src/js/search-common.js"></script>
  <script src="../src/js/onOffSeacrhAdvance.js"></script>
  <script src="../src/js/gio-hang.js"></script>
  <!-- Lọc sản phẩm theo phân loại  -->
  <!-- <script src="../src/js/filter-product.js"></script> -->
  <title>Giỏ hàng</title>
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
              <a href="gio-hang.html"><img src="../assets/images/cart.svg" alt="cart" /></a>
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
      <img src="../assets/images/CAY21.jpg" alt="CAY21">
    </div>
  </div>


  <!-- ARTICLE -->
  <div class="article">
    <div class="title-cart">
      <p class="text-success h1 text-center text-uppercase">Giỏ hàng</p>
    </div>
    <div class="infor-order bg-light">
      <div class="status-order">
        <img class="cart-2" src="../assets/images/cart.svg" alt="cart">
        <hr>
        <img src="../assets/images/id-card.svg" alt="id-card">
        <hr>
        <img src="../assets/images/circle-check.svg" alt="ccheck">
      </div>
        <?php if (count($cart_items) > 0):?>
          <?php foreach ($cart_items as $item): ?>

            <div class="order">

              <div class="order-img">
                <img src="<?php echo ".." . $item['ImageURL']; ?>" width="120" class="cart-image">
              </div>

              <div class="frame">
                <div class="name-price">
                    <p><strong><?php echo htmlspecialchars($item['ProductName']); ?></strong></p>
                    <!-- Giá sản phẩm hiển thị, gán thêm data-price để JS dễ lấy -->
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
                  <!-- Modal -->
               
                  <div class="add-del">
                    <div class="oder">
                      <div class="wrapper" >
                      <form action="gio-hang.php" method="POST" class="update-form">
                        <!-- Truyền ProductID để xác định sản phẩm cần cập nhật -->
                        <input type="hidden" name="update_product_id" value="<?php echo $item['ProductID']; ?>">
                        
                        <!-- Nút giảm số lượng -->
                        <button type="button" class="quantity-btn" onclick="changeQuantity(this, -1)">-</button>
                        
                        <!-- Trường số lượng, gán thuộc tính data-price để JS dùng cho tính toán nếu cần -->
                        <input type="number" name="quantity" value="<?php echo max(1, $item['Quantity']); ?>" min="1" 
                              class="quantity-input" data-price="<?php echo $item['Price']; ?>">
                        
                        <!-- Nút tăng số lượng -->
                        <button type="button" class="quantity-btn" onclick="changeQuantity(this, 1)">+</button>
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

      <div class="dat-hang">
        <button onclick="clickCart()" type="button" class="btn btn-success" style="width: 185px;
    height: 50px; margin: 10px 0 15px 0;">ĐẶT HÀNG</button>
      </div>
      <div class="text" style="margin-bottom: 10px;">
        <!-- quay về trang chủ  -->
        <a style="text-decoration: none;" href="../index.html">Tiếp tục mua hàng</a>
      </div>
    </div>


  </div>

  <!-- <div class="type-tree" id="type-tree"></div>
  <div id="product-list">Kết quả ở đây</div> -->

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
  </div>
</body>

</html>