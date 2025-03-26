document.addEventListener("DOMContentLoaded", async function () {
  const params = new URLSearchParams(window.location.search);
  const categoryId = params.get("category_id");
  const categoryName = params.get("category_name");

  const categoryMap = {
    1: "Cây dễ chăm",
    2: "Cây văn phòng",
    3: "Cây để bàn",
    4: "Cây dưới nước",
  };

  const typeTree = document.getElementById("type-tree");
  const productList = document.getElementById("product-list");
  const paginationDiv = document.getElementById("pagination-button");

  if (categoryName) typeTree.textContent = categoryName;
  if (categoryId) {
    document.getElementById("product_type_list").textContent =
      categoryMap[categoryId] || "Danh mục khác";
    await loadProducts(categoryId);
  }

  async function loadProducts(categoryId) {
    try {
      const response = await fetch(
        `../php-api/filter-product.php?category_id=${categoryId}`
      );
      const text = await response.text();
      console.log("Raw API Response:", text);

      const data = JSON.parse(text);
      if (data.error) {
        productList.innerHTML = `<p class="text-danger">${data.error}</p>`;
        return;
      }

      paginateProducts(data);
    } catch (error) {
      console.error("Error fetching data:", error);
      productList.innerHTML = `<p class="text-danger">Lỗi khi tải dữ liệu.</p>`;
    }
  }

  function paginateProducts(data) {
    let currentPage = 1;
    const itemsPerPage = 8;
    const totalPages = Math.ceil(data.length / itemsPerPage);

    function renderPage(page) {
      productList.innerHTML = "";
      const start = (page - 1) * itemsPerPage;
      const pageData = data.slice(start, start + itemsPerPage);

      pageData.forEach((product) => {
        productList.appendChild(createProductCard(product));
      });
      renderPagination();
    }

    function renderPagination() {
      paginationDiv.innerHTML = "";
      paginationDiv.appendChild(
        createPaginationButton("‹", currentPage > 1, () =>
          renderPage(--currentPage)
        )
      );

      for (let i = 1; i <= totalPages; i++) {
        paginationDiv.appendChild(
          createPaginationButton(
            i,
            true,
            () => {
              currentPage = i;
              renderPage(currentPage);
            },
            i === currentPage
          )
        );
      }

      paginationDiv.appendChild(
        createPaginationButton("›", currentPage < totalPages, () =>
          renderPage(++currentPage)
        )
      );
    }

    renderPage(currentPage);
  }

  function createProductCard(product) {
    const card = document.createElement("div");
    card.className = "card mb-3";
    card.innerHTML = `
      <div class="card-body">
        <h5 class="card-title">
          <a href="user-sanpham.php?id=${
            product.ProductID
          }" class="text-decoration-none text-dark">
            ${product.ProductName}
          </a>
        </h5>
        <p class="card-text">${product.DescriptionBrief}</p>
        <p class="card-text"><strong>Giá:</strong> ${Number(
          product.Price
        ).toLocaleString()} VNĐ</p>
        <a href="user-sanpham.php?id=${product.ProductID}">
          <img src="..${
            product.ImageURL
          }" class="img-fluid" style="height: 275px;" alt="${
      product.ProductName
    }">
        </a>
      </div>
    `;
    return card;
  }

  function createPaginationButton(label, enabled, onClick, isActive = false) {
    const button = document.createElement("button");
    button.textContent = label;
    button.className = `btn ${
      isActive ? "btn-success active" : "btn-secondary"
    } m-1`;
    button.disabled = !enabled;
    if (enabled) button.addEventListener("click", onClick);
    return button;
  }
});
