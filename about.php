<?php 
require_once('config/database.php');
// Ensure these paths match your folder structure. 
// If this file is in the 'user' folder, change to '../includes/header.php'
include('includes/header.php'); 
?>

<head>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        /* --- ABOUT PAGE SPECIFIC CSS --- */
        
        /* 1. Page Header */
        .page-banner {
            background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('assets/images/hero-bg.jpg'); /* Add a background image if you have one */
            background-color: #1a1a1a; /* Fallback color */
            background-size: cover;
            background-position: center;
            padding: 80px 20px;
            text-align: center;
            color: white;
        }

        .page-banner h1 { font-size: 3rem; margin-bottom: 10px; }
        .page-banner p { font-size: 1.2rem; color: #ccc; max-width: 600px; margin: 0 auto; }

        /* 2. Main Container */
        .about-container {
            max-width: 1100px;
            margin: 50px auto;
            padding: 0 20px;
        }

        /* 3. Story Section (Image + Text) */
        .story-section {
            display: flex;
            align-items: center;
            gap: 50px;
            margin-bottom: 80px;
        }
        
        .story-text { flex: 1; }
        .story-text h2 { font-size: 2.2rem; color: #1e3a8a; margin-bottom: 20px; }
        .story-text p { color: #555; line-height: 1.8; margin-bottom: 20px; }

        .story-img {
            flex: 1;
            height: 350px;
            background-color: #ddd; /* Placeholder color */
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .story-img img { width: 100%; height: 100%; object-fit: cover; }

        /* 4. Values Grid */
        .values-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            text-align: center;
        }

        .value-card {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            transition: 0.3s;
            border: 1px solid #eee;
        }
        .value-card:hover { transform: translateY(-5px); border-color: #2563eb; }

        .value-icon { font-size: 2.5rem; margin-bottom: 15px; display: block; }
        .value-card h3 { margin-bottom: 10px; color: #333; }
        .value-card p { color: #666; font-size: 0.9rem; }

        /* Responsive */
        @media (max-width: 768px) {
            .story-section { flex-direction: column; }
            .story-img { width: 100%; height: 250px; }
            .page-banner h1 { font-size: 2.2rem; }
        }
    </style>
</head>

<div class="fade-in">

    <div class="page-banner">
        <h1>About Us</h1>
        <p>Driving your dreams forward with reliable, affordable, and premium car rentals.</p>
    </div>

    <div class="about-container">
        
        <div class="story-section">
            <div class="story-img">
                <img src="assets/images/hero-bg.jpg" alt="Our Fleet">
            </div>
            <div class="story-text">
                <h2>Who We Are</h2>
                <p>
                    Started in 2024, <strong>RentalHub</strong> was born from a simple idea: car rental shouldn't be complicated. 
                    We set out to create a seamless experience where you can book a car in minutes and hit the road with confidence.
                </p>
                <p>
                    Whether you need a compact car for city driving, a luxury sedan for a business trip, or an SUV for a family vacation, 
                    we have a fleet that fits every need. We prioritize safety, transparency, and customer satisfaction above all else.
                </p>
                <a href="user/view-cars.php" class="btn btn-primary">Browse Our Fleet</a>
            </div>
        </div>

        <div style="text-align:center; margin-bottom:40px;">
            <h2 style="color:#1e3a8a; font-size:2rem;">Why Choose Us?</h2>
            <p style="color:#666;">We go the extra mile so you don't have to.</p>
        </div>

        <div class="values-grid">
            <div class="value-card">
                <span class="value-icon"></span>
                <h3>Safety First</h3>
                <p>Every car undergoes a rigorous 25-point safety inspection before every trip.</p>
            </div>
            <div class="value-card">
                <span class="value-icon"></span>
                <h3>Best Prices</h3>
                <p>No hidden fees. We offer the most competitive daily and weekly rates in the market.</p>
            </div>
            <div class="value-card">
                <span class="value-icon"></span>
                <h3>Premium Fleet</h3>
                <p>From economy to luxury, choose from a wide range of top-model vehicles.</p>
            </div>
            <div class="value-card">
                <span class="value-icon"></span>
                <h3>24/7 Support</h3>
                <p>Our dedicated support team is here to assist you anytime, anywhere.</p>
            </div>
        </div>

    </div>
</div>

<?php include 'includes/footer.php'; ?>