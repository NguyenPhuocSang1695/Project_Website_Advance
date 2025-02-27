document.addEventListener("DOMContentLoaded", function () {
  const inforBanking = document.getElementById("inforBanking");
  const mastercard = document.getElementById("mastercard");

  mastercard.addEventListener("click", function () {
    if (inforBanking.style.display === "flex") {
      inforBanking.style.display = "none";
    } else {
      inforBanking.style.display = "flex";
    }
  });

  const newInformation = document.getElementById("new-information");
  const defaultInformation = document.getElementById("default-information");
  const defaultInformationButton = document.getElementById(
    "use-default-information"
  );
  const newInformationButton = document.getElementById("use-new-informaion");

  defaultInformationButton.addEventListener("click", function () {
    if (defaultInformation.style.display === "flex") {
      defaultInformation.style.display = "none";
      newInformation.style.display = "none";
    } else {
      defaultInformation.style.display = "flex";
      newInformation.style.display = "none";
    }
  });

  newInformationButton.addEventListener("click", function () {
    if (newInformation.style.display === "flex") {
      newInformation.style.display = "none";
      defaultInformation.style.display = "none";
    } else {
      newInformation.style.display = "flex";
      defaultInformation.style.display = "none";
    }
  });
});
