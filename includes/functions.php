 <?php include '../user/login.php';?>
 <?php 
if (session_status() === PHP_SESSION_NONE) session_start();

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function requireLogin() {
    if (isLoggedIn()) {
        header('Location: /online-car-rental/user/');
        exit;   
    }
    else{
        header('Location: /online-car-rental/user/login.php');
    }
}

function isAdmin() {
    return isset($_SESSION['admin_id']);
}

function requireAdmin() {
    if (!isAdmin()) {
        header('Location: /online-car-rental/admin/login.php');
        exit;
    }
}
?> 
