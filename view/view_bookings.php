<?php
session_start();
 
require_once '../config/db_config.php';

 
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'manager') {
    header("Location: ../controller/login.php");
    exit();
}

$message = "";

 
if (isset($_GET['action']) && isset($_GET['id'])) {
    $booking_id = (int)$_GET['id'];
    $new_status = ($_GET['action'] == 'confirm') ? 'Confirmed' : 'Cancelled';
    
    $update_sql = "UPDATE bookings SET status = '$new_status' WHERE id = $booking_id";
    if ($conn->query($update_sql)) {
        $message = "✅ Booking #$booking_id has been $new_status.";
    }
}

 
$sql = "SELECT b.*, u.name as customer_name, t.name as turf_name 
        FROM bookings b
        JOIN users u ON b.user_id = u.id
        JOIN turfs t ON b.turf_id = t.id
        ORDER BY b.booking_date DESC, b.time_slot ASC";

$result = $conn->query($sql);

 
include '../includes/manager_header.php'; 
?>

<div class="glass-card" style="padding: 25px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
        <h2 style="color: #2ecc71;">📅 All Turf Bookings</h2>
        <?php if($message) echo "<span style='color: #2ecc71; font-weight: bold;'>$message</span>"; ?>
    </div>

    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; color: white; text-align: left; font-size: 14px;">
            <thead>
                <tr style="border-bottom: 2px solid rgba(255,255,255,0.1); color: #2ecc71;">
                    <th style="padding: 15px;">ID</th>
                    <th style="padding: 15px;">Customer</th>
                    <th style="padding: 15px;">Turf Name</th>
                    <th style="padding: 15px;">Date</th>
                    <th style="padding: 15px;">Time Slot</th>
                    <th style="padding: 15px;">Status</th>
                    <th style="padding: 15px; text-align: center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr style="border-bottom: 1px solid rgba(255,255,255,0.05); transition: 0.3s;" onmouseover="this.style.background='rgba(255,255,255,0.05)'" onmouseout="this.style.background='transparent'">
                            <td style="padding: 15px;">#<?php echo $row['id']; ?></td>
                            <td style="padding: 15px; font-weight: bold;"><?php echo htmlspecialchars($row['customer_name']); ?></td>
                            <td style="padding: 15px;"><?php echo htmlspecialchars($row['turf_name']); ?></td>
                            <td style="padding: 15px;"><?php echo date('M d, Y', strtotime($row['booking_date'])); ?></td>
                            <td style="padding: 15px;"><?php echo htmlspecialchars($row['time_slot']); ?></td>
                            <td style="padding: 15px;">
                                <?php 
                                    $status_color = "#f1c40f"; // Yellow for Pending
                                    if($row['status'] == 'Confirmed') $status_color = "#2ecc71";
                                    if($row['status'] == 'Cancelled') $status_color = "#e74c3c";
                                ?>
                                <span style="color: <?php echo $status_color; ?>; font-weight: bold; background: rgba(255,255,255,0.1); padding: 4px 10px; border-radius: 5px; font-size: 12px;">
                                    <?php echo $row['status']; ?>
                                </span>
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                <?php if($row['status'] == 'Pending'): ?>
                                    <a href="view_bookings.php?action=confirm&id=<?php echo $row['id']; ?>" 
                                       style="color: #2ecc71; text-decoration: none; margin-right: 15px; font-size: 13px;"
                                       onclick="return confirm('Confirm this booking?')">✔️ Confirm</a>
                                    
                                    <a href="view_bookings.php?action=cancel&id=<?php echo $row['id']; ?>" 
                                       style="color: #e74c3c; text-decoration: none; font-size: 13px;"
                                       onclick="return confirm('Cancel this booking?')">❌ Cancel</a>
                                <?php else: ?>
                                    <span style="opacity: 0.5; font-size: 12px;">No Actions</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" style="padding: 40px; text-align: center; opacity: 0.5;">
                            No bookings found in the database.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php 
 
include '../includes/footer.php'; 
?>