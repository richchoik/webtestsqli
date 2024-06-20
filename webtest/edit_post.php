<?php
    /*include "connect.php";
    if(isset($_POST['post_id'])) {
        $post_id = $_POST['post_id'];
        $query = "SELECT * FROM posts WHERE post_id = '$post_id'";
        $result = mysqli_query($conn, $query);
        if($result) {
            if(mysqli_num_rows($result)>0) {
                while($row = mysqli_fetch_assoc($result)) {
                    echo "<div>";
                    echo "<form method='post'>";
                    echo "<input type='hidden' name='post_id' value='" . $row['post_id'] . "'>";
                    echo "<textarea name='title'>" . $row['title'] . "</textarea><br>";
                    echo "<textarea name='content'>" . $row['content'] . "</textarea><br>";
                    echo "<button type='submit' name='update_btn'>Lưu chỉnh sửa</button>";
                    echo "<button type='submit' name='back_btn'>Quay lại</button>";
                    echo "</form>";
                    echo "<p><em>Created at: " . $row['create_time'] . "</em></p>";
                    echo "</div>";
                }
            }
        }
    }
    if(isset($_POST['update_btn'])) {
        $post_id = $_POST['post_id'];
        $title = $_POST['title'];
        $content = $_POST['content'];

        $query = "UPDATE posts SET title = '$title', content = '$content' WHERE post_id = '$post_id'";

        if(mysqli_query($conn, $query)) {
            echo "Chỉnh sửa bài đăng thành công!";
        } else {
            echo "Lỗi: " . mysqli_error($conn);
        }
    }
    if(isset($_POST['back_btn'])) {
        header('location:newfeed.php');
    }*/
?>
<?php
    include "connect.php";
    
    // Hàm để sử dụng tham số trong prepared statements
    function safeParam($conn, $param) {
        return mysqli_real_escape_string($conn, $param);
    }

    if(isset($_POST['post_id'])) {
        $post_id = safeParam($conn, $_POST['post_id']); // Sử dụng safeParam() để xử lý dữ liệu
        $query = "SELECT * FROM posts WHERE post_id = '$post_id'";
        $result = mysqli_query($conn, $query);
        if($result) {
            if(mysqli_num_rows($result)>0) {
                while($row = mysqli_fetch_assoc($result)) {
                    echo "<div>";
                    echo "<form method='post'>";
                    echo "<input type='hidden' name='post_id' value='" . htmlspecialchars($row['post_id']) . "'>"; // Sử dụng htmlspecialchars() để tránh XSS
                    echo "<textarea name='title'>" . htmlspecialchars($row['title']) . "</textarea><br>"; // Sử dụng htmlspecialchars() để tránh XSS
                    echo "<textarea name='content'>" . htmlspecialchars($row['content']) . "</textarea><br>"; // Sử dụng htmlspecialchars() để tránh XSS
                    echo "<button type='submit' name='update_btn'>Lưu chỉnh sửa</button>";
                    echo "<button type='submit' name='back_btn'>Quay lại</button>";
                    echo "</form>";
                    echo "<p><em>Created at: " . htmlspecialchars($row['create_time']) . "</em></p>"; // Sử dụng htmlspecialchars() để tránh XSS
                    echo "</div>";
                }
            }
        }
    }
    if(isset($_POST['update_btn'])) {
        $post_id = safeParam($conn, $_POST['post_id']);
        $title = safeParam($conn, $_POST['title']);
        $content = safeParam($conn, $_POST['content']);

        $query = "UPDATE posts SET title = '$title', content = '$content' WHERE post_id = '$post_id'";

        if(mysqli_query($conn, $query)) {
            echo "Chỉnh sửa bài đăng thành công!";
        } else {
            echo "Lỗi: " . mysqli_error($conn);
        }
    }
    if(isset($_POST['back_btn'])) {
        header('location:newfeed.php');
    }
?>

