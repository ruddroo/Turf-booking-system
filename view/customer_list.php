 <?php
session_start();
require_once '../config/db_config.php';

// Security check: Only managers can access
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'manager') {
    header("Location: ../controller/login.php");
    exit();
}

// SQL Logic: Fetching 'mobile' column and counting bookings
$sql = "SELECT u.id, u.name, u.email, u.mobile, COUNT(b.id) as total_bookings 
        FROM users u 
        LEFT JOIN bookings b ON u.id = b.user_id 
        WHERE u.role = 'customer' 
        GROUP BY u.id 
        ORDER BY total_bookings DESC";

$result = $conn->query($sql);

include '../includes/manager_header.php'; 
?>

<div class="glass-card" style="padding: 25px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
        <h2 style="color: #2ecc71;">👥 Customer Directory</h2>
        <span style="background: rgba(255,255,255,0.1); padding: 5px 15px; border-radius: 20px; font-size: 13px; color: #ddd;">
            <?php echo $result->num_rows; ?> Total Users
        </span>
    </div>

    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; color: white; text-align: left;">
            <thead>
                <tr style="border-bottom: 2px solid rgba(255,255,255,0.1); color: #2ecc71; font-size: 14px;">
                    <th style="padding: 15px;">Customer</th>
                    <th style="padding: 15px;">Contact Details</th>
                    <th style="padding: 15px; text-align: center;">Total Bookings</th>
                    <th style="padding: 15px; text-align: center;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr style="border-bottom: 1px solid rgba(255,255,255,0.05); transition: 0.3s;" onmouseover="this.style.background='rgba(255,255,255,0.05)'" onmouseout="this.style.background='transparent'">
                            <td style="padding: 15px;">
                                <div style="font-weight: bold; font-size: 16px;">
                                    <?php echo htmlspecialchars($row['name']); ?>
                                    <?php if($row['total_bookings'] >= 5): ?>
                                        <span title="Frequent Booker" style="font-size: 12px; margin-left: 5px;">⭐</span>
                                    <?php endif; ?>
                                </div>
                                <div style="font-size: 12px; color: #aaa;">ID: #<?php echo $row['id']; ?></div>
                            </td>
                            <td style="padding: 15px;">
                                <div style="font-size: 14px; margin-bottom: 3px;">📧 <?php echo htmlspecialchars($row['email']); ?></div>
                                <div style="font-size: 14px; color: #2ecc71;">📱 <?php echo htmlspecialchars($row['mobile'] ?? 'No Number'); ?></div>
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                <span style="background: rgba(46, 204, 113, 0.2); color: #2ecc71; padding: 4px 12px; border-radius: 10px; font-weight: bold;">
                                    <?php echo $row['total_bookings']; ?>
                                </span>
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                <a href="view_bookings.php?customer_id=<?php echo $row['id']; ?>" style="text-decoration: none;">
                                    <button class="btn" style="margin: 0; padding: 6px 15px; font-size: 11px; background: #34495e; border: 1px solid rgba(255,255,255,0.1);">History</button>
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" style="padding: 40px; text-align: center; opacity: 0.5;">No customers found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../includes/footer.php'; ?>