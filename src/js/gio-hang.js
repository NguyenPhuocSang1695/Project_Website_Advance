// Hàm thay đổi số lượng và tự động submit form để cập nhật lên database
function changeQuantity(button, delta) {
  // Lấy phần tử input chứa số lượng
  let quantityInput = button.parentNode.querySelector(".quantity-input");

  // Lấy giá trị hiện tại của số lượng
  let currentQuantity = parseInt(quantityInput.value, 10);

  // Tính toán số lượng mới
  let newQuantity = currentQuantity + delta;

  // Kiểm tra số lượng mới không nhỏ hơn 1
  if (newQuantity > 0) {
    // Cập nhật giá trị số lượng trong ô input
    quantityInput.value = newQuantity;

    // Gửi form để cập nhật giỏ hàng ngay lập tức
    quantityInput.form.submit();
  }
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
