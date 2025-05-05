<?php
// Ensure no errors are output in the response
error_reporting(0);
ini_set('display_errors', 0);

header('Content-Type: application/json');

try {
    // Database connection
    $conn = new mysqli("localhost", "c01u", "KtdVb9kNDRutbwFB", "c01db");

    if ($conn->connect_error) {
        throw new Exception('Connection failed: ' . $conn->connect_error);
    }

    // Get and validate form data
    if (!isset($_POST['productId']) || !isset($_POST['productName']) || !isset($_POST['categoryID']) || 
        !isset($_POST['price']) || !isset($_POST['description'])) {
        throw new Exception('Missing required fields');
    }

    $productId = (int)$_POST['productId'];
    $productName = trim($_POST['productName']);
    $categoryId = (int)$_POST['categoryID'];
    $price = (float)$_POST['price'];
    $description = trim($_POST['description']);
    $status = $_POST['status'] ?? 'appear';

    if (empty($productName)) {
        throw new Exception('Product name cannot be empty');
    }

    if ($price <= 0) {
        throw new Exception('Price must be greater than 0');
    }

    if (!in_array($status, ['hidden', 'appear'])) {
        throw new Exception('Invalid status value');
    }

    // First, get the current image URL
    $currentImageQuery = "SELECT ImageURL FROM products WHERE ProductID = ?";
    $stmt = $conn->prepare($currentImageQuery);
    if (!$stmt) {
        throw new Exception('Failed to prepare current image query: ' . $conn->error);
    }
    
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $result = $stmt->get_result();
    $currentImageData = $result->fetch_assoc();
    
    if (!$currentImageData) {
        throw new Exception('Product not found');
    }
    
    $currentImageURL = $currentImageData['ImageURL'];
    $stmt->close();

    $newImageURL = $currentImageURL; // Default to current image if no new one is uploaded

    // Handle new image upload if provided
    if (isset($_FILES['imageURL']) && $_FILES['imageURL']['error'] === 0) {
        $file = $_FILES['imageURL'];
        
        // Validate file
        $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
        $maxSize = 2 * 1024 * 1024; // 2MB
        
        if (!in_array($file['type'], $allowedTypes)) {
            throw new Exception('Invalid file type. Only JPG, JPEG and PNG allowed.');
        }
        
        if ($file['size'] > $maxSize) {
            throw new Exception('File size too large. Maximum 2MB allowed.');
        }
        
        // Generate unique filename
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'product_' . uniqid() . '.' . $extension;
        $uploadPath = '../../assets/images/' . $filename;
        
        // Make sure the directory exists
        $uploadDir = dirname($uploadPath);
        if (!is_dir($uploadDir)) {
            if (!mkdir($uploadDir, 0777, true)) {
                throw new Exception('Failed to create upload directory');
            }
        }
        
        // Move uploaded file
        if (!move_uploaded_file($file['tmp_name'], $uploadPath)) {
            throw new Exception('Failed to upload image');
        }
        
        $newImageURL = '/assets/images/' . $filename;
        
        // Delete old image if it exists and is different
        if ($currentImageURL && $currentImageURL !== $newImageURL) {
            $oldImagePath = '../../' . $currentImageURL;
            if (file_exists($oldImagePath)) {
                unlink($oldImagePath);
            }
        }
    }

    // Update product in database
    $sql = "UPDATE products SET 
            ProductName = ?,
            CategoryID = ?,
            Price = ?,
            Description = ?,
            Status = ?,
            ImageURL = ?
            WHERE ProductID = ?";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception('Failed to prepare update query: ' . $conn->error);
    }

    $stmt->bind_param("sidsssi", $productName, $categoryId, $price, $description, $status, $newImageURL, $productId);

    if (!$stmt->execute()) {
        throw new Exception('Failed to update product: ' . $stmt->error);
    }

    if ($stmt->affected_rows === 0) {
        throw new Exception('No changes were made to the product');
    }

    echo json_encode([
        'success' => true,
        'message' => 'Product updated successfully',
        'productId' => $productId
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
} finally {
    if (isset($stmt)) {
        $stmt->close();
    }
    if (isset($conn)) {
        $conn->close();
    }
}
?>