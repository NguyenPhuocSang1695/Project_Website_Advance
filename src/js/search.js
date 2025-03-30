document.addEventListener("DOMContentLoaded", function () {
  // Get search parameters from URL
  const urlParams = new URLSearchParams(window.location.search);
  const searchQuery = urlParams.get("q") || "";
  const category = urlParams.get("category") || "";
  const minPrice = urlParams.get("minPrice") || "";
  const maxPrice = urlParams.get("maxPrice") || "";
  const advancedSearch = urlParams.get("advanced") === "true";
  const currentPage = parseInt(urlParams.get("page") || "1");

  // Display search parameters
  displaySearchParams(searchQuery, category, minPrice, maxPrice);

  // Fetch search results
  fetchSearchResults(searchQuery, category, minPrice, maxPrice, currentPage);

  // Set up the category filter in the advanced search if it exists
  populateCategoryFilter();

  // Make sure the form doesn't submit when selecting a category
  const categoryFilter = document.getElementById("categoryFilter");
  if (categoryFilter) {
    categoryFilter.addEventListener("change", function (e) {
      // Don't submit the form automatically when changing category
      e.preventDefault();
    });
  }

  // Set up the search form submission
  const searchForm = document.getElementById("searchForm");
  if (searchForm) {
    searchForm.addEventListener("submit", function (e) {
      e.preventDefault(); // Prevent default form submission
      performSearch(); // Use our custom search function
    });
  }

  // Initialize the advanced search visibility based on URL parameter
  const advancedSearchForm = document.getElementById("advancedSearchForm");
  advancedSearchForm = false; // Thay vì đọc từ URL

  if (advancedSearchForm) {
    advancedSearchForm.style.display = advancedSearch ? "block" : "none";
  }
});

/**
 * Populates the category filter with available categories
 */
function populateCategoryFilter() {
  const categoryFilter = document.getElementById("categoryFilter");
  if (categoryFilter) {
    // Get the category from URL if available
    const urlParams = new URLSearchParams(window.location.search);
    const selectedCategory = urlParams.get("category") || "";

    // Set the selected category if it exists
    if (selectedCategory) {
      // Try to find and select the option
      for (let i = 0; i < categoryFilter.options.length; i++) {
        if (categoryFilter.options[i].value === selectedCategory) {
          categoryFilter.selectedIndex = i;
          break;
        }
      }
    }
  }
}

/**
 * Performs search when the search form is submitted
 */
function performSearch() {
  const searchInput = document.getElementById("searchInput").value;

  // Check if advanced search is visible
  const advancedSearchForm = document.getElementById("advancedSearchForm");
  const isAdvancedSearchVisible =
    advancedSearchForm && advancedSearchForm.style.display !== "none";

  // Only get category and price if advanced search is visible
  let category = "";
  let minPrice = "";
  let maxPrice = "";

  if (isAdvancedSearchVisible) {
    const categoryFilter = document.getElementById("categoryFilter");
    // Only use category if a real option is selected (not the placeholder)
    if (
      categoryFilter &&
      categoryFilter.selectedIndex > 0 &&
      categoryFilter.value !== "Chọn phân loại"
    ) {
      category = categoryFilter.value;
    }

    minPrice = document.getElementById("minPrice")?.value || "";
    maxPrice = document.getElementById("maxPrice")?.value || "";
  } else {
    // Get category even if advanced search is not visible
    const categoryFilter = document.getElementById("categoryFilter");
    if (
      categoryFilter &&
      categoryFilter.selectedIndex > 0 &&
      categoryFilter.value !== "Chọn phân loại"
    ) {
      category = categoryFilter.value;
    }
  }

  // Build query string
  let queryString = `q=${encodeURIComponent(searchInput)}`;

  // Add advanced search parameter to track toggle state
  queryString += `&advanced=${isAdvancedSearchVisible}`;

  // Add category regardless of advanced search state
  if (category) {
    queryString += `&category=${encodeURIComponent(category)}`;
  }

  // Only add price parameters if advanced search is active
  if (isAdvancedSearchVisible) {
    if (minPrice) {
      queryString += `&minPrice=${encodeURIComponent(minPrice)}`;
    }

    if (maxPrice) {
      queryString += `&maxPrice=${encodeURIComponent(maxPrice)}`;
    }
  }

  // Start on page 1 for new searches
  queryString += "&page=1";

  // Redirect to search results page
  window.location.href = "./search-result.html?" + queryString;
}

