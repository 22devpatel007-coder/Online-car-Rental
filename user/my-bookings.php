<?php
session_start();
require_once '../config/database.php';
include 'u_head.php';

// 1. Security & Logic
if (!isset($_SESSION['user_id'])) header("Location: ../login.php");

if (isset($_GET['cancel_id'])) {
    $conn->query("UPDATE bookings SET status='cancelled' WHERE booking_id=".(int)$_GET['cancel_id']." AND user_id=".$_SESSION['user_id']);
    echo "<script>window.location='my_bookings.php';</script>";
}

// 2. Data Fetching
$user_id = $_SESSION['user_id'];
$res = $conn->query("SELECT b.*, c.car_name, c.brand, c.image FROM bookings b JOIN cars c ON b.car_id = c.car_id WHERE b.user_id = $user_id ORDER BY b.booking_id DESC");

// 3. Smart Config for Status Colors
$badge_map = [
    'confirmed'=>'#28a745', 'paid'=>'#28a745', 
    'pending'=>'#ffc107', 
    'cancelled'=>'#dc3545', 'completed'=>'#6c757d'
];
?>

<style>
    .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px; padding: 30px; }
    .card { background: #1a1a1a; border: 1px solid #333; border-radius: 10px; overflow: hidden; transition: 0.3s; }
    .card:hover { transform: translateY(-5px); border-color: #555; }
    .img-box { height: 180px; width: 100%; object-fit: cover; }
    .info { padding: 15px; color: #ccc; font-size: 0.9rem; }
    .head { display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; }
    .title { color: white; font-size: 1.1rem; font-weight: bold; margin: 0; }
    .badge { padding: 4px 10px; border-radius: 15px; font-size: 0.75rem; color: #fff; font-weight: bold; text-transform: uppercase; }
    .row { margin-bottom: 5px; display: flex; justify-content: space-between; }
    .btn-cancel { display: block; width: 100%; text-align: center; margin-top: 15px; padding: 8px; border: 1px solid #dc3545; color: #dc3545; border-radius: 5px; text-decoration: none; transition: 0.2s; }
    .btn-cancel:hover { background: #dc3545; color: white; }
</style>

<div class="grid fade-in">
    <?php while($r = $res->fetch_assoc()): 
        $st = strtolower($r['status']);
        $clr = $badge_map[$st] ?? '#6c757d'; // Default color if status unknown
    ?>
    <div class="card">
        <img src="/online-car-rental/<?php echo str_replace('\\','/',$r['image']); ?>" class="img-box">
        <div class="info">
            <div class="head">
                <h3 class="title"><?php echo $r['brand'].' '.$r['car_name']; ?></h3>
                <span class="badge" style="background:<?php echo $clr; ?>"><?php echo $st; ?></span>
            </div>
            
            <div class="row"><span>📅 Dates:</span> <span style="color:white"><?php echo date('d M', strtotime($r['pickup_date'])); ?> - <?php echo date('d M', strtotime($r['return_date'])); ?></span></div>
            <div class="row"><span>📍 Route:</span> <span><?php echo $r['location']; ?> ➝ <?php echo $r['drop_location']; ?></span></div>
            <div class="row"><span>💰 Total:</span> <span style="color:#28a745; font-weight:bold;">₹<?php echo number_format($r['total_amount']); ?></span></div>

            <?php if($st !== 'cancelled' && $st !== 'completed'): ?>
                <a href="?cancel_id=<?php echo $r['booking_id']; ?>" class="btn-cancel" onclick="return confirm('Cancel this booking?')">Cancel Booking</a>
            <?php endif; ?>
        </div>
    </div>
    <?php endwhile; ?>
    
    <?php if($res->num_rows == 0): ?>
        <div style="grid-column: 1/-1; text-align:center; padding:50px; color:#666;">
            <h3>No bookings yet.</h3>
            <a href="view-cars.php" style="color:#007bff;">Book a Car</a>
        </div>
    <?php endif; ?>
</div>

<?php include 'u_footer.php'; ?>