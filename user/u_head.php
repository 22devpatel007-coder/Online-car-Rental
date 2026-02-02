<?php
// 1. Start Session to check login status
if (session_status() === PHP_SESSION_NONE) session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RentalHub - Online Car Rental</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <header class="main-header">
        <nav class="navbar">
            <a href="dashboard.php" class="logo">
                <span class="logo-icon"></span>RentalHUb
            </a>
            
            <ul class="nav-links">
                <li><a href="dashboard.php">HOME</a></li>
                <li><a href="view-cars.php">CAR</a></li>
                
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li><a href="my-bookings.php">MY BOOKING</a></li>
                <?php endif; ?>
                
                <li><a href="cart.php">MY CART</a></li>
                <li><a href="../contact.php">CONTACT US</a></li>
            </ul>

            <div class="auth-buttons">
                <?php if (isset($_SESSION['user_id'])): ?>
                    
                    <span style="font-size: 0.9rem; margin-right: 15px; font-weight: bold; color: #1e3a8a;">
                        Hi, <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'User'); ?>
                    </span>
                    <a href="logout.php" class="btn-join" style="background-color: #dc3545;">Logout</a>

                <?php else: ?>
                    
                    <a href="../login.php" class="btn-login">Login</a>
                    <a href="../signup.php" class="btn-join">Join</a>

                <?php endif; ?>
            </div>
        </nav>
    </header>