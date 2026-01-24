<?php
session_start();
require_once '../config/db_config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   
    $user_id = $_SESSION['user_id'];
    $turf_id = mysqli_real_escape_string($conn, $_POST['turf_id']);
    $date = mysqli_real_escape_string($conn, $_POST['booking_date']);
    $slot = mysqli_real_escape_string($conn, $_POST['time_slot']);
    $price = mysqli_real_escape_string($conn, $_POST['total_price']);

    $sql = "INSERT INTO bookings (user_id, turf_id, booking_date, time_slot, total_price, status) 
            VALUES ('$user_id', '$turf_id', '$date', '$slot', '$price', 'Pending')";

    if ($conn->query($sql) === TRUE) {

        header("Location: ../view/book_turf.php?id=$turf_id&success=1");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}
?>