<?php include __DIR__.'/../config/database.php'; include __DIR__.'/../includes/functions.php'; requireAdmin(); include __DIR__.'/../includes/header.php'; ?>
<h2>Admin dashboard</h2>
<div class="row">
  <a class="button" href="add-car.php">Add car</a>
  <a class="button" href="view-cars.php">View cars</a>
  <a class="button" href="manage-bookings.php">Manage bookings</a>
  <a class="button" href="manage-users.php">Manage users</a>
  <a class="button secondary" href="logout.php">Logout</a>
</div>
<?php include __DIR__.'/../includes/footer.php'; ?>
