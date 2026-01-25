<?php 
require_once '../config/database.php'; 
include 'u_head.php'; 

// Smart Helper
function e($s) { return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }

// Fetch Data
$id = (int)($_GET['id'] ?? 0);
$stmt = $conn->prepare("SELECT * FROM cars WHERE car_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$c = $stmt->get_result()->fetch_assoc();

if (!$c) die("<div style='text-align:center;padding:50px;color:white;'><h2>Car Not Found</h2><a href='view-cars.php' style='color:#007bff;'>Return to Fleet</a></div>");
?>

<style>
    /* Compact Smart CSS */
    .box { max-width: 1000px; margin: 40px auto; display: flex; background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.5); }
    
    .img-wrap { flex: 1.2; background: #000; min-height: 400px; }
    .img-wrap img { width: 100%; height: 100%; object-fit: cover; }
    
    .info { flex: 1; padding: 40px; display: flex; flex-direction: column; }
    .head h2 { margin: 0; color: #333; font-size: 2rem; }
    .sub { color: #888; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 20px; }
    
    .price { font-size: 1.8rem; color: #007bff; font-weight: bold; border-bottom: 1px solid #eee; padding-bottom: 20px; margin-bottom: 25px; }
    
    .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 30px; }
    .item { background: #f8f9fa; padding: 10px 15px; border-radius: 8px; border: 1px solid #eee; color: #555; }
    .icon { font-size: 1.2rem; margin-right: 5px; }

    .btns { margin-top: auto; display: flex; gap: 10px; }
    .btn { flex: 1; padding: 12px; text-align: center; border-radius: 6px; text-decoration: none; font-weight: bold; transition: 0.3s; }
    .btn-cart { border: 1px solid #333; color: #333; }
    .btn-cart:hover { background: #333; color: white; }
    .btn-book { background: #007bff; color: white; border: 1px solid #007bff; }
    .btn-book:hover { background: #0056b3; }

    @media(max-width:768px) { .box { flex-direction: column; } .img-wrap { min-height: 250px; } }
</style>

<div class="box fade-in">
    <div class="img-wrap">
        <img src="/online-car-rental/<?= str_replace('\\','/',$c['image']) ?>" alt="<?= e($c['car_name']) ?>">
    </div>

    <div class="info">
        <div class="head">
            <h2><?= e($c['car_name']) ?></h2>
            <div class="sub"><?= e($c['brand']) ?> • <?= e($c['model']) ?></div>
        </div>

        <div class="price">
            ₹<?= number_format($c['price_per_day']) ?> <small style="font-size:0.9rem; color:#999;">/ day</small>
        </div>

        <div class="grid">
            <div class="item"><span class="icon">model:</span> <?= e($c['year']) ?></div>
            <div class="item"><span class="icon">fule:</span> <?= e($c['fuel_type']) ?></div>
            <div class="item"><span class="icon">transmission:</span> <?= e($c['transmission']) ?></div>
            <div class="item"><span class="icon">seats:</span> <?= (int)$c['seats'] ?> Seats</div>
        </div>

        <p style="color:#666; line-height:1.6; margin-bottom:30px;">
            Enjoy a premium driving experience with this <strong><?= e($c['car_name']) ?></strong>. Reliable, comfortable, and efficient.
        </p>

        <div class="btns">
            <a href="cart.php?action=add&id=<?= $c['car_id'] ?>" class="btn btn-cart">🛒 Add to Cart</a>
            
            <a href="bookings.php?car_id=<?= $c['car_id'] ?>" class="btn btn-book">Book Now</a>
        </div>
    </div>
</div>

<?php include 'u_footer.php'; ?>