<?php
session_start();
require_once '../config/db_config.php';
 
if (!isset($_SESSION['user_id'])) {
    header("Location: ../controller/login.php");
    exit();
}
 
$turf_id = mysqli_real_escape_string($conn, $_GET['id']);
$turf_query = $conn->query("SELECT * FROM turfs WHERE id = $turf_id");
$turf = $turf_query->fetch_assoc();
 
$booking_price = $turf['price'] * 1.5;
 
$booking_success = isset($_GET['success']) ? true : false;
 
include '../includes/customer_header.php';
?>
 
<div class="glass-card" style="max-width: 600px; margin: 40px auto; padding: 30px;">
    <?php if ($booking_success): ?>
        <div style="text-align: center; padding: 20px;">
            <div style="font-size: 60px; margin-bottom: 20px;">✅</div>
            <h2 style="color: #2ecc71; margin-bottom: 10px;">Booking Request Sent!</h2>
            <p style="color: white; opacity: 0.9; margin-bottom: 30px;">Your request for <strong><?php echo htmlspecialchars($turf['name']); ?></strong> is now pending manager approval.</p>
            <a href="customer_dashboard.php" style="text-decoration: none;">
                <button class="btn" style="width: 200px;">Back to Dashboard</button>
            </a>
        </div>
    <?php else: ?>
        <h2 style="color: #2ecc71; margin-bottom: 20px; text-align: center;">Reserve Your Slot</h2>
       
        <form action="../controller/book_turf.php" method="POST">
            <input type="hidden" name="turf_id" value="<?php echo $turf_id; ?>">
            <input type="hidden" name="total_price" value="<?php echo $booking_price; ?>">
 
            <div class="input-group">
                <label style="color: white; margin-bottom: 8px; display: block;">Selected Turf</label>
                <input type="text" value="<?php echo htmlspecialchars($turf['name']); ?>" readonly
                       style="background: rgba(255,255,255,0.1); color: #2ecc71; font-weight: bold; border: 1px solid rgba(255,255,255,0.2);">
            </div>
 
            <div class="input-group" style="margin-top: 15px;">
                <label style="color: white; margin-bottom: 8px; display: block;">Select Date</label>
                <input type="date" name="booking_date" required min="<?php echo date('Y-m-d'); ?>"
                       style="width: 100%; padding: 12px; border-radius: 10px; border: none; background: white; color: #333;">
            </div>
 
            <div class="input-group" style="margin-top: 15px;">
                <label style="color: white; margin-bottom: 8px; display: block;">90-Minute Slot</label>
                <select name="time_slot" required style="width: 100%; padding: 12px; border-radius: 10px; border: none; background: white; color: #333;">
                    <option value="" disabled selected>-- Choose a Time Slot --</option>
                    <option value="08:00 AM - 09:30 AM">08:00 AM - 09:30 AM</option>
                    <option value="10:00 AM - 11:30 AM">10:00 AM - 11:30 AM</option>
                    <option value="04:00 PM - 05:30 PM">04:00 PM - 05:30 PM</option>
                    <option value="06:00 PM - 07:30 PM">06:00 PM - 07:30 PM</option>
                </select>
            </div>
 
            <div style="background: rgba(46, 204, 113, 0.15); padding: 20px; border-radius: 12px; text-align: center; margin-top: 25px; border: 1px solid rgba(46, 204, 113, 0.3);">
                <p style="font-size: 13px; color: #ccc; margin-bottom: 5px;">Total Price (1.5x Hourly Rate)</p>
                <strong style="font-size: 24px; color: #2ecc71;">$<?php echo number_format($booking_price, 2); ?></strong>
            </div>
 
            <button type="submit" class="btn" style="margin-top: 25px; font-weight: bold; background: #2ecc71;">Confirm Request</button>
        </form>
    <?php endif; ?>
</div>
 
<?php include '../includes/footer.php'; ?>