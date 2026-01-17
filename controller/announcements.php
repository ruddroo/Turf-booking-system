 <?php
session_start();
require_once '../config/db_config.php';

 
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'manager') {
    header("Location: ../controller/login.php");
    exit();
}

 
if (isset($_GET['delete_id'])) {
    $id = (int)$_GET['delete_id'];
    $conn->query("DELETE FROM announcements WHERE id = $id");
    header("Location: ../view/announcements.php?msg=deleted");
    exit();
}

 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);
    $discount = (int)$_POST['discount'];

    if (isset($_POST['announcement_id']) && !empty($_POST['announcement_id'])) {
     
        $id = (int)$_POST['announcement_id'];
        $sql = "UPDATE announcements SET title='$title', message='$message', discount='$discount' WHERE id=$id";
        $status = "updated";
    } else {
        
        $post_date = date('Y-m-d');
        $sql = "INSERT INTO announcements (title, message, discount, post_date) VALUES ('$title', '$message', '$discount', '$post_date')";
        $status = "success";
    }

    if ($conn->query($sql) === TRUE) {
        header("Location: ../view/announcements.php?status=$status");
        exit();
    }
}
?>