<?php
session_start();
require_once 'config/database.php'; // Make sure this path is correct
// include '../includes/header.php';
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // 1. SMART QUERY: Use Prepared Statements (Safer than your screenshot code)
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // 2. VERIFY PASSWORD
        // ... existing password verification code ...

        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['user_id']; // Matches your DB column
            $_SESSION['user_name'] = $user['full_name']; // Matches your DB column
             $_SESSION['role'] = $user['role'];

             // CORRECTED REDIRECT LOGIC
             if ($user['role'] === 'admin') {
             // Admin goes to the Dashboard
                header("Location: admin/dashboard.php"); 
              } else {
              // Regular User goes to the Homepage
             header("Location: user/dashboard.php");
            }
             exit();
        } else {
            $error = "Invalid password!";
        }
    } else {
        $error = "No account found with that email!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | DriveEasy</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

    <?php include 'includes/header.php'; ?>

    <div class="auth-wrapper">
        <div class="auth-card">
            <h2>Welcome Back</h2>
            <p>Please enter your details to sign in.</p>

            <?php if($error): ?>
                <div class="alert" style="background: #ff4444; color: white; border-radius: 5px; padding: 10px; margin-bottom: 15px;">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form action="login.php" method="POST">
                
                <div class="form-group">
                    <input type="email" name="email" class="form-control" 
                           placeholder="Enter your email" 
                           value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" 
                           required>
                </div>

                <div class="form-group">
                    <input type="password" name="password" class="form-control" 
                           placeholder="Enter your password" required>
                </div>

                <div style="display: flex; justify-content: space-between; margin-bottom: 20px; font-size: 0.85rem; color: #888;">
                    <label><input type="checkbox"> Remember me</label>
                    <a href="forget_password.php" style="color: #666;">Forgot Password?</a>
                </div>

                <button type="submit" name="login" class="btn btn-primary" style="width: 100%;">Login</button>
            </form>

            <div class="auth-footer">
                New here? <a href="signup.php" style="color: #007bff; font-weight: 600;">Sign Up</a>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>

</body>
</html>