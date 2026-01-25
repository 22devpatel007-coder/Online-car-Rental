<?php 
require_once('config/database.php');
include('includes/header.php');

// PHP Logic (Kept exactly as you had it)
$sent = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = mysqli_prepare($conn, "INSERT INTO contact_messages (name,email,subject,message) VALUES (?,?,?,?)");
    mysqli_stmt_bind_param($stmt, 'ssss', $_POST['name'], $_POST['email'], $_POST['subject'], $_POST['message']);
    $sent = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}
?>
<head>
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<div class="auth-wrapper">    
    <div class="auth-card" style="max-width: 500px;"> <h2>Contact Us</h2>
        <p>Please enter your details to get in touch.</p>
        <?php if ($sent): ?>
            <div class="alert" style="background-color: #28a745; color: white; text-align: center; margin-bottom: 20px;">
                Message sent successfully!
            </div>
        <?php endif; ?>

        <form method="post">
            <div class="form-group">
                <label>Name</label>
                <input type="text" name="name" class="form-control" placeholder="Enter your full name" required>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
            </div>

            <div class="form-group">
                <label>Subject</label>
                <input type="text" name="subject" class="form-control" placeholder="What is this regarding?" required>
            </div>

            <div class="form-group">
                <label>Message</label>
                <textarea name="message" class="form-control" rows="4" placeholder="Type your message here..." style="resize: vertical; min-height: 100px;" required></textarea>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 10px;">Send Message</button>
        </form>

        <div class="auth-footer">
            <p>Prefer to call? <span style="color: #007bff;">+91 98765 43210</span></p>
        </div>

    </div>
</div>

<?php include 'includes/footer.php'; ?>