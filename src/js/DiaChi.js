<script src="../src/js/jquery.js"></script>;

$(document).ready(function () {
  $("#province").on("change", function () {
    var province_id = $(this).val();
    if (province_id) {
      $.ajax({
        url: "get_district.php",
        data: { province_id: province_id },
        dataType: "json",
        success: function (data) {
          $("#district").empty();
          $.each(data, function (i, district) {
            $("#district").append(
              $("<option>", { value: district.id, text: district.name })
            );
          });
          $("#ward").empty();
        },
      });
    }
  });

  $("#district").on("change", function () {
    var district_id = $(this).val();
    if (district_id) {
      $.ajax({
        url: "get_wards.php",
        data: { district_id: district_id },
        dataType: "json",
        success: function (data) {
          $("#ward").empty();
          $.each(data, function (i, ward) {
            $("#ward").append(
              $("<option>", { value: ward.id, text: ward.name })
            );
          });
        },
      });
    }
  });
});
