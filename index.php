<?php
require_once './src/php/token.php';
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <!-- CSS  -->
  <link rel="stylesheet" type="text/css" href="./src/css/trang-chu.css" />
  <link rel="stylesheet" href="./assets/libs/bootstrap-5.3.3-dist/css/bootstrap.min.css" />
  <link rel="stylesheet" href="assets/icon/fontawesome-free-6.7.2-web/css/all.min.css" />
  <link rel="stylesheet" href="./src/css/searchAdvanceMobile.css" />
  <link rel="stylesheet" href="./src/css/footer.css">
  <!-- JS  -->
  <!-- <script src="./src/js/main.js"></script> -->
  <script src="./assets/libs/bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
  <script src="./src/js/onOffSeacrhAdvance.js"></script>
  <!-- <script src="./src/js/search.js"></script> -->
  <!-- <script src="./src/js/tim-kiem-nang-cao.js"></script> -->
  <script src="./src/js/search-common.js"></script>
  <script src="./src/js/search-index.js"></script>
  <!-- Tìm kiếm  -->
  <title>Trang Chủ</title>
  <!-- <script src="./src/js/search.js"></script> -->
  <!-- AVCCVSA -->
</head>

<!-- <script src="./src/js/search.js"></script> -->

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
                    <input type="search" placeholder="Tìm kiếm sản phẩm..." id="searchInput"
                      name="search" class="search-input" />
                    <button type="button" class="advanced-search-toggle" id="advanced-search-toggle"
                      onclick="toggleAdvancedSearch()" title="Tìm kiếm nâng cao">
                      <i class="fas fa-sliders-h"></i>
                    </button>
                    <button type="submit" class="search-button" onclick="performSearch()"
                      title="Tìm kiếm">
                      <i class="fas fa-search"></i>
                    </button>
                  </div>
                </div>

                <!-- Form tìm kiếm nâng cao được thiết kế lại -->
                <div id="advancedSearchForm" class="advanced-search-panel" style="display: none">
                  <div class="advanced-search-header">
                    <h5>Tìm kiếm nâng cao</h5>
                    <button type="button" class="close-advanced-search"
                      onclick="toggleAdvancedSearch()">
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
                        require_once './php-api/connectdb.php'; // Đường dẫn đúng tới file kết nối

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
                          <input type="number" id="minPrice" name="minPrice" placeholder="Từ"
                            min="0" />
                          <span class="price-separator">-</span>
                          <input type="number" id="maxPrice" name="maxPrice" placeholder="Đến"
                            min="0" />
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

            <!-- Header, Giỏ hàng và user -->
            <div class="cart-icon">
              <a href="./pages/gio-hang.php"><img src="./assets/images/cart.svg" alt="cart" /></a>
            </div>
            <div class="user-icon">
              <label for="tick" style="cursor: pointer">
                <img src="assets/images/user.svg" alt="" />
              </label>
              <input id="tick" hidden type="button" data-bs-toggle="offcanvas"
                data-bs-target="#offcanvasExample" aria-controls="offcanvasExample" />
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
                        <a class="nav-link login-logout" href="./pages/user-register.php">Đăng
                          kí</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link login-logout" href="./pages/user-login.php">Đăng nhập</a>
                      </li>
                    <?php else: ?>
                      <li class="nav-item">
                        <a class="nav-link hs-ls-dx" href="ho-so.php">Hồ sơ</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link hs-ls-dx" href="./pages/user-History.php">Lịch sử mua
                          hàng</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link hs-ls-dx" href="./src/php/logout.php">Đăng xuất</a>
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
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas"
              data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar"
              aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>
            <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasNavbar"
              aria-labelledby="offcanvasNavbarLabel">
              <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="offcanvasNavbarLabel">
                  THEE TREE
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas"
                  aria-label="Close"></button>
              </div>
              <div id="offcanvasbody" class="offcanvas-body offcanvas-fullscreen mt-20">
                <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                  <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="index.php">Trang chủ</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="#">Giới thiệu</a>
                  </li>
                  <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button"
                      data-bs-toggle="dropdown" aria-expanded="false">
                      Sản phẩm
                    </a>
                    <ul class="dropdown-menu">
                      <?php
                      require_once './php-api/connectdb.php'; // hoặc đường dẫn đúng đến file connect của bạn
                      $conn = connect_db();

                      $sql = "SELECT CategoryID, CategoryName FROM categories ORDER BY CategoryID ASC";
                      $result = $conn->query($sql);

                      if ($result && $result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                          $categoryID = htmlspecialchars($row['CategoryID']);
                          $categoryName = htmlspecialchars($row['CategoryName']);
                          echo "<li><a class='dropdown-item' href='./pages/phan-loai.php?category_id=$categoryID'>$categoryName</a></li>";
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
                <!-- form tìm kiếm trên mobile  -->
                <form class="searchFormMobile mt-3" role="search" id="searchFormMobile">
                  <div class="d-flex">
                    <input class="form-control me-2" type="search" placeholder="Tìm kiếm"
                      aria-label="Search" style="height: 37.6px;" />
                    <!-- Nút tìm kiếm nâng cao trên mobile  -->
                    <button type="button" class="advanced-search-toggle"
                      onclick="toggleMobileSearch()" title="Tìm kiếm nâng cao">
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
                        require_once './php-api/connectdb.php'; // Đường dẫn đúng tới file kết nối

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
                          <input type="number" id="minPriceMobile" name="minPrice"
                            placeholder="Từ" min="0" />
                          <span class="price-separator">-</span>
                          <input type="number" id="maxPriceMobile" name="maxPrice"
                            placeholder="Đến" min="0" />
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
          <a href="index.php"><img class="img-fluid" src="./assets/images/LOGO-2.jpg" alt="LOGO" /></a>
        </div>
        <div class="brand-name">THE TREE</div>
      </div>
      <div class="choose">
        <ul>
          <li>
            <a href="index.php" style="font-weight: bold">Trang chủ</a>
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
                require_once './php-api/connectdb.php'; // hoặc đường dẫn đúng đến file connect của bạn
                $conn = connect_db();

                $sql = "SELECT CategoryID, CategoryName FROM categories ORDER BY CategoryID ASC";
                $result = $conn->query($sql);

                if ($result && $result->num_rows > 0) {
                  while ($row = $result->fetch_assoc()) {
                    $categoryID = htmlspecialchars($row['CategoryID']);
                    $categoryName = htmlspecialchars($row['CategoryName']);
                    echo "<li><a class='dropdown-item' href='./pages/phan-loai.php?category_id=$categoryID'>$categoryName</a></li>";
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
      <img src="./assets/images/CAY21.jpg" alt="CAY21" />
    </div>
  </div>

  <main>
    <!-- DANH MỤC SẢN PHẨM -->
    <?php
    $host = 'localhost';
    $user = 'root';
    $password = '';
    $dbname = 'c01db';

    $conn = new mysqli($host, $user, $password, $dbname);
    if ($conn->connect_error) {
      die("Kết nối thất bại: " . $conn->connect_error);
    }

    // Truy vấn: Lấy sản phẩm đầu tiên (MIN ProductID) trong mỗi danh mục có ít nhất 1 sản phẩm Status = 'appear'
    $sql = "
  SELECT p.*, c.CategoryName
  FROM categories c
  JOIN (
    SELECT CategoryID, MIN(ProductID) AS MinProductID
    FROM products
    WHERE Status = 'appear'
    GROUP BY CategoryID
  ) AS sub ON c.CategoryID = sub.CategoryID
  JOIN products p ON p.ProductID = sub.MinProductID
  ORDER BY c.CategoryID;
";

    $result = $conn->query($sql);
    $products = [];
    if ($result && $result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        $products[] = $row;
      }
    }
    $conn->close();
    ?>

    <section id="container_1" class="class">
      <h2 class="font_size">DANH MỤC SẢN PHẨM</h2>
      <div class="IMG">
        <?php foreach ($products as $product): ?>
          <div class="img__TREE">
            <a
              href="./pages/phan-loai.php?category_id=<?= $product['CategoryID'] ?>&category_name=<?= urlencode($product['CategoryName']) ?>">
              <img class="THE-TREE" src=".<?= htmlspecialchars($product['ImageURL']) ?>"
                alt="<?= htmlspecialchars($product['CategoryName']) ?>" />
              <p class="content_TREE-1"><?= htmlspecialchars($product['CategoryName']) ?></p>
            </a>
          </div>
        <?php endforeach; ?>
      </div>
    </section>



    <section id="product1" class="section-p1">
      <!-- SẢN PHẨM MỚI -->
      <h2 class="font_size">SẢN PHẨM MỚI</h2>

      <div class="pro-container">
        <?php
        require_once './php-api/connectdb.php';
        $conn = connect_db();

        $limit = 8; // số sản phẩm mỗi trang
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($page < 1) $page = 1;
        $offset = ($page - 1) * $limit;

        // Đếm tổng số sản phẩm
        $countResult = $conn->query('SELECT COUNT(*) as total FROM products WHERE Status = "appear"');
        $totalRows = $countResult->fetch_assoc()['total'];
        $totalPages = ceil($totalRows / $limit);

        // Truy vấn sản phẩm phân trang
        $stmt = $conn->prepare('
      SELECT ProductID, ProductName, Price, ImageURL 
      FROM products 
      WHERE Status = "appear"
      ORDER BY ProductID DESC
      LIMIT ? OFFSET ?');
        $stmt->bind_param("ii", $limit, $offset);
        $stmt->execute();
        $result = $stmt->get_result();

        // Hiển thị sản phẩm
        if ($result && $result->num_rows > 0):
          while ($product = $result->fetch_assoc()):
        ?>
            <div class="pro">
              <a style="text-decoration: none" href="./pages/user-sanpham.php?id=<?= htmlspecialchars($product['ProductID']) ?>">
                <img style="width: 100%; height: 300px;" src=".<?= htmlspecialchars($product['ImageURL']) ?>" alt="<?= htmlspecialchars($product['ProductName']) ?>" />
                <div class="item_name__price">
                  <p style="text-decoration: none; color: black; font-size: 20px; font-weight:bold"><?= htmlspecialchars($product['ProductName']) ?></p>
                  <span style="font-size: 20px"><?= number_format($product['Price'], 0, ',', '.') ?> vnđ</span>
                </div>
              </a>
            </div>
        <?php
          endwhile;
        else:
          echo "<p>Không có sản phẩm nào để hiển thị.</p>";
        endif;
        ?>
      </div>


      <!-- PHÂN TRANG -->
      <div class="pagination" style="margin-top: 20px; text-align: center; display: flex; justify-content:center">
        <?php if ($totalPages > 1): ?>
          <!-- Nút trang trước -->
          <a href="?page=<?= max(1, $page - 1) ?>" style="
            display: inline-block;
            margin: 0 5px;
            padding: 8px 12px;
            background-color: <?= $page === 1 ? '#ccc' : '#eee' ?>;
            color: <?= $page === 1 ? '#fff' : '#000' ?>;
            border-radius: 5px;
            text-decoration: none;
        ">
            < </a>

              <?php
              if ($totalPages <= 5) {
                // Hiển thị tất cả các trang nếu tổng số trang <= 5
                for ($i = 1; $i <= $totalPages; $i++) {
                  echo '<a href="?page=' . $i . '" style="
                    display: inline-block;
                    margin: 0 5px;
                    padding: 8px 12px;
                    background-color: ' . ($i === $page ? '#4CAF50' : '#eee') . ';
                    color: ' . ($i === $page ? '#fff' : '#000') . ';
                    border-radius: 5px;
                    text-decoration: none;
                ">' . $i . '</a>';
                }
              } else {
                // Hiển thị trang đầu
                echo '<a href="?page=1" style="
                display: inline-block;
                margin: 0 5px;
                padding: 8px 12px;
                background-color: ' . (1 === $page ? '#4CAF50' : '#eee') . ';
                color: ' . (1 === $page ? '#fff' : '#000') . ';
                border-radius: 5px;
                text-decoration: none;
            ">1</a>';

                // Hiển thị dấu "..." nếu trang hiện tại cách trang đầu > 2
                if ($page > 3) {
                  echo '<span style="display: inline-block; margin: 0 5px; padding: 8px 12px;">...</span>';
                }

                // Hiển thị các trang gần trang hiện tại
                $start = max(2, $page - 1);
                $end = min($totalPages - 1, $page + 1);
                for ($i = $start; $i <= $end; $i++) {
                  echo '<a href="?page=' . $i . '" style="
                    display: inline-block;
                    margin: 0 5px;
                    padding: 8px 12px;
                    background-color: ' . ($i === $page ? '#4CAF50' : '#eee') . ';
                    color: ' . ($i === $page ? '#fff' : '#000') . ';
                    border-radius: 5px;
                    text-decoration: none;
                ">' . $i . '</a>';
                }

                // Hiển thị dấu "..." nếu trang hiện tại cách trang cuối > 2
                if ($page < $totalPages - 2) {
                  echo '<span style="display: inline-block; margin: 0 5px; padding: 8px 12px;">...</span>';
                }

                // Hiển thị trang cuối
                echo '<a href="?page=' . $totalPages . '" style="
                display: inline-block;
                margin: 0 5px;
                padding: 8px 12px;
                background-color: ' . ($totalPages === $page ? '#4CAF50' : '#eee') . ';
                color: ' . ($totalPages === $page ? '#fff' : '#000') . ';
                border-radius: 5px;
                text-decoration: none;
            ">' . $totalPages . '</a>';
              }
              ?>

              <!-- Nút trang sau -->
              <a href="?page=<?= min($totalPages, $page + 1) ?>" style="
            display: inline-block;
            margin: 0 5px;
            padding: 8px 12px;
            background-color: <?= $page === $totalPages ? '#ccc' : '#eee' ?>;
            color: <?= $page === $totalPages ? '#fff' : '#000' ?>;
            border-radius: 5px;
            text-decoration: none;
        ">></a>
            <?php endif; ?>
      </div>



    </section>
  </main>

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
  <script>
    // search.js - Xử lý tìm kiếm cho website bán cây

    document.addEventListener("DOMContentLoaded", function() {
      // Kiểm tra nếu đang ở trang kết quả tìm kiếm
      if (window.location.pathname.includes("search-result.php")) {
        loadSearchResults();
      }

      // Khởi tạo sự kiện cho form tìm kiếm
      initializeSearchForms();
    });

    // Khởi tạo các form tìm kiếm
    function initializeSearchForms() {
      // Xử lý form tìm kiếm desktop
      const desktopForm = document.getElementById("searchForm");
      if (desktopForm) {
        desktopForm.addEventListener("submit", function(e) {
          e.preventDefault();
          performSearch();
        });
      }

      // Xử lý form tìm kiếm mobile
      const mobileForm = document.getElementById("searchFormMobile");
      if (mobileForm) {
        mobileForm.addEventListener("submit", function(e) {
          e.preventDefault();
          performSearchMobile();
        });
      }
    }

    // Hàm chuyển đổi hiển thị form tìm kiếm nâng cao desktop
    function toggleAdvancedSearch() {
      const advancedForm = document.getElementById("advancedSearchForm");
      if (advancedForm) {
        if (advancedForm.style.display === "none") {
          advancedForm.style.display = "block";
        } else {
          advancedForm.style.display = "none";
        }
      }
    }

    // Hàm chuyển đổi hiển thị form tìm kiếm nâng cao mobile
    function toggleMobileSearch() {
      const mobileFilterContainer = document.getElementById(
        "search-filter-container-mobile"
      );
      if (mobileFilterContainer) {
        if (
          mobileFilterContainer.style.display === "none" ||
          !mobileFilterContainer.style.display
        ) {
          mobileFilterContainer.style.display = "block";
        } else {
          mobileFilterContainer.style.display = "none";
        }
      }
    }

    // Thiết lập giá trị khoảng giá cho form desktop
    function setPrice(min, max) {
      document.getElementById("minPrice").value = min;
      document.getElementById("maxPrice").value = max === 0 ? "" : max;
    }

    // Thiết lập giá trị khoảng giá cho form mobile
    function setPriceMobile(min, max) {
      document.getElementById("minPriceMobile").value = min;
      document.getElementById("maxPriceMobile").value = max === 0 ? "" : max;
    }

    // Đặt lại bộ lọc tìm kiếm desktop
    function resetFilters() {
      document.getElementById("categoryFilter").value = "Chọn phân loại";
      document.getElementById("minPrice").value = "";
      document.getElementById("maxPrice").value = "";
      document.getElementById("searchInput").value = "";
    }

    // Đặt lại bộ lọc tìm kiếm mobile
    function resetMobileFilters() {
      document.getElementById("categoryFilter-mobile").value = "";
      document.getElementById("minPriceMobile").value = "";
      document.getElementById("maxPriceMobile").value = "";
      document.querySelector('#searchFormMobile input[type="search"]').value = "";
    }

    // Xử lý tìm kiếm từ form desktop
    function performSearch() {
      const searchInput = document.getElementById("searchInput").value;
      const category = document.getElementById("categoryFilter").value;
      const minPrice = document.getElementById("minPrice").value;
      const maxPrice = document.getElementById("maxPrice").value;

      // Tạo URL tìm kiếm
      redirectToSearchPage(searchInput, category, minPrice, maxPrice);
    }

    // Xử lý tìm kiếm từ form mobile
    function performSearchMobile() {
      const searchInput = document.querySelector(
        '#searchFormMobile input[type="search"]'
      ).value;
      const category = document.getElementById("categoryFilter-mobile").value;
      const minPrice = document.getElementById("minPriceMobile").value;
      const maxPrice = document.getElementById("maxPriceMobile").value;

      // Tạo URL tìm kiếm
      redirectToSearchPage(searchInput, category, minPrice, maxPrice);
    }

    // Chuyển hướng đến trang kết quả tìm kiếm
    function redirectToSearchPage(search, category, minPrice, maxPrice) {
      let url = "./pages/search-result.php?q=" + encodeURIComponent(search);

      if (
        category &&
        category !== "Chọn phân loại" &&
        category !== "Tất cả phân loại"
      ) {
        url += "&category=" + encodeURIComponent(category);
      }

      if (minPrice) {
        url += "&minPrice=" + encodeURIComponent(minPrice);
      }

      if (maxPrice) {
        url += "&maxPrice=" + encodeURIComponent(maxPrice);
      }

      window.location.href = url;
    }

    // Tải kết quả tìm kiếm
    function loadSearchResults() {
      // Phân tích URL để lấy tham số tìm kiếm
      const urlParams = new URLSearchParams(window.location.search);
      const search = urlParams.get("q") || "";
      const category = urlParams.get("category") || "";
      const minPrice = urlParams.get("minPrice") || "";
      const maxPrice = urlParams.get("maxPrice") || "";

      // Hiển thị thông tin tìm kiếm
      displaySearchParams(search, category, minPrice, maxPrice);

      // Gọi API để lấy kết quả
      fetchSearchResults(search, category, minPrice, maxPrice);
    }

    // Hiển thị tham số tìm kiếm
    function displaySearchParams(search, category, minPrice, maxPrice) {
      const searchParamsContainer = document.getElementById("searchParams");
      if (!searchParamsContainer) return;

      const searchParamsList = searchParamsContainer.querySelector(
        ".search-params-list"
      );
      if (!searchParamsList) return;

      // Tiêu đề kết quả tìm kiếm
      const title = searchParamsContainer.querySelector("h3");
      if (title) {
        title.textContent = "Kết quả tìm kiếm cho:";
      }

      // Xóa các tham số cũ
      searchParamsList.innerHTML = "";

      // Thêm tham số tìm kiếm vào danh sách
      if (search) {
        const searchParam = document.createElement("li");
        searchParam.innerHTML = `<span class="param-label">Từ khóa:</span> ${search}`;
        searchParamsList.appendChild(searchParam);
      }

      if (
        category &&
        category !== "Chọn phân loại" &&
        category !== "Tất cả phân loại"
      ) {
        const categoryParam = document.createElement("li");
        categoryParam.innerHTML = `<span class="param-label">Phân loại:</span> ${category}`;
        searchParamsList.appendChild(categoryParam);
      }

      if (minPrice || maxPrice) {
        const priceParam = document.createElement("li");
        let priceText = '<span class="param-label">Giá:</span> ';

        if (minPrice && maxPrice) {
          priceText += `${formatCurrency(minPrice)} - ${formatCurrency(maxPrice)}`;
        } else if (minPrice) {
          priceText += `Từ ${formatCurrency(minPrice)}`;
        } else if (maxPrice) {
          priceText += `Đến ${formatCurrency(maxPrice)}`;
        }

        priceParam.innerHTML = priceText;
        searchParamsList.appendChild(priceParam);
      }

      // Luôn hiển thị container, thêm thông báo mặc định nếu không có tham số
      if (searchParamsList.children.length === 0) {
        const defaultParam = document.createElement("li");
        defaultParam.innerHTML = `<span class="param-label">Tất cả sản phẩm</span>`;
        searchParamsList.appendChild(defaultParam);
      }

      // Luôn hiển thị container
      searchParamsContainer.style.display = "block";
    }

    // Định dạng giá tiền
    function formatCurrency(amount) {
      return new Intl.NumberFormat("vi-VN", {
        style: "currency",
        currency: "VND",
      }).format(amount);
    }

    // Gọi API để lấy kết quả tìm kiếm
    function fetchSearchResults(search, category, minPrice, maxPrice) {
      const searchResultsContainer = document.getElementById("searchResults");
      if (!searchResultsContainer) return;

      // Hiển thị spinner loading
      searchResultsContainer.innerHTML = `
    <div class="loading-spinner">
      <i class="fas fa-spinner fa-spin"></i> Đang tải...
    </div>
  `;

      // Tạo URL gọi API
      let apiUrl = "../php-api/search.php?q=" + encodeURIComponent(search);

      if (
        category &&
        category !== "Chọn phân loại" &&
        category !== "Tất cả phân loại"
      ) {
        apiUrl += "&category=" + encodeURIComponent(category);
      }

      if (minPrice) {
        apiUrl += "&minPrice=" + encodeURIComponent(minPrice);
      }

      if (maxPrice) {
        apiUrl += "&maxPrice=" + encodeURIComponent(maxPrice);
      }

      // Gọi API
      fetch(apiUrl)
        .then((response) => {
          if (!response.ok) {
            throw new Error("Network response was not ok");
          }
          return response.json();
        })
        .then((data) => {
          // Xử lý dữ liệu và hiển thị kết quả
          if (data.message) {
            // Hiển thị thông báo nếu không có kết quả
            displayNoResults(searchResultsContainer, data.message);
            return;
          }

          // Hiển thị kết quả sản phẩm
          displayProducts(data, searchResultsContainer);
        })
        .catch((error) => {
          // Hiển thị lỗi nếu có
          displayError(searchResultsContainer, error.message);
        });
    }

    // Hiển thị lỗi
    function displayError(container, errorMessage) {
      container.innerHTML = `
    <div class="error-message">
      <i class="fas fa-exclamation-circle"></i>
      <p>Có lỗi xảy ra khi tải kết quả tìm kiếm: ${errorMessage}</p>
    </div>
  `;
    }

    // Hiển thị khi không có kết quả
    function displayNoResults(container, message) {
      container.innerHTML = `
    <div class="no-results-message" style="position: static !important;">
      <i class="fas fa-search"></i>
      <p>${message || "Không tìm thấy sản phẩm nào phù hợp với tìm kiếm của bạn"
        }</p>
      <div class="suggestions">
        <p>Gợi ý:</p>
        <ul>
          <li>Kiểm tra lỗi chính tả của từ khóa tìm kiếm</li>
          <li>Thử sử dụng từ khóa khác</li>
          <li>Thử tìm kiếm với ít bộ lọc hơn</li>
        </ul>
      </div>
    </div>
  `;
    }

    // Hiển thị danh sách sản phẩm
    function displayProducts(products, container) {
      // Nếu không có sản phẩm
      if (!products || products.length === 0) {
        displayNoResults(container);
        return;
      }

      // Lấy trang hiện tại từ URL
      const urlParams = new URLSearchParams(window.location.search);
      const currentPage = parseInt(urlParams.get("page")) || 1;

      // Số sản phẩm mỗi trang (đã sửa thành 8)
      const itemsPerPage = 8;

      // Tính chỉ số bắt đầu và kết thúc cho trang hiện tại
      const startIndex = (currentPage - 1) * itemsPerPage;
      const endIndex = Math.min(startIndex + itemsPerPage, products.length);

      // Lấy các sản phẩm cho trang hiện tại
      const currentPageProducts = products.slice(startIndex, endIndex);

      // Khởi tạo cấu trúc HTML cho danh sách sản phẩm
      let productsHTML = `
    <div class="search-results-count">Tìm thấy ${products.length} sản phẩm</div>
    <div class="products-grid">
  `;

      // Thêm mỗi sản phẩm vào grid
      currentPageProducts.forEach((product) => {
        productsHTML += `
      <div class="product-card">
        <div class="product-image">
          <img src="..${product.ImageURL}" alt="${product.ProductName}">
        </div>
        <div class="product-info">
          <h3 class="product-name">${product.ProductName}</h3>
          <div class="product-price">${formatCurrency(product.Price)}</div>
          <a href="user-sanpham.php?id=${product.ProductID
          }" class="btn-view-product">Xem chi tiết</a>
        </div>
      </div>
    `;
      });

      productsHTML += `</div>`;

      // Đặt HTML vào container
      container.innerHTML = productsHTML;

      // Hiển thị phân trang
      setupPagination(products.length);
    }

    // Thiết lập phân trang
    function setupPagination(totalItems) {
      const paginationContainer = document.getElementById("pagination");
      if (!paginationContainer) return;

      // Số sản phẩm mỗi trang (đã sửa thành 8)
      const itemsPerPage = 8;

      // Chỉ hiển thị phân trang nếu có nhiều hơn số sản phẩm trên một trang
      if (totalItems <= itemsPerPage) {
        paginationContainer.style.display = "none";
        return;
      }

      // Lấy trang hiện tại từ URL
      const urlParams = new URLSearchParams(window.location.search);
      const currentPage = parseInt(urlParams.get("page")) || 1;

      // Tính tổng số trang (8 sản phẩm mỗi trang)
      const totalPages = Math.ceil(totalItems / itemsPerPage);

      // Tạo HTML phân trang
      let paginationHTML = "";

      // Nút Previous
      paginationHTML += `
    <a href="#" class="btn btn-secondary pagination-item ${currentPage === 1 ? "disabled" : ""
        }" 
       onclick="${currentPage > 1
          ? "changePage(" + (currentPage - 1) + ")"
          : "return false"
        }" 
       aria-label="Previous page">
      <i class="fas fa-chevron-left"></i>
    </a>
  `;

      // Số trang
      const startPage = Math.max(1, currentPage - 2);
      const endPage = Math.min(totalPages, currentPage + 2);

      // Hiển thị trang đầu tiên nếu cần
      if (startPage > 1) {
        paginationHTML += `
      <a href="#" class="btn btn-secondary pagination-item" onclick="changePage(1)">1</a>
    `;

        if (startPage > 2) {
          paginationHTML += `<span class="btn btn-secondary pagination-ellipsis">...</span>`;
        }
      }

      // Các số trang
      for (let i = startPage; i <= endPage; i++) {
        const btnClass = i === currentPage ? "btn-success" : "btn-secondary";
        paginationHTML += `
    <a href="#" class="btn pagination-item ${btnClass}" onclick="changePage(${i})">${i}</a>
  `;
      }


      // Hiển thị trang cuối cùng nếu cần
      if (endPage < totalPages) {
        if (endPage < totalPages - 1) {
          paginationHTML += `<span class="btn btn-secondary pagination-ellipsis">...</span>`;
        }

        paginationHTML += `
      <a href="#" class=" btn btn-secondary pagination-item" onclick="changePage(${totalPages})">${totalPages}</a>
    `;
      }

      // Nút Next
      paginationHTML += `
    <a href="#" class="btn btn-secondary pagination-item ${currentPage === totalPages ? "disabled" : ""
        }" 
       onclick="${currentPage < totalPages
          ? "changePage(" + (currentPage + 1) + ")"
          : "return false"
        }" 
       aria-label="Next page">
      <i class="fas fa-chevron-right"></i>
    </a>
  `;

      // Đặt HTML phân trang
      paginationContainer.innerHTML = paginationHTML;
      paginationContainer.style.display = "flex";
    }

    // Thay đổi trang
    function changePage(page) {
      const urlParams = new URLSearchParams(window.location.search);
      urlParams.set("page", page);

      // Cập nhật URL với trang mới
      window.location.search = urlParams.toString();
      return false; // Ngăn chặn hành vi mặc định của liên kết
    }

    // Lọc và sắp xếp sản phẩm (có thể thêm chức năng này sau)
    function sortProducts(sortBy) {
      const urlParams = new URLSearchParams(window.location.search);
      urlParams.set("sort", sortBy);
      urlParams.delete("page"); // Quay về trang 1 khi thay đổi sắp xếp

      // Cập nhật URL với sắp xếp mới
      window.location.search = urlParams.toString();
    }
  </script>
</body>

</html>