/**
 * Displays the search parameters used
 */
function displaySearchParams(query, category, minPrice, maxPrice) {
  const searchParamsContainer = document.getElementById("searchParams");
  if (!searchParamsContainer) return; // Exit if container doesn't exist in the HTML

  let paramsHTML =
    '<h3>Kết quả tìm kiếm cho:</h3><ul class="search-params-list">';

  if (query) {
    paramsHTML += `<li><strong>Từ khóa:</strong> ${escapeHTML(query)}</li>`;
  }

  // Only show category and price filters if they were part of an advanced search
  const urlParams = new URLSearchParams(window.location.search);
  const advancedSearch = urlParams.get("advanced") === "true";

  if (advancedSearch) {
    if (category && category !== "Chọn phân loại") {
      paramsHTML += `<li><strong>Phân loại:</strong> ${escapeHTML(
        category
      )}</li>`;
    }

    if (minPrice && maxPrice) {
      paramsHTML += `<li><strong>Giá:</strong> ${formatPrice(
        minPrice
      )} - ${formatPrice(maxPrice)}</li>`;
    } else if (minPrice) {
      paramsHTML += `<li><strong>Giá từ:</strong> ${formatPrice(
        minPrice
      )}</li>`;
    } else if (maxPrice) {
      paramsHTML += `<li><strong>Giá đến:</strong> ${formatPrice(
        maxPrice
      )}</li>`;
    }
  }

  paramsHTML += "</ul>";
  searchParamsContainer.innerHTML = paramsHTML;
}

/**
 * Fetches search results from the PHP script
 */
function fetchSearchResults(
  query,
  category,
  minPrice,
  maxPrice,
  currentPage = 1
) {
  // Find the existing search results container
  const resultsContainer = document.getElementById("searchResults");
  if (!resultsContainer) return; // Exit if container doesn't exist

  // Clear any duplicate containers (if there's more than one with the same ID)
  const duplicateContainers = document.querySelectorAll("#searchResults");
  if (duplicateContainers.length > 1) {
    for (let i = 1; i < duplicateContainers.length; i++) {
      duplicateContainers[i].remove();
    }
  }

  resultsContainer.innerHTML =
    '<div class="loading-spinner"><i class="fas fa-spinner fa-spin"></i> Đang tải...</div>';

  // Check if we're using advanced search
  const urlParams = new URLSearchParams(window.location.search);
  const advancedSearch = urlParams.get("advanced") === "true";

  // Build the query string
  let queryString = "";
  if (query) queryString += `q=${encodeURIComponent(query)}&`;

  // Only include these parameters if advanced search was used
  if (advancedSearch) {
    // Only include category if it's a valid selection
    if (category && category !== "Chọn phân loại") {
      queryString += `category=${encodeURIComponent(category)}&`;
    }

    if (minPrice) queryString += `minPrice=${encodeURIComponent(minPrice)}&`;
    if (maxPrice) queryString += `maxPrice=${encodeURIComponent(maxPrice)}&`;
  }

  // Remove trailing '&' if exists
  if (queryString.endsWith("&")) {
    queryString = queryString.slice(0, -1);
  }

  // Make API request to our PHP script
  fetch(`../php-api/search.php?${queryString}`)
    .then((response) => {
      if (!response.ok) {
        throw new Error("Network response was not ok");
      }
      return response.json();
    })
    .then((data) => {
      displayResults(data, resultsContainer, currentPage);
      createPagination(data.length, currentPage);
    })
    .catch((error) => {
      resultsContainer.innerHTML = `
        <div class="error-message">
          <i class="fas fa-exclamation-circle"></i>
          <p>Có lỗi xảy ra khi tải kết quả tìm kiếm: ${error.message}</p>
        </div>
      `;
    });
}

/**
 * Resets all search filters
 */
function resetFilters() {
  document.getElementById("searchInput").value = "";

  const categoryFilter = document.getElementById("categoryFilter");
  if (categoryFilter) {
    categoryFilter.selectedIndex = 0;
  }

  const minPrice = document.getElementById("minPrice");
  if (minPrice) {
    minPrice.value = "";
  }

  const maxPrice = document.getElementById("maxPrice");
  if (maxPrice) {
    maxPrice.value = "";
  }
}

