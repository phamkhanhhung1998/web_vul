<?php
// Bắt đầu session
session_start();

// Kiểm tra xem người dùng đã đăng nhập chưa
if (isset($_SESSION['user_id'])) {
    // Người dùng đã đăng nhập, lấy user_id từ session
    $user_id = $_SESSION['user_id'];
    $username = $_SESSION['username'];
    echo $username;
    echo $user_id;
} else {
    // Người dùng chưa đăng nhập, không thực hiện gì cả hoặc chuyển hướng đến trang đăng nhập
    header("Location: login.php");
    exit(); // Dừng xử lý tiếp tục
}

// Kiểm tra xem phương thức gửi dữ liệu là POST hay không
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Kiểm tra xem các trường cần thiết đã được gửi hay không
    if (isset($_POST['product_id']) && isset($_POST['comment'])) {
        // Lấy dữ liệu từ form
        $product_id = $_POST['product_id'];
        $comment = $_POST['comment'];
        
        // Kết nối đến cơ sở dữ liệu
        include './connect/conn.php';
        
        // Kiểm tra kết nối
        if (!$conn) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . mysqli_connect_error());
        }

        // Kiểm tra xem người dùng đã bình luận về sản phẩm này chưa
        $check_sql = "SELECT * FROM comments WHERE product_id = '$product_id' AND user_id = '$user_id'";
        $check_result = mysqli_query($conn, $check_sql);

        if (mysqli_num_rows($check_result) > 0) {
            // Người dùng đã bình luận về sản phẩm này, thông báo cho họ
            header("Location: product.php?product_id=$product_id");
            echo "Bạn đã bình luận về sản phẩm này rồi.";
        } else {
            // Chuẩn bị truy vấn SQL để thêm bình luận vào bảng comments
            $sql = "INSERT INTO comments (product_id, user_id, comment) VALUES ('$product_id', '$user_id', '$comment')";

            // Thực thi truy vấn
            if (mysqli_query($conn, $sql)) {
                // Bình luận đã được thêm thành công, có thể thực hiện các hành động khác tùy thuộc vào yêu cầu của bạn
                // Ví dụ: chuyển hướng người dùng đến trang chi tiết sản phẩm
                header("Location: product.php?product_id=$product_id");
                exit(); // Dừng xử lý tiếp tục
            } else {
                echo "Lỗi: " . $sql . "<br>" . mysqli_error($conn);
                ;
            }
        }

        // Đóng kết nối
        mysqli_close($conn);
    } else {
        echo "Vui lòng nhập đầy đủ thông tin bình luận.";
    }
} else {
    // Nếu phương thức gửi dữ liệu không phải là POST, chuyển hướng người dùng đến trang chi tiết sản phẩm
    header("Location: product.php?product_id=$product_id");
    exit(); // Dừng xử lý tiếp tục
}
?>
