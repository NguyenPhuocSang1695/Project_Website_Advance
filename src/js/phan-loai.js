document.addEventListener("DOMContentLoaded", async function () {
  // Lấy tham số từ URL
  const params = new URLSearchParams(window.location.search);
  const categoryId = params.get("category_id");
  const categoryName = params.get("category_name");

  // Danh sách loại cây
  const categoryMap = {
    3: "Cây dễ chăm",
    1: "Cây văn phòng",
    4: "Cây để bàn",
    2: "Cây dưới nước",
  };

  const typeTree = document.getElementById("type-tree");
  const productList = document.getElementById("product-list");
  const paginationDiv = document.getElementById("pagination-button");

  // Nếu đúng phân loại thì hiển thị tên phân loại
  if (categoryName) typeTree.textContent = categoryName;
  if (categoryId) {
    document.getElementById("product_type_list").textContent =
      categoryMap[categoryId] || "Danh mục khác";
    await loadProducts(categoryId);
  }

  // Lấy dữ liệu từ php để xử lý
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

  // Xử lý dữ liệu từ API, kiểm tra số lượng sản phẩm ở 1 trang
  function paginateProducts(data) {
    let currentPage = 1; // Vị trí mặc định là trang đầu tiên
    const itemsPerPage = 8; // Số lượng sản phẩm tối đa ở 1 trang
    // Tính tổng số trang cần thiết
    const totalPages = Math.ceil(data.length / itemsPerPage);

    // Chuyển trang
    function renderPage(page) {
      productList.innerHTML = "";
      const start = (page - 1) * itemsPerPage;
      const pageData = data.slice(start, start + itemsPerPage);

      pageData.forEach((product) => {
        productList.appendChild(createProductCard(product));
      });

      // Cuộn lên đầu trang sau khi chuyển trang (sau khi render)
      requestAnimationFrame(() => {
        window.scrollTo({ top: 0, behavior: "smooth" });
      });

      renderPagination();
    }

    // Nút phân trang
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

  // Tạo danh sách sản phẩm
  function createProductCard(product) {
    const card = document.createElement("div");
    card.className = "card mb-3";
    card.innerHTML = `
      <div class="card-body">
        <a href="user-sanpham.php?id=${product.ProductID}">
          <img src="..${
            product.ImageURL
          }" class="img-fluid" style="height: 275px;width: 275px" alt="${
      product.ProductName
    }">
        </a>
        <h5 class="card-title" style="margin: 10px 0; font-weight: bold;">
          <a href="user-sanpham.php?id=${
            product.ProductID
          }" class="text-decoration-none text-dark" >
            ${product.ProductName}
          </a>
        </h5>
        <p class="card-text"><strong>Giá:</strong> ${Number(
          product.Price
        ).toLocaleString()} VNĐ</p>
      </div>
    `;
    return card;
  }

  // Tạo nút phân trang
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