/**
 * Sets the price range inputs to predefined values
 */
function setPrice(min, max) {
  const minPriceInput = document.getElementById("minPrice");
  const maxPriceInput = document.getElementById("maxPrice");

  if (minPriceInput) {
    minPriceInput.value = min;
  }

  if (maxPriceInput) {
    maxPriceInput.value = max > 0 ? max : "";
  }
}

/**
 * Toggles the advanced search panel
 */
function toggleAdvancedSearch() {
  const advancedSearchForm = document.getElementById("advancedSearchForm");
  if (advancedSearchForm) {
    if (advancedSearchForm.style.display === "none") {
      advancedSearchForm.style.display = "block";
    } else {
      advancedSearchForm.style.display = "none";
    }
  }

  // Update the toggle button icon
  const advancedSearchToggle = document.getElementById(
    "advanced-search-toggle"
  );
  if (advancedSearchToggle) {
    if (advancedSearchForm.style.display === "none") {
      advancedSearchToggle.innerHTML = '<i class="fas fa-sliders-h"></i>';
      advancedSearchToggle.title = "Tìm kiếm nâng cao";
    } else {
      advancedSearchToggle.innerHTML = '<i class="fas fa-times"></i>';
      advancedSearchToggle.title = "Đóng tìm kiếm nâng cao";
    }
  }
}

/**
 * Navigate to a specific page
 */
function goToPage(pageNumber) {
  const urlParams = new URLSearchParams(window.location.search);
  urlParams.set("page", pageNumber);
  window.location.href = window.location.pathname + "?" + urlParams.toString();
}

/**
 * Creates pagination controls
 */
function createPagination(totalItems, currentPage) {
  const itemsPerPage = 8;
  const totalPages = Math.ceil(totalItems / itemsPerPage);

  // Get existing pagination container
  let paginationContainer = document.getElementById("pagination");
  if (!paginationContainer) return; // Exit if container doesn't exist in the HTML

  // Don't show pagination if not needed
  if (totalPages <= 1) {
    paginationContainer.style.display = "none";
    return;
  }

  paginationContainer.style.display = "flex";

  let paginationHTML = "";

  // Previous button
  paginationHTML += `<button class="page-btn prev-btn btn btn-success" ${
    currentPage === 1 ? "disabled" : ""
  } onclick="goToPage(${currentPage - 1})">
    <i class="fas fa-chevron-left"></i>
  </button>`;

  // Page buttons
  const maxPageButtons = 5;
  let startPage = Math.max(1, currentPage - Math.floor(maxPageButtons / 2));
  let endPage = Math.min(totalPages, startPage + maxPageButtons - 1);

  // Adjust start page if we're near the end
  if (endPage - startPage + 1 < maxPageButtons) {
    startPage = Math.max(1, endPage - maxPageButtons + 1);
  }

  // First page button if needed
  if (startPage > 1) {
    paginationHTML += `<button class="page-btn btn btn-success" onclick="goToPage(1)">1</button>`;
    if (startPage > 2) {
      paginationHTML += `<span class="page-ellipsis">...</span>`;
    }
  }

  // Page buttons
  for (let i = startPage; i <= endPage; i++) {
    paginationHTML += `<button class="page-btn btn btn-success ${
      i === currentPage ? "active" : ""
    }" onclick="goToPage(${i})">${i}</button>`;
  }

  // Last page button if needed
  if (endPage < totalPages) {
    if (endPage < totalPages - 1) {
      paginationHTML += `<span class="page-ellipsis">...</span>`;
    }
    paginationHTML += `<button class="page-btn btn btn-success" onclick="goToPage(${totalPages})">${totalPages}</button>`;
  }

  // Next button
  paginationHTML += `<button class="page-btn next-btn btn btn-success" ${
    currentPage === totalPages ? "disabled" : ""
  } onclick="goToPage(${currentPage + 1})">
    <i class="fas fa-chevron-right"></i>
  </button>`;

  paginationContainer.innerHTML = paginationHTML;
}

/**
 * Displays the search results with pagination
 */
