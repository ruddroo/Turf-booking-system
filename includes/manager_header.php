<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="../assets/style.css">
    <title>Manager Panel - GreenField Turf</title>
    <style>
        .cust-nav { 
            background: rgba(0, 0, 0, 0.3); 
            backdrop-filter: blur(10px); 
            padding: 15px 50px; 
            display: flex; 
            justify-content: space-between; 
            align-items: center;
            border-bottom: 1px solid rgba(255,255,255,0.2);
        }
        .cust-nav a { color: white; text-decoration: none; margin-left: 20px; font-weight: 500; font-size: 14px; transition: 0.3s; }
        .cust-nav a:hover { color: #2ecc71; }
        .cust-nav .logo { color: #2ecc71; font-size: 22px; font-weight: bold; }
        
        .container { 
            max-width: 1200px; 
            margin: 0 auto; 
            padding: 20px; 
        }
    </style>
</head>
<body>
<div class="overlay" style="display: block; min-height: 100vh;">
    <nav class="cust-nav">
        <div class="logo">Manager</div>
        <div>
            <a href="manager_dashboard.php">📊 Stats</a>
            <a href="manage_turfs.php">🏟️ Turfs</a>
            <a href="view_bookings.php">📅 Bookings</a>
            <a href="customer_list.php">👥 Customers</a>
            <a href="announcements.php">📢 Offers</a>
            <a href="../controller/logout.php" style="color: #e74c3c; font-weight: bold;">🚪 Logout</a>
        </div>
    </nav>
    <div class="container"></div>