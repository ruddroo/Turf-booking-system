<?php
session_start();
require_once '../config/db_config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'customer') {
    header("Location: ../controller/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$userName = $_SESSION['user_name'];

$booking_res = $conn->query("SELECT COUNT(*) as total FROM bookings WHERE user_id = $user_id");
$total_bookings = $booking_res->fetch_assoc()['total'];

$announcement_res = $conn->query("SELECT message FROM announcements ORDER BY id DESC LIMIT 1");
$latest_announcement = ($announcement_res->num_rows > 0) ? $announcement_res->fetch_assoc()['message'] : "No new updates.";

include '../includes/customer_header.php'; 
?>
<div class="glass-card">
    <h2>Hello, <?php echo htmlspecialchars($userName); ?>! 👋</h2>
    <p>Welcome back to GreenField Turf. Ready for a match?</p>
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 20px;">
        <div style="background: rgba(255,255,255,0.1); padding: 20px; border-radius: 15px;">
            <h3>My Stats</h3>
            <p style="font-size: 24px; margin: 10px 0;">⚽ <?php echo $total_bookings; ?> Bookings</p>
            <a href="my_bookings.php" style="color: #2ecc71; text-decoration: none; font-size: 14px;">View History →</a>
        </div>
        <div style="background: rgba(255,255,255,0.1); padding: 20px; border-radius: 15px;">
            <h3>Announcements</h3>
            <p style="font-style: italic; margin-top: 10px;">"<?php echo htmlspecialchars($latest_announcement); ?>"</p>
        </div>
    </div>
</div>
<?php include '../includes/footer.php'; ?>