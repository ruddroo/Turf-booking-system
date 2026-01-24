<?php
session_start();
require_once '../config/db_config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ajax_request'])) {
    header('Content-Type: application/json');
    $response = ['success' => false, 'errors' => []];

    $name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $mobile = trim($_POST['mobile']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $dob = $_POST['dob_yyyy'] . "-" . $_POST['dob_mm'] . "-" . $_POST['dob_dd'];
    $gender = $_POST['gender'] ?? "";
    $blood_group = $_POST['blood_group'];

   
    if (empty($name) || empty($email) || empty($mobile) || empty($password) || empty($gender) || empty($blood_group)) {
        $response['errors'][] = "All fields are mandatory.";
    } elseif (strlen($mobile) != 11) {
        $response['errors'][] = "Mobile must be 11 digits.";
    } elseif ($password !== $confirm_password) {
        $response['errors'][] = "Passwords do not match.";
    }

    if (empty($response['errors'])) {
        $hashed_pass = password_hash($password, PASSWORD_DEFAULT);

        
        $stmt = $conn->prepare("INSERT INTO users (name, email, mobile, password, dob, gender, blood_group) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $name, $email, $mobile, $hashed_pass, $dob, $gender, $blood_group);

        if ($stmt->execute()) {
            $response['success'] = true;
            $_SESSION['user_name'] = $name; 
        } else {
            $response['errors'][] = "Database error: " . $conn->error;
        }
        $stmt->close();
    }

    echo json_encode($response);
    exit(); 
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="../assets/style.css">
    <title>Registration</title>
    <style>
        .error-msg { color: #ff4d4d; font-size: 14px; margin-bottom: 10px; display: none; padding: 10px; border: 1px solid #ff4d4d; border-radius: 5px; }
        .success-box { display: none; background: rgba(46, 204, 113, 0.2); padding: 20px; border-radius: 10px; border: 1px solid #2ecc71; text-align: left; }
        .dob-group { display: flex; gap: 5px; }
        .dob-group input { width: 32%; }
    </style>
</head>
<body>
<div class="overlay">
    <div class="auth-card">
        <div id="error-container" class="error-msg"></div>

        <div id="success-container" class="success-box">
            <h2 style="color: #2ecc71; text-align: center;">✅ Registration Successful!</h2>
            <p id="welcome-msg"></p>
            <a href="login.php"><button class="btn" style="width:100%; margin-top:15px;">Login Now</button></a>
        </div>

        <div id="form-container">
            <h2>Sign Up</h2>
            <form id="regForm">
                <input type="hidden" name="ajax_request" value="1">

                <div class="input-group">
                    <label>Full Name</label>
                    <input type="text" name="full_name" required>
                </div>
                <div class="input-group">
                    <label>Email</label>
                    <input type="email" name="email" required>
                </div>
                <div class="input-group">
                    <label>Mobile Number</label>
                    <input type="text" name="mobile" required>
                </div>
                <div class="input-group">
                    <label>Password</label>
                    <input type="password" name="password" required>
                </div>
                <div class="input-group">
                    <label>Confirm Password</label>
                    <input type="password" name="confirm_password" required>
                </div>
                <div class="input-group">
                    <label>Date of Birth</label>
                    <div class="dob-group">
                        <input type="number" name="dob_dd" placeholder="DD" required>
                        <input type="number" name="dob_mm" placeholder="MM" required>
                        <input type="number" name="dob_yyyy" placeholder="YYYY" required>
                    </div>
                </div>
                <div class="input-group">
                    <label>Gender</label>
                    <div style="display:flex; gap:15px; margin-top:5px;">
                        <label><input type="radio" name="gender" value="Male"> Male</label>
                        <label><input type="radio" name="gender" value="Female"> Female</label>
                    </div>
                </div>
                <div class="input-group">
                    <label>Blood Group</label>
                    <select name="blood_group" required>
                        <option value="">Select</option>
                        <option value="A+">A+</option>
                        <option value="A-">A-</option>
                        <option value="B+">B+</option>
                        <option value="B-">B-</option>
                        <option value="O+">O+</option>
                        <option value="O-">O-</option>
                         <option value="AB+">AB+</option>
                        <option value="AB-">AB-</option>
                    </select>
                </div>
                <button type="submit" class="btn">Register</button>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('regForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const errorDiv = document.getElementById('error-container');
    const formData = new FormData(this);

    fetch(window.location.href, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('form-container').style.display = 'none';
            errorDiv.style.display = 'none';
            document.getElementById('success-container').style.display = 'block';
            document.getElementById('welcome-msg').innerHTML = "<strong>Name:</strong> " + formData.get('full_name');
        } else {
            errorDiv.style.display = 'block';
            errorDiv.innerHTML = data.errors.join('<br>');
        }
    })
    .catch(err => {
        console.error("Fetch error:", err);
    });
});
</script>
</body>
</html>