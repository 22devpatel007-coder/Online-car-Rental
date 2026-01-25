<?php
require_once '../config/database.php';
include 'u_head.php';

// Helper: Short and safe echo
function e($s) { return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }

// Fetch Cars
$result = $conn->query("SELECT * FROM cars ORDER BY car_id DESC");
?>

<style>
    /* Compact CSS */
    .header { text-align: center; padding: 40px 0; }
    .header h2 { font-size: 2.5rem; color: #333; margin-bottom: 5px; }
    
    .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px; padding-bottom: 50px; }
    
    .card { background: #fff; border: 1px solid #ddd; border-radius: 10px; overflow: hidden; transition: 0.3s; position: relative; }
    .card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.15); border-color: #007bff; }
    
    .img-box { height: 200px; overflow: hidden; }
    .img-box img { width: 100%; height: 100%; object-fit: cover; transition: 0.5s; }
    .card:hover .img-box img { transform: scale(1.1); }
    
    .info { padding: 20px; }
    .title { font-size: 1.2rem; font-weight: bold; color: #333; }
    .sub { color: #888; font-size: 0.9rem; text-transform: uppercase; margin-bottom: 15px; }
    
    .feats { display: flex; justify-content: space-between; border-block: 1px solid #eee; padding: 10px 0; color: #666; font-size: 0.85rem; margin-bottom: 15px; }
    .price { font-size: 1.3rem; color: #007bff; font-weight: bold; display: block; margin-bottom: 15px; }
    
    /* Buttons */
    .actions { display: flex; gap: 8px; }
    .btn { flex: 1; padding: 10px; text-align: center; border-radius: 5px; text-decoration: none; font-weight: bold; font-size: 0.9rem; transition: 0.2s; }
    .btn-view { border: 1px solid #333; color: #333; }
    .btn-view:hover { background: #333; color: white; }
    .btn-book { background: #007bff; color: white; border: 1px solid #007bff; }
    .btn-book:hover { background: #0056b3; }
    .btn-cart { flex: 0 0 40px; background: #ffc107; color: #000; border: 1px solid #ffc107; font-size: 1.2rem; display: flex; align-items: center; justify-content: center; }
    .btn-cart:hover { background: #e0a800; }
</style>

<div class="container fade-in">
    
    <div class="header">
        <h2>Our Premium Fleet</h2>
        <p style="color:#666;">Luxury and economy vehicles for every journey.</p>
    </div>

    <div class="grid">
        <?php if ($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <div class="card">
                    <div class="img-box">
                        <img src="/online-car-rental/<?= str_replace('\\', '/', $row['image']); ?>" alt="<?= e($row['car_name']) ?>">
                    </div>

                    <div class="info">
                        <div class="title"><?= e($row['car_name']) ?></div>
                        <div class="sub"><?= e($row['brand']) ?> • <?= e($row['model']) ?></div>

                        <div class="feats">
                            <span> <?= e($row['year']) ?></span>
                            <span> <?= e($row['fuel_type']) ?></span>
                            <span> <?= e($row['transmission']) ?></span>
                        </div>

                        <span class="price">₹<?= number_format($row['price_per_day']) ?> <small style="color:#888; font-size:0.8rem;">/ day</small></span>

                        <div class="actions">
                            <a href="car-details.php?id=<?= $row['car_id'] ?>" class="btn btn-view">View</a>
                            <a href="bookings.php?car_id=<?= $row['car_id'] ?>" class="btn btn-book">Book Now</a>
                            
                            <a href="cart.php?action=add&id=<?= $row['car_id'] ?>" class="btn btn-cart" title="Add to Shortlist">
                                cart
                            </a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div style="grid-column:1/-1; text-align:center; padding:50px; color:#888;">
                <h3>No cars available.</h3>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'u_footer.php'; ?>