<?php
session_start();
require_once '../config/db_config.php';

 
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'manager') {
    header("Location: ../controller/login.php");
    exit();
}

$message = "";

 
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_turf'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $price = $_POST['price'];
    $size = $_POST['size'];

    $sql = "INSERT INTO turfs (name, location, price, size) VALUES ('$name', '$location', '$price', '$size')";
    
    if ($conn->query($sql) === TRUE) {
        $message = "✅ Turf added successfully!";
    } else {
        $message = "❌ Error: " . $conn->error;
    }
}

$result = $conn->query("SELECT * FROM turfs ORDER BY id DESC");

include '../includes/manager_header.php'; 
?>

<div style="display: flex; gap: 20px; flex-direction: column;">
    
    <div class="glass-card" style="padding: 25px;">
        <h3 style="color: #2ecc71; margin-bottom: 20px;">➕ Add New Turf</h3>
        <?php if($message) echo "<p style='margin-bottom:15px; font-weight:bold; color:#2ecc71;'>$message</p>"; ?>
        
        <form method="POST" style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 15px; align-items: end;">
            <div class="input-group" style="margin:0;">
                <label>Turf Name</label>
                <input type="text" name="name" required placeholder="e.g. Arena 7">
            </div>
            <div class="input-group" style="margin:0;">
                <label>Location</label>
                <input type="text" name="location" required placeholder="e.g. Uttara">
            </div>
            <div class="input-group" style="margin:0;">
                <label>Price ($/hr)</label>
                <input type="number" name="price" required placeholder="30">
            </div>
            <div class="input-group" style="margin:0;">
                <label>Size</label>
                <select name="size" style="width:100%; padding:12px; border-radius:10px; border:none; background:rgba(255,255,255,0.9);">
                    <option value="5-a-side">5-a-side</option>
                    <option value="7-a-side">7-a-side</option>
                    <option value="9-a-side">9-a-side</option>
                    <option value="11-a-side">11-a-side</option>
                </select>
            </div>
            <button type="submit" name="add_turf" class="btn" style="grid-column: span 4; margin-top: 10px;">Register Turf</button>
        </form>
    </div>

    <div class="glass-card" style="padding: 25px;">
        <h3 style="color: #2ecc71; margin-bottom: 20px;">🏟️ Existing Turfs</h3>
        <table style="width: 100%; border-collapse: collapse; color: white; text-align: left;">
            <thead>
                <tr style="border-bottom: 2px solid rgba(255,255,255,0.1);">
                    <th style="padding: 12px;">ID</th>
                    <th style="padding: 12px;">Name</th>
                    <th style="padding: 12px;">Location</th>
                    <th style="padding: 12px;">Size</th>
                    <th style="padding: 12px;">Price</th>
                    <th style="padding: 12px;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                            <td style="padding: 12px;"><?php echo $row['id']; ?></td>
                            <td style="padding: 12px; font-weight: bold; color: #2ecc71;"><?php echo htmlspecialchars($row['name']); ?></td>
                            <td style="padding: 12px;"><?php echo htmlspecialchars($row['location']); ?></td>
                            <td style="padding: 12px;"><?php echo htmlspecialchars($row['size']); ?></td>
                            <td style="padding: 12px;">$<?php echo number_format($row['price'], 2); ?></td>
                            <td style="padding: 12px;">
                                <a href="../controller/delete_turf.php?id=<?php echo $row['id']; ?>" 
                                   style="color: #e74c3c; text-decoration: none; font-size: 13px;" 
                                   onclick="return confirm('Are you sure you want to delete this turf?')">🗑️ Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="padding: 20px; text-align: center; opacity: 0.5;">No turfs registered yet.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php 
include '../includes/footer.php'; 
?>