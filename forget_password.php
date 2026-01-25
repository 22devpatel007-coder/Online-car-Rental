<?php
session_start();
require_once 'config/database.php'; // Adjust path if needed

$msg = '';
$err = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);

    // 1. Check if email exists in DB
    $stmt = $conn->prepare("SELECT user_id, full_name FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows > 0) {
        // 2. LOGIC: Here you would generate a token and send an email.
        // For this demo, we simulate a success message.
        $user = $res->fetch_assoc();
        $msg = "Reset Link sent to " . htmlspecialchars($email);
    } else {
        $err = "We couldn't find an account with that email.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password | DriveEasy</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <div class="auth-wrapper">
        <div class="auth-card">
            
            <h2 style="color:#1e3a8a;">Forgot Password?</h2>
            <p style="color:#666; margin-bottom:20px;">Enter your email and we'll send you a reset link.</p>

            <?php if($msg): ?>
                <div style="background:#d4edda; color:#155724; padding:10px; border-radius:5px; margin-bottom:15px; border:1px solid #c3e6cb;">
                     <?php echo $msg; ?>
                </div>
            <?php endif; ?>

            <?php if($err): ?>
                <div style="background:#f8d7da; color:#721c24; padding:10px; border-radius:5px; margin-bottom:15px; border:1px solid #f5c6cb;">
                    ❌ <?php echo $err; ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" class="form-control" placeholder="name@example.com" required>
                </div>

                <button type="submit" class="btn btn-primary" style="width:100%;">Send Reset Link</button>
            </form>

            <div class="auth-footer">
                Remember your password? <a href="login.php">Login here</a>
            </div>

        </div>
    </div>

</body>
</html>