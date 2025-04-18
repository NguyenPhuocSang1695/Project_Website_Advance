<?php
require_once('../src/php/token.php');
require_once('../src/php/check_token_v2.php');
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
  <!-- JS  -->
  <script src="../assets/libs/bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
  <!-- <script src="../src/js/main.js"></script> -->
  <script src="../src/js/search-common.js"></script>
  <script src="../src/js/onOffSeacrhAdvance.js"></script>
  <script src="../src/js/thanh-toan.js"></script>
  <script src="../src/js/search-index.js"></script>
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
            <input type="text" name="name" id="name" placeholder="Nguyễn Phước Sang" disabled>
            <label for=""><strong>Email</strong></label>
            <input type="email" name="email" id="email" placeholder="sangnguyen20050916@gmail.com" disabled>
            <label for=""><strong>Số điện thoại</strong></label>
            <input type="text" name="sdt" id="sdt" placeholder="0366679203" disabled>
            <label for=""><strong>Địa chỉ</strong></label>
            <input type="text" name="diachi" id="diachi" placeholder="103a Phan Huy Ích, Phường 11, Quận 12, TP.HCM"
              disabled>
          </form>

          <form action="" id="new-information-form">
            <label for=""><strong>Họ và tên</strong></label>
            <input type="text" name="name" id="new-name" placeholder="Họ và tên">
            <label for=""><strong>Email</strong></label>
            <input type="email" name="email" id="new-email" placeholder="Email">
            <label for=""><strong>Số điện thoại</strong></label>
            <input type="text" name="sdt" id="new-sdt" placeholder="Số điện thoại">
            <label for=""><strong>Địa chỉ</strong></label>
            <input type="text" name="diachi" id="new-diachi" placeholder="Địa chỉ">
          </form>

          <div class="infor-goods">
            <hr style="border: 3px dashed green; width: 100%" />
            <div class="order">
              <div class="order-img">
                <img src="../assets/images/CAY5.jpg" alt="Phat tai" />
              </div>
              <div class="frame">
                <div class="name-price">
                  <p><strong>Cây phát tài</strong></p>
                  <p><strong>750.000đ</strong></p>
                </div>

                <div class="function">
                  <!-- Button trigger modal -->
                  <button type="button" class="btn" data-bs-toggle="modal" data-bs-target="#exampleModal"
                    style="width: 53px">
                    <i class="fa-solid fa-trash" style="font-size: 25px;"></i>
                  </button>

                  <!-- Modal -->
                  <div class="modal fade w-100" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h1 class="modal-title fs-5" id="exampleModalLabel">
                            Thông báo
                          </h1>
                          <button type="button" class="btn-close" style="width: 10%" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                        </div>
                        <div class="modal-body d-flex justify-content-center align-items-center">
                          Bạn có chắc muốn xóa sản phẩm chứ!
                        </div>
                        <div class="modal-footer d-flex flex-row">
                          <button type="button" class="btn btn-secondary" style="width: 20%" data-bs-dismiss="modal">
                            Đóng
                          </button>
                          <button type="button" class="btn btn-primary" style="width: 45%">
                            Xóa
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Nút xóa và thêm số lượng sản phẩm  -->
                  <div class="add-del">
                    <div class="oder">
                      <div class="wrapper">
                        <span class="minus">-</span>
                        <span class="num">01</span>
                        <span class="plus">+</span>
                      </div>
                      <script src="../src/js/san-pham.js"></script>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <hr style="border: 3px dashed green; width: 100%" />
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
            <a href="../pages/hoan-tat.php"><button type="button" class="btn btn-success" id="payment-button" style="width: 185px;
    height: 50px;">THANH TOÁN</button></a>
          </div>
          <a href="../index.php" style="text-decoration: none;
        margin-bottom: 10px;">Tiếp tục mua hàng</a>
        </div>
      </div>

  </div>
  </main>

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
  <script src="../src/js/thanh-toan.js"></script>
</body>

</html>