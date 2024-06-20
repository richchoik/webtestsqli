<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký</title>
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
            <h1>ĐĂNG KÝ</h1>
              <form action="signup.php" method="post">
                <div class="row">
                  <div class="left">
                    Họ và tên
                  </div>
                  <div class="right">
                    <input type="text" name="accname">
                  </div>
                </div>
                <div class="row">
                  <div class="left">
                    Tên đăng nhập
                  </div>
                  <div class="right">
                    <input type="text" name="username">
                  </div>
                </div>
                <div class="row">
                  <div class="left">
                    Mật khẩu
                  </div>
                  <div class="right">
                    <input type="password" name="password">
                  </div>
                </div>
                <div class="row">
                  <button name="signup_btn">Đăng ký</button>
                </div>
                <div class="row">
                  <div class="left">
                    Bạn đã có tài khoản?
                  </div>
                  <div class="right">
                    <button name="login_btn">Đăng nhập</button>
                  </div>
                </div>    
                    
              </form>
        </div>
      </div>
    </div>
</body>
</html>

<?php 
    include "connect.php";
    function generateUniqueId() {
        return uniqid(); // tạo một chuỗi ngẫu nhiên không trùng lặp
    }

    function isUserIdExists($conn, $user_id) {
        $query = "SELECT * FROM users_acc WHERE user_id = '$user_id'";
        $result = mysqli_query($conn, $query);
        $count = mysqli_num_rows($result);
        return ($count > 0); // Trả về true nếu `user_id` đã tồn tại, ngược lại trả về false
    }

    function isValid($input) {
        // Biểu thức chính quy kiểm tra xem chuỗi có chỉ chứa chữ cái in hoa, in thường, số và dấu chấm không (dành cho username)
        return preg_match('/^[a-zA-Z0-9.]+$/', $input);
    }
    function isHoTen($input) {
        //Biểu thức chính quy kiểm tra tính hợp lệ của họ và tên, cho phép chữ cái có dấu và khoảng trắng
        return preg_match('/^[a-zA-ZÀ-ỹ\s]+$/', $input);
    }

    if(isset($_POST['login_btn'])) {
        header('location:index.php');
    }

    // Dùng prepare statement là một cách an toàn để thực hiện các truy vấn sql vì nó tách biệt giữa câu sql và dữ liệu người dùng
    // thay vì tạo câu truy vấn sql bằng cách nối chuỗi với dữ liệu người dùng, ta chỉ cần cung cấp các placeholder (?) trong câu 
    // truy vấn và sau đó gán các giá trị cho các placeholder đó. Điều này giúp tránh được việc chèn dữ liệu người dùng trực tiếp vào 
    //câu truy vấn SQL, từ đó ngăn chặn SQL injection
    function createUserAccount($conn, $accname, $username, $password) {
        $stmt = $conn->prepare("INSERT INTO users_acc (user_id, name, username, password) VALUES (?, ?, ?, ?)");
        $user_id = generateUniqueId(); // Tạo user_id mới
        while (isUserIdExists($conn, $user_id)) {
            $user_id = generateUniqueId(); // Nếu đã tồn tại, tạo user_id mới
        }
        $stmt->bind_param("ssss", $user_id, $accname, $username, $password);
        if ($stmt->execute()) {
            // Hiển thị thông báo thành công
            echo "Tạo tài khoản thành công, vui lòng đăng nhập lại";
            echo "<form action='index.php' method='post'>";
            echo "<button name='login_btn'>Đăng nhập</button>";
            echo "</form>";
            if (isset($_POST['login_btn'])) header("location:index.php");
        } else {
            // Nếu có lỗi khi thêm vào cơ sở dữ liệu, hiển thị thông báo lỗi
            echo "Đã xảy ra lỗi: " . $conn->error;
        }
    }

    if(isset($_POST['signup_btn'])) {
        $accname = $_POST['accname'];
        $username = $_POST['username'];
        $password = $_POST['password'];
        
        $accname = trim($accname);//loại bỏ khoảng trắng ở đầu và cuối xâu

        if(!isHoTen($accname)) {
            echo "Họ và tên không được chứa ký tự đặc biệt";
        }
        else if(!isValid($username) || !isValid($password)) {
            echo "Tên người dùng và mật khẩu chỉ được chứa chữ cái in hoa, in thường, số và dấu chấm.";
        }
        else {
            /*$query = "SELECT * FROM users_acc WHERE BINARY(username) = '$username'";
            $result = mysqLi_query($conn, $query);
            $count = mysqli_num_rows($result);
            if($count>0) echo "Tên đăng nhập đã tồn tại";
            else {
                $user_id = generateUniqueId();//tạo id mới
                while(isUserIdExists($conn, $user_id)) { // Kiểm tra xem user_id đã tồn tại chưa
                    $user_id = generateUniqueId(); // Nếu đã tồn tại, tạo user_id mới
                    $_SESSION['user_id']=$user_id;
                }
                $insert_query = "INSERT INTO users_acc (user_id, name, username, password) VALUES ('$user_id', '$accname', '$username', '$password')";
                if (mysqli_query($conn, $insert_query)) {
                    // Hiển thị thông báo thành công
                    echo "Tạo tài khoản thành công, vui lòng đăng nhập lại";
                    echo "<form action='index.php' method='post'>";
                    echo "<button name='login_btn'>Đăng nhập</button>";
                    echo "</form>";
                    if(isset($_POST['login_btn'])) header("location:index.php");
                } 
                else {
                    // Nếu có lỗi khi thêm vào cơ sở dữ liệu, hiển thị thông báo lỗi
                    echo "Đã xảy ra lỗi: " . mysqli_error($conn);
                }
            }*/
            $query = "SELECT * FROM users_acc WHERE BINARY(username) = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                echo "Tên đăng nhập đã tồn tại";
            } 
            else {
                createUserAccount($conn, $accname, $username, $password);
            }
        }
        
    }
?>