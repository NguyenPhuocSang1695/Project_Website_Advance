document.addEventListener("DOMContentLoaded", async function () {
  // Lấy tham số từ URL
  const params = new URLSearchParams(window.location.search);
  const categoryId = params.get("category_id");
  const categoryName = params.get("category_name");

  // Lấy danh mục từ API PHP
  async function getCategories() {
    try {
      const response = await fetch("../php-api/get_categories.php");
      const categories = await response.json();

      // Tạo mảng categoryMap động từ dữ liệu lấy được
      const categoryMap = {};
      categories.forEach((category) => {
        categoryMap[category.CategoryID] = category.CategoryName;
      });

      // Cập nhật danh mục trong giao diện
      updateCategoryDropdown(categories);

      // Nếu category_id có trong URL, hiển thị tên danh mục
      displayCategoryName(categoryMap, categoryId);

      // Gọi hàm để tải sản phẩm nếu category_id có
      if (categoryId) {
        await loadProducts(categoryId);
      }
    } catch (error) {
      console.error("Lỗi khi tải danh mục:", error);
    }
  }

  // Cập nhật danh mục vào dropdown
  function updateCategoryDropdown(categories) {
    const dropdownMenu = document.querySelector(".dropdown-menu");
    dropdownMenu.innerHTML = ""; // Xóa danh sách cũ
    categories.forEach((category) => {
      const listItem = document.createElement("li");
      listItem.innerHTML = `<a class="dropdown-item" href="./phan-loai.php?category_id=${category.CategoryID}">${category.CategoryName}</a>`;
      dropdownMenu.appendChild(listItem);
    });
  }

  // Hiển thị tên danh mục nếu có category_id
  function displayCategoryName(categoryMap, categoryId) {
    const categoryName = categoryMap[categoryId] || "Danh mục không tồn tại";
    document.getElementById("product_type_list").textContent = categoryName;
  }

  // Lấy dữ liệu từ php để xử lý
  async function loadProducts(categoryId) {
    const productList = document.getElementById("product-list");
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
      const productList = document.getElementById("product-list");
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
      const paginationDiv = document.getElementById("pagination-button");
      paginationDiv.innerHTML = "";

      // Nút quay lại trang trước
      paginationDiv.appendChild(
        createPaginationButton("‹", currentPage > 1, () =>
          renderPage(--currentPage)
        )
      );

      // Hiển thị các nút số trang
      if (totalPages <= 5) {
        // Hiển thị tất cả các trang nếu totalPages <= 5
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
      } else {
        // Hiển thị trang 1
        paginationDiv.appendChild(
          createPaginationButton(
            1,
            true,
            () => {
              currentPage = 1;
              renderPage(currentPage);
            },
            currentPage === 1
          )
        );

        // Xác định các trang hiển thị giữa
        let startPage = Math.max(2, currentPage - 1);
        let endPage = Math.min(totalPages - 1, currentPage + 1);

        // Nếu đang ở trang 1, hiển thị 2, 3, 4
        if (currentPage === 1) {
          endPage = 4;
        }
        // Nếu đang ở trang cuối, hiển thị n-3, n-2, n-1
        else if (currentPage === totalPages) {
          startPage = totalPages - 3;
        }

        // Thêm dấu "..." nếu cần
        if (startPage > 2) {
          paginationDiv.appendChild(
            createPaginationButton("...", false, null, false)
          );
        }

        // Hiển thị các trang giữa
        for (let i = startPage; i <= endPage; i++) {
          if (i < totalPages) {
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
        }

        // Thêm dấu "..." nếu cần
        if (endPage < totalPages - 1) {
          paginationDiv.appendChild(
            createPaginationButton("...", false, null, false)
          );
        }

        // Hiển thị trang cuối
        paginationDiv.appendChild(
          createPaginationButton(
            totalPages,
            true,
            () => {
              currentPage = totalPages;
              renderPage(currentPage);
            },
            currentPage === totalPages
          )
        );
      }

      // Nút đến trang tiếp theo
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
          }" class="text-decoration-none text-dark">
            ${product.ProductName}
          </a>
        </h5>
        <p class="card-text"><strong >Giá:</strong> <span style = "color: green;">${Number(
          product.Price
        ).toLocaleString()} VNĐ</span></p>
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

  // Gọi hàm getCategories để lấy danh mục
  getCategories();
});
