<?php
session_start();
require_once '../config/db_config.php';
 
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'customer') {
    header("Location: ../controller/login.php");
    exit();
}

$discount_query = $conn->query("SELECT MAX(discount) as max_discount FROM announcements");
$discount_row = $discount_query->fetch_assoc();
$current_discount = $discount_row['max_discount'] ?? 0;
 

$offers_res = $conn->query("SELECT * FROM announcements ORDER BY post_date DESC LIMIT 3");
 
$search_query = "";
if (isset($_GET['search'])) {
    $search_query = mysqli_real_escape_string($conn, $_GET['search']);
    $sql = "SELECT * FROM turfs WHERE name LIKE '%$search_query%' OR location LIKE '%$search_query%' ORDER BY id DESC";
} else {
    $sql = "SELECT * FROM turfs ORDER BY id DESC";
}
$result = $conn->query($sql);
 
include '../includes/customer_header.php'; 
?>
 
<?php if ($offers_res->num_rows > 0): ?>
<div style="margin-bottom: 30px;">
<h3 style="color: #f1c40f; margin-bottom: 15px;">🎁 Active Promotions</h3>
<div style="display: flex; gap: 15px; overflow-x: auto; padding-bottom: 10px;">
<?php while($offer = $offers_res->fetch_assoc()): ?>
<div class="glass-card" style="min-width: 280px; flex: 1; background: rgba(241, 196, 15, 0.1); border: 1px solid rgba(241, 196, 15, 0.3); padding: 15px;">
<div style="display: flex; justify-content: space-between;">
<strong style="color: #f1c40f;"><?php echo htmlspecialchars($offer['title']); ?></strong>
<span style="background: #f1c40f; color: #000; padding: 2px 8px; border-radius: 5px; font-size: 11px; font-weight: bold;">
<?php echo $offer['discount']; ?>% OFF
</span>
</div>
<p style="font-size: 12px; margin-top: 5px; opacity: 0.8;"><?php echo htmlspecialchars($offer['message']); ?></p>
</div>
<?php endwhile; ?>
</div>
</div>
<?php endif; ?>
 
<div class="glass-card">
<h2 style="margin-bottom: 20px;">Available Turfs</h2>
<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px;">
<?php if ($result->num_rows > 0): ?>
<?php while($row = $result->fetch_assoc()): ?>
<?php 
                 
                    $original_price = $row['price'];
                    $discount_amount = ($original_price * $current_discount) / 100;
                    $final_price = $original_price - $discount_amount;
                ?>
<div class="glass-card" style="background: rgba(255, 255, 255, 0.05); padding: 15px; position: relative;">
<?php if($current_discount > 0): ?>
<div style="position: absolute; top: 10px; right: 10px; background: #e74c3c; color: white; padding: 5px 10px; border-radius: 5px; font-size: 12px; font-weight: bold; z-index: 10;">
                            SAVE <?php echo $current_discount; ?>%
</div>
<?php endif; ?>
 
                    <div style="width: 100%; height: 150px; background: url('https://images.unsplash.com/photo-1574629810360-7efbbe195018?q=80&w=400') center/cover; border-radius: 10px; margin-bottom: 15px;"></div>
<h3 style="color: #2ecc71;"><?php echo htmlspecialchars($row['name']); ?></h3>
<p style="font-size: 14px; opacity: 0.7;">📍 <?php echo htmlspecialchars($row['location']); ?></p>
<div style="display: flex; justify-content: space-between; align-items: center; margin-top: 15px; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 15px;">
<div>
<?php if($current_discount > 0): ?>
<span style="text-decoration: line-through; color: #888; font-size: 14px;">$<?php echo number_format($original_price, 2); ?></span><br>
<?php endif; ?>
<span style="font-size: 22px; font-weight: bold; color: #2ecc71;">$<?php echo number_format($final_price, 2); ?></span>
<small style="font-size: 10px; color: #aaa;">/hr</small>
</div>
<a href="book_turf.php?id=<?php echo $row['id']; ?>&applied_discount=<?php echo $current_discount; ?>">
<button class="btn" style="margin: 0; padding: 10px 20px;">Book Now</button>
</a>
</div>
</div>
<?php endwhile; ?>
<?php endif; ?>
</div>
</div>
 
<?php include '../includes/footer.php'; ?>