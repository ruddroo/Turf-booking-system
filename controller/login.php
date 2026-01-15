<?php
session_start();
require_once '../config/db_config.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    $login_type = $_POST['login_type'];
    $manager_key = isset($_POST['manager_key']) ? $_POST['manager_key'] : "";

    if ($login_type == 'manager') {
        $sql = "SELECT * FROM managers WHERE email='$email' AND secret_key='$manager_key'";
        $redirect = "../view/manager_dashboard.php";
    } else {
        $sql = "SELECT * FROM users WHERE email='$email'";
        $redirect = "../view/customer_dashboard.php";
    }

    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
         
        if (password_verify($password, $user['password']) || $login_type == 'manager') {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_role'] = $login_type;
            $_SESSION['last_login'] = time(); 

            header("Location: $redirect");
            exit();
        } else { 
            $error = "Invalid Password."; 
        }
    } else { 
        $error = "User not found."; 
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="../assets/style.css">
    <title>Login</title>
    <script>
        function goBack() {
            window.history.back();
        }

        function toggleManager() {
            var mKey = document.getElementById('m-key');
            var type = document.getElementById('l-type');
            var regLink = document.getElementById('reg-link');
            var toggleText = document.getElementById('toggle-text');
            
            if (mKey.style.display === 'none') {
                mKey.style.display = 'block';
                type.value = 'manager';
                regLink.style.display = 'none'; 
                toggleText.innerText = "Customer Login?";
            } else {
                mKey.style.display = 'none';
                type.value = 'customer';
                regLink.style.display = 'block';
                toggleText.innerText = "Manager Access?";
            }
        }
    </script>
</head>
<body>
<div class="overlay">
    <div class="auth-card">
        <div style="text-align: left; margin-bottom: 10px;">
            <a href="javascript:void(0)" onclick="goBack()" style="text-decoration: none; color: #555;">← Back</a>
        </div>
        <h2>Login</h2>
        <?php if($error) echo "<p style='color:red;'>$error</p>"; ?>
        <form method="POST">
            <input type="hidden" name="login_type" id="l-type" value="customer">
            <div class="input-group">
                <label>Email</label>
                <input type="email" name="email" required>
            </div>
            <div class="input-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            <div id="m-key" class="input-group" style="display:none;">
                <label>Manager Secret Key</label>
                <input type="password" name="manager_key">
            </div>
            <button type="submit" class="btn">Login</button>
        </form>
        <div class="toggle-link"><a href="#" id="toggle-text" onclick="toggleManager()">Manager Access?</a></div>
        <div id="reg-link" class="toggle-link">New? <a href="register.php">Register here</a></div>
    </div>
</div>
</body>
</html>