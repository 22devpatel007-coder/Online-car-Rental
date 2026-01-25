<?php 
session_start(); // 1. Start Session at the very top
require_once('config/database.php'); 
include('includes/header.php'); 

$message = "";

if (isset($_POST['signup'])) {
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check if email already exists
    $checkEmail = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($checkEmail);

    if ($result->num_rows > 0) {
        $message = "<p style='color:red;'>Email already exists!</p>";
    } else {
        // Set default role as 'user'
        $sql = "INSERT INTO users (full_name, email, password, role) VALUES ('$full_name', '$email', '$password', 'user')";
        
        if ($conn->query($sql) === TRUE) {
            // --- AUTO-LOGIN LOGIC START ---
            
            // 1. Get the ID of the new user we just created
            $new_user_id = $conn->insert_id;

            // 2. Set Session Variables (Logs them in)
            $_SESSION['user_id'] = $new_user_id;
            $_SESSION['user_name'] = $full_name;
            $_SESSION['role'] = 'user';

            // 3. Redirect to View Cars page immediately
            // Note: Adjust path if your view-cars.php is inside a 'user' folder
            header("Location: user/view-cars.php"); 
            exit();

            // --- AUTO-LOGIN LOGIC END ---
        } else {
            $message = "Error: " . $conn->error;
        }
    }
}
?>

<head>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<section class="auth-container">
    <div class="auth-box">
        <h2>Join RentalHub</h2>
        <p>Create an account to start booking cars.</p>
        <?php echo $message; ?>
        <form action="" method="POST"> <input type="text" name="full_name" placeholder="Full Name" required>
            <input type="email" name="email" placeholder="Email Address" required>
            <input type="password" name="password" placeholder="Create Password" required>
            <button type="submit" name="signup" class="btn-primary">Register Now</button>
        </form>
        <p class="auth-footer">Already have an account? <a href="login.php">Login</a></p>
    </div>
</section>

<?php include('includes/footer.php'); ?>