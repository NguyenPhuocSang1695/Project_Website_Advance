// Biến toàn cục
let searchTimeout;
const MIN_CHARS_FOR_SUGGESTIONS = 2;

// Khởi tạo tính năng tìm kiếm
document.addEventListener("DOMContentLoaded", function () {
  // Xác định đường dẫn gốc dựa vào vị trí trang hiện tại
  const rootPath = window.location.pathname.includes("/pages/") ? "../" : "./";

  // Khởi tạo cho tìm kiếm desktop
  const searchInput = document.getElementById("searchInput");
  if (searchInput) {
    searchInput.addEventListener("input", showSuggestions);
    searchInput.addEventListener("keypress", function (e) {
      if (e.key === "Enter") {
        performSearch(rootPath);
      }
    });
  }

  // Nút tìm kiếm
  const searchButton = document.querySelector(".search-button");
  if (searchButton) {
    searchButton.addEventListener("click", function () {
      performSearch(rootPath);
    });
  }

  // Nút tìm kiếm nâng cao
  const advancedSearchButton = document.querySelector(".btn-search");
  if (advancedSearchButton) {
    advancedSearchButton.addEventListener("click", function () {
      performAdvancedSearch(rootPath);
    });
  }

  // Ẩn gợi ý khi click ngoài
  document.addEventListener("click", function (e) {
    if (!e.target.closest(".search-group")) {
      hideProductList();
    }
  });

  // Tạo danh sách sản phẩm gợi ý nếu chưa có
  initSuggestionProducts();
});

// Tạo danh sách sản phẩm gợi ý nếu trang hiện tại không có
function initSuggestionProducts() {
  const productList = document.getElementById("productList");

  // Nếu danh sách trống, tạo dữ liệu mẫu hoặc tải từ localStorage
  if (productList && !productList.children.length) {
    // Thêm dữ liệu mẫu hoặc lấy từ API
    const sampleProducts = getSampleProducts();

    sampleProducts.forEach((product) => {
      const productElement = createProductElement(product);
      productList.appendChild(productElement);
    });
  }
}

// Lấy danh sách sản phẩm mẫu
function getSampleProducts() {
  return [
    {
      id: 1,
      name: "Cây phát tài",
      price: "750.000 vnđ",
      image: "../assets/images/CAY5.jpg",
      category: "cay-de-cham",
    },
    {
      id: 2,
      name: "Cây kim ngân",
      price: "280.000 vnđ",
      image: "../assets/images/CAY6.jpg",
      category: "cay-van-phong",
    },
    {
      id: 3,
      name: "Cây trầu bà",
      price: "120.000 vnđ",
      image: "../assets/images/CAY7.jpg",
      category: "cay-de-ban",
    },
    {
      id: 4,
      name: "Cây lan chi",
      price: "120.000 vnđ",
      image: "../assets/images/CAY8.jpg",
      category: "cay-duoi-nuoc",
    },
    {
      id: 5,
      name: "Cây trầu bà đỏ",
      price: "320.000 vnđ",
      image: "../assets/images/CAY9.jpg",
      category: "cay-de-ban",
    },
  ];
}

// Tạo phần tử sản phẩm
function createProductElement(product) {
  const div = document.createElement("div");
  div.className = "product";
  div.setAttribute("data-category", product.category);

  const rootPath = window.location.pathname.includes("/pages/") ? "../" : "./";
  const imagePath = product.image.startsWith("../")
    ? product.image.substring(3)
    : product.image;
  const imageFullPath =
    rootPath +
    (product.image.startsWith("./") ? product.image.substring(2) : imagePath);

  div.innerHTML = `
    <img src="${imageFullPath}" alt="${product.name}" />
    <div class="p-details">
      <h2>${product.name}</h2>
      <h3>${product.price}</h3>
    </div>
  `;

  return div;
}

// Hiển thị gợi ý khi gõ
function showSuggestions() {
  clearTimeout(searchTimeout);

  searchTimeout = setTimeout(() => {
    const searchTerm = document
      .getElementById("searchInput")
      .value.toLowerCase()
      .trim();

    // Ẩn danh sách nếu chuỗi tìm kiếm quá ngắn
    if (searchTerm.length < MIN_CHARS_FOR_SUGGESTIONS) {
      hideProductList();
      return;
    }

    // Lấy tất cả sản phẩm trong list
    const productList = document.getElementById("productList");
    if (!productList) return;

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
      hideProductList();
    }
  }, 300);
}

// Ẩn danh sách gợi ý
function hideProductList() {
  const productList = document.getElementById("productList");
  if (productList) {
    productList.style.display = "none";
    productList.classList.remove("showing-suggestions");
  }
}

// Thực hiện tìm kiếm thông thường
function performSearch(rootPath) {
  const searchInput = document.getElementById("searchInput");
  if (!searchInput) return;

  const searchTerm = searchInput.value.trim();
  if (!searchTerm) return;

  // Lưu từ khóa tìm kiếm
  const searchParams = {
    term: searchTerm,
    category: "",
    minPrice: "",
    maxPrice: "",
  };

  localStorage.setItem("lastSearchParams", JSON.stringify(searchParams));

  // Chuyển trang với tham số tìm kiếm
  const path =
    rootPath || (window.location.pathname.includes("/pages/") ? "../" : "./");
  window.location.href =
    path + "pages/phan-loai1.html?search=" + encodeURIComponent(searchTerm);
}

// Thực hiện tìm kiếm nâng cao
function performAdvancedSearch(rootPath) {
  const searchTerm = document.getElementById("searchInput").value.trim();
  const category = document.getElementById("categoryFilter").value;
  const minPrice = document.getElementById("minPrice").value;
  const maxPrice = document.getElementById("maxPrice").value;

  // Tạo URL với tham số tìm kiếm
  const path =
    rootPath || (window.location.pathname.includes("/pages/") ? "../" : "./");
  let searchUrl = path + "pages/phan-loai1.html?";
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

  // Lưu tham số tìm kiếm
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

// Thiết lập giá cho các preset
function setPrice(min, max) {
  document.getElementById("minPrice").value = min;
  document.getElementById("maxPrice").value = max || "";
}

// Đặt lại các bộ lọc
function resetFilters() {
  document.getElementById("searchInput").value = "";
  if (document.getElementById("categoryFilter"))
    document.getElementById("categoryFilter").value = "";
  if (document.getElementById("minPrice"))
    document.getElementById("minPrice").value = "";
  if (document.getElementById("maxPrice"))
    document.getElementById("maxPrice").value = "";
}
