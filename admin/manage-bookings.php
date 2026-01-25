<?php
session_start();
require_once '../config/database.php';

// 1. SECURITY CHECK (Keep unauthorized users out)
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Helper function to escape HTML (if not already in included functions)
if (!function_exists('e')) {
    function e($string) {
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }
}

// 2. HANDLE STATUS UPDATES
if (isset($_GET['status'], $_GET['id'])) {
    $st = $_GET['status']; 
    $id = (int)$_GET['id'];
    
    // Simple validation for status
    $allowed_status = ['confirmed', 'cancelled', 'completed'];
    if (in_array($st, $allowed_status)) {
        $stmt = mysqli_prepare($conn, "UPDATE bookings SET status=? WHERE booking_id=?");
        mysqli_stmt_bind_param($stmt, 'si', $st, $id);
        mysqli_stmt_execute($stmt);
        
        // Redirect to clean URL
        header("Location: bookings.php");
        exit;
    }
}

// 3. FETCH DATA
$res = mysqli_query($conn, "SELECT b.*, u.full_name, c.car_name FROM bookings b JOIN users u ON b.user_id=u.user_id JOIN cars c ON b.car_id=c.car_id ORDER BY b.created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Bookings | Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
          /* ============================
   ADMIN BOOKINGS (GRID LAYOUT)
   ============================ */

/* 1. The Grid Container */
.grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); /* Responsive columns */
    gap: 25px;
    padding: 20px 0;
}

/* 2. The Booking Card */
.card {
    background-color: white; /* Dark background */
    border: 1px solid #333;
    border-radius: 10px;
    padding: 20px !important;
    box-shadow: 0 4px 10px rgba(0,0,0,0.3);
    transition: transform 0.2s, border-color 0.2s;
    display: flex;
    flex-direction: column;
}

.card:hover {
    transform: translateY(-5px);
    border-color: #555;
}

/* 3. Typography inside Card */
.card h3 {
    margin-top: 0;
    margin-bottom: 10px;
    color: black;
    font-size: 1.25rem;
    border-bottom: 1px solid #333;
    padding-bottom: 10px;
}

.card p {
    color: black;
    font-size: 0.95rem;
    line-height: 1.5;
    margin-bottom: 8px;
}

/* 4. The Badge (Status Line) */
.badge {
    background-color: white;
    color: #ffd700;
    padding: 8px 12px;
    border-radius: 6px;
    font-size: 0.85rem;
    font-weight: bold;
    border: 1px solid #444;
    display: inline-block;
    width: fit-content;
}

/* 5. Button Row */
.row {
    display: flex;
    gap: 10px;
    margin-top: auto;
    padding-top: 15px;
}

/* 6. Buttons Styling */
.button {
    text-decoration: none;
    padding: 8px 16px;
    border-radius: 5px;
    font-size: 0.9rem;
    font-weight: 600;
    text-align: center;
    flex: 1;
    transition: background 0.3s;
    border: none;
    cursor: pointer;
    background-color: #28a745; /* Green */
    color: white;
}

.button:hover {
    background-color: #218838;
}

.button.secondary {
    background-color: white;
    color: black;
    border: 1px solid #555;
}

.button.secondary:hover {
    background-color: black; /* Red */
    color: white;
    border-color: white;
}
    </style>  
</head>
<body>
    <?php include 'admin_sidebar.php'; ?>
    <div class="admin-content">
        
        <div class="admin-header">
            <h2>Manage Bookings</h2>
        </div>

        <div class="admin-panel-container" style="background-color: transparent; border:none; box-shadow:none; padding:0;">
            
            <div class="grid">
                <?php while ($b = mysqli_fetch_assoc($res)): ?>
                    <div class="card fade-in">
                        <h3><?php echo e($b['car_name']); ?> — ₹<?php echo e($b['total_amount']); ?></h3>
                        
                        <p>
                            <strong>Customer:</strong> <?php echo e($b['full_name']); ?><br>
                            <strong>Dates:</strong> <?php echo e($b['pickup_date']); ?> → <?php echo e($b['return_date']); ?><br>
                            <span style="font-size:0.85rem; color:#888;">(<?php echo (int)$b['total_days']; ?> days)</span>
                        </p>
                        
                        <p class="badge">
                            Status: <?php echo ucfirst(e($b['status'])); ?> • Paid: <?php echo ucfirst(e($b['payment_status'])); ?>
                        </p>
                        
                        <div class="row">
                            <?php if($b['status'] == 'pending'): ?>
                                <a class="button" href="?id=<?php echo (int)$b['booking_id']; ?>&status=confirmed">Confirm</a>
                            <?php endif; ?>
                            
                            <a class="button secondary" href="?id=<?php echo (int)$b['booking_id']; ?>&status=cancelled" onclick="return confirm('Cancel this booking?');">Cancel</a>
                            
                            <a class="button secondary" href="?id=<?php echo (int)$b['booking_id']; ?>&status=completed">Complete</a>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>

            <?php if(mysqli_num_rows($res) == 0): ?>
                <div style="text-align:center; padding:50px; color:#888;">
                    <h3>No bookings found.</h3>
                </div>
            <?php endif; ?>

        </div>
    </div>

</div>

</body>
</html>