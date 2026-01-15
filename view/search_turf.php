<?php
session_start();
require_once '../config/db_config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'customer') {
    header("Location: ../controller/login.php");
    exit();
}

$search_query = "";
if (isset($_GET['search'])) {
    $search_query = mysqli_real_escape_string($conn, $_GET['search']);
    $sql = "SELECT * FROM turfs WHERE name LIKE '%$search_query%' OR location LIKE '%$search_query%' ORDER BY id DESC";
} else {
    $sql = "SELECT * FROM turfs ORDER BY id DESC";
}

$result = $conn->query($sql);

if (isset($_GET['ajax']) && $_GET['ajax'] == "1") {
    header('Content-Type: text/html; charset=UTF-8');

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            ?>
            <div class="glass-card" style="background: rgba(255, 255, 255, 0.05); padding: 15px; text-align: left; border: 1px solid rgba(255,255,255,0.1); transition: 0.3s;">
                <div style="width: 100%; height: 180px; background: url('https://images.unsplash.com/photo-1574629810360-7efbbe195018?q=80&w=500') center/cover; border-radius: 10px; margin-bottom: 15px;"></div>
                
                <h3 style="color: #2ecc71; margin-bottom: 8px;"><?php echo htmlspecialchars($row['name']); ?></h3>
                <p style="font-size: 14px; margin-bottom: 5px; color: #ddd;">📍 <?php echo htmlspecialchars($row['location']); ?></p>
                <p style="font-size: 14px; margin-bottom: 15px; color: #bbb;">📏 Size: <strong><?php echo htmlspecialchars($row['size']); ?></strong></p>
                
                <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 15px;">
                    <span style="font-size: 20px; font-weight: bold; color: #2ecc71;">$<?php echo number_format($row['price'], 2); ?><small style="font-size: 12px; color: #aaa;">/hr</small></span>
                    
                    <a href="book_turf.php?id=<?php echo $row['id']; ?>" style="text-decoration: none;">
                        <button class="btn" style="margin-top: 0; padding: 8px 20px; font-size: 13px;">Book Slot</button>
                    </a>
                </div>
            </div>
            <?php
        }
    } else {
        ?>
        <div style="grid-column: 1 / -1; padding: 60px; text-align: center; background: rgba(0,0,0,0.2); border-radius: 15px;">
            <p style="font-size: 18px; opacity: 0.6;">No turfs found for "<?php echo htmlspecialchars($search_query); ?>".</p>
            <a href="search_turf.php" style="color: #2ecc71; text-decoration: none; font-size: 14px;">Show all available turfs</a>
        </div>
        <?php
    }
    exit();
}

include '../includes/customer_header.php';
?>

<div class="glass-card">
    <div style="text-align: left; margin-bottom: 20px;">
        <h2 style="color: #2ecc71;">🔍 Search Available Turfs</h2>
        <p style="opacity: 0.8;">Find and book the best pitches in your area.</p>
    </div>

    <form id="searchForm" method="GET" action="search_turf.php" style="display: flex; gap: 10px; margin-bottom: 30px;">
        <input type="text" id="searchInput" name="search" placeholder="Enter turf name or location..." 
               value="<?php echo htmlspecialchars($search_query); ?>" 
               style="flex: 1; padding: 12px 15px; border-radius: 10px; border: none; background: rgba(255,255,255,0.9); color: #333; outline: none;">
        
        <button type="submit" class="btn" style="width: 120px; margin-top: 0; padding: 12px;">Search</button>
        
        <?php if(!empty($search_query)): ?>
            <a href="search_turf.php" style="text-decoration: none;">
                <button type="button" class="btn" style="width: 80px; margin-top: 0; background: #95a5a6; padding: 12px;">Clear</button>
            </a>
        <?php endif; ?>
    </form>

    <div id="turfResults" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 25px;">
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <div class="glass-card" style="background: rgba(255, 255, 255, 0.05); padding: 15px; text-align: left; border: 1px solid rgba(255,255,255,0.1); transition: 0.3s;">
                    <div style="width: 100%; height: 180px; background: url('https://images.unsplash.com/photo-1574629810360-7efbbe195018?q=80&w=500') center/cover; border-radius: 10px; margin-bottom: 15px;"></div>
                    
                    <h3 style="color: #2ecc71; margin-bottom: 8px;"><?php echo htmlspecialchars($row['name']); ?></h3>
                    <p style="font-size: 14px; margin-bottom: 5px; color: #ddd;">📍 <?php echo htmlspecialchars($row['location']); ?></p>
                    <p style="font-size: 14px; margin-bottom: 15px; color: #bbb;">📏 Size: <strong><?php echo htmlspecialchars($row['size']); ?></strong></p>
                    
                    <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 15px;">
                        <span style="font-size: 20px; font-weight: bold; color: #2ecc71;">$<?php echo number_format($row['price'], 2); ?><small style="font-size: 12px; color: #aaa;">/hr</small></span>
                        
                        <a href="book_turf.php?id=<?php echo $row['id']; ?>" style="text-decoration: none;">
                            <button class="btn" style="margin-top: 0; padding: 8px 20px; font-size: 13px;">Book Slot</button>
                        </a>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div style="grid-column: 1 / -1; padding: 60px; text-align: center; background: rgba(0,0,0,0.2); border-radius: 15px;">
                <p style="font-size: 18px; opacity: 0.6;">No turfs found for "<?php echo htmlspecialchars($search_query); ?>".</p>
                <a href="search_turf.php" style="color: #2ecc71; text-decoration: none; font-size: 14px;">Show all available turfs</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
document.getElementById('searchForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const q = document.getElementById('searchInput').value.trim();
    const url = 'search_turf.php?ajax=1' + (q ? '&search=' + encodeURIComponent(q) : '');

    fetch(url, { method: 'GET', headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(res => res.text())
        .then(html => {
            document.getElementById('turfResults').innerHTML = html;
            const currentUrl = 'search_turf.php' + (q ? '?search=' + encodeURIComponent(q) : '');
            window.history.replaceState({}, '', currentUrl);
        })
        .catch(err => console.error(err));
});
</script>

<?php include '../includes/footer.php'; ?>
