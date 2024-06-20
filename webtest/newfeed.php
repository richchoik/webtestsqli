<?php
    session_start();
    if(!isset($_SESSION['username'])) {
        header('location:index.php');
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Spine</title>
    <link href = "style.css" rel = "stylesheet"/>
    <script src="script.js"></script>
    <style>
        .status {
            border: 2px solid;
            border-radius: 10px;
            width: auto;
            height: auto;
            margin-bottom: 10px;
        }
        .tennd {
            border: 2px solid;
            width: auto;
            height: auto;
            font-weight: bold;
            color: #333;
        }
        .menu_bar {
            form {
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
        }
    </style>
</head>
<body>   
    <div class="menu_bar">
        <form action="newfeed.php" method = "post">
            <button name="logout_btn">Đăng xuất</button>
            <br>
            <button name="change_pw">Đổi mật khẩu</button>
            <br>
            <button name="home_page_btn">Trang chủ</button>
            <br>
            <button name="wall_btn">Trang cá nhân</button>
            <br>
            <input type="hidden" name="confirm_del_acc" id="confirm_del_acc" value="0">
            <button name="del_acc" onclick="cfDelAcc()">Xóa tài khoản</button>
            <br>
            <input type="hidden" name="name" value="<?php echo isset($_SESSION['name']) ? $_SESSION['name'] : ''; ?>"> <!-- lấy họ tên từ các file kia -->
            <textarea name="searching_area" id="srch_area" onfocus="clearSrch()">Tìm kiếm...</textarea>
            <button name="srch_btn">Search</button>
        </form>
        <?php
            echo "<div class='tennd'>";
            echo $_POST['name'];
            echo "</div>";
        ?>
    </div> 
    <div class="feed">
        <form action="newfeed.php" method="post">
            <input type="hidden" name="user_id" value="<?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : ''; ?>"> <!-- lấy userid từ các file kia -->
            <textarea name="title" id="post_tt" class="post_titl" onfocus="clearTt()">Tiêu đề</textarea>
            <br>
            <textarea name="content" id="post_area" class="post_styl" onfocus="clearText()";>Hãy nói lên suy nghĩ của bạn</textarea>
            <br>
            <button name="up_btn">Đăng</button>
        </form>
        <?php
            include "connect.php";
            if(isset($_POST['home_page_btn']))
            {
                $query="SELECT posts.*, users_acc.name AS author_name 
                        FROM posts 
                        INNER JOIN users_acc ON posts.user_id = users_acc.user_id 
                        ORDER BY posts.create_time DESC";
                $result=mysqli_query($conn,$query);
                if($result)
                {
                    if(mysqli_num_rows($result)>0)
                    {
                        while($row = mysqli_fetch_assoc($result))
                        {
                            echo "<div class='status'>";
                            echo "<h4>" . $row['title'] . "</h4>";
                            echo "<p>" . $row['content'] . "</p>";
                            echo "<p><em>Created at: " . $row['create_time'] . "</em></p>";
                            echo "<p><em>Author: " . $row['author_name'] . "</em></p>";
                            echo "</div>";
                        }
                    }
                }
            }
            if(isset($_POST['wall_btn']))
            {
                $user_id=$_SESSION['user_id'];
                $query = "SELECT * FROM posts WHERE user_id = '$user_id' ORDER BY create_time DESC";
                $result = mysqli_query($conn, $query);
                if($result)
                {
                    if(mysqli_num_rows($result)>0)
                    {
                        while($row = mysqli_fetch_assoc($result))
                        {
                            echo "<div class='status'>";
                            echo "<h4>" . $row['title'] . "</h4>";
                            echo "<p>" . $row['content'] . "</p>";
                            echo "<p><em>Created at: " . $row['create_time'] . "</em></p>";
                            // Nút chỉnh sửa
                            echo "<form action='edit_post.php' method='post'>";
                            echo "<input type='hidden' name='post_id' value='" . $row['post_id'] . "'>";
                            echo "<button type='submit' name='edit_btn'>Chỉnh sửa</button>";
                            echo "</form>";
                            // Nút xóa
                            echo "<form action='delete_post.php' method='post'>";
                            echo "<input type='hidden' name='post_id' value='" . $row['post_id'] . "'>"; //lưu lại post_id
                            echo "<button type='submit' name='delete_btn'>Xóa</button>";
                            echo "</form>";
                            echo "</div>";
                        }
                    }
                }
            }
            if(isset($_POST['srch_btn'])) 
            {
                $keyword = mysqli_real_escape_string($conn, $_POST['searching_area']);
                $keyword = '%' . $keyword . '%';
                $query = "SELECT posts.*, users_acc.name AS author_name 
                          FROM posts 
                          INNER JOIN users_acc ON posts.user_id = users_acc.user_id 
                          WHERE title LIKE ? OR content LIKE ?";
                $stmt = mysqli_prepare($conn, $query);
                mysqli_stmt_bind_param($stmt, "ss", $keyword, $keyword);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);

                if($result)
                {
                    if(mysqli_num_rows($result) > 0) 
                    { 
                        while($row = mysqli_fetch_assoc($result)) 
                        {
                            echo "<div class='status'>";
                            foreach ($row as $key => $value) 
                            {
                                if ($key == 'title') {
                                    echo "<h4>{$value}</h4>";
                                } 
                                else {
                                    echo "<p><em>{$key}: {$value}</em></p>";
                                }
                            }
                            echo "</div>";
                        }
                        
                    }
                }
            }
        ?>
    </div>

</body>
</html>

<?php
    if(isset($_POST['logout_btn'])) {
        unset($_SESSION['username']);
        header('location:index.php');
    }
    if(isset($_POST['change_pw'])) {
        //$_SESSION['username']=$username;
        header('location:change_password.php');
    }
    include "connect.php";
    function generateUniqueId() {
        return uniqid(); // tạo một chuỗi ngẫu nhiên không trùng lặp
    }
    function isPostIdExists($conn, $post_id) {
        $query = "SELECT * FROM posts WHERE post_id = '$post_id'";
        $result = mysqli_query($conn, $query);
        $count = mysqli_num_rows($result);
        return ($count > 0); // Trả về true nếu `user_id` đã tồn tại, ngược lại trả về false
    }
    if(isset($_POST['up_btn']))
    {
        $post_id = generateUniqueId();
        while(isPostIdExists($conn, $post_id)) { // Kiểm tra xem user_id đã tồn tại chưa
            $post_id = generateUniqueId(); // Nếu đã tồn tại, tạo user_id mới
        }
        $user_id=$_POST['user_id'];  
        $title = $_POST['title'];
        $content = $_POST['content'];
        $create_time = date('Y-m-d H:i:s');
        if($title == '') $title='Không có tiêu đề';
        if($content == '') echo "Không thể đăng một bài viết không có gì cả";
        else {
            $query = "INSERT INTO posts (post_id, user_id, title, content, create_time) VALUES ('$post_id', '$user_id', '$title', '$content', '$create_time')";
            if ($conn->query($query) == TRUE) echo "Bài viết đã được đăng thành công!";
            else echo "Lỗi";
        }
    }
    if(isset($_POST['confirm_del_acc']) && $_POST['confirm_del_acc'] == "1") {
        $user_id = $_SESSION['user_id'];
        $query = "DELETE FROM users_acc WHERE user_id = '$user_id'";
        if (mysqli_query($conn, $query)) {
            // Xóa thành công, đăng xuất người dùng và quay lại trang index.php
            unset($_SESSION['username']);
            header('location: index.php');
        } 
        else {
            echo "Lỗi khi xóa tài khoản: " . mysqli_error($conn);
        }
    }
?>
