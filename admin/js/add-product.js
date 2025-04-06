// Xử lý sự kiện submit form
document.getElementById("productForm").addEventListener("submit", function (e) {
  e.preventDefault();

  let formData = new FormData(this);

  fetch("../php/add-product.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        alert("Sản phẩm đã được thêm thành công!");
        document.getElementById("productForm").reset(); // Reset form
      } else {
        alert("Có lỗi xảy ra. Vui lòng thử lại.");
      }
    })
    .catch((error) => {
      console.error("Lỗi:", error);
      alert("Có lỗi xảy ra. Vui lòng thử lại.");
    });
});
