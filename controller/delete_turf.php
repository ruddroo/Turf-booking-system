<?php
session_start();
require_once '../config/db_config.php';

 
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'manager') {
    header("Location: login.php");
    exit();
}

 
if (isset($_GET['id'])) {
    $turf_id = (int)$_GET['id'];

    
    $delete_bookings = "DELETE FROM bookings WHERE turf_id = $turf_id";
    $conn->query($delete_bookings);

   
    $sql = "DELETE FROM turfs WHERE id = $turf_id";

    if ($conn->query($sql) === TRUE) {
         
        header("Location: ../view/manage_turfs.php?msg=deleted");
        exit();
    } else {
        echo "Error deleting record: " . $conn->error;
    }
} else {
    
    header("Location: ../view/manage_turfs.php");
    exit();
}
?>