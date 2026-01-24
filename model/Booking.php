<?php
class Booking {
    private $conn;
    public function __construct($db) { $this->conn = $db; }

    public function cancel($booking_id, $user_id) {
        return $this->conn->query("UPDATE bookings SET status = 'Cancelled' 
                WHERE id = $booking_id AND user_id = $user_id AND status = 'Pending'");
    }
}
?>