function displayResults(data, container, currentPage = 1) {
  // Clear loading message
  container.innerHTML = "";

  // Check if we have a message (no results)
  if (data.message) {
    container.innerHTML = `
      <div class="no-results">
        <i class="fas fa-search"></i>
        <p>${data.message}</p>
        <a href="javascript:history.back()" class="back-button">
          <i class="fas fa-arrow-left"></i> Quay lại
        </a>
      </div>
    `;
    return;
  }

  // If we have products
  if (data.length > 0) {
    const itemsPerPage = 8;
    const startIndex = (currentPage - 1) * itemsPerPage;
    const endIndex = Math.min(startIndex + itemsPerPage, data.length);
    const currentPageData = data.slice(startIndex, endIndex);

    const resultsCount = document.createElement("div");
    resultsCount.className = "results-count";
    resultsCount.innerHTML = `<p>Tìm thấy ${data.length} sản phẩm (hiển thị ${
      startIndex + 1
    } - ${endIndex})</p>`;
    container.appendChild(resultsCount);

    const productsGrid = document.createElement("div");
    productsGrid.className = "products-grid";

    currentPageData.forEach((product) => {
      const productCard = createProductCard(product);
      productsGrid.appendChild(productCard);
    });

    container.appendChild(productsGrid);
  } else {
    container.innerHTML = `
      <div class="no-results">
        <i class="fas fa-search"></i>
        <p>Không tìm thấy sản phẩm nào phù hợp với tiêu chí tìm kiếm.</p>
        <a href="javascript:history.back()" class="back-button">
          <i class="fas fa-arrow-left"></i> Quay lại
        </a>
      </div>
    `;
  }
}

/**
 * Creates a product card element
 */
function createProductCard(product) {
  const card = document.createElement("div");
  card.className = "product-card";
  card.setAttribute("data-product-id", product.ProductID);

  const imageUrl = product.ImageURL || "images/placeholder.jpg";

  card.innerHTML = `
    <div class="product-image">
      <img src="../${escapeHTML(imageUrl)}" alt="${escapeHTML(
    product.ProductName
  )}" onerror="this.src='images/placeholder.jpg'">
    </div>
    <div class="product-info">
      <h3 class="product-name">${escapeHTML(product.ProductName)}</h3>
      <p class="product-price">${formatPrice(product.Price)}</p>
      <button class="add-to-cart-btn" onclick="addToCart(${product.ProductID})">
        <i class="fas fa-shopping-cart"></i> Thêm vào giỏ
      </button>
    </div>
  `;

  card.addEventListener("click", function (e) {
    // Prevent navigation if clicking on the Add to Cart button
    if (e.target.closest(".add-to-cart-btn")) {
      e.stopPropagation();
      return;
    }

    // Navigate to product detail page
    window.location.href = `./user-sanpham.php?id=${product.ProductID}`;
  });

  return card;
}

/**
 * Adds a product to the shopping cart
 */
function addToCart(productId) {
  // You would implement this based on your cart system
  console.log(`Adding product ID ${productId} to cart`);

  // Example implementation with localStorage
  let cart = JSON.parse(localStorage.getItem("cart") || "[]");

  // Check if product already in cart
  const existingItem = cart.find((item) => item.id === productId);

  if (existingItem) {
    existingItem.quantity += 1;
  } else {
    cart.push({
      id: productId,
      quantity: 1,
    });
  }

  localStorage.setItem("cart", JSON.stringify(cart));

  // Show confirmation message
  showNotification("Đã thêm sản phẩm vào giỏ hàng!");
}

/**
 * Shows a notification message
 */
function showNotification(message) {
  // Create notification element if it doesn't exist
  let notification = document.getElementById("notification");

  if (!notification) {
    notification = document.createElement("div");
    notification.id = "notification";
    notification.className = "notification";
    document.body.appendChild(notification);
  }

  // Set message and show notification
  notification.textContent = message;
  notification.classList.add("show");

  // Hide after 3 seconds
  setTimeout(() => {
    notification.classList.remove("show");
  }, 3000);
}

/**
 * Formats a price with Vietnamese currency
 */
function formatPrice(price) {
  return new Intl.NumberFormat("vi-VN", {
    style: "currency",
    currency: "VND",
  }).format(price);
}

/**
 * Escapes HTML to prevent XSS
 */
function escapeHTML(str) {
  if (!str) return "";
  return String(str)
    .replace(/&/g, "&amp;")
    .replace(/</g, "&lt;")
    .replace(/>/g, "&gt;")
    .replace(/"/g, "&quot;")
    .replace(/'/g, "&#039;");
}
