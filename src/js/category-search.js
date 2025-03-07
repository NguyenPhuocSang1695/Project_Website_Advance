// Xử lý hiển thị kết quả tìm kiếm trên trang phân loại
document.addEventListener("DOMContentLoaded", function () {
  // Lấy tham số tìm kiếm từ URL
  const urlParams = new URLSearchParams(window.location.search);
  const searchTerm = urlParams.get("search");
  const category = urlParams.get("category");
  const minPrice = urlParams.get("minPrice");
  const maxPrice = urlParams.get("maxPrice");

  // Hoặc lấy từ localStorage nếu không có trong URL
  let searchParams;
  if (
    !searchTerm &&
    !category &&
    !minPrice &&
    !maxPrice &&
    localStorage.getItem("lastSearchParams")
  ) {
    searchParams = JSON.parse(localStorage.getItem("lastSearchParams"));
  } else {
    searchParams = {
      term: searchTerm || "",
      category: category || "",
      minPrice: minPrice || "",
      maxPrice: maxPrice || "",
    };
  }

  // Nếu có tham số tìm kiếm, áp dụng bộ lọc
  if (
    searchParams.term ||
    searchParams.category ||
    searchParams.minPrice ||
    searchParams.maxPrice
  ) {
    // Hiển thị thông tin tìm kiếm
    displaySearchInfo(searchParams);

    // Lọc sản phẩm
    filterProducts(searchParams);
  }
});

// Hiển thị thông tin tìm kiếm
function displaySearchInfo(params) {
  // Tạo hoặc lấy phần tử thông tin tìm kiếm
  let infoPanel = document.getElementById("searchInfoPanel");
  if (!infoPanel) {
    infoPanel = document.createElement("div");
    infoPanel.id = "searchInfoPanel";
    infoPanel.className = "search-info-panel";

    // Chèn vào đầu danh sách sản phẩm
    const container = document.querySelector(
      ".products-container, .product-container"
    );
    if (container) {
      container.parentNode.insertBefore(infoPanel, container);
    }
  }

  // Tạo nội dung thông tin tìm kiếm
  let infoContent = '<div class="search-criteria">';
  infoContent += "<h3>Kết quả tìm kiếm</h3><ul>";

  if (params.term) {
    infoContent += `<li>Từ khóa: <strong>"${params.term}"</strong></li>`;
  }

  if (params.category) {
    const categoryName = getCategoryDisplayName(params.category);
    infoContent += `<li>Phân loại: <strong>${categoryName}</strong></li>`;
  }

  if (params.minPrice || params.maxPrice) {
    const minPrice = params.minPrice ? formatPrice(params.minPrice) : "0đ";
    const maxPrice = params.maxPrice
      ? formatPrice(params.maxPrice)
      : "không giới hạn";
    infoContent += `<li>Khoảng giá: <strong>${minPrice} - ${maxPrice}</strong></li>`;
  }

  infoContent += "</ul></div>";

  // Thêm nút xóa bộ lọc
  infoContent += `
    <button type="button" class="clear-search-btn" onclick="clearSearch()">
      <i class="fas fa-times"></i> Xóa bộ lọc
    </button>
  `;

  infoPanel.innerHTML = infoContent;
}

// Lọc sản phẩm theo điều kiện tìm kiếm
function filterProducts(params) {
  // Lấy tất cả sản phẩm trên trang
  const products = document.querySelectorAll(".product-item, .pro");
  let foundMatch = false;

  products.forEach((product) => {
    // Lấy thông tin sản phẩm (điều chỉnh selector theo HTML của trang)
    const productName = product
      .querySelector(".product-title, .product-name, p")
      .textContent.toLowerCase();
    const priceElement = product.querySelector(".product-price, span");
    const productPrice = priceElement
      ? extractPrice(priceElement.textContent)
      : 0;
    const productCategory = product.getAttribute("data-category") || "";

    // Áp dụng các điều kiện lọc
    let matchName = true;
    let matchCategory = true;
    let matchPrice = true;

    // Lọc theo tên
    if (params.term && !productName.includes(params.term.toLowerCase())) {
      matchName = false;
    }

    // Lọc theo phân loại
    if (params.category && productCategory !== params.category) {
      matchCategory = false;
    }

    // Lọc theo khoảng giá
    const minPriceValue = params.minPrice ? parseInt(params.minPrice) : 0;
    const maxPriceValue = params.maxPrice
      ? parseInt(params.maxPrice)
      : Infinity;

    if (productPrice < minPriceValue || productPrice > maxPriceValue) {
      matchPrice = false;
    }

    // Hiển thị hoặc ẩn sản phẩm
    if (matchName && matchCategory && matchPrice) {
      product.style.display = "";
      foundMatch = true;
    } else {
      product.style.display = "none";
    }
  });

  // Hiển thị thông báo nếu không tìm thấy kết quả
  displayNoResultsMessage(foundMatch);
}

// Hiển thị thông báo không tìm thấy kết quả
function displayNoResultsMessage(foundMatch) {
  let noResultsMsg = document.getElementById("noResultsMessage");

  if (!foundMatch) {
    if (!noResultsMsg) {
      noResultsMsg = document.createElement("div");
      noResultsMsg.id = "noResultsMessage";
      noResultsMsg.className = "no-results-message";
      noResultsMsg.innerHTML = `
        <i class="fas fa-search"></i>
        <p>Không tìm thấy sản phẩm phù hợp với điều kiện tìm kiếm</p>
        <button type="button" class="btn-reset-search" onclick="clearSearch()">
          <i class="fas fa-redo-alt"></i> Xem tất cả sản phẩm
        </button>
      `;

      const container = document.querySelector(
        ".products-container, .product-container"
      );
      if (container) {
        container.appendChild(noResultsMsg);
      }
    } else {
      noResultsMsg.style.display = "flex";
    }
  } else if (noResultsMsg) {
    noResultsMsg.style.display = "none";
  }
}

// Xóa tìm kiếm và hiển thị tất cả sản phẩm
function clearSearch() {
  // Xóa tham số tìm kiếm khỏi localStorage
  localStorage.removeItem("lastSearchParams");

  // Chuyển về URL không có tham số tìm kiếm
  window.location.href = "phan-loai1.html";
}

// Trích xuất giá từ chuỗi "xxx.xxx vnđ"
function extractPrice(priceString) {
  return parseInt(priceString.replace(/\D/g, ""));
}

// Định dạng giá tiền
function formatPrice(price) {
  return price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") + "đ";
}

// Lấy tên hiển thị của phân loại
function getCategoryDisplayName(categoryValue) {
  const categories = {
    "cay-de-cham": "Cây dễ chăm",
    "cay-van-phong": "Cây văn phòng",
    "cay-de-ban": "Cây để bàn",
    "cay-duoi-nuoc": "Cây dưới nước",
  };

  return categories[categoryValue] || categoryValue;
}
