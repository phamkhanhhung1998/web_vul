<?php
session_start();
//$conn = mysqli_connect("localhost", "root", "", "test");
include './connect/conn.php';
if (!$conn) {
    die("Kết nối đến cơ sở dữ liệu thất bại: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    // $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    //$password_hash = password_hash($password, PASSWORD_BCRYPT);
    $fullname = mysqli_real_escape_string($conn, $_POST['full_name']);
    // Kiểm tra email đã tồn tại chưa
    $sql = "SELECT id FROM user WHERE user_name='$username'";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) > 0) {
        echo "Email đã tồn tại. Vui lòng chọn email khác.";
    } else {
        // Thêm người dùng mới vào cơ sở dữ liệu
        $sql = "INSERT INTO user (user_name, password,full_name, isadmin) VALUES ('$username','$password','$fullname',0)";
        
        if (mysqli_query($conn, $sql)) {
            echo "<p style=\"text-align:center\">Đăng ký thành công! Bạn có thể <a href='login.php'>đăng nhập</a> ngay bây giờ. </p>";
        } else {
            echo "Lỗi: " . mysqli_error($conn);
        }
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Ký</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        h2 {
            text-align: center;
        }
        form {
            max-width: 400px;
            margin: auto;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 10px;
            background-color: #f9f9f9;
        }
        input[type="text"], input[type="email"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 5px 0 20px 0;
            display: inline-block;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            width: 100%;
            background-color: #4CAF50;
            color: white;
            padding: 14px 20px;
            margin: 8px 0;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <h2>Đăng Ký</h2>
    <form method="post" action="register.php">
        <label for="username">Username</label><br>
        <input type="text" id="username" name="username" required><br>
        <label for="full_name">Full name</label><br>
        <input type="text" id="full_name" name="full_name" required><br>
        <!-- <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" required><br> -->
        <label for="password">Password</label><br>
        <input type="password" id="password" name="password" required><br>
        <input type="submit" value="Đăng Ký">
    </form>
</body>
</html>
