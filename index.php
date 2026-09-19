<?php
session_start();
require_once 'config.php';

if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_destroy();
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $role = $_POST['role'] ?? '';
    $user_id = $_POST['user_id'] ?? '';
    
    if ($role && $user_id) {
        $table = '';
        $pk = '';
        if ($role === 'Author') { $table = 'AUTHOR'; $pk = 'AUTHOR_ID'; }
        elseif ($role === 'Reviewer') { $table = 'REVIEWER'; $pk = 'REVIEWER_ID'; }
        elseif ($role === 'Administrator') { $table = 'ADMINISTRATOR'; $pk = 'ADMIN_ID'; }
        
        if ($conn) {
            $sql = "SELECT * FROM $table WHERE $pk = :id";
            $stmt = oci_parse($conn, $sql);
            oci_bind_by_name($stmt, ":id", $user_id);
            oci_execute($stmt);
            $row = oci_fetch_assoc($stmt);
        } else {
            $row = false;
        }
        
        if ($row) {
            $_SESSION['logged_in'] = true;
            $_SESSION['role'] = $role;
            $_SESSION['user_id'] = $user_id;
            
            if ($role === 'Author') header("Location: modules/author.php");
            elseif ($role === 'Reviewer') header("Location: modules/reviewer.php");
            elseif ($role === 'Administrator') header("Location: modules/administrator.php");
            exit;
        } else {
            if (!$conn) {
                $error = "DB Connection Error: Cannot authenticate user without Oracle database.";
            } else {
                $error = "Invalid User ID or Role. Please check your credentials.";
            }
        }
    } else {
        $error = "Please enter User ID and select a Role.";
    }
}
?>
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RPRMS - Login</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="card">
        <div class="card-header">
            <h2>System Login</h2>
        </div>
        <div class="card-body">
            <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']): ?>
                <div style="text-align: center;">
                    <h3 style="margin-top: 0;">Welcome back, <?= htmlspecialchars($_SESSION['user_id']) ?></h3>
                    <p style="margin-bottom: 30px;">Logged in as: <strong><?= htmlspecialchars($_SESSION['role']) ?></strong></p>
                    
                    <div class="button-group" style="flex-direction: column; align-items: center; gap: 10px;">
                        <?php if ($_SESSION['role'] === 'Author'): ?>
                            <a href="modules/author.php" class="btn btn-primary" style="width: 250px; text-decoration: none;">Author Profile</a>
                            <a href="modules/research_paper.php" class="btn btn-primary" style="width: 250px; text-decoration: none;">Submit/View Papers</a>
                            <a href="modules/revision.php" class="btn btn-primary" style="width: 250px; text-decoration: none;">Manage Revisions</a>
                        <?php elseif ($_SESSION['role'] === 'Reviewer'): ?>
                            <a href="modules/reviewer.php" class="btn btn-primary" style="width: 250px; text-decoration: none;">Reviewer Profile</a>
                            <a href="modules/review.php" class="btn btn-primary" style="width: 250px; text-decoration: none;">Submit Reviews</a>
                        <?php elseif ($_SESSION['role'] === 'Administrator'): ?>
                            <a href="modules/administrator.php" class="btn btn-primary" style="width: 250px; text-decoration: none;">Admin Profile</a>
                            <a href="modules/research_field.php" class="btn btn-primary" style="width: 250px; text-decoration: none;">Manage Fields</a>
                            <a href="modules/final_decision.php" class="btn btn-primary" style="width: 250px; text-decoration: none;">Final Decisions</a>
                        <?php endif; ?>
                        
                        <a href="index.php?action=logout" class="btn btn-danger" style="width: 250px; text-decoration: none; margin-top: 20px;">Logout</a>
                    </div>
                </div>
            <?php else: ?>
                <form action="index.php" method="post">
                    <?php if (isset($error)): ?>
                        <p style="color: red; text-align: center; margin-bottom: 15px;"><?= htmlspecialchars($error) ?></p>
                    <?php endif; ?>
                    <div class="form-group">
                        <label for="user_id">User ID:</label>
                        <input type="text" id="user_id" name="user_id" placeholder="Enter your ID (e.g., A001)" required>
                    </div>
                    <div class="form-group">
                        <label for="role">Role:</label>
                        <select id="role" name="role" required>
                            <option value="">Select Role</option>
                            <option value="Author">Author</option>
                            <option value="Reviewer">Reviewer</option>
                            <option value="Administrator">Administrator</option>
                        </select>
                    </div>
                    <div class="button-group">
                        <button type="submit" name="login" value="1" class="btn btn-primary">Login</button>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
