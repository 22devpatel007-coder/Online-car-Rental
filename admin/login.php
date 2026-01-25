<?php
session_start();
require_once '../config/database.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // 1. Check Database
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // 2. Verify Password (Simple comparison for this project)
        // If you used hashing, use: if (password_verify($password, $user['password'])) {
        if ($password === $user['password']) {
            
            // 3. Set Session Variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['role'] = $user['role'];

            // 4. Redirect based on Role
            if ($user['role'] === 'admin') {
                header("Location: admin/dashboard.php");
            } else {
                header("Location: index.php");
            }
            exit();
        } else {
            $error = "Incorrect password.";
        }
    } else {
        $error = "No account found with this email.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | REntalHub</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

    <header>
        <div class="container nav-container">
            <div class="logo">
                <div class="logo-icon"></div>
                <span>REntalHub</span>
            </div>
            <a href="index.php" style="color:#aaa;">&larr; Back to Home</a>
        </div>
    </header>

    <div class="auth-wrapper">
        <div class="auth-card">
            <h2>Welcome Back</h2>
            <p>Login to manage your bookings</p>

            <?php if($error): ?>
                <div class="alert"><?php echo $error; ?></div>
            <?php endif; ?>

            <form action="login.php" method="POST">
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%;">Sign In</button>
            </form>

            <!-- <div class="auth-footer">
                Don't have an account? <a href="register.php">Sign Up</a>
            </div> -->
        </div>
    </div>

</body>
</html>