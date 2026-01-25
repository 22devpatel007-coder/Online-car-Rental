<head>
    <style>
            .admin-wrapper {
    display: flex;
    min-height: 100vh;
    background-color: #fff; /* Main Dark Background */
    color: white;
}
.admin-content {
    margin-left: 260px; /* Same width as sidebar to prevent overlap */
    flex: 1;
    padding: 40px;
    width: calc(100% - 260px);
}

.admin-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 40px;
    border-bottom: 1px solid #333;
    padding-bottom: 20px;
}

.admin-header h2 {
    font-size: 2rem;
    margin-bottom: 5px;
    color: #000000ff;
}
/* 2. Sidebar Styles */
.sidebar {
    width: 260px;
    background-color: #1a1a1a;
    border-right: 1px solid #333;
    padding: 30px 20px;
    display: flex;
    flex-direction: column;
    position: fixed; /* Keeps sidebar fixed while scrolling */
    height: 100vh;
    top: 0;
    left: 0;
    z-index: 100;
}

.sidebar a {
    text-decoration: none;
    color: #ccc;
    padding: 12px 15px;
    margin-bottom: 5px;
    border-radius: 6px;
    transition: all 0.3s;
    font-size: 0.95rem;
    display: block;
}

.sidebar a:hover {
    background-color: #333;
    color: white;
    transform: translateX(5px); /* Small slide effect */
}

.sidebar a.active {
    background-color: #007bff; /* Active Blue Color */
    color: white;
}

.logout-link {
    margin-top: auto; /* Pushes logout to the bottom */
    background-color: #2c0b0e;
    color: #ff4444 !important;
    border: 1px solid #5c181e;
    text-align: center;
}

.logout-link:hover {
    background-color: #dc3545 !important;
    color: white !important;
}

    </style>
<head>        
<div class="admin-wrapper">
    
    <div class="sidebar">
        <div class="logo" style="margin-bottom: 30px;">
            <!-- <div class="logo-icon" style="font-size: 1rem;">DE</div> -->
            <span style="font-size: 1.2rem; font-weight: bold; color: white;">AdminPanel</span>
        </div>
        
        <a href="dashboard.php" class="">Dashboard</a>
        <a href="manage_cars.php">Manage Cars</a>
        <a href="manage-bookings.php">Bookings</a>
        <a href="manage-user.php">Messages</a>
        <a href="users.php">Users</a>
        
        <a href="../logout.php" class="logout-link">Logout</a>
</div>