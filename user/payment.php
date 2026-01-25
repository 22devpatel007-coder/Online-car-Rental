<?php
session_start();
require_once '../config/database.php';
include 'u_head.php';

$booking_id = (int)($_GET['booking_id'] ?? 0);

// Fetch Booking Info
$stmt = $conn->prepare("SELECT b.*, c.car_name, c.brand FROM bookings b JOIN cars c ON b.car_id=c.car_id WHERE booking_id=? AND user_id=?");
$stmt->bind_param("ii", $booking_id, $_SESSION['user_id']);
$stmt->execute();
$b = $stmt->get_result()->fetch_assoc();

if (!$b) die("<h2 style='color:white;text-align:center;padding:50px;'>Invalid Booking</h2>");

// Handle Payment
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Simulate Success
    $conn->query("UPDATE bookings SET status='confirmed', payment_status='paid' WHERE booking_id=$booking_id");
    echo "<script>alert('Payment Successful!'); window.location='my-bookings.php';</script>";
}
?>

<style>
  .pay-box { max-width: 850px; margin: 40px auto; display: flex; gap: 30px; color: white; }
  .receipt, .pay-form { background: #1a1a1a; border: 1px solid #333; padding: 25px; border-radius: 10px; }
  .receipt { flex: 1; }
  .pay-form { flex: 1.5; }
  .row { display: flex; justify-content: space-between; margin-bottom: 10px; color: #ccc; }
  .total { border-top: 1px solid #444; margin-top: 15px; padding-top: 15px; font-size: 1.5rem; color: #28a745; font-weight: bold; }
  
  input, select { 
      width: 100%; 
      padding: 12px; 
      margin-bottom: 15px; 
      background: #252525; 
      border: 1px solid #444; 
      color: white; 
      border-radius: 5px; 
  }
  
  .hidden { display: none; }
  
  .btn { 
      width: 100%; 
      padding: 12px; 
      background: #007bff; 
      color: white; 
      border: none; 
      border-radius: 5px; 
      cursor: pointer; 
      font-size: 1.1rem; 
      font-weight: bold;
      margin-top: 10px;
  }
  .btn:hover { background: #0056b3; }
  
  /* Mobile Responsive */
  @media (max-width: 768px) {
      .pay-box { flex-direction: column; }
  }
</style>

<div class="pay-box fade-in">
    <div class="receipt">
        <h3>Payment Receipt</h3>
        <p style="color:#888; font-size:0.9rem;">Order #<?php echo $booking_id; ?></p>
        <hr style="border-color:#333; margin:15px 0;">
        <div class="row"><span>Car</span> <span><?php echo $b['brand']." ".$b['car_name']; ?></span></div>
        <div class="row"><span>Dates</span> <span><?php echo $b['pickup_date']; ?> to <?php echo $b['return_date']; ?></span></div>
        <div class="row"><span>Days</span> <span><?php echo $b['total_days']; ?> Days</span></div>
        <div class="row total"><span>Total</span> <span>₹<?php echo number_format($b['total_amount']); ?></span></div>
    </div>

    <div class="pay-form">
        <h3 style="margin-bottom:20px;">Choose Payment Method</h3>
        
        <form method="POST">
            <label style="color:#ccc; margin-bottom:5px; display:block;">Select Method</label>
            <select name="method" id="method" onchange="toggleForm()">
                <option value="upi">UPI (GPay / PhonePe / Paytm)</option>
                <option value="card">Debit / Credit Card</option>
                <option value="net">Net Banking</option>
            </select>

            <div id="upi-sec">
                <input type="text" placeholder="Enter UPI ID (e.g. 9876543210@ybl)" required>
            </div>

            <div id="card-sec" class="hidden">
                <input type="text" placeholder="Card Number (0000 0000 0000 0000)" maxlength="19">
                <div style="display:flex; gap:10px;">
                    <input type="text" placeholder="MM/YY" maxlength="5">
                    <input type="password" placeholder="CVV" maxlength="3">
                </div>
                <input type="text" placeholder="Cardholder Name">
            </div>

            <div id="net-sec" class="hidden">
                <select>
                    <option>State Bank of India</option>
                    <option>HDFC Bank</option>
                    <option>ICICI Bank</option>
                    <option>Axis Bank</option>
                    <option>Kotak Mahindra Bank</option>
                </select>
            </div>

            <button type="submit" class="btn">Pay Now</button>
        </form>
    </div>
</div>

<script>
function toggleForm() {
    let m = document.getElementById('method').value;
    
    // Hide all sections first
    document.getElementById('upi-sec').classList.add('hidden');
    document.getElementById('card-sec').classList.add('hidden');
    document.getElementById('net-sec').classList.add('hidden');
    
    // Show the selected section
    document.getElementById(m + '-sec').classList.remove('hidden');
}
</script>

<?php include 'u_footer.php'; ?>