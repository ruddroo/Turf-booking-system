<?php
session_start();
 
require_once '../config/db_config.php';

 
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'manager') {
    header("Location: ../controller/login.php");
    exit();
}

$turf_res = $conn->query("SELECT COUNT(*) as total FROM turfs");
$total_turfs = $turf_res->fetch_assoc()['total'];

$booking_res = $conn->query("SELECT COUNT(*) as total FROM bookings WHERE status = 'Pending'");
$pending_bookings = $booking_res->fetch_assoc()['total'];

$user_res = $conn->query("SELECT COUNT(*) as total FROM users");
$total_customers = $user_res->fetch_assoc()['total'];

$managerName = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : "Admin";

include '../includes/manager_header.php'; 
?>

<div class="glass-card" style="padding: 25px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2 style="color: #2ecc71;">Welcome, <?php echo htmlspecialchars($managerName); ?>! 👔</h2>
        <span style="background: rgba(46, 204, 113, 0.2); color: #2ecc71; padding: 5px 15px; border-radius: 20px; font-size: 14px; font-weight: bold;">
            Manager Access
        </span>
    </div>
    
    <p style="opacity: 0.8; margin-bottom: 30px;">Real-time overview of your turf operations and customer activity.</p>

    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-top: 20px;">
        
        <div style="background: rgba(39, 174, 96, 0.8); padding: 25px; border-radius: 15px; color: white; text-align: center; box-shadow: 0 10px 20px rgba(0,0,0,0.2);">
            <h3 style="font-size: 2.5rem; margin-bottom: 5px;"><?php echo str_pad($total_turfs, 2, "0", STR_PAD_LEFT); ?></h3>
            <p style="text-transform: uppercase; font-size: 11px; letter-spacing: 1px; font-weight: bold; opacity: 0.9;">Total Turfs</p>
            <hr style="margin: 15px 0; opacity: 0.2;">
            <a href="manage_turfs.php" style="color: white; text-decoration: none; font-size: 13px; font-weight: 500;">Manage Turfs →</a>
        </div>

        <div style="background: rgba(41, 128, 185, 0.8); padding: 25px; border-radius: 15px; color: white; text-align: center; box-shadow: 0 10px 20px rgba(0,0,0,0.2);">
            <h3 style="font-size: 2.5rem; margin-bottom: 5px;"><?php echo str_pad($pending_bookings, 2, "0", STR_PAD_LEFT); ?></h3>
            <p style="text-transform: uppercase; font-size: 11px; letter-spacing: 1px; font-weight: bold; opacity: 0.9;">Pending Requests</p>
            <hr style="margin: 15px 0; opacity: 0.2;">
            <a href="view_bookings.php" style="color: white; text-decoration: none; font-size: 13px; font-weight: 500;">Review All →</a>
        </div>

        <div style="background: rgba(142, 68, 173, 0.8); padding: 25px; border-radius: 15px; color: white; text-align: center; box-shadow: 0 10px 20px rgba(0,0,0,0.2);">
            <h3 style="font-size: 2.5rem; margin-bottom: 5px;"><?php echo str_pad($total_customers, 2, "0", STR_PAD_LEFT); ?></h3>
            <p style="text-transform: uppercase; font-size: 11px; letter-spacing: 1px; font-weight: bold; opacity: 0.9;">Registered Users</p>
            <hr style="margin: 15px 0; opacity: 0.2;">
            <a href="customer_list.php" style="color: white; text-decoration: none; font-size: 13px; font-weight: 500;">View List →</a>
        </div>

    </div>

    <div style="margin-top: 40px; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 30px;">
        <h3 style="margin-bottom: 20px; font-size: 18px;">⚡ Quick Actions</h3>
        <div style="display: flex; gap: 15px; justify-content: flex-start;">
            <a href="manage_turfs.php" style="text-decoration: none;">
                <button class="btn" style="width: 200px; background: #34495e; margin: 0;">➕ Add New Turf</button>
            </a>
            <a href="announcements.php" style="text-decoration: none;">
                <button class="btn" style="width: 200px; background: #27ae60; margin: 0;">📢 Post Offer</button>
            </a>
            <a href="view_bookings.php" style="text-decoration: none;">
                <button class="btn" style="width: 200px; background: #2980b9; margin: 0;">📅 Check Schedule</button>
            </a>
        </div>
    </div>
</div>

<?php 
 
include '../includes/footer.php'; 
?>