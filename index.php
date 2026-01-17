<?php
session_start();

 
if (isset($_SESSION['user_id'])) {
    
    
    if ($_SESSION['user_role'] === 'manager') {
        header("Location: view/manager_dashboard.php");
        exit();
    } else {
        header("Location: view/customer_dashboard.php");
        exit();
    }

} else {
  
    header("Location: controller/login.php");
    exit();
}
?>