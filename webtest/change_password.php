<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đổi mật khẩu</title>
    <style>
        .container {
            max-width: 1170px;
            width: 100%;
            padding-left: 15px;
            padding-right: 15px;
            margin: auto;
            padding-bottom:15px;
            border-radius: 15px;
            border: 2px solid rgb(137, 247, 192);
        }
        .row {
            display: flex;
            button {
                background-color: rgb(13, 171, 108);
                width: 150px;
                height: 40px;
                border-radius: 10px;
                margin-right:20px;
            }
            button:hover{
                background-color: aquamarine;
            }
        }
        .col-3 {
            flex: 0 0 25%;
            max-width: 25%;
        }
        .col-6 {
            flex: 0 0 50%;
            max-width: 50%;
            
        }
        .left {
            width: 30%;
            padding-top: 10px;
            font-size: 20px;
        }
        .right {
            input {
                width: 300px;
                height: 40px;
                margin-bottom: 10px;
                font-size: 14px;
                font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
                font-weight: 500;
                border-radius: 20px;
                padding-left: 20px;
            }
        }
    </style>
</head>
<body>
<div class="container">
      <div class="row">
        <div class="col-3"></div>
          <div class="col-6">
            <h1>ĐỔI MẬT KHẨU</h1>
              <form action="change_password.php" method="post">
                <div class="row">
                  <div class="left">
                    Mật khẩu cũ
                  </div>
                  <div class="right">
                    <input type="password" name="old_pw">
                  </div>
                </div>
                <div class="row">
                  <div class="left">
                    Mật khẩu mới
                  </div>
                  <div class="right">
                    <input type="password" name="new_pw">
                  </div>
                </div>
                <div class="row">
                  <button name="change_btn">Xác nhận</button>
                  <button name="cancel">Quay lại</button>
                </div>    
                    
              </form>
        </div>
      </div>
    </div>
</body>
</html>

<?php 
    session_start();
    include "connect.php";
    if(isset($_POST['change_btn'])) {
        $oldpw=$_POST['old_pw'];
        $newpw=$_POST['new_pw'];

        $username = $_SESSION["username"];
        //kiểm tra mật khẩu cũ 
        //dùng prepare statement và bind parameter
        $check_query = "SELECT password FROM users_acc WHERE username = ?";
        if($conn) {
            $stmt = $conn->prepare($check_query);
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $stored_pw = $row["password"];
                
                if($oldpw == $stored_pw) {
                    // Cập nhật mật khẩu mới
                    $update_query = "UPDATE users_acc SET password = ? WHERE username = ?";
                    $stmt = $conn->prepare($update_query);
                    $stmt->bind_param("ss", $newpw, $username);
                    if($stmt->execute()) {
                        header('location:relogin.php');
                        //exit();
                    } 
                    else echo "Có lỗi xảy ra. Vui lòng thử lại!" . $conn->error;
                } 
                else echo "Mật khẩu cũ không chính xác";
            } 
            else echo "Không tìm thấy người dùng";
        }
        unset($_SESSION['username']);
    }
    if(isset($_POST['cancel'])) {
        header('location:newfeed.php');
        //exit();
    }





        /*$check_query = "SELECT password from users_acc WHERE username = '$username' ";
        if($conn) {
            $check_result = $conn->query($check_query);
            //var_dump($check_result);
            if($check_result->num_rows > 0) {
                $row = $check_result->fetch_assoc();
                $stored_pw = $row["password"];
                
                if($oldpw == $stored_pw) {
                    $update_query = "UPDATE users_acc SET password = '$newpw' WHERE username = '$username' ";
                    if($conn->query($update_query)===TRUE) header('location:relogin.php');
                    else echo "Có lỗi xảy ra. Vui lòng thử lại!" . $conn->error;
                }
                else echo "Mật khẩu cũ không chính xác";
            }
            else echo "Không tìm thấy người dùng";
        }
        unset($_SESSION['username']);
    }
    if(isset($_POST['cancel'])) {
        header('location:newfeed.php');
    }*/
?>