<?php
session_start();
require_once 'includes/mock_seeder.php';

// Handle Logout
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_destroy();
    header("Location: index.php");
    exit;
}

// Handle Login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $user_id = $_POST['user_id'] ?? '';
    $password = $_POST['password'] ?? '';
    
    $role = '';
    
    if (isset($_SESSION['author'][$user_id]) && $_SESSION['author'][$user_id]['password'] === $password) {
        $role = 'Author';
    } elseif (isset($_SESSION['reviewer'][$user_id]) && $_SESSION['reviewer'][$user_id]['password'] === $password) {
        $role = 'Reviewer';
    } elseif (isset($_SESSION['administrator'][$user_id]) && $_SESSION['administrator'][$user_id]['password'] === $password) {
        $role = 'Administrator';
    }
    
    if ($role !== '') {
        $_SESSION['logged_in'] = true;
        $_SESSION['role'] = $role;
        $_SESSION['user_id'] = $user_id;
        
        if ($role === 'Author') header("Location: modules/author.php");
        elseif ($role === 'Reviewer') header("Location: modules/reviewer.php");
        elseif ($role === 'Administrator') header("Location: modules/administrator.php");
        exit;
    } else {
        $error = "Invalid User ID or Password.";
    }
}
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
                        <label for="password">Password:</label>
                        <div style="flex: 1; position: relative; display: flex;">
                            <input type="password" id="password" name="password" placeholder="Enter your password" required style="width: 100%; padding-right: 30px;">
                            <span id="togglePassword" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer; opacity: 0.7; user-select: none;" title="Toggle Password Visibility">
                                👁️
                            </span>
                        </div>
                    </div>

                    <div class="button-group">
                        <button type="submit" name="login" value="1" class="btn btn-primary">Login</button>
                    </div>
                </form>
                <div style="margin-top: 20px; padding: 15px; background-color: #f9f9f9; border: 1px dashed #ccc; font-size: 13px; color: #555;">
                    <strong>Mock System Default Credentials:</strong><br>
                    Author: ID = <b>A001</b>, Password = <b>author123</b><br>
                    Reviewer: ID = <b>R001</b>, Password = <b>reviewer123</b><br>
                    Administrator: ID = <b>ADMIN1</b>, Password = <b>admin123</b>
                </div>

            <?php endif; ?>
        </div>
    </div>
    <script>
        const togglePassword = document.getElementById('togglePassword');
        const password = document.getElementById('password');
        
        if(togglePassword && password) {
            togglePassword.addEventListener('click', function () {
                if (password.type === 'password') {
                    password.type = 'text';
                    this.innerHTML = '🙈';
                } else {
                    password.type = 'password';
                    this.innerHTML = '👁️';
                }
            });
        }
    </script>
</body>
</html>
