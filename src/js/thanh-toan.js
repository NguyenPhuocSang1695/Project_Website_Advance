document.addEventListener("DOMContentLoaded", function () {
  const defaultInformation = document.getElementById(
    "default-information-form"
  );
  const newInformation = document.getElementById("new-information-form");
  const defaultInformationButton = document.getElementById(
    "default-information"
  );
  const newInformationButton = document.getElementById("new-information");

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
