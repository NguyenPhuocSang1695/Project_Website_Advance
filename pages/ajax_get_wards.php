<?php 
    header('Content-Type: application/json');
    $conn = new mysqli("localhost", "root", "", "c01db");
    if ($conn->connect_error) {
      die("Kết nối thất bại: " . $conn->connect_error);
    }
    $district_id = $_GET['district_id'];

    // echo $district_id;
    
    $sql = "SELECT * FROM `wards` WHERE `district_id` = {$district_id}";
    $result = mysqli_query($conn, $sql);


    $data[0] = [
        'id' => null,
        'name' => 'Chọn một xã/phường'
    ];

    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = [
            'id' => $row['wards_id'],
            'name'=> $row['name']
        ];
    }
    echo json_encode($data);
?>