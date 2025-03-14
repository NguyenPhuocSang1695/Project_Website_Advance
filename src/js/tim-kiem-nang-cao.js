// Hiển thị/ẩn form tìm kiếm nâng cao
function toggleAdvancedSearch() {
  const searchForm = document.getElementById("advancedSearchForm");

  if (searchForm.style.display === "none") {
    searchForm.style.display = "block";
    setTimeout(() => {
      searchForm.classList.add("show");
    }, 10);
  } else {
    searchForm.classList.remove("show");
    setTimeout(() => {
      searchForm.style.display = "none";
    }, 300);
  }
}

// Xóa nội dung ô tìm kiếm
document.addEventListener("DOMContentLoaded", function () {
  const clearButton = document.getElementById("clearSearch");
  clearButton.addEventListener("click", function () {
    document.getElementById("searchInput").value = "";
    document.getElementById("searchInput").focus();
    performSearch();
  });
});

// Thiết lập giá trị cho khoảng giá với các mức cài sẵn
function setPrice(min, max) {
  document.getElementById("minPrice").value = min;
  document.getElementById("maxPrice").value = max || "";
  // Thực hiện tìm kiếm ngay khi người dùng chọn khoảng giá
  performSearch();
}

// Chuyển đổi giá tiền từ chuỗi "xxx.xxx vnđ" sang số
function extractPrice(priceString) {
  // Loại bỏ tất cả các ký tự không phải số
  return parseInt(priceString.replace(/\D/g, ""));
}

// Thực hiện tìm kiếm với các bộ lọc
function performSearch() {
  // Lấy giá trị từ các trường tìm kiếm
  const searchTerm = document
    .getElementById("searchInput")
    .value.toLowerCase()
    .trim();
  const categoryFilter = document.getElementById("categoryFilter").value;
  const minPrice = document.getElementById("minPrice").value
    ? parseInt(document.getElementById("minPrice").value)
    : 0;
  const maxPrice = document.getElementById("maxPrice").value
    ? parseInt(document.getElementById("maxPrice").value)
    : Infinity;

  // Lấy tất cả sản phẩm
  const products = document.querySelectorAll("#productList .product");
  let foundMatch = false;

  // Duyệt qua từng sản phẩm để kiểm tra điều kiện
  products.forEach((product) => {
    // Lấy thông tin sản phẩm
    const productName = product.querySelector("h2").textContent.toLowerCase();
    const productPrice = extractPrice(product.querySelector("h3").textContent);
    const productCategory = product.getAttribute("data-category") || "";

    // Áp dụng các điều kiện tìm kiếm
    let matchName = true;
    let matchCategory = true;
    let matchPrice = true;

    // Kiểm tra tên sản phẩm
    if (searchTerm && !productName.includes(searchTerm)) {
      matchName = false;
    }

    // Kiểm tra phân loại
    if (categoryFilter && productCategory !== categoryFilter) {
      matchCategory = false;
    }

    // Kiểm tra khoảng giá
    if (productPrice < minPrice || productPrice > maxPrice) {
      matchPrice = false;
    }

    // Hiển thị hoặc ẩn sản phẩm dựa trên kết quả kiểm tra
    if (matchName && matchCategory && matchPrice) {
      product.style.display = "";
      foundMatch = true;
    } else {
      product.style.display = "none";
    }
  });

  // Hiển thị thông báo nếu không tìm thấy sản phẩm
  const noResultsMsg = document.getElementById("noResultsMessage");

  if (!foundMatch) {
    noResultsMsg.style.display = "flex";
  } else {
    noResultsMsg.style.display = "none";
  }
}

// Đặt lại tất cả các bộ lọc
function resetFilters() {
  // Đặt lại các trường tìm kiếm
  document.getElementById("searchInput").value = "";
  document.getElementById("categoryFilter").value = "";
  document.getElementById("minPrice").value = "";
  document.getElementById("maxPrice").value = "";

  // Hiển thị lại tất cả sản phẩm
  const products = document.querySelectorAll("#productList .product");
  products.forEach((product) => {
    product.style.display = "";
  });

  // Ẩn thông báo không tìm thấy sản phẩm
  document.getElementById("noResultsMessage").style.display = "none";
}

// Khởi tạo sự kiện khi trang đã tải xong
document.addEventListener("DOMContentLoaded", function () {
  // Lắng nghe sự kiện Enter trên thanh tìm kiếm
  document
    .getElementById("searchInput")
    .addEventListener("keypress", function (e) {
      if (e.key === "Enter") {
        performSearch();
      }
    });

  // Thêm sự kiện tìm kiếm tự động cho các trường lọc
  document
    .getElementById("categoryFilter")
    .addEventListener("change", performSearch);
  document.getElementById("minPrice").addEventListener("input", performSearch);
  document.getElementById("maxPrice").addEventListener("input", performSearch);

  // Nút tìm kiếm trong thanh tìm kiếm chính
  const searchButton = document.querySelector(".search-button");
  if (searchButton) {
    searchButton.addEventListener("click", performSearch);
  }
});

// Kiểm tra nếu sản phẩm có dữ liệu phân loại, nếu không thì thêm vào
function ensureProductCategories() {
  const products = document.querySelectorAll(
    "#productList .product:not([data-category])"
  );
  const categories = [
    "cay-de-cham",
    "cay-van-phong",
    "cay-de-ban",
    "cay-duoi-nuoc",
  ];

  products.forEach((product, index) => {
    // Gán phân loại mặc định nếu chưa có
    const categoryIndex = index % categories.length;
    product.setAttribute("data-category", categories[categoryIndex]);
  });
}

// Gọi hàm đảm bảo dữ liệu phân loại
document.addEventListener("DOMContentLoaded", ensureProductCategories);
