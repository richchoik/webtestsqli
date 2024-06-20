<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đổi mật khẩu</title>
</head>
<body>
    Đổi mật khẩu thành công. Vui lòng đăng nhập lại
    <form method="post">
        <button name="relogin_btn">Đăng nhập</button>
    </form>
</body>
</html>

<?php
    if(isset($_POST['relogin_btn'])) {
        header('location:index.php');
    }
?>