<!DOCTYPE html>
<?php
require_once('../src/php/token.php');
require_once('../src/php/check_token_v1.php');
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  require_once('../src/php/connect.php');

  // Lấy dữ liệu từ form
  $fullname = $_POST['fullname'];
  $username = $_POST['username'];
  $email = $_POST['email'];
  $phone = $_POST['phone'];
  $province = $_POST['province'];
  $district = $_POST['district'];
  $ward = $_POST['wards'];
  $address = $_POST['address'];
  $password = $_POST['password'];
  $confirmPassword = $_POST['confirm-password'];

  // Kiểm tra trùng username
  $checkUsername = $conn->prepare("SELECT UserName FROM users WHERE UserName = ?");
  $checkUsername->bind_param("s", $username);
  $checkUsername->execute();
  $checkUsername->store_result();
  if ($checkUsername->num_rows > 0) {
    $errors['username'] = "Tên đăng nhập đã tồn tại!";
  }
  $checkUsername->close();

  // Kiểm tra trùng email
  $checkEmail = $conn->prepare("SELECT Email FROM users WHERE Email = ?");
  $checkEmail->bind_param("s", $email);
  $checkEmail->execute();
  $checkEmail->store_result();
  if ($checkEmail->num_rows > 0) {
    $errors['email'] = "Email đã tồn tại!";
  }
  $checkEmail->close();

  // Các kiểm tra đầu vào
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = "Email không hợp lệ!";
  }
  if (!preg_match("/^[a-z0-9_-]{3,16}$/", $username)) {
    $errors['username'] = "Tên đăng nhập không hợp lệ!";
  }
  if (!preg_match("/^[0-9]{10,11}$/", $phone)) {
    $errors['phone'] = "Số điện thoại không hợp lệ!";
  }
  if ($password !== $confirmPassword) {
    $errors['confirm-password'] = "Mật khẩu xác nhận không khớp!";
  }
  if (!preg_match("/(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*[!@#$%^&*()]).{8,}/", $password)) {
    $errors['password'] = "Mật khẩu phải có ít nhất 8 ký tự, gồm chữ hoa, chữ thường, số và ký tự đặc biệt.";
  }
  if (!preg_match('/^([\p{L}]+(?:\s[\p{L}]+){0,79})$/u', $fullname)) {
    $errors['fullname'] = "Họ tên không hợp lệ! Chỉ được chứa chữ cái và tối đa 80 từ.";
  }


  // Nếu không có lỗi thì thêm vào CSDL
  if (empty($errors)) {
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (FullName, UserName, Email, Phone, Province, District, Ward, Address, PasswordHash) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssss", $fullname, $username, $email, $phone, $province, $district, $ward, $address, $passwordHash);
    $stmt->execute();
    $stmt->close();
    $conn->close();

    // Chuyển trang sau khi đăng ký thành công
    header("Location: user-login.php");
    exit;
  }
  $conn->close();
}
// Kết nối để load danh sách tỉnh/thành (đặt ở cuối để luôn chạy được cả GET)
$conn = new mysqli("localhost", "root", "", "c01db");
if ($conn->connect_error) {
  die("Kết nối thất bại: " . $conn->connect_error);
}


$provinceQuery = "SELECT province_id, name FROM province ORDER BY name ASC";
$provinceResult = $conn->query($provinceQuery);

?>

