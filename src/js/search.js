// Tìm kiếm cơ bản với JavaScript
// Hàm chuẩn hóa chuỗi, loại bỏ dấu tiếng Việt
function removeVietnameseTones(str) {
  return str
    .normalize("NFD")
    .replace(/[\u0300-\u036f]/g, "") // Xóa dấu
    .replace(/đ/g, "d")
    .replace(/Đ/g, "D")
    .toLowerCase(); // Chuyển về chữ thường
}

const searchProduct = () => {
  const product = [
    { name: "Cây Phát Tài", price: 750000, status: "còn hàng" },
    { name: "Cây Kim Ngân", price: 280000, status: "còn hàng" },
    { name: "Cây Trầu Bà", price: 120000, status: "còn hàng" },
    { name: "Cây Lan Chi", price: 120000, status: "còn hàng" },
    { name: "Cây Trầu Bà Đỏ", price: 320000, status: "hết hàng" },
    { name: "Cây Lưỡi Hổ", price: 750000, status: "còn hàng" },
    { name: "Cây Hạnh Phúc", price: 1200000, status: "hết hàng" },
    { name: "Cây Trầu Bà Lớn", price: 1100000, status: "còn hàng" },
    { name: "Cây Phát Tài DORADO", price: 220000, status: "còn hàng" },
    { name: "Cây Vạn Lộc", price: 1150000, status: "còn hàng" },
    { name: "Cây Ngọc Vừng", price: 1750000, status: "còn hàng" },
  ];

  let inputValue = document.getElementById("searchInput").value.trim();
  let resultDiv = document.getElementById("result");

  if (!inputValue) {
    resultDiv.innerHTML =
      "<span style='color:red;'>Vui lòng nhập tên sản phẩm!</span>";
    return;
  }

  // Chuẩn hóa input
  let normalizedInput = removeVietnameseTones(inputValue);

  // Tìm kiếm tất cả sản phẩm chứa từ khóa nhập vào
  const results = product.filter((item) =>
    removeVietnameseTones(item.name).includes(normalizedInput)
  );

  if (results.length > 0) {
    resultDiv.innerHTML = `<strong>Kết quả tìm kiếm:</strong> <br>`;
    results.forEach((item) => {
      resultDiv.innerHTML += `
                        <div class="product-item">
                            <strong>Tên:</strong> ${item.name} <br>
                            <strong>Giá:</strong> ${item.price.toLocaleString()} VNĐ <br>
                            <strong>Trạng thái:</strong> ${item.status}
                        </div>
                    `;
    });
  } else {
    resultDiv.innerHTML =
      "<span style='color:red;'>Không tìm thấy sản phẩm.</span>";
  }
};

// Lắng nghe sự kiện Enter trên ô input
document
  .getElementById("searchInput")
  .addEventListener("keydown", function (event) {
    if (event.key === "Enter") {
      event.preventDefault(); // Ngăn form bị reload
      searchProduct();
    }
  });
