<?php
session_start();
require_once '../config/database.php'; // Go up one level to find config

// 1. SECURITY CHECK: Ensure user is logged in AND is an 'admin'
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// 2. FETCH LIVE DATA COUNTS
// Count Users (excluding admins)
$userCount = 0;
$sql = "SELECT COUNT(*) as total FROM users WHERE role = 'user'";
$result = $conn->query($sql);
if($result) $userCount = $result->fetch_assoc()['total'];

// Count Cars
$carCount = 0;
$sql = "SELECT COUNT(*) as total FROM cars";
$result = $conn->query($sql);
if($result) $carCount = $result->fetch_assoc()['total'];

// Count Bookings
$bookingCount = 0;
$checkTable = $conn->query("SHOW TABLES LIKE 'bookings'");
if($checkTable && $checkTable->num_rows > 0) {
    $sql = "SELECT COUNT(*) as total FROM bookings";
    $result = $conn->query($sql);
    if($result) $bookingCount = $result->fetch_assoc()['total'];
}

// Count Messages
$msgCount = 0;
$checkTable = $conn->query("SHOW TABLES LIKE 'contact_messages'");
if($checkTable && $checkTable->num_rows > 0) {
    $sql = "SELECT COUNT(*) as total FROM contact_messages";
    $result = $conn->query($sql);
    if($result) $msgCount = $result->fetch_assoc()['total'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | DriveEasy</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        /* --- ADMIN DASHBOARD STYLES --- */

        /* 1. Page Layout */
        body {
            background-color: #f4f7f6;
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* Adjust content to sit next to sidebar (Assuming Sidebar is fixed width ~250px) */
        .admin-content {
            margin-left: 250px; /* Pushes content right */
            padding: 30px;
            min-height: 100vh;
            transition: margin-left 0.3s;
        }

        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .admin-header h2 {
            margin: 0;
            color: #333;
        }

        /* 2. Stats Grid (Responsive) */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); 
            gap: 25px;
            margin-bottom: 40px;
        }

        .stat-card {
            background-color: #fff;
            border: 1px solid #ddd;
            padding: 25px;
            border-radius: 12px;
            text-align: center;
            transition: transform 0.3s, box-shadow 0.3s;
            display: flex;
            flex-direction: column;
            justify-content: center;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 123, 255, 0.15);
            border-color: #007bff;
        }

        .stat-card span {
            display: block;
            color: #666;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 10px;
            font-weight: 600;
        }

        .stat-card h1 {
            font-size: 2.5rem;
            color: #333;
            margin: 0;
            font-weight: 700;
        }

        /* 3. Action Buttons */
        .btn {
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            transition: 0.3s;
            display: inline-block;
        }
        .btn-primary {
            background-color: #007bff;
            color: white;
            border: 1px solid #007bff;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }

        /* 4. Tables (Responsive Wrapper) */
        .table-responsive {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            margin-top: 20px;
            border-radius: 8px;
            border: 1px solid #ddd;
            background: white;
        }

        .admin-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 600px; 
        }

        .admin-table th, 
        .admin-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
            white-space: nowrap;
            color: #333;
        }

        .admin-table th {
            background-color: #f8f9fa;
            color: #555;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
        }

        .admin-table tr:hover {
            background-color: #f1f1f1;
        }

        /* =========================================
           MOBILE MEDIA QUERIES (Max-Width 768px)
           ========================================= */
        @media screen and (max-width: 768px) {
            
            /* Sidebar Reset */
            .admin-content {
                margin-left: 0; /* Full width on mobile */
                padding: 20px;
            }

            /* Adjust Grid */
            .stats-grid {
                gap: 15px;
                grid-template-columns: 1fr; /* Stack cards vertically */
            }

            /* Adjust Header */
            .admin-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }

            /* Stat Cards */
            .stat-card {
                padding: 20px;
                flex-direction: row; /* Horizontal layout on mobile looks nice too */
                justify-content: space-between;
                align-items: center;
                text-align: left;
            }
            .stat-card span { margin-bottom: 0; }
            .stat-card h1 { font-size: 1.8rem; }

            /* Table Fonts */
            .admin-table th, 
            .admin-table td {
                padding: 10px;
                font-size: 0.85rem;
            }
        }
    </style>
</head>
<body>

<?php include 'admin_sidebar.php'; ?>

<div class="admin-content">
    
    <div class="admin-header">
        <div>
            <h2>Dashboard Overview</h2>
            <p style="color: #666; margin-top: 5px;">Welcome back, <strong><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Admin'); ?></strong></p>
        </div>
        <div style="color: #666; font-size: 0.9rem; background: white; padding: 8px 15px; border-radius: 20px; border: 1px solid #ddd;">
            📅 <?php echo date('l, F j, Y'); ?>
        </div>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <span>Registered Users</span>
            <h1><?php echo $userCount; ?></h1>
        </div>
        
        <div class="stat-card">
            <span>Total Cars</span>
            <h1><?php echo $carCount; ?></h1>
        </div>

        <div class="stat-card">
            <span>Total Bookings</span>
            <h1><?php echo $bookingCount; ?></h1>
        </div>

        <div class="stat-card">
            <span>New Messages</span>
            <h1><?php echo $msgCount; ?></h1>
        </div>
    </div>

    <div style="background: white; padding: 25px; border-radius: 10px; border: 1px solid #ddd; margin-top: 30px;">
        <h3 style="margin-top: 0; margin-bottom: 20px; color: #333;">Quick Actions</h3>
        
        <div style="display: flex; gap: 15px; flex-wrap: wrap;">
            <a href="manage_cars.php" class="btn btn-primary">+ Add New Car</a>
            <a href="users.php" class="btn" style="background: #333; color: white;">View All Users</a>
            <a href="manage-bookings.php" class="btn" style="background: #28a745; color: white;">Manage Bookings</a>
        </div>
    </div>

</div>

</body>
</html>