<?php 
// 1. Start Session & Include Config
if (session_status() === PHP_SESSION_NONE) session_start();
include '../config/database.php'; 
include 'u_head.php'; 

// Force Login Check
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

// 2. Fetch Car Details
$car_id = (int)($_GET['car_id'] ?? $_POST['car_id'] ?? 0);

$stmt = mysqli_prepare($conn, "SELECT * FROM cars WHERE car_id=?");
mysqli_stmt_bind_param($stmt, 'i', $car_id);
mysqli_stmt_execute($stmt);
$c = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if (!$c) { 
    echo "<div class='container' style='padding:50px; text-align:center; color:white;'><h2>Car not found.</h2></div>"; 
    include 'u_footer.php'; 
    exit; 
}

// 3. Handle Form Submission
$err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pickup_date = $_POST['pickup_date']; 
    $return_date = $_POST['return_date'];
    
    // --- NEW FIELDS ---
    $location = $_POST['location'];       // Pickup Location
    $drop_location = $_POST['drop_location']; // Drop Location
    $phone = $_POST['phone'];             // Phone Number
    // ------------------

    // Calculate Days
    $days = (strtotime($return_date) - strtotime($pickup_date)) / (60*60*24);
    
    if ($days <= 0) {
        $err = 'Return date must be after pickup date';
    } else {
        $amount = $days * (float)$c['price_per_day'];
        
        // --- UPDATED SQL QUERY ---
        // Added 'drop_location' to the INSERT statement
        $sql = "INSERT INTO bookings (user_id, car_id, location, drop_location, phone_number, pickup_date, return_date, total_days, total_amount, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')";
        
        $stmt2 = mysqli_prepare($conn, $sql);
        
        // Bind params: iisssssid 
        // user_id(i), car_id(i), location(s), drop_location(s), phone(s), pickup(s), return(s), days(i), amount(d)
        mysqli_stmt_bind_param($stmt2, 'iisssssid', $_SESSION['user_id'], $car_id, $location, $drop_location, $phone, $pickup_date, $return_date, $days, $amount);
        
        $created = mysqli_stmt_execute($stmt2);
        
        if ($created) {
            $new_booking_id = mysqli_insert_id($conn);
            header("Location: payment.php?booking_id=" . $new_booking_id);
            exit;
        } else {
            $err = 'Could not create booking. DB Error: ' . mysqli_error($conn);
        }
    }
}
?>
<head>
    <style>
        /* CSS remains exactly the same as your previous code */
        /* --- BOOKING PAGE --- */
        .booking-container { max-width: 1000px; margin: 50px auto; display: flex; gap: 30px; padding: 0 20px; align-items: flex-start; }
        .booking-car-card { flex: 1; background-color: #fff; border: 1px solid #000; border-radius: 12px; padding: 25px; box-shadow: 0 10px 30px rgba(0,0,0,0.5); }
        .booking-car-card h3 { color: #000; margin-top: 10px; font-size: 1.5rem; } 
        .booking-car-card p { color: #555; margin-bottom: 5px; }
        .booking-car-card .price { font-size: 1.2rem; color: #007bff; font-weight: bold; margin-top: 15px; display: block; }
        .booking-form-card { flex: 1.2; background-color: #fff; border: 1px solid #000; border-radius: 12px; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.5); }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; color: #333; margin-bottom: 8px; font-size: 0.9rem; }
        .form-control { width: 100%; padding: 12px; background-color: #fff; border: 1px solid #444; border-radius: 6px; color: #000; font-size: 1rem; transition: 0.3s; }
        .form-control:focus { border-color: #007bff; outline: none; background-color: #f9f9f9; }
        .booking-summary { padding: 20px; border-radius: 8px; margin-top: 25px; border: 1px solid #333; }
        .summary-row { display: flex; justify-content: space-between; margin-bottom: 10px; color: #000; font-size: 0.95rem; }
        .summary-total { display: flex; justify-content: space-between; margin-top: 15px; padding-top: 15px; border-top: 1px solid #444; color: #000; font-weight: bold; font-size: 1.2rem; }
        .summary-total span:last-child { color: #28a745; }
        .btn-primary { background-color: #007bff; color: white; border: none; padding: 12px 20px; border-radius: 6px; font-size: 1rem; font-weight: 600; cursor: pointer; transition: background 0.3s; }
        .btn-primary:hover { background-color: #0056b3; }
        .alert { background-color: #dc3545; color: white; padding: 12px; border-radius: 6px; margin-bottom: 20px; font-size: 0.9rem; }
        
        /* Layout for side-by-side inputs */
        .row-inputs { display: flex; gap: 15px; }
        .row-inputs .form-group { flex: 1; }

        /* --- RESPONSIVE MEDIA QUERIES --- */
        @media (max-width: 768px) {
            /* 1. Stack the main container vertically */
            .booking-container {
                flex-direction: column;
                margin: 20px auto;
                padding: 0 15px;
            }

            /* 2. Make cards full width */
            .booking-car-card,
            .booking-form-card {
                width: 100%;
                flex: none; /* Override flex: 1 */
            }

            /* 3. Stack the input rows (e.g. Pickup/Drop Locations) vertically */
            .row-inputs {
                flex-direction: column;
                gap: 0;
            }
        }
    </style>
</head>

<div class="booking-container fade-in">
    
    <div class="booking-car-card">
        <h4 style="color:#555; margin-bottom:15px;">You are booking:</h4>
        
        <div style="height: 200px; overflow:hidden; border-radius:8px; margin-bottom:15px;">
            <img src="/online-car-rental/<?php echo str_replace('\\', '/', $c['image']); ?>" 
                 alt="<?php echo htmlspecialchars($c['car_name']); ?>"
                 style="width:100%; height:100%; object-fit:cover;">
        </div>

        <h3><?php echo htmlspecialchars($c['car_name']); ?></h3>
        <p><?php echo htmlspecialchars($c['brand']); ?> • <?php echo htmlspecialchars($c['model']); ?></p>
        <p class="price">₹<span id="pricePerDay"><?php echo number_format($c['price_per_day']); ?></span> / day</p>
    </div>

    <div class="booking-form-card">
        <h2 style="color:#333; margin-bottom: 20px;">Confirm Booking</h2>
        
        <?php if ($err): ?>
            <div class="alert">
                <?php echo htmlspecialchars($err); ?>
            </div>
        <?php endif; ?>

        <form method="post">
            <input type="hidden" name="car_id" value="<?php echo (int)$car_id; ?>">
            
            <div class="row-inputs">
                <div class="form-group">
                    <label>Pickup Location</label>
                    <input type="text" name="location" class="form-control" placeholder="City or Airport" required>
                </div>

                <div class="form-group">
                    <label>Drop Location</label>
                    <input type="text" name="drop_location" class="form-control" placeholder="City or Airport" required>
                </div>
            </div>

            <div class="form-group">
                <label>Phone Number</label>
                <input type="tel" name="phone" class="form-control" placeholder="Enter contact number" required pattern="[0-9]{10}" title="Please enter a valid 10-digit number">
            </div>

            <div class="row-inputs">
                <div class="form-group">
                    <label>Pickup Date</label>
                    <input type="date" name="pickup_date" id="pickup" class="form-control" required min="<?php echo date('Y-m-d'); ?>">
                </div>

                <div class="form-group">
                    <label>Return Date</label>
                    <input type="date" name="return_date" id="return" class="form-control" required min="<?php echo date('Y-m-d'); ?>">
                </div>
            </div>

            <div class="booking-summary">
                <div class="summary-row">
                    <span>Total Days:</span>
                    <span id="totalDays">0</span>
                </div>
                <div class="summary-total">
                    <span>Total to Pay:</span>
                    <span>₹ <span id="totalAmount">0</span></span>
                </div>
            </div>

            <button type="submit" class="btn btn-primary" style="width:100%; margin-top:20px;">Proceed to Payment</button>
        </form>
    </div>
</div>

<script>
    const pickupInput = document.getElementById('pickup');
    const returnInput = document.getElementById('return');
    const pricePerDay = <?php echo (float)$c['price_per_day']; ?>; 
    const daysDisplay = document.getElementById('totalDays');
    const amountDisplay = document.getElementById('totalAmount');

    function updatePrice() {
        const d1 = new Date(pickupInput.value);
        const d2 = new Date(returnInput.value);

        if (d1 && d2 && d2 > d1) {
            const diffTime = Math.abs(d2 - d1);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)); 
            
            daysDisplay.innerText = diffDays;
            amountDisplay.innerText = (diffDays * pricePerDay).toLocaleString('en-IN');
        } else {
            daysDisplay.innerText = "0";
            amountDisplay.innerText = "0";
        }
    }

    pickupInput.addEventListener('change', updatePrice);
    returnInput.addEventListener('change', updatePrice);
</script>

<?php include 'u_footer.php'; ?>