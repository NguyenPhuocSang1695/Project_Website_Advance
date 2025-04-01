// Hàm thay đổi số lượng và tự động submit form để cập nhật lên database
function changeQuantity(button, amount) {
  // Tìm đến form update của nút vừa nhấn
  const form = button.closest(".update-form");
  // Lấy input số lượng trong form
  const input = form.querySelector(".quantity-input");
  let newValue = parseInt(input.value) + amount;
  if (newValue < 1) {
    newValue = 1; // Không cho số lượng nhỏ hơn 1
  }
  // Cập nhật giá trị mới cho input
  input.value = newValue;

  // Tự động submit form để gửi dữ liệu cập nhật lên server
  form.requestSubmit();
}

// Hàm tính lại tổng giỏ hàng
function updateCartTotal() {
  let total = 0;
  // Duyệt qua tất cả các sản phẩm (.order)
  document.querySelectorAll(".order").forEach((order) => {
    const input = order.querySelector(".quantity-input");
    // Lấy giá sản phẩm từ data-price
    const price = parseFloat(input.getAttribute("data-price"));
    const quantity = parseInt(input.value);
    const productTotal = price * quantity;
    total += productTotal;

    // Cập nhật lại giá của sản phẩm trong đơn hàng
    const priceElement = order.querySelector(".price");
    if (priceElement) {
      priceElement.textContent = productTotal.toLocaleString("vi-VN") + " VNĐ";
    }
  });
  // Cập nhật tổng tiền của giỏ hàng
  document.getElementById("total-price").textContent =
    total.toLocaleString("vi-VN") + " VNĐ";
}

// Hàm xử lý submit form cập nhật (nếu cần)
// Bạn có thể thêm kiểm tra hợp lệ trước khi submit form lên server
function updateCart(form) {
  return true;
}
