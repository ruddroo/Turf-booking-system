<?php
session_start();
require_once '../config/db_config.php';
 
//  Only customers can see their own bookings
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'customer') {
    header("Location: ../controller/login.php");
    exit();
}
 
$user_id = $_SESSION['user_id'];
 
// SQL to get bookings, joining with the turfs table to get the Turf Name
$sql = "SELECT b.*, t.name as turf_name, t.location 
        FROM bookings b 
        JOIN turfs t ON b.turf_id = t.id 
        WHERE b.user_id = $user_id 
        ORDER BY b.booking_date DESC";
 
$result = $conn->query($sql);
 
include '../includes/customer_header.php';
?>
 
<div class="glass-card" style="padding: 25px;">
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
<h2 style="color: #2ecc71;">📅 My Booking History</h2>
<a href="search_turf.php" style="text-decoration: none;">
<button class="btn" style="margin: 0; padding: 10px 20px; font-size: 13px;">+ Book New Turf</button>
</a>
</div>
 
    <div style="overflow-x: auto;">
<table style="width: 100%; border-collapse: collapse; color: white; text-align: left;">
<thead>
<tr style="border-bottom: 2px solid rgba(255,255,255,0.1); color: #2ecc71;">
<th style="padding: 15px;">Turf Details</th>
<th style="padding: 15px;">Date & Time</th>
<th style="padding: 15px;">Total Price</th>
<th style="padding: 15px;">Status</th>
</tr>
</thead>
<tbody>
<?php if ($result->num_rows > 0): ?>
<?php while($row = $result->fetch_assoc()): ?>
<tr style="border-bottom: 1px solid rgba(255,255,255,0.05); transition: 0.3s;" onmouseover="this.style.background='rgba(255,255,255,0.05)'" onmouseout="this.style.background='transparent'">
<td style="padding: 15px;">
<strong style="display: block; font-size: 16px;"><?php echo htmlspecialchars($row['turf_name']); ?></strong>
<small style="opacity: 0.6;">📍 <?php echo htmlspecialchars($row['location']); ?></small>
</td>
<td style="padding: 15px;">
<div style="font-size: 14px;"><?php echo date('M d, Y', strtotime($row['booking_date'])); ?></div>
<div style="font-size: 12px; opacity: 0.7;"><?php echo htmlspecialchars($row['time_slot']); ?></div>
</td>
<td style="padding: 15px;">
<span style="font-weight: bold; color: #2ecc71;">$<?php echo number_format($row['total_price'], 2); ?></span>
</td>
<td style="padding: 15px;">
<?php 
     $status = $row['status'];
     $bg = "rgba(255,255,255,0.1)";
     $color = "#f1c40f"; 
     if($status == 'Confirmed') { $color = "#2ecc71"; $bg = "rgba(46, 204, 113, 0.1)"; }
    if($status == 'Cancelled') { $color = "#e74c3c"; $bg = "rgba(231, 76, 60, 0.1)"; }
 ?>
<span style="background: <?php echo $bg; ?>; color: <?php echo $color; ?>; padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: bold; border: 1px solid <?php echo $color; ?>55;">
<?php echo $status; ?>
</span>
</td>
</tr>
<?php endwhile; ?>
<?php else: ?>
<tr>
<td colspan="4" style="padding: 50px; text-align: center; opacity: 0.5;">
<div style="font-size: 40px; margin-bottom: 10px;">🏟️</div>
<p>You haven't made any bookings yet.</p>
</td>
</tr>
<?php endif; ?>
</tbody>
</table>
</div>
</div>
 
<?php include '../includes/footer.php'; ?>