// search.js - Xử lý tìm kiếm cho website bán cây

document.addEventListener("DOMContentLoaded", function () {
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
    desktopForm.addEventListener("submit", function (e) {
      e.preventDefault();
      performSearch();
    });
  }

  // Xử lý form tìm kiếm mobile
  const mobileForm = document.getElementById("searchFormMobile");
  if (mobileForm) {
    mobileForm.addEventListener("submit", function (e) {
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
  let url = "./search-result.php?q=" + encodeURIComponent(search);

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
      <p>${
        message || "Không tìm thấy sản phẩm nào phù hợp với tìm kiếm của bạn"
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
          <a href="user-sanpham.php?id=${
            product.ProductID
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
    <a href="#" class="btn btn-secondary pagination-item ${
      currentPage === 1 ? "disabled" : ""
    }" 
       onclick="${
         currentPage > 1
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
    paginationHTML += `
      <a href="#" class="btn btn-secondary pagination-item ${
        i === currentPage ? "active" : ""
      }" 
         onclick="changePage(${i})">${i}</a>
    `;
  }

  // Hiển thị trang cuối cùng nếu cần
  if (endPage < totalPages) {
    if (endPage < totalPages - 1) {
      paginationHTML += `<span class="btn btn-secondary pagination-ellipsis">...</span>`;
    }

    paginationHTML += `
      <a href="#" class=" btn btn-success pagination-item" onclick="changePage(${totalPages})">${totalPages}</a>
    `;
  }

  // Nút Next
  paginationHTML += `
    <a href="#" class="btn btn-secondary pagination-item ${
      currentPage === totalPages ? "disabled" : ""
    }" 
       onclick="${
         currentPage < totalPages
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
