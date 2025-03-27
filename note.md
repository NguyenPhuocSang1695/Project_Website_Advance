sửa lại đoạn này ở các file dòng 407

<ul class="dropdown-menu">
                <li>
                  <a class="dropdown-item" href="./pages/phan-loai.html?category_id=3">Cây dễ chăm</a>
                </li>
                <li>
                  <a class="dropdown-item" href="./pages/phan-loai.html?category_id=1">Cây văn phòng</a>
                </li>
                <li>
                  <a class="dropdown-item" href="./pages/phan-loai.html?category_id=4">Cây để bàn</a>
                </li>
                <li>
                  <a class="dropdown-item" href="./pages/phan-loai.html?category_id=2">Cây dưới nước</a>
                </li>
              </ul>

<!-- sửa lại sản phẩm mới cho đúng theo phân loại  -->
<!-- Thông tin cây  -->

🌿 Cây văn phòng
Cây 4: Cây lan ý - 180.000đ
Cây 5: Cây phát tài núi - 1.950.000đ
Cây 7: Trầu bà lá xanh - 200.000đ
Cây 11: Cây Thiết Mộc Lan - 1.000.000đ
Cây 41: Cây bàng Singapore - 400.000đ** ✅ [Thêm mới]
Cây 22: Cây trúc Nhật - 250.000đ** ✅ [Thêm mới]
Cây 23: Cây cọ nhật - 650.000đ** ✅ [Thêm mới]
Cây 24: Cây kim tiền - 450.000đ** ✅ [Thêm mới]
Cây 25: Cây ngọc ngân - 300.000đ\*\* ✅ [Thêm mới]

💦 Cây dưới nước
Cây 1: Cây thường xuân - 160.000đ
Cây 2: Cây lá sọc dưa hấu - 85.000đ
Cây 9: Trầu bà đỏ - 300.000đ
Cây 12: Trầu bà vàng - 200.000đ
Cây 17: Tróc bạc - 180.000đ
Cây 26: Cây rong la hán - 90.000đ** ✅ [Thêm mới]
Cây 27: Cây bèo Nhật - 75.000đ ✅ [Thay thế]
Cây 28: Cây dương xỉ nước - 110.000đ** ✅ [Thêm mới]
Cây 29: Cây thủy sinh cỏ thìa - 140.000đ\*\* ✅ [Thêm mới]
Cây 30: Cây thủy cúc - 100.000đ\*\* ✅ [Thêm mới]

🌱 Cây dễ chăm  
Cây 8: Cây Dây nhện - 35.000đ
Cây 10: Lưỡi hổ vàng - 200.000đ
Cây 14: Cây lưỡi hổ xanh - 220.000đ
Cây 18: Lưỡi hổ búp sen - 45.000đ
Cây 13: Thiết mộc lan sọc vàng - 500.000đ
Cây 31: Cây cau tiểu trâm - 70.000đ** ✅ [Thêm mới]
Cây 32: Cây phú quý - 320.000đ** ✅ [Thêm mới]
Cây 33: Cây vạn niên thanh - 250.000đ** ✅ [Thêm mới]
Cây 34: Cây sen đá - 90.000đ** ✅ [Thêm mới]
Cây 35: Cây cỏ lan chi - 50.000đ\*\* ✅ [Thêm mới]

🖥 Cây để bàn
Cây 3: Cây cẩm nhung - 150.000đ
Cây 16: Vạn lộc đỏ - 300.000đ
Cây 19: Cây Pachira aquatica - 250.000đ
Cây 20: Cây môn Hồng - 100.000đ
Cây 6: Cây kim ngân - 100.000đ
Cây 36: Cây bonsai sam hương - 700.000đ** ✅ [Thêm mới]
Cây 37: Cây bạch mã hoàng tử - 270.000đ** ✅ [Thêm mới]
Cây 38: Cây hạnh phúc mini - 400.000đ** ✅ [Thêm mới]
Cây 39: Cây tùng bồng lai - 350.000đ** ✅ [Thêm mới]
Cây 40: Cây trường sinh - 200.000đ\*\* ✅ [Thêm mới]

// <?php
// require_once './connectdb.php'; // Kết nối database
// $conn = connect_db();

// if (isset($_GET['category_id'])) {
//     $category_id = intval($\_GET['category_id']);

// $sql = "SELECT * FROM products WHERE CategoryID = ?";
//     $stmt = $conn->prepare($sql);
// $stmt->bind_param("i", $category_id);
// $stmt->execute();
// $result = $stmt->get_result();

// $products = [];
//     while ($row = $result->fetch_assoc()) {
// // Chuẩn hóa đường dẫn ảnh, chỉ giữ lại phần `/assets/...`
// $row['ImageURL'] = preg_replace('/^(\.\.\/)+/', '/', $row['ImageURL']);

// $products[] = $row;
// }

// echo json_encode($products);
// } else {
// echo json_encode(["error" => "Không tìm thấy sản phẩm!"]);
// }
