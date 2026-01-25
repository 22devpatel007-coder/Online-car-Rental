
<?php
// Offline-friendly WAMP connection (works across versions)
$DB_HOST = 'localhost';
$DB_USER = 'root';
$DB_PASS = ''; // Change if your WAMP has a password
$DB_NAME = 'car_rental_db';

$conn = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if (!$conn) {
    die('Database connection failed: ' . mysqli_connect_error());
}

mysqli_set_charset($conn, 'utf8mb4');

// // Common helper: sanitize output
// function e($str) {
//     return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8');
// }

date_default_timezone_set('Asia/Kolkata'); // Vadodara/India
?>