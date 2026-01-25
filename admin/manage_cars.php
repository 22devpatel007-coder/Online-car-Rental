<?php
session_start();
require_once '../config/database.php';

// 1. Security Check
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$message = "";

// 2. HANDLE ADD CAR
if (isset($_POST['add_car'])) {
    // Collect all fields from the form
    $car_name = $_POST['car_name'];
    $brand = $_POST['brand'];
    $model = $_POST['model'];
    $year = $_POST['year'];
    $price = $_POST['price_per_day'];
    $fuel = $_POST['fuel_type'];
    $transmission = $_POST['transmission'];
    $seats = $_POST['seats'];

    // Handle Image Upload
    $target_dir = "../assets/images/cars/";
    // Create folder if it doesn't exist
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $filename = time() . "_" . basename($_FILES["image"]["name"]); // Rename to avoid duplicates
    $target_file = $target_dir . $filename;
    $db_image_path = "assets/images/cars/" . $filename; // Path to save in DB

    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        // SQL Insert matching your NEW database schema
        $sql = "INSERT INTO cars (car_name, brand, model, year, price_per_day, fuel_type, seats, transmission, image) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssiddiss", $car_name, $brand, $model, $year, $price, $fuel, $seats, $transmission, $db_image_path);
        
        if ($stmt->execute()) {
            $message = "Vehicle added successfully!";
        } else {
            $message = "Database Error: " . $conn->error;
        }
    } else {
        $message = "Failed to upload image.";
    }
}

