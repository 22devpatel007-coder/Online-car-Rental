<?php 
require_once('../config/database.php');
 include('u_head.php');
 ?>
<head>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>            
<section class="hero">
    <div class="hero-content">
        <h1>Looking for a <span class="highlight">Ride?</span> Rent a Car in Just a Few Clicks.</h1>
        <p>Experience the freedom of travel with our premium fleet of vehicles. From economy cars to luxury SUVs, we have the perfect ride for your next adventure.</p>
        <div class="hero-btns">
            <a href="view-cars.php" class="btn-primary">Browse Cars</a>
            <a href="../about.php" class="btn-secondary">Learn More</a>
        </div>
    </div>
</section>

<section class="features">
    <h2>Why Choose REant4u?</h2>
    <div class="feature-grid">
        <div class="feature-card">
            <div class="icon"></div>
            <h3>Transparent Pricing</h3>
            <p>No hidden charges. Clear and transparent pricing for all our rental services.</p>
        </div>
        <div class="feature-card">
            <div class="icon"></div>
            <h3>24/7 Support</h3>
            <p>Our support team is always ready to assist you anytime, anywhere.</p>
        </div>
        <div class="feature-card">
            <div class="icon"></div>
            <h3>Fast & Secure</h3>
            <p>Fast and secure online booking system with instant confirmation.</p>
        </div>
    </div>
</section>

<section class="cta-banner">
    <div class="cta-text">
        <h3>Ready to hit the road?</h3>
        <p>Sign up today and get 10% off your first rental!</p>
    </div>
    <a href="user/signup.php" class="btn-white">Get Started Now</a>
</section>

<?php include('u_footer.php'); ?>