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
        alert(data.message || "Sản phẩm đã được thêm thành công!");
        document.getElementById("productForm").reset(); // Reset form
        // Ẩn overlay sau khi thêm thành công
        document.getElementById("addProductOverlay").style.display = "none";
      } else {
        alert(data.message || "Có lỗi xảy ra hoặc sản phẩm đã tồn tại. Vui lòng thử lại.");
      }
    })
    .catch((error) => {
      console.error("Lỗi:", error);
      alert("Có lỗi xảy ra. Vui lòng thử lại.");
    });
});
