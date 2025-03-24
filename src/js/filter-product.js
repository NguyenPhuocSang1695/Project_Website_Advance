document.addEventListener("DOMContentLoaded", function () {
  document.querySelectorAll(".dropdown-item").forEach((item) => {
    item.addEventListener("click", function () {
      let categoryMap = {
        "Cây dễ chăm": 1,
        "Cây văn phòng": 2,
        "Cây để bàn": 3,
        "Cây dưới nước": 4,
      };

      let categoryName = this.innerText.trim();
      let categoryId = categoryMap[categoryName];
      let typeTree = document.getElementById("type-tree");
      if (categoryId) {
        fetch("../php-api/filter-product.php?category_id=" + categoryId)
          .then((response) => response.text()) // Đọc dữ liệu dưới dạng text trước
          .then((text) => {
            try {
              let data = JSON.parse(text); // Thử chuyển đổi JSON
              let productList = document.getElementById("product-list");
              productList.innerHTML = "";
              typeTree.innerHTML = categoryName;

              if (data.error) {
                productList.innerHTML = `<p class="text-danger">${data.error}</p>`;
                return;
              }

              data.forEach((product) => {
                let productItem = document.createElement("div");
                productItem.classList.add("card", "mb-3");
                productItem.innerHTML = `
                                    <div class="card-body">
                                        <h5 class="card-title">${product.ProductName}</h5>
                                        <p class="card-text">${product.DescriptionBrief}</p>
                                        <p class="card-text"><strong>Giá:</strong> ${product.Price} VNĐ</p>
                                        <img src="${product.ImageURL}" class="img-fluid" alt="${product.ProductName}">
                                    </div>
                                `;
                productList.appendChild(productItem);
              });
            } catch (error) {
              console.error("Error parsing JSON:", error, text);
            }
          })
          .catch((error) => console.error("Error fetching data:", error));
      }
    });
  });
});