<html>

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- CSS  -->
  <link rel="stylesheet" href="../assets/libs/bootstrap-5.3.3-dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="../src/css/user-register.css">
  <link rel="stylesheet" href="../assets/icon/fontawesome-free-6.7.2-web/css/all.min.css">
  <link rel="stylesheet" href="../src/css/search-styles.css">
  <link rel="stylesheet" href="../src/css/searchAdvanceMobile.css">
  <link rel="stylesheet" href="../src/css/footer.css">
  <!-- JS  -->
  <script src="../src/js/search-common.js"></script>
  <script src="../assets/libs/bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
  <script></script>
  <!-- <script src="../src/js/main.js"></script> -->
  <script src="../src/js/onOffSeacrhAdvance.js"></script>
  <script src="../src/js/search-index.js"></script>
  <title>Đăng ký</title>
  <style>
    /* hiện lỗi */
    .error-message {
      color: red;
      font-size: 12px;
      margin-top: 5px;
    }

    .container1 {
      display: flex;
      justify-content: space-between;
      align-items: center;
      width: 100%;
      max-width: 900px;
      /* Điều chỉnh kích thước theo nhu cầu */
      margin: auto;
      margin-top: 35px;
    }

    .form-card {
      flex: 1;
      width: 50%;
      box-sizing: border-box;
    }

    @media (max-width: 768px) {
      .container {
        flex-direction: column;
      }

      .form-card {
        width: 100%;
      }
    }

    .form-group label {
      font-weight: bold;
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
              <form id="searchForm" class="search-form" method="get">
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
              <a href="user-register.php"><img src="../assets/images/cart.svg" alt="cart" /></a>
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
  <div class="section">
    <div class="img-21">
      <img src="../assets/images/CAY21.jpg" alt="CAY21">
    </div>
  </div>

  <!-- MAIN -->
  <div class="container1">
    <div class="form-card">
      <div class="form-image">
        <h2>Tham gia ngay với chúng tôi</h2>
        <p>Tạo tài khoản để trải nghiệm các tính năng tuyệt vời:</p>
        <ul class="feature-list">
          <li>Giao diện trực quan, dễ sử dụng</li>
          <li>Bảo mật thông tin tuyệt đối</li>
          <li>Hỗ trợ khách hàng 24/7</li>
        </ul>
      </div>

      <div class="form-card">
        <div class="form-content">
          <div class="form-header">
            <h1>Tạo tài khoản mới</h1>
            <p style="font-weight: bold">Điền thông tin dưới đây để bắt đầu</p>
          </div>
          <form method="POST" action="">
            <div class="form-row">
              <div class="form-group">
                <label for="fullname">Họ và tên</label>
                <input type="text" id="fullname" name="fullname" class="form-control" required>
              </div>
              <div class="form-group">
                <label for="username">Tên đăng nhập</label>
                <input type="text" id="username" name="username" class="form-control" required>
                <p class="error-message"><?php echo $errors['username'] ?? ''; ?></p>
              </div>
            </div>

            <div class="form-group">
              <label for="email">Địa chỉ email</label>
              <input type="email" id="email" name="email" class="form-control" required>
              <p class="error-message"><?php echo $errors['email'] ?? ''; ?></p>
            </div>

            <div class="form-group">
              <label for="phone">Số điện thoại</label>
              <input type="text" id="phone" name="phone" class="form-control" required>
              <p class="error-message"><?php echo $errors['phone'] ?? ''; ?></p>
            </div>

            <div class="form-row">
              <div class="form-group">
                <label for="province">Tỉnh/Thành phố</label>
                <select id="province" name="province" class="form-control">
                  <option value="">Chọn một tỉnh</option>
                  <?php
                  if ($provinceResult->num_rows > 0) {
                    while ($row = $provinceResult->fetch_assoc()) {
                      echo '<option value="' . $row['province_id'] . '">' . $row['name'] . '</option>';
                    }
                  }
                  ?>
                </select>

              </div>
              <div class="form-group">
                <label for="district">Quận/Huyện</label>
                <select id="district" name="district" class="form-control">
                  <option value="">Chọn một quận/huyện</option>
                </select>
              </div>
            </div>

            <div class="form-group">
              <label for="wards">Phường/Xã</label>
              <select id="wards" name="wards" class="form-control">
                <option value="">Chọn một xã</option>
              </select>
            </div>

            <div class="form-group">
              <label for="address">Địa chỉ</label>
              <input type="text" id="address" name="address" class="form-control" required>
            </div>

            <div class="checkbox-container">
              <input type="checkbox" id="terms">
              <label for="terms">Tôi đã đọc và đồng ý với <a href="#">Điều khoản dịch vụ</a> và <a href="#">Chính sách
                  bảo
                  mật</a></label>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label for="password">Mật khẩu</label>
                <input type="password" id="password" name="password" class="form-control" required>
                <p class="error-message"><?php echo $errors['password'] ?? ''; ?></p>
              </div>
              <div class="form-group">
                <label for="confirm-password">Xác nhận mật khẩu</label>
                <input type="password" id="confirm-password" name="confirm-password" class="form-control" required>
                <p class="error-message"><?php echo $errors['confirm-password'] ?? ''; ?></p>
              </div>
            </div>

            <button type="submit" class="btn">Đăng ký ngay</button>
            <!-- <button type="reset" class="btn btn-reset" onclick="resetForm()">Làm mới</button> -->
            <div class="checkbox-container">
              <p for>Bạn đã có tài khoản? <a href="user-login.php">Đăng nhập</a> </p>
            </div>
          </form>
        </div>
      </div>
      <script>
        function validateForm() {
          var password = document.getElementById("password").value;
          var confirmPassword = document.getElementById("confirm-password").value;
          if (password !== confirmPassword) {
            document.getElementById("confirm-password-error").innerText = "Mật khẩu xác nhận không khớp!";
            return false;
          }
          return true;
        }

        function resetForm() {
          document.querySelector("form").reset(); // Reset các ô nhập liệu

          // Xóa luôn các thông báo lỗi
          let errorMessages = document.querySelectorAll(".error-message");
          errorMessages.forEach(msg => {
            msg.innerText = ""; // Xóa nội dung lỗi
            msg.style.display = "none"; // Ẩn lỗi luôn
          });
        }
      </script>
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
  <!-- </div> -->

  <script src="../src/js/user-register.js"></script>
  <script src="../src/js/Trang_chu.js"></script>

  <script>
    $(document).ready(function() {
      // Listen for changes in the "province" select box
      $('#province').on('change', function() {
        var province_id = $(this).val();
        // console.log(province_id);
        if (province_id) {
          // If a province is selected, fetch the districts for that province using AJAX
          $.ajax({
            url: 'ajax_get_district.php',
            method: 'GET',
            dataType: "json",
            data: {
              province_id: province_id
            },
            success: function(data) {
              // Clear the current options in the "district" select box
              $('#district').empty();

              // Add the new options for the districts for the selected province
              $.each(data, function(i, district) {
                // console.log(district);
                $('#district').append($('<option>', {
                  value: district.id,
                  text: district.name
                }));
              });
              // Clear the options in the "wards" select box
              $('#wards').empty();
            },
            error: function(xhr, textStatus, errorThrown) {
              console.log('Error: ' + errorThrown);
            }
          });
          $('#wards').empty();
        } else {
          // If no province is selected, clear the options in the "district" and "wards" select boxes
          $('#district').empty();
        }
      });

      // Listen for changes in the "district" select box
      $('#district').on('change', function() {
        var district_id = $(this).val();
        // console.log(district_id);
        if (district_id) {
          // If a district is selected, fetch the awards for that district using AJAX
          $.ajax({
            url: 'ajax_get_wards.php',
            method: 'GET',
            dataType: "json",
            data: {
              district_id: district_id
            },
            success: function(data) {
              // console.log(data);
              // Clear the current options in the "wards" select box
              $('#wards').empty();
              // Add the new options for the awards for the selected district
              $.each(data, function(i, wards) {
                $('#wards').append($('<option>', {
                  value: wards.id,
                  text: wards.name
                }));
              });
            },
            error: function(xhr, textStatus, errorThrown) {
              console.log('Error: ' + errorThrown);
            }
          });
        } else {
          // If no district is selected, clear the options in the "award" select box
          $('#wards').empty();
        }
      });
    });
  </script>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
    $(document).ready(function() {
      $('#province').on('change', function() {
        var province_id = $(this).val();
        if (province_id) {
          $.ajax({
            url: 'ajax_get_district.php',
            method: 'GET',
            dataType: "json",
            data: {
              province_id: province_id
            },
            success: function(data) {
              $('#district').empty().append('<option value="">Chọn quận/huyện</option>');
              $.each(data, function(i, district) {
                $('#district').append($('<option>', {
                  value: district.id,
                  text: district.name
                }));
              });
              $('#wards').empty().append('<option value="">Chọn phường/xã</option>');
            }
          });
        } else {
          $('#district').empty().append('<option value="">Chọn quận/huyện</option>');
          $('#wards').empty().append('<option value="">Chọn phường/xã</option>');
        }
      });

      $('#district').on('change', function() {
        var district_id = $(this).val();
        if (district_id) {
          $.ajax({
            url: 'ajax_get_wards.php',
            method: 'GET',
            dataType: "json",
            data: {
              district_id: district_id
            },
            success: function(data) {
              $('#wards').empty().append('<option value="">Chọn phường/xã</option>');
              $.each(data, function(i, wards) {
                $('#wards').append($('<option>', {
                  value: wards.id,
                  text: wards.name
                }));
              });
            }
          });
        } else {
          $('#wards').empty().append('<option value="">Chọn phường/xã</option>');
        }
      });
    });
  </script>

</body>

</html>