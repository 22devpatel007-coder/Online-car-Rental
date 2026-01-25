<?php
session_start();
require_once '../config/database.php';

// 1. Auth Check
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php"); exit;
}

// 2. Handle Delete Request
if (isset($_GET['del'])) {
    $id = (int)$_GET['del'];
    // Prevent deleting self
    if ($id != $_SESSION['user_id']) {
        $conn->query("DELETE FROM users WHERE user_id=$id");
        echo "<script>alert('User deleted.'); window.location='users.php';</script>";
    }
}

// 3. Fetch Users
$result = $conn->query("SELECT * FROM users ORDER BY user_id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        /* Page Specific Styles */
        body { background: #f4f7f6; }
        .admin-content { margin-left: 250px; padding: 30px; transition: 0.3s; }
        
        .card { background: #fff; border-radius: 8px; border: 1px solid #ddd; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .header-box { padding: 20px; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center; }
        .header-box h2 { margin: 0; color: #333; font-size: 1.5rem; }

        /* Responsive Table */
        .table-wrapper { width: 100%; overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; min-width: 600px; }
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid #eee; font-size: 0.95rem;color: #000; }
        th { background: #f8f9fa; color: #555; font-weight: 600; text-transform: uppercase; font-size: 0.85rem; }
        tr:hover { background: #f1f1f1; }

        /* Smart Badges */
        .badge { padding: 5px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: bold; text-transform: uppercase; }
        .b-admin { background: #e2e6ea; color: #444; border: 1px solid #ccc; }
        .b-user  { background: #d1e7dd; color: #0f5132; border: 1px solid #badbcc; }

        /* Action Buttons */
        .btn-del { color: #dc3545; font-weight: bold; padding: 5px 10px; border: 1px solid #dc3545; border-radius: 4px; transition: 0.2s; font-size: 0.8rem; }
        .btn-del:hover { background: #dc3545; color: white; }
        .disabled { opacity: 0.5; cursor: not-allowed; border-color: #ccc; color: #ccc; }

        /* Mobile Fix */
        @media(max-width:768px){ .admin-content{ margin-left:0; } }
    </style>
</head>
<body>

<?php include 'admin_sidebar.php'; ?>

<div class="admin-content">
    
    <div class="card">
        <div class="header-box">
            <h2>User Management</h2>
            <span style="color:#666; font-size:0.9rem;">Total: <?php echo $result->num_rows; ?></span>
        </div>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Joined</th>
                        <th style="text-align:right;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td style="color:#888;">#<?php echo $row['user_id']; ?></td>
                        <td style="font-weight:600;"><?php echo htmlspecialchars($row['full_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        
                        <td>
                            <?php if($row['role'] == 'admin'): ?>
                                <span class="badge b-admin">Admin</span>
                            <?php else: ?>
                                <span class="badge b-user">User</span>
                            <?php endif; ?>
                        </td>

                        <td><?php echo date('M d, Y', strtotime($row['created_at'])); ?></td>

                        <td style="text-align:right;">
                            <?php if($row['user_id'] == $_SESSION['user_id']): ?>
                                <span class="btn-del disabled">Delete</span>
                            <?php else: ?>
                                <a href="users.php?del=<?php echo $row['user_id']; ?>" 
                                   class="btn-del"
                                   onclick="return confirm('Permanently delete this user?');">
                                   Delete
                                </a>
                            <?php endif; ?>
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