// 3. HANDLE DELETE CAR
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM cars WHERE car_id=$id");
    header("Location: manage_cars.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Fleet | Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
     <style>
        /* --- ADMIN DASHBOARD STYLES --- */    
        /* ============================
   MANAGE CARS PAGE STYLES
   ============================ */
/* 3. Panel Container (The White Cards) */
.admin-panel-container {
    background-color: #fff;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.05); /* Soft shadow */
    margin-bottom: 30px;
    border: 1px solid #e1e4e8;
}

.admin-panel-container h3 {
    margin-top: 0;
    font-size: 1.4rem;
    color: #333;
    border-left: 5px solid #007bff; /* Blue accent line on left */
    padding-left: 15px;
}

/* 4. Form Styling */
form label {
    font-weight: 600;
    font-size: 0.9rem;
    color: #555;
    margin-bottom: 8px;
    display: block;
}

.form-control {
    width: 100%;
    padding: 10px 15px;
    font-size: 1rem;
    border: 1px solid #ced4da;
    border-radius: 5px;
    background-color: #fff;
    transition: border-color 0.2s;
    color: #495057;
}

.form-control:focus {
    border-color: #007bff;
    outline: none;
    box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
}

/* File Input Styling */
input[type="file"].form-control {
    padding: 6px; /* Adjust padding for file input */
}

/* 5. Buttons */
.btn {
    display: inline-block;
    font-weight: 600;
    text-align: center;
    border: 1px solid transparent;
    padding: 10px 20px;
    font-size: 1rem;
    border-radius: 5px;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-primary {
    background-color: #007bff;
    color: white;
}

.btn-primary:hover {
    background-color: #0056b3;
}

.btn-danger {
    background-color: #dc3545;
    color: white;
    padding: 6px 12px;
    font-size: 0.85rem;
    text-decoration: none;
}

.btn-danger:hover {
    background-color: #c82333;
}

/* 6. Admin Table (Fleet List) */
.admin-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 15px;
}

.admin-table th {
    background-color: #343a40; /* Dark header */
    color: #fff;
    padding: 12px;
    text-align: left;
    font-weight: 600;
    font-size: 0.95rem;
}

.admin-table td {
    padding: 12px;
    border-bottom: 1px solid #dee2e6;
    vertical-align: middle; /* Center content vertically */
    color: #333;
}

.admin-table tr:hover {
    background-color: #f8f9fa; /* Light grey hover effect */
}

.admin-table img {
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

/* 7. Alerts */
.alert {
    padding: 15px;
    border-radius: 5px;
    font-weight: 600;
    border: 1px solid transparent;
}
/* Success alert is styled inline in your PHP, but this is a fallback class */
.alert-success {
    background-color: #d4edda;
    color: #155724;
    border-color: #c3e6cb;
}

</style>
</head>
<body>
<?php include 'admin_sidebar.php'; ?>
    <div class="admin-content">
        <div class="admin-header">
            <h2>Manage Fleet</h2>
        </div>

        <?php if($message): ?>
            <div class="alert" style="background:#28a745; color:white; padding:10px; margin-bottom:20px;">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <div class="admin-panel-container">
            <h3 style="color:black; margin-bottom:20px;">+ Add New Vehicle</h3>
            <form method="POST" enctype="multipart/form-data">
                
                <div style="display:flex; gap:20px; margin-bottom:15px;">
                    <div style="flex:1">
                        <label style="color:#black;">Car Name (e.g. City Cruiser)</label>
                        <input type="text" name="car_name" class="form-control" required>
                    </div>
                    <div style="flex:1">
                        <label style="color:#black;">Brand (e.g. Honda)</label>
                        <input type="text" name="brand" class="form-control" required>
                    </div>
                </div>

                <div style="display:flex; gap:20px; margin-bottom:15px;">
                    <div style="flex:1">
                        <label style="color:#black;">Model (e.g. City)</label>
                        <input type="text" name="model" class="form-control" required>
                    </div>
                    <div style="flex:1">
                        <label style="color:#black;">Year</label>
                        <input type="number" name="year" class="form-control" value="2024" required>
                    </div>
                </div>

                <div style="display:flex; gap:20px; margin-bottom:15px;">
                    <div style="flex:1">
                        <label style="color:#black;">Price Per Day (₹)</label>
                        <input type="number" name="price_per_day" class="form-control" required>
                    </div>
                    <div style="flex:1">
                        <label style="color:#black;">Seats</label>
                        <input type="number" name="seats" class="form-control" value="5" required>
                    </div>
                </div>

                <div style="display:flex; gap:20px; margin-bottom:15px;">
                    <div style="flex:1">
                        <label style="color:#black;">Fuel Type</label>
                        <select name="fuel_type" class="form-control" style="height:45px;">
                            <option value="Petrol">Petrol</option>
                            <option value="Diesel">Diesel</option>
                            <option value="Electric">Electric</option>
                        </select>
                    </div>
                    <div style="flex:1">
                        <label style="color:#black;">Transmission</label>
                        <select name="transmission" class="form-control" style="height:45px;">
                            <option value="Manual">Manual</option>
                            <option value="Automatic">Automatic</option>
                        </select>
                    </div>
                </div>

                <div style="margin-bottom:20px;">
                    <label style="color:#black;">Car Image</label>
                    <input type="file" name="image" class="form-control" required style="padding:10px;">
                </div>

                <button type="submit" name="add_car" class="btn btn-primary">Add Vehicle</button>
            </form>
        </div>

        <div class="admin-panel-container">
            <h3 style="color:black; margin-bottom:20px;">Current Fleet</h3>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Brand/Model</th>
                        <th>Price</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $result = $conn->query("SELECT * FROM cars ORDER BY car_id DESC");
                    while($row = $result->fetch_assoc()):
                    ?>
                    <tr>
                        <td>
                            <img src="../<?php echo $row['image']; ?>" style="width:80px; height:50px; object-fit:cover; border-radius:4px;">
                        </td>
                        <td><?php echo $row['car_name']; ?></td>
                        <td><?php echo $row['brand'] . " " . $row['model']; ?></td>
                        <td>₹<?php echo $row['price_per_day']; ?></td>
                        <td>
                            <a href="manage_cars.php?delete=<?php echo $row['car_id']; ?>" 
                               class="btn btn-danger" 
                               onclick="return confirm('Are you sure you want to delete this car?');">
                               Delete
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

    </div>
</div>

</body>
</html>