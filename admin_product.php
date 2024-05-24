<?php
// Bắt đầu session
session_start();

// Kiểm tra xem người dùng đã đăng nhập với quyền admin chưa
if(!isset($_SESSION['user_id']) || $_SESSION['isadmin'] != 1) {
    echo "Bạn không có quyền truy cập trang này.";
    exit();
}

// Kết nối đến cơ sở dữ liệu
//$conn = mysqli_connect("localhost", "root", "", "test");
include './connect/conn.php';
// Kiểm tra kết nối
if (!$conn) {
    die("Kết nối đến cơ sở dữ liệu thất bại: " . mysqli_connect_error());
}

// Truy vấn lấy danh sách sản phẩm
$sql = "SELECT * FROM products";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh Sách Sản Phẩm</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        h2 {
            text-align: center;
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
        li {
            padding: 10px;
            border-bottom: 1px solid #ccc;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        a {
            text-decoration: none;
            margin-left: 10px;
            padding: 5px 10px;
            border-radius: 4px;
            color: white;
        }
        .edit-btn {
            background-color: #4CAF50;
        }
        .edit-btn:hover {
            background-color: #45a049;
        }
        .delete-btn {
            background-color: #f44336;
        }
        .delete-btn:hover {
            background-color: #e53935;
        }
    </style>
</head>
<body>
    <h2>Danh Sách Sản Phẩm</h2>
    <?php
    if (mysqli_num_rows($result) > 0) {
        echo "<ul>";
        while($row = mysqli_fetch_assoc($result)) {
            echo "<li>";
            echo "<span>" . $row['name'] . "</span>";
            echo "<span>";
            echo "<a href='update_product.php?product_id=" . $row['id'] . "' class='edit-btn'>Sửa</a>";
            echo "<a href='delete_product.php?product_id=" . $row['id'] . "' class='delete-btn' onclick=\"return confirm('Bạn có chắc chắn muốn xóa sản phẩm này không?');\">Xóa</a>";
            echo "</span>";
            echo "</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>Không có sản phẩm nào.</p>";
    }

    // Đóng kết nối
    mysqli_close($conn);
    ?>
</body>
</html>