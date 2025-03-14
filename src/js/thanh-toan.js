document.addEventListener("DOMContentLoaded", function () {
  const defaultInformation = document.getElementById(
    "default-information-form"
  );
  const newInformation = document.getElementById("new-information-form");
  const defaultInformationButton = document.getElementById(
    "default-information"
  );
  const newInformationButton = document.getElementById("new-information");
  // Thẻ visa
  const visaCard = document.getElementById("visa-card");
  const visaForm = document.getElementById("banking-form");

  let isVisaFormVisible = false; // Biến lưu trạng thái

  visaCard.addEventListener("click", function () {
    isVisaFormVisible = !isVisaFormVisible; // Đảo trạng thái
    visaForm.style.display = isVisaFormVisible ? "block" : "none";
  });
  // Ẩn hiện icon visa
  const cardType = document.getElementById("card-type");
  const codButton = document.getElementById("cod-button");
  const bankingButton = document.getElementById("banking-button");
  function toggleCardType() {
    if (bankingButton.checked) {
      cardType.style.display = "block";
    } else if (codButton.checked) {
      cardType.style.display = "none";
    }
  }
  codButton.addEventListener("change", toggleCardType);
  bankingButton.addEventListener("change", toggleCardType);
  toggleCardType();

  // Hàm hiển thị form tùy chọn thông tin
  function toggleForm() {
    if (defaultInformationButton.checked) {
      defaultInformation.style.display = "flex";
      newInformation.style.display = "none";
    } else if (newInformationButton.checked) {
      defaultInformation.style.display = "none";
      newInformation.style.display = "flex";
    }
  }

  // Lắng nghe sự kiện thay đổi của radio buttons
  defaultInformationButton.addEventListener("change", toggleForm);
  newInformationButton.addEventListener("change", toggleForm);

  // Gọi hàm ngay khi trang tải để kiểm tra trạng thái ban đầu
  toggleForm();
});
