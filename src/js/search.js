// Biến toàn cục để quản lý độ trễ khi gõ
let searchTimeout;
const MIN_CHARS_FOR_SUGGESTIONS = 2;

// Khởi tạo tính năng tìm kiếm khi trang đã tải xong
document.addEventListener("DOMContentLoaded", function () {
  // Khởi tạo cho tìm kiếm desktop
  initSearch("searchInput", "productList");

  // Khởi tạo cho tìm kiếm mobile
  initSearch("mobileSearchInput", "mobileProductList");

  // Thêm sự kiện cho nút tìm kiếm
  const searchButtons = document.querySelectorAll(".search-button");
  searchButtons.forEach((button) => {
    button.addEventListener("click", function () {
      // Xác định input tương ứng dựa trên vị trí của nút
      const isDesktop = !this.closest(".mobile-search-container");
      const inputId = isDesktop ? "searchInput" : "mobileSearchInput";
      performSearch(inputId);
    });
  });

  // Thêm sự kiện cho nút tìm kiếm nâng cao
  const advancedSearchBtn = document.querySelector(".btn-search");
  if (advancedSearchBtn) {
    advancedSearchBtn.addEventListener("click", function () {
      performAdvancedSearch();
    });
  }

  // Ẩn danh sách gợi ý khi click ra ngoài
  document.addEventListener("click", function (e) {
    if (
      !e.target.closest(".search-group") &&
      !e.target.closest(".mobile-search-container")
    ) {
      hideAllSuggestions();
    }
  });

  // Đồng bộ danh sách sản phẩm từ desktop sang mobile
  syncProductLists();
});

// Khởi tạo sự kiện tìm kiếm cho input và danh sách kết quả
function initSearch(inputId, listId) {
  const searchInput = document.getElementById(inputId);
  if (!searchInput) return;

  // Sự kiện khi gõ văn bản
  searchInput.addEventListener("input", function () {
    showSuggestions(inputId, listId);
  });

  // Sự kiện khi nhấn phím Enter
  searchInput.addEventListener("keypress", function (e) {
    if (e.key === "Enter") {
      performSearch(inputId);
    }
  });

  // Sự kiện khi focus vào input và đã có text
  searchInput.addEventListener("focus", function () {
    if (this.value.trim().length >= MIN_CHARS_FOR_SUGGESTIONS) {
      showSuggestions(inputId, listId);
    }
  });
}

// Hiển thị gợi ý khi gõ
function showSuggestions(inputId, listId) {
  clearTimeout(searchTimeout);

  searchTimeout = setTimeout(() => {
    const searchInput = document.getElementById(inputId);
    const productList = document.getElementById(listId);

    if (!searchInput || !productList) return;

    const searchTerm = searchInput.value.toLowerCase().trim();

    // Ẩn danh sách nếu chuỗi tìm kiếm quá ngắn
    if (searchTerm.length < MIN_CHARS_FOR_SUGGESTIONS) {
      productList.style.display = "none";
      return;
    }

    // Lấy tất cả sản phẩm trong list
    const products = productList.querySelectorAll(".product");
    let matchFound = false;

    products.forEach((product) => {
      const productName = product.querySelector("h2").textContent.toLowerCase();

      if (productName.includes(searchTerm)) {
        product.style.display = "";
        matchFound = true;
      } else {
        product.style.display = "none";
      }
    });

    // Hiển thị hoặc ẩn danh sách gợi ý
    if (matchFound) {
      productList.style.display = "block";
      productList.classList.add("showing-suggestions");
    } else {
      productList.style.display = "none";
    }
  }, 300);
}

// Ẩn tất cả danh sách gợi ý
function hideAllSuggestions() {
  const suggestions = document.querySelectorAll(".product-list");
  suggestions.forEach((list) => {
    list.style.display = "none";
    list.classList.remove("showing-suggestions");
  });
}

// Thực hiện tìm kiếm với input thông thường và chuyển trang
function performSearch(inputId) {
  const searchInput = document.getElementById(inputId);
  if (!searchInput) return;

  const searchTerm = searchInput.value.trim();
  if (!searchTerm) return; // Không tìm kiếm nếu không có từ khóa

  // Lưu từ khóa tìm kiếm vào localStorage
  const searchParams = {
    term: searchTerm,
    category: "",
    minPrice: "",
    maxPrice: "",
  };

  localStorage.setItem("lastSearchParams", JSON.stringify(searchParams));

  // Chuyển trang với tham số tìm kiếm
  window.location.href =
    "./pages/phan-loai1.html?search=" + encodeURIComponent(searchTerm);
}

// Thực hiện tìm kiếm nâng cao và chuyển trang
function performAdvancedSearch() {
  const searchTerm = document.getElementById("searchInput").value.trim();
  const category = document.getElementById("categoryFilter").value;
  const minPrice = document.getElementById("minPrice").value;
  const maxPrice = document.getElementById("maxPrice").value;

  // Tạo URL với tham số tìm kiếm
  let searchUrl = "./pages/phan-loai1.html?";
  const params = [];

  if (searchTerm) {
    params.push("search=" + encodeURIComponent(searchTerm));
  }

  if (category) {
    params.push("category=" + encodeURIComponent(category));
  }

  if (minPrice) {
    params.push("minPrice=" + encodeURIComponent(minPrice));
  }

  if (maxPrice) {
    params.push("maxPrice=" + encodeURIComponent(maxPrice));
  }

  // Nếu không có tham số nào, vẫn đến trang phân loại
  if (params.length === 0) {
    window.location.href = "./pages/phan-loai1.html";
    return;
  }

  // Lưu tham số tìm kiếm vào localStorage
  const searchParams = {
    term: searchTerm,
    category: category,
    minPrice: minPrice,
    maxPrice: maxPrice,
  };

  localStorage.setItem("lastSearchParams", JSON.stringify(searchParams));

  // Chuyển trang với tham số tìm kiếm
  window.location.href = searchUrl + params.join("&");
}

// Cập nhật giao diện khi chọn preset giá
function setPrice(min, max) {
  document.getElementById("minPrice").value = min;
  document.getElementById("maxPrice").value = max || "";
}

// Hàm đồng bộ danh sách sản phẩm
function syncProductLists() {
  const desktopList = document.getElementById("productList");
  const mobileList = document.getElementById("mobileProductList");

  if (desktopList && mobileList) {
    mobileList.innerHTML = desktopList.innerHTML;
  }
}
function setPrice(min, max) {
  document.getElementById("minPrice").value = min;
  document.getElementById("maxPrice").value = max || "";
}
