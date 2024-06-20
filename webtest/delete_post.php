<?php
    /*include "connect.php";
    if(isset($_POST['post_id'])) {
        $post_id = $_POST['post_id'];
        $query = "DELETE FROM posts WHERE post_id = '$post_id'";
        if(mysqli_query($conn, $query)) {
            // xóa xong quay lại newfeed
            header("location:newfeed.php");
        } 
        else {
            echo "Lỗi: " . mysqli_error($conn);
        }
    } 
    else {
        // Nếu post_id không được gửi, chuyển hướng người dùng đến trang newfeed.php
        header("location:newfeed.php");
    }*/
?>
<?php
include "connect.php";
if(isset($_POST['post_id'])) {
    // Chuẩn bị câu lệnh sử dụng prepared statement
    $query = "DELETE FROM posts WHERE post_id = ?";
    
    // Khởi tạo một câu lệnh prepared
    $stmt = mysqli_prepare($conn, $query);
    
    // Kiểm tra nếu câu lệnh prepared không thành công
    if($stmt === false) {
        echo "Lỗi: " . mysqli_error($conn);
    } else {
        // Ràng buộc dữ liệu vào câu lệnh prepared statement
        mysqli_stmt_bind_param($stmt, "i", $_POST['post_id']);
        
        // Thực thi câu lệnh prepared statement
        if(mysqli_stmt_execute($stmt)) {
            // xóa xong quay lại newfeed
            header("location:newfeed.php");
        } else {
            echo "Lỗi: " . mysqli_error($conn);
        }
        
        // Đóng câu lệnh prepared statement
        mysqli_stmt_close($stmt);
    }
} else {
    // Nếu post_id không được gửi, chuyển hướng người dùng đến trang newfeed.php
    header("location:newfeed.php");
}
?>

