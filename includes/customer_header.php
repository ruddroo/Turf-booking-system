<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="../assets/style.css">
    <title>Customer Panel - GreenField Turf</title>
    <style>
        .cust-nav { 
            background: rgba(255, 255, 255, 0.1); 
            backdrop-filter: blur(10px); 
            padding: 15px 50px; 
            display: flex; 
            justify-content: space-between; 
            align-items: center;
            border-bottom: 1px solid rgba(255,255,255,0.2);
        }
        .cust-nav a { color: white; text-decoration: none; margin-left: 20px; font-weight: 500; }
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
        <div class="logo">Customer</div>
        <div>
            <a href="customer_dashboard.php">🏠 Home</a>
            <a href="search_turf.php">🔍 Search Turfs</a>
            <a href="my_bookings.php">📅 My Bookings</a>
            <a href="profile.php">👤 My Profile</a>
            <a href="../controller/logout.php" style="color: #e74c3c;">🚪 Logout</a>
        </div>
    </nav>
    <div class="container">