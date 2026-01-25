<?php include __DIR__.'/../config/database.php'; include __DIR__.'/../includes/functions.php'; requireAdmin();
$id = (int)($_GET['id'] ?? 0);
$stmt = mysqli_prepare($conn, "DELETE FROM cars WHERE car_id=?");
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
header('Location: view-cars.php');
