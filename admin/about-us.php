<?php include __DIR__.'/../config/database.php'; include __DIR__.'/../includes/functions.php'; requireAdmin(); include __DIR__.'/../includes/header.php'; ?>
<?php
$ok=false;
if ($_SERVER['REQUEST_METHOD']==='POST') {
  $content = $_POST['content'];
  file_put_contents(__DIR__.'/../pages/about-content.html', $content);
  $ok=true;
}
$content = '';
if (file_exists(__DIR__.'/../pages/about-content.html')) {
  $content = file_get_contents(__DIR__.'/../pages/about-content.html');
}
?>
<div class="card fade-in" style="padding:16px">
  <h2>Edit About Us</h2>
  <?php if ($ok): ?><p class="badge">Updated successfully.</p><?php endif; ?>
  <form method="post">
    <div class="form-group"><label>Content</label>
      <textarea name="content" rows="8" style="width:100%"><?php echo e($content); ?></textarea>
    </div>
    <button class="button">Save</button>
  </form>
</div>
<?php include __DIR__.'/../includes/footer.php'; ?>

<?php include __DIR__.'/../config/database.php'; include __DIR__.'/../includes/header.php'; ?>
<div class="card fade-in" style="padding:16px">
  <h2>About Us</h2>
  <?php
  if (file_exists(__DIR__.'/about-content.html')) {
    echo file_get_contents(__DIR__.'/about-content.html');
  } else {
    echo "<p>We make car rentals simple and reliable.</p>";
  }
  ?>
</div>
<?php include __DIR__.'/../includes/footer.php'; ?>
