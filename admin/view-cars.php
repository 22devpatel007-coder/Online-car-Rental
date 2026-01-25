<?php include __DIR__.'/../config/database.php'; include __DIR__.'/../includes/functions.php'; requireAdmin(); include __DIR__.'/../includes/header.php'; ?>
<h2>All cars</h2>
<div class="grid">
<?php
$res = mysqli_query($conn, "SELECT * FROM cars ORDER BY created_at DESC");
while ($c = mysqli_fetch_assoc($res)): ?>
  <div class="card fade-in">
    <img src="/online-car-rental/<?php echo e($c['image']); ?>">
    <div style="padding:12px">
      <h3><?php echo e($c['car_name']); ?></h3>
      <p class="badge">Status: <?php echo e($c['availability']); ?></p>
      <div class="row" style="margin-top:10px">
        <a class="button" href="edit-car.php?id=<?php echo (int)$c['car_id']; ?>">Edit</a>
        <a class="button danger" href="delete-car.php?id=<?php echo (int)$c['car_id']; ?>" onclick="return confirm('Delete car?')">Delete</a>
      </div>
    </div>
  </div>
<?php endwhile; ?>
</div>
<?php include __DIR__.'/../includes/footer.php'; ?>
