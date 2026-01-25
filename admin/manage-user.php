<?php
session_start();
require_once '../config/database.php';

// 1. SECURITY CHECK
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// 2. HANDLE DELETE MESSAGE
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM contact_messages WHERE id = $id");
    header("Location: messages.php");
    exit();
}

// 3. FETCH MESSAGES
// We check if table exists first to avoid crashes if you haven't created it yet
$tableExists = $conn->query("SHOW TABLES LIKE 'contact_messages'");
if ($tableExists && $tableExists->num_rows > 0) {
    $result = $conn->query("SELECT * FROM contact_messages ORDER BY created_at DESC");
} else {
    $result = false;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Customer Messages | Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        /* --- ADMIN MESSAGES PAGE --- */
.message-box {
    background-color: white;
    padding: 15px;
    border-radius: 6px;
    color: black;
    font-size: 0.95rem;
    line-height: 1.6;
    margin: 15px 0;
    border-left: 3px solid #007bff; /* Blue accent line */
    white-space: pre-wrap; /* Preserves paragraphs/line breaks */
}

.message-meta {
    font-size: 0.85rem;
    color: #888;
    margin-bottom: 5px;
}
.row {
    display: flex;
    gap: 10px;
    margin-top: auto;
    padding-top: 15px;
}
/* --- MESSAGE ACTION BUTTONS --- */
.msg-btn-row {
    display: flex;
    gap: 15px; /* Space between buttons */
    margin-top: 20px; /* Push to bottom of card */
}

/* Base Style for both buttons */
.btn-msg {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 10px 15px;
    border-radius: 6px;
    text-decoration: none;
    font-weight: 600;
    font-size: 0.9rem;
    transition: all 0.3s ease;
    flex: 1; /* Makes them equal width */
    cursor: pointer;
    border: 1px solid transparent;
}

/* 1. Reply Button (Solid Blue) */
.btn-reply {
    background-color: #007bff;
    color: white;
    box-shadow: 0 4px 6px rgba(0, 123, 255, 0.2); /* Soft blue glow */
}

.btn-reply:hover {
    background-color: #0056b3;
    transform: translateY(-2px); /* Slight lift */
    box-shadow: 0 6px 12px rgba(0, 123, 255, 0.3);
}

/* 2. Delete Button (Outlined Red) */
.btn-delete {
    background-color: transparent; /* See-through background */
    color: #ff4444;
    border-color: #ff4444;
}

.btn-delete:hover {
    background-color: #ff4444; /* Fills with red on hover */
    color: white;
    transform: translateY(-2px);
}
    </style>  
</head>
<body>

<?php include 'admin_sidebar.php'; ?>

    <div class="admin-content">
        <div class="admin-header">
            <h2>Customer Queries</h2>
        </div>

        <div class="admin-panel-container" style="background-color: transparent; border:none; box-shadow:none; padding:0;">
            
            <?php if ($result && $result->num_rows > 0): ?>
                
                <div class="grid">
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <div class="card fade-in">
                            <h3><?php echo htmlspecialchars($row['name']); ?></h3>
                            <div class="message-meta">
                                Email: <?php echo htmlspecialchars($row['email']); ?><br>
                                DATE: <?php echo date('d M Y, h:i A', strtotime($row['created_at'])); ?>
                            </div>

                            <div class="message-box">
                                <?php echo nl2br(htmlspecialchars($row['message'])); ?>
                            </div>

                            <div class="msg-btn-row">
    
                                  <a href="mailto:<?php echo $row['email']; ?>?subject=Re: Inquiry" class="btn-msg btn-reply">
                                    Reply via Email
                                     </a>

                                    <a href="messages.php?delete=<?php echo $row['message_id']; ?>" 
                                       class="btn-msg btn-delete"
                                       onclick="return confirm('Permanently delete this message?');">
                                       Delete
                                    </a>

                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>

            <?php else: ?>
                <div style="text-align:center; padding:50px; color:#888; background:#1a1a1a; border-radius:10px; border:1px solid #333;">
                    <h3>No messages found.</h3>
                    <?php if(!$result): ?>
                        <p style="color:#dc3545; font-size:0.9rem; margin-top:10px;">
                            (Warning: 'contact_messages' table not found in database)
                        </p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

        </div>
    </div>

</div>

</body>
</html>