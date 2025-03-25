document.addEventListener("DOMContentLoaded", function () {
  let params = new URLSearchParams(window.location.search);
  let categoryId = params.get("category_id");
  let categoryName = params.get("category_name");

  const categoryMap = {
    1: "Cây dễ chăm",
    2: "Cây văn phòng",
    3: "Cây để bàn",
    4: "Cây dưới nước",
  };

  let typeTree = document.getElementById("type-tree");
  let productList = document.getElementById("product-list");
  let paginationDiv = document.getElementById("pagination-button");

  if (categoryName) {
    typeTree.innerHTML = categoryName;
  }

  if (categoryId) {
    document.getElementById(
      "product_type_list"
    ).innerText = `${categoryMap[categoryId]}`;

    fetch(`../php-api/filter-product.php?category_id=${categoryId}`)
      .then((response) => response.text())
      .then((text) => {
        try {
          console.log("Raw API Response:", text);
          let data = JSON.parse(text);

          if (data.error) {
            productList.innerHTML = `<p class="text-danger">${data.error}</p>`;
            return;
          }

          let currentPage = 1;
          let itemsPerPage = 8;
          let totalPages = Math.ceil(data.length / itemsPerPage);

          function renderPage(page) {
            productList.innerHTML = "";
            let start = (page - 1) * itemsPerPage;
            let end = start + itemsPerPage;
            let pageData = data.slice(start, end);

            pageData.forEach((product) => {
              let productItem = document.createElement("div");
              productItem.classList.add("card", "mb-3");

              productItem.innerHTML = `
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
          <img src="../../${
            product.ImageURL
          }" class="img-fluid" style="height: 275px;" alt="${
                product.ProductName
              }">
        </a>
      </div>
    `;
              productList.appendChild(productItem);
            });

            renderPagination();
          }

          function renderPagination() {
            paginationDiv.innerHTML = "";

            // Nút "‹ Trước"
            let prevBtn = document.createElement("button");
            prevBtn.innerText = "‹";
            prevBtn.classList.add("btn", "btn-secondary", "m-1");
            prevBtn.disabled = currentPage === 1;
            prevBtn.addEventListener("click", function () {
              if (currentPage > 1) {
                currentPage--;
                renderPage(currentPage);
              }
            });
            paginationDiv.appendChild(prevBtn);

            // Nút số trang
            for (let i = 1; i <= totalPages; i++) {
              let btn = document.createElement("button");
              btn.innerText = i;
              btn.classList.add("btn", "btn-success", "m-1");
              if (i === currentPage) btn.classList.add("active");

              btn.addEventListener("click", function () {
                currentPage = i;
                renderPage(currentPage);
              });

              paginationDiv.appendChild(btn);
            }

            // Nút "Sau ›"
            let nextBtn = document.createElement("button");
            nextBtn.innerText = "›";
            nextBtn.classList.add("btn", "btn-secondary", "m-1");
            nextBtn.disabled = currentPage === totalPages;
            nextBtn.addEventListener("click", function () {
              if (currentPage < totalPages) {
                currentPage++;
                renderPage(currentPage);
              }
            });
            paginationDiv.appendChild(nextBtn);
          }

          renderPage(currentPage);
        } catch (error) {
          console.error("Error parsing JSON:", error, text);
          productList.innerHTML = `<p class="text-danger">Lỗi khi xử lý dữ liệu.</p>`;
        }
      })
      .catch((error) => {
        console.error("Error fetching data:", error);
        productList.innerHTML = `<p class="text-danger">Lỗi khi tải dữ liệu.</p>`;
      });
  }
});
