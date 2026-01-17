<?php
session_start();
require_once '../config/db_config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../controller/login.php");
    exit();
}

$user_id = (int)$_SESSION['user_id'];
$message = "";

$upload_dir = __DIR__ . "/../upload/profile_pics/";  
$upload_url = "../upload/profile_pics/";             

$user_res = $conn->query("SELECT * FROM users WHERE id = $user_id");
$user = $user_res ? $user_res->fetch_assoc() : null;

if (!$user) {
    die("User not found!");
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_profile'])) {

    $new_name   = mysqli_real_escape_string($conn, $_POST['name'] ?? '');
    $new_mobile = mysqli_real_escape_string($conn, $_POST['mobile'] ?? '');

    $profile_pic_name = $user['profile_pic'] ?? null;

    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === 0) {

        $allowed_ext = ['jpg', 'jpeg', 'png', 'webp'];
        $file_name   = $_FILES['profile_pic']['name'];
        $file_tmp    = $_FILES['profile_pic']['tmp_name'];
        $file_size   = (int)$_FILES['profile_pic']['size'];

        $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        if (!in_array($ext, $allowed_ext)) {
            $message = "❌ Only JPG, JPEG, PNG, WEBP allowed!";
        } elseif ($file_size > 2 * 1024 * 1024) {
            $message = "❌ File size must be under 2MB!";
        } else {

            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            $new_file_name = "user_" . $user_id . "_" . time() . "." . $ext;
            $destination   = $upload_dir . $new_file_name;

            if (move_uploaded_file($file_tmp, $destination)) {

                if (!empty($profile_pic_name) && file_exists($upload_dir . $profile_pic_name)) {
                    @unlink($upload_dir . $profile_pic_name);
                }

                $profile_pic_name = $new_file_name;
            } else {
                $message = "❌ Failed to upload image!";
            }
        }
    } elseif (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] !== 4) {
        $message = "❌ Image upload error!";
    }


    if ($message === "") {

        $profile_pic_sql = $profile_pic_name
            ? "'" . mysqli_real_escape_string($conn, $profile_pic_name) . "'"
            : "NULL";

        $update_sql = "UPDATE users 
                       SET name = '$new_name',
                           mobile = '$new_mobile',
                           profile_pic = $profile_pic_sql
                       WHERE id = $user_id";

        if ($conn->query($update_sql)) {
            $message = "✅ Profile updated successfully!";
            $_SESSION['user_name'] = $new_name;

            $user_res = $conn->query("SELECT * FROM users WHERE id = $user_id");
            $user = $user_res->fetch_assoc();
        } else {
            $message = "❌ Error updating profile: " . $conn->error;
        }
    }
}


if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'manager') {
    include '../includes/manager_header.php';
} else {
    include '../includes/customer_header.php';
}
?>

<div class="glass-card" style="max-width: 500px; margin: 40px auto; padding: 30px;">

    <div style="text-align: center; margin-bottom: 25px;">

        <div style="width: 80px; height: 80px; border-radius: 50%; margin: 0 auto 15px; overflow: hidden; border: 3px solid #2ecc71; display:flex; align-items:center; justify-content:center;">
            <?php if (!empty($user['profile_pic']) && file_exists($upload_dir . $user['profile_pic'])): ?>
                <img src="<?php echo $upload_url . htmlspecialchars($user['profile_pic']); ?>"
                     style="width:100%; height:100%; object-fit:cover;">
            <?php else: ?>
                <div style="width: 80px; height: 80px; background: #2ecc71; display:flex; align-items:center; justify-content:center; font-size:30px; color:white; font-weight:bold;">
                    <?php echo strtoupper(substr($user['name'], 0, 1)); ?>
                </div>
            <?php endif; ?>
        </div>

        <h2 style="color: white; margin-bottom: 5px;"><?php echo htmlspecialchars($user['name']); ?></h2>

        <span style="background: rgba(255,255,255,0.1); padding: 4px 12px; border-radius: 15px; font-size: 12px; color: #2ecc71; font-weight: bold; text-transform: uppercase;">
            <?php echo htmlspecialchars($user['role']); ?> Account
        </span>
    </div>

    <?php if ($message): ?>
        <div style="background: rgba(46, 204, 113, 0.2); color: #2ecc71; padding: 12px; border-radius: 8px; margin-bottom: 20px; text-align: center; font-size: 14px;">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">

        <div class="input-group">
            <label style="color: #aaa; font-size: 13px;">Full Name</label>
            <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required
                   style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.1); background: rgba(255,255,255,0.05); color: white; margin-top: 5px;">
        </div>

        <div class="input-group" style="margin-top: 15px;">
            <label style="color: #aaa; font-size: 13px;">Email Address (Cannot change)</label>
            <input type="email" value="<?php echo htmlspecialchars($user['email']); ?>" disabled
                   style="width: 100%; padding: 12px; border-radius: 8px; border: none; background: rgba(255,255,255,0.05); color: #666; cursor: not-allowed; margin-top: 5px;">
        </div>

        <div class="input-group" style="margin-top: 15px;">
            <label style="color: #aaa; font-size: 13px;">Mobile Number</label>
            <input type="text" name="mobile" value="<?php echo htmlspecialchars($user['mobile'] ?? ''); ?>" placeholder="e.g. 017XXXXXXXX"
                   style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.1); background: rgba(255,255,255,0.05); color: white; margin-top: 5px;">
        </div>


        <div class="input-group" style="margin-top: 15px;">
            <label style="color: #aaa; font-size: 13px;">Profile Picture</label>
            <input type="file" name="profile_pic" accept="image/*"
                   style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.1); background: rgba(255,255,255,0.05); color: white; margin-top: 5px;">
            <small style="color:#777; display:block; margin-top:6px;">Allowed: JPG/JPEG/PNG/WEBP (Max 2MB)</small>
        </div>

        <button type="submit" name="update_profile" class="btn"
                style="margin-top: 25px; background: #2ecc71; width: 100%; font-weight: bold;">
            Update Profile Info
        </button>
    </form>

    <div style="margin-top: 25px; padding-top: 20px; border-top: 1px solid rgba(255,255,255,0.1); text-align: center;">
        <p style="font-size: 12px; color: #888;">
            Member since: <?php echo date('M Y', strtotime($user['created_at'] ?? 'now')); ?>
        </p>

        <a href="../controller/logout.php"
           style="color: #e74c3c; text-decoration: none; font-size: 13px; font-weight: bold; display: block; margin-top: 10px;">
            Logout Account
        </a>
    </div>

</div>

<?php include '../includes/footer.php'; ?>
