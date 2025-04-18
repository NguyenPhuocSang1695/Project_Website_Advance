<?php
require_once '../../php-api/connectdb.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $conn = connect_db();

    // Lấy dữ liệu từ form
    $productName = $_POST['productName'];
    $categoryID = $_POST['categoryID'];
    $price = $_POST['price'];
    $description = $_POST['description'];

    // Mặc định
    $status = 'appear';
    $isPurchase = 0;

    // Xử lý ảnh (chỉ 1 ảnh, vì input không phải multiple)
    $imageRelativeURL = "";

    if (isset($_FILES['imageURL']) && $_FILES['imageURL']['error'] === UPLOAD_ERR_OK) {
        $targetDir = "../../assets/images/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $originalName = basename($_FILES["imageURL"]["name"]);
        $fileExtension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

        // Tạo tên file mới
        $newFileName = uniqid("product_") . "." . $fileExtension;
        $targetFilePath = $targetDir . $newFileName;

        // Di chuyển ảnh vào thư mục đích
        if (move_uploaded_file($_FILES["imageURL"]["tmp_name"], $targetFilePath)) {
            $imageRelativeURL = "/assets/images/" . $newFileName;
        } else {
            echo json_encode(["success" => false, "message" => "Không thể lưu ảnh."]);
            exit;
        }
    } else {
        echo json_encode(["success" => false, "message" => "Ảnh không hợp lệ hoặc chưa được chọn."]);
        exit;
    }

    // Thêm vào cơ sở dữ liệu
    $sql = "INSERT INTO products (ProductName, CategoryID, Price, Description, ImageURL, Status, IsPurchase)
            VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("siisssi", $productName, $categoryID, $price, $description, $imageRelativeURL, $status, $isPurchase);



    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "message" => "Lỗi khi thêm sản phẩm: " . $stmt->error]);
    }

    $stmt->close();
    $conn->close();
}

// Giữ nguyên tên củ của file ảnh tải lên và xử lý tránh ghi đè file cũ
// $newFileName = $originalName; // giữ nguyên tên file
// $targetFilePath = $targetDir . $originalName;
// if (file_exists($targetFilePath)) {
//     // Nếu tên đã tồn tại, thêm timestamp vào tên để tránh ghi đè
//     $newFileName = pathinfo($originalName, PATHINFO_FILENAME) . "_" . time() . "." . $fileExtension;
//     $targetFilePath = $targetDir . $newFileName;
// } else {
//     $newFileName = $originalName;
// }
// $imageRelativeURL = "/assets/images/" . $newFileName;
