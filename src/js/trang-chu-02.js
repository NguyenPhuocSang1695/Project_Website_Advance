const products = [
  { name: "Cây phát tài", price: "750.000 vnđ", image: "./img/CAY5.jpg" },
  { name: "Cây kim ngân", price: "280.000 vnđ", image: "./img/CAY6.jpg" },
  { name: "Cây trầu bà", price: "120.000 vnđ", image: "./img/CAY7.jpg" },
  { name: "Cây lan chi", price: "120.000 vnđ", image: "./img/CAY8.jpg" },
  {
    name: "Cây trầu bà đỏ",
    price: "320.000 vnđ",
    image: "./img/CAY9.jpg",
  },
  { name: "Cây lưỡi hổ", price: "750.000 vnđ", image: "./img/CAY10.jpg" },
  {
    name: "Cây lưỡi hổ vàng",
    price: "160.000 vnđ",
    image: "./img/CAY11.jpg",
  },
  {
    name: "Cây hạnh phúc",
    price: "1.200.000 vnđ",
    image: "./img/CAY12.jpg",
  },
  {
    name: "Cây trầu bà châu lớn",
    price: "1.100.000 vnđ",
    image: "./img/CAY13.jpg",
  },
  {
    name: "Cây phát tài DORADO",
    price: "220.000 vnđ",
    image: "./img/CAY14.jpg",
  },
  {
    name: "Cây vạn lộc",
    price: "1.150.000 vnđ",
    image: "./img/CAY16.jpg",
  },
  {
    name: "Cây ngọc vừng",
    price: "1.750.000 vnđ",
    image: "./img/CAY17.jpg",
  },
];

// Filter products based on search input
function filterProducts02() {
  const input = document.getElementById("searchInput");
  const productList = document.getElementById("productList");
  const searchTerm = input.value.toLowerCase();

  // Filter products based on search term
  const filteredProducts = products.filter((product) =>
    product.name.toLowerCase().includes(searchTerm)
  );

  // Clear the existing product list
  productList.innerHTML = "";

  // Add filtered products to the list
  filteredProducts.forEach((product) => {
    const productItem = document.createElement("div");
    productItem.classList.add("product");
    productItem.innerHTML = `
    <img src="${product.image}" alt="${product.name}">
    <div class="p-details">
      <h2>${product.name}</h2>
      <h3>${product.price}</h3>
    </div>
  `;

    // Add the event listener to each product
    productItem.addEventListener("click", function () {
      window.location.href = "san-pham1.html"; // Chuyển hướng khi click vào sản phẩm
    });

    // Append product item to the product list
    productList.appendChild(productItem);
  });

  // Show or hide the product list based on input
  if (searchTerm.trim() !== "") {
    productList.style.display = "block"; // Show the list when there's a search term
  } else {
    productList.style.display = "none"; // Hide the list if no search term
  }
}

// Add click event listener to document
document.addEventListener("click", function (event) {
  const productList = document.getElementById("productList");
  const searchInput = document.getElementById("searchInput");

  // Hide product list if click is outside productList and searchInput
  if (!productList.contains(event.target) && event.target !== searchInput) {
    productList.style.display = "none";
  }
});

function handleSearchEnter(event) {
  // Check if the pressed key is Enter
  if (event.key === "Enter") {
    // Prevent the default form submission behavior
    event.preventDefault();
    // Navigate to the phan-loai page
    window.location.href = "./user/phan-loai1.html";
  }
}
