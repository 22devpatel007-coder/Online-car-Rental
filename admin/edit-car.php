<?php include __DIR__.'/../config/database.php'; include __DIR__.'/../includes/functions.php'; requireAdmin(); include __DIR__.'/../includes/header.php'; ?>
<?php
$id = (int)($_GET['id'] ?? 0);
$stmt = mysqli_prepare($conn, "SELECT * FROM cars WHERE car_id=?");
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$c = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
if (!$c) { echo "<p>Car not found.</p>"; include __DIR__.'/../includes/footer.php'; exit; }

$ok=false;
if ($_SERVER['REQUEST_METHOD']==='POST') {
  $img = $c['image'];
  if (!empty($_FILES['image']['name'])) {
    $name = time().'_'.basename($_FILES['image']['name']);
    $target = __DIR__.'/../uploads/'.$name;
    if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) $img = 'uploads/'.$name;
  }
  $stmt2 = mysqli_prepare($conn, "UPDATE cars SET car_name=?, brand=?, model=?, year=?, price_per_day=?, fuel_type=?, seats=?, transmission=?, image=?, availability=? WHERE car_id=?");
  mysqli_stmt_bind_param($stmt2, 'sssidsissssi',
    $_POST['car_name'], $_POST['brand'], $_POST['model'], $_POST['year'], $_POST['price_per_day'],
    $_POST['fuel_type'], $_POST['seats'], $_POST['transmission'], $img, $_POST['availability'], $id
  );
  $ok = mysqli_stmt_execute($stmt2);
}
?>
<div class="card fade-in" style="padding:16px">
  <h2>Edit car</h2>
  <?php if ($ok): ?><p class="badge">Updated.</p><?php endif; ?>
  <form method="post" enctype="multipart/form-data">
    <div class="form-group"><label>Car name</label><input name="car_name" value="<?php echo e($c['car_name']); ?>" required></div>
    <div class="form-group"><label>Brand</label><input name="brand" value="<?php echo e($c['brand']); ?>" required></div>
    <div class="form-group"><label>Model</label><input name="model" value="<?php echo e($c['model']); ?>" required></div>
    <div class="row">
      <div class="col"><div class="form-group"><label>Year</label><input type="number" name="year" value="<?php echo (int)$c['year']; ?>" required></div></div>
      <div class="col"><div class="form-group"><label>Price/day</label><input type="number" step="0.01" name="price_per_day" value="<?php echo e($c['price_per_day']); ?>" required></div></div>
    </div>
    <div class="row">
      <div class="col"><div class="form-group"><label>Fuel type</label><input name="fuel_type" value="<?php echo e($c['fuel_type']); ?>" required></div></div>
      <div class="col"><div class="form-group"><label>Seats</label><input type="number" name="seats" value="<?php echo (int)$c['seats']; ?>" required></div></div>
    </div>
    <div class="form-group"><label>Transmission</label><input name="transmission" value="<?php echo e($c['transmission']); ?>" required></div>
    <div class="form-group"><label>Image</label><input type="file" name="image" accept="image/*"></div>
    <div class="form-group"><label>Availability</label>
      <select name="availability">
        <option <?php if($c['availability']=='available') echo 'selected'; ?>>available</option>
        <option <?php if($c['availability']=='booked') echo 'selected'; ?>>booked</option>
      </select>
    </div>
    <button class="button">Update</button>
  </form>
</div>
<?php include __DIR__.'/../includes/footer.php'; ?>
