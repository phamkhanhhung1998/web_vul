<?php
// Bắt đầu session
session_start();
include './connect/conn.php';
// Kiểm tra xem người dùng đã đăng nhập với quyền admin chưa
if(!isset($_SESSION['user_id']) || $_SESSION['isadmin'] != 1) {
    // Nếu không phải admin, có thể chuyển hướng người dùng hoặc hiển thị thông báo lỗi
    echo "Bạn không có quyền truy cập trang này.";
    echo "{$_SESSION['user_id']} và {$_SESSION['isadmin']}";
    exit();
}

// Xử lý việc tạo sản phẩm mới khi người dùng gửi form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Kiểm tra xem các trường cần thiết đã được gửi hay không
    if (isset($_POST['name']) && isset($_POST['price']) && isset($_POST['description']) && isset($_FILES['image'])) {
        // Lấy dữ liệu từ form
        $name = $_POST['name'];
        $price = $_POST['price'];
        $description = $_POST['description'];
        
        // Xử lý ảnh được tải lên
        $target_dir = "uploads/"; // Thư mục lưu trữ ảnh
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        $newFileName = uniqid() . '.' . $imageFileType; // Tạo tên file mới để tránh trùng lặp
        $target_path = $target_dir . $newFileName;    

        // Kiểm tra kích thước tệp ảnh
        if ($_FILES["image"]["size"] > 500000) {
            echo "Sorry, your file is too large.";
            exit();
        }
        
        // Kiểm tra định dạng ảnh
        // if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        // && $imageFileType != "gif" ) {
        //     echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        //     exit();
        // }
        
        // Lưu tệp ảnh vào thư mục
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_path)) {
            // Kết nối đến cơ sở dữ liệu
          //  $conn = mysqli_connect("localhost", "root", "", "test");

            // Kiểm tra kết nối
            if (!$conn) {
                die("Kết nối đến cơ sở dữ liệu thất bại: " . mysqli_connect_error());
            }

            // Chuẩn bị truy vấn SQL để thêm sản phẩm mới vào cơ sở dữ liệu
            $sql = "INSERT INTO products (name, price, description, image_url) VALUES ('$name', '$price', '$description', '$target_path')";

            // Thực thi truy vấn
            if (mysqli_query($conn, $sql)) {
                // Sản phẩm đã được thêm thành công, có thể thực hiện các hành động khác tùy thuộc vào yêu cầu của bạn
                echo "Sản phẩm đã được thêm thành công.";
                header("Location:all_product.php");
            } else {
                echo "Lỗi: " . $sql . "<br>" . mysqli_error($conn);
            }

            // Đóng kết nối
            mysqli_close($conn);
        } else {
            //echo "Sorry, there was an error uploading your file.";
        }
    } else {
        echo "Vui lòng nhập đầy đủ thông tin sản phẩm.";
    }
}
?>