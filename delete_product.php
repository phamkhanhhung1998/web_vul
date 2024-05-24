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

// Xóa sản phẩm khỏi cơ sở dữ liệu
$product_id = $_GET['product_id'];
$sql = "DELETE FROM products WHERE id = $product_id";

if (mysqli_query($conn, $sql)) {
    echo "Sản phẩm đã được xóa thành công.";
    header("Location: admin_product.php");
} else {
    echo "Lỗi: " . mysqli_error($conn);
}

mysqli_close($conn);
?>
