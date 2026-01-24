<?php
session_start();
require_once '../config/db_config.php';
 
// Security check
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'customer') {
    header("Location: login.php");
    exit();
}
 
if (isset($_GET['id'])) {
    $booking_id = (int)$_GET['id'];
    $user_id = $_SESSION['user_id'];
 
    $sql = "UPDATE bookings SET status = 'Cancelled' 
            WHERE id = $booking_id AND user_id = $user_id AND status = 'Pending'";
 
    if ($conn->query($sql) === TRUE) {
        header("Location: ../view/my_bookings.php?msg=cancelled");
    } else {
        echo "Error: " . $conn->error;
    }
} else {
    header("Location: ../view/my_bookings.php");
}
exit();