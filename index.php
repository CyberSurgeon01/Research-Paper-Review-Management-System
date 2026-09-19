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
    
    $user_id = trim($user_id);
    $password = trim($password);

    // Bulletproof fallback for default credentials
    if ($user_id === 'A001' && $password === 'author123') {
        $role = 'Author';
    } elseif ($user_id === 'R001' && $password === 'reviewer123') {
        $role = 'Reviewer';
    } elseif ($user_id === 'ADMIN1' && $password === 'admin123') {
        $role = 'Administrator';
    } else {
        if (isset($_SESSION['author'][$user_id]) && $_SESSION['author'][$user_id]['password'] === $password) {
            $role = 'Author';
        } elseif (isset($_SESSION['reviewer'][$user_id]) && $_SESSION['reviewer'][$user_id]['password'] === $password) {
            $role = 'Reviewer';
        } elseif (isset($_SESSION['administrator'][$user_id]) && $_SESSION['administrator'][$user_id]['password'] === $password) {
            $role = 'Administrator';
        }
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
    <title>RPRMS</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body {
            background-color: #f4f5f7;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
        }
        .minimal-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .minimal-header h1 {
            color: #0b1a45;
            margin: 0 0 5px 0;
            font-size: 26px;
        }
        .minimal-header p {
            color: #555;
            margin: 0;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .card {
            width: 100%;
            max-width: 380px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            border: none;
            border-radius: 8px;
            overflow: hidden;
            background: #fff;
        }
        .card-header {
            background-color: #0b1a45;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .card-header h2 {
            margin: 0;
            font-size: 18px;
            font-weight: normal;
        }
        .card-body {
            padding: 30px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
            font-size: 13px;
        }
        .form-group input {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 14px;
            background-color: #fafafa;
        }
        .form-group input:focus {
            outline: none;
            border-color: #0b1a45;
            background-color: #fff;
        }
        .btn-primary {
            display: block;
            width: 100%;
            padding: 12px;
            background-color: #0b1a45;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 15px;
            cursor: pointer;
            margin-top: 10px;
            text-align: center;
            font-weight: bold;
        }
        .btn-primary:hover {
            opacity: 0.9;
        }
        .btn-danger {
            background-color: #d9534f;
        }
    </style>
</head>
<body>

    <?php if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']): ?>
    
    <div class="minimal-header">
        <h1>Research Paper Review</h1>
        <p>Management System</p>
    </div>

    <div class="card">
        <div class="card-header">
            <h2>Sign In</h2>
        </div>
        <div class="card-body">
            <form action="index.php" method="post">
                <?php if (isset($error)): ?>
                    <div style="color: #d9534f; text-align: center; margin-bottom: 15px; font-size: 13px; padding: 8px; background: #fdf0f0; border-radius: 4px;">
                        <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>
                
                <div class="form-group" style="margin-bottom: 20px;">
                    <label for="user_id">User ID</label>
                    <input type="text" id="user_id" name="user_id" placeholder="e.g., A001" required>
                </div>
                
                <div class="form-group" style="margin-bottom: 25px; position: relative;">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="••••••••" required style="padding-right: 35px;">
                    <span id="togglePassword" style="position: absolute; right: 12px; top: 35px; cursor: pointer; opacity: 0.5; user-select: none;">👁️</span>
                </div>

                <button type="submit" name="login" value="1" class="btn-primary">Login</button>
            </form>
            
            <div style="margin-top: 25px; text-align: center; font-size: 11px; color: #999; line-height: 1.6;">
                Mock Credentials:<br>
                A001 (author123) | R001 (reviewer123) | ADMIN1 (admin123)
            </div>
        </div>
    </div>

    <?php else: ?>
    
    <div class="minimal-header">
        <h1>System Dashboard</h1>
        <p>Logged in as: <strong><?= htmlspecialchars($_SESSION['role']) ?></strong></p>
    </div>

    <div class="card" style="max-width: 400px;">
        <div class="card-body" style="text-align: center; padding: 40px 30px;">
            <h3 style="margin-top: 0; color: #0b1a45; margin-bottom: 30px; font-size: 20px;">Welcome back, <?= htmlspecialchars($_SESSION['user_id']) ?></h3>
            
            <div style="display: flex; flex-direction: column; gap: 12px;">
                <?php if ($_SESSION['role'] === 'Author'): ?>
                    <a href="modules/author.php" class="btn-primary" style="text-decoration: none; box-sizing: border-box;">Author Profile</a>
                    <a href="modules/research_paper.php" class="btn-primary" style="text-decoration: none; box-sizing: border-box;">Submit / View Papers</a>
                    <a href="modules/revision.php" class="btn-primary" style="text-decoration: none; box-sizing: border-box;">Manage Revisions</a>
                <?php elseif ($_SESSION['role'] === 'Reviewer'): ?>
                    <a href="modules/reviewer.php" class="btn-primary" style="text-decoration: none; box-sizing: border-box;">Reviewer Profile</a>
                    <a href="modules/review.php" class="btn-primary" style="text-decoration: none; box-sizing: border-box;">Submit Assigned Reviews</a>
                <?php elseif ($_SESSION['role'] === 'Administrator'): ?>
                    <a href="modules/administrator.php" class="btn-primary" style="text-decoration: none; box-sizing: border-box;">Admin Profile</a>
                    <a href="modules/research_field.php" class="btn-primary" style="text-decoration: none; box-sizing: border-box;">Manage Research Fields</a>
                    <a href="modules/final_decision.php" class="btn-primary" style="text-decoration: none; box-sizing: border-box;">Issue Final Decisions</a>
                <?php endif; ?>
                
                <a href="index.php?action=logout" class="btn-primary btn-danger" style="text-decoration: none; margin-top: 20px; box-sizing: border-box;">Logout</a>
            </div>
        </div>
    </div>
    
    <?php endif; ?>

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