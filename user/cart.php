<?php
session_start();
require_once '../config/database.php';
include 'u_head.php';

// 1. Initialize Cart
if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

// 2. Handle Actions (Add / Remove)
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    
    if ($_GET['action'] == 'add') {
        // Add if not already in cart
        if (!in_array($id, $_SESSION['cart'])) {
            $_SESSION['cart'][] = $id;
            echo "<script>alert('Added to Shortlist!'); window.location='view-cars.php';</script>";
        }
    } 
    elseif ($_GET['action'] == 'remove') {
        // Remove item
        $key = array_search($id, $_SESSION['cart']);
        if ($key !== false) unset($_SESSION['cart'][$key]);
        header("Location: cart.php"); // Refresh
        exit;
    }
}

// 3. Fetch Cars in Cart
$cars = [];
if (!empty($_SESSION['cart'])) {
    // Create a comma-separated list of IDs (e.g., "1,5,8") for the SQL query
    $ids = implode(',', $_SESSION['cart']); 
    $result = $conn->query("SELECT * FROM cars WHERE car_id IN ($ids)");
    while($row = $result->fetch_assoc()) $cars[] = $row;
}
?>

<style>
    .cart-container { max-width: 900px; margin: 40px auto; padding: 20px; }
    .cart-grid { display: grid; gap: 20px; }
    
    /* Smart Horizontal Card Design */
    .cart-item { 
        display: flex; 
        background: #1a1a1a; 
        border: 1px solid #333; 
        border-radius: 10px; 
        overflow: hidden; 
        align-items: center; 
        padding-right: 20px;
        transition: 0.2s;
    }
    .cart-item:hover { border-color: #555; transform: translateX(5px); }

    .item-img { width: 200px; height: 120px; object-fit: cover; }
    
    .item-info { flex: 1; padding: 0 20px; }
    .item-title { color: white; margin: 0; font-size: 1.2rem; }
    .item-sub { color: #888; font-size: 0.9rem; margin-top: 5px; }
    
    .item-price { color: #28a745; font-weight: bold; font-size: 1.1rem; text-align: right; min-width: 100px;}
    
    .actions { display: flex; gap: 10px; margin-left: 20px; }
    .btn-book { background: #007bff; color: white; text-decoration: none; padding: 8px 15px; border-radius: 5px; font-weight: bold; }
    .btn-remove { color: #dc3545; text-decoration: none; padding: 8px 10px; border: 1px solid #dc3545; border-radius: 5px; }
    .btn-remove:hover { background: #dc3545; color: white; }

    /* Empty State */
    .empty-cart { text-align: center; padding: 50px; color: #666; border: 2px dashed #333; border-radius: 10px; }

    @media (max-width: 600px) {
        .cart-item { flex-direction: column; padding: 15px; text-align: center; }
        .item-img { width: 100%; height: 150px; margin-bottom: 15px; border-radius: 5px; }
        .actions { margin: 15px 0 0 0; justify-content: center; width: 100%; }
        .item-price { text-align: center; margin: 10px 0; }
    }
</style>

<div class="cart-container fade-in">
    <h2 style="color:#0000; margin-bottom:20px; border-bottom:1px solid #333; padding-bottom:10px;">
        My Shortlist <span style="font-size:0.9rem; color:#0000;">(<?php echo count($cars); ?> cars)</span>
    </h2>

    <?php if (empty($cars)): ?>
        <div class="empty-cart">
            <h3>Your list is empty.</h3>
            <p>Save cars here to compare them before booking.</p>
            <a href="view-cars.php" class="btn-book" style="margin-top:15px; display:inline-block;">Browse Cars</a>
        </div>
    <?php else: ?>
        <div class="cart-grid">
            <?php foreach ($cars as $c): ?>
            <div class="cart-item">
                <img src="/online-car-rental/<?php echo str_replace('\\','/',$c['image']); ?>" class="item-img">
                
                <div class="item-info">
                    <h3 class="item-title"><?php echo $c['brand'].' '.$c['car_name']; ?></h3>
                    <div class="item-sub">
                        ⛽ <?php echo $c['fuel_type']; ?> &nbsp;•&nbsp; ⚙️ <?php echo $c['transmission']; ?> &nbsp;•&nbsp; 💺 <?php echo $c['seats']; ?> Seats
                    </div>
                </div>

                <div class="item-price">
                    ₹<?php echo number_format($c['price_per_day']); ?><br>
                    <span style="font-size:0.7rem; color:#666; font-weight:normal;">/ day</span>
                </div>

                <div class="actions">
                    <a href="bookings.php?car_id=<?php echo $c['car_id']; ?>" class="btn-book">Book Now</a>
                    <a href="cart.php?action=remove&id=<?php echo $c['car_id']; ?>" class="btn-remove">Remove</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php include 'u_footer.php'; ?>