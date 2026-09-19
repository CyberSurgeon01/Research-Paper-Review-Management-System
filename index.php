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
    <title>RPRMS - Login</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        /* Center everything perfectly */
        body {
            justify-content: center;
            background-color: #eef2f5;
        }
        
        /* Elegant Title */
        .brand-title {
            text-align: center;
            margin-bottom: 30px;
            color: #0b1a45;
        }
        .brand-title h1 {
            font-size: 32px;
            margin: 0 0 5px 0;
            letter-spacing: 0.5px;
        }
        .brand-title p {
            font-size: 14px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin: 0;
        }

        /* Perfectly sized login card */
        .login-card {
            width: 420px !important;
            margin: 0 auto !important;
            border-radius: 8px !important;
            box-shadow: 0 10px 25px rgba(0,0,0,0.08) !important;
        }
        .login-card .card-header {
            padding: 25px 20px;
            background-color: #0b1a45;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
        }
        .login-card .card-header h2 {
            font-size: 20px;
            font-weight: 500;
        }
        .login-card .card-body {
            padding: 40px;
        }

        /* Refined form inputs */
        .form-group {
            flex-direction: column;
            align-items: stretch;
            margin-bottom: 20px;
        }
        .form-group label {
            flex: none;
            margin-bottom: 8px;
            font-size: 13px;
            color: #444;
        }
        .form-group input {
            padding: 12px 15px;
            border: 1px solid #ccd1d9;
            border-radius: 6px;
            background-color: #fcfcfc;
            transition: all 0.2s ease;
        }
        .form-group input:focus {
            outline: none;
            border-color: #0b1a45;
            background-color: #fff;
            box-shadow: 0 0 0 3px rgba(11, 26, 69, 0.1);
        }
        
        .btn-login {
            width: 100%;
            padding: 14px;
            font-size: 16px;
            border-radius: 6px;
            margin-top: 10px;
        }

        /* Password Toggle */
        .pwd-wrapper {
            position: relative;
            display: flex;
            width: 100%;
        }
        .pwd-wrapper input {
            width: 100%;
            padding-right: 40px;
        }
        .pwd-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #888;
            font-size: 16px;
            user-select: none;
        }
        .pwd-toggle:hover {
            color: #333;
        }

        /* Dashboard Links */
        .dash-link {
            display: block;
            width: 100%;
            margin-bottom: 15px;
            padding: 12px;
            text-align: center;
            text-decoration: none;
            border-radius: 6px;
            box-sizing: border-box;
        }
    </style>
</head>
<body>

    <div class="brand-title">
        <h1>RPRMS</h1>
        <p>Research Paper Review System</p>
    </div>

    <div class="card login-card">
        <?php if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']): ?>
        
        <!-- LOGIN FORM -->
        <div class="card-header">
            <h2>Secure Login</h2>
        </div>
        <div class="card-body">
            <form action="index.php" method="post">
                <?php if (isset($error)): ?>
                    <div style="background-color: #fdf2f2; color: #d9534f; padding: 12px; border-radius: 6px; margin-bottom: 20px; font-size: 14px; text-align: center; border: 1px solid #fadcd9;">
                        <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>
                
                <div class="form-group">
                    <label for="user_id">User ID</label>
                    <input type="text" id="user_id" name="user_id" placeholder="Enter ID (e.g., A001)" required>
                </div>
                
                <div class="form-group" style="margin-bottom: 30px;">
                    <label for="password">Password</label>
                    <div class="pwd-wrapper">
                        <input type="password" id="password" name="password" placeholder="Enter password" required>
                        <span id="togglePassword" class="pwd-toggle" title="Toggle Visibility">👁️</span>
                    </div>
                </div>

                <button type="submit" name="login" value="1" class="btn btn-primary btn-login">Login to Portal</button>
            </form>
            
            <div style="margin-top: 30px; text-align: center; font-size: 12px; color: #888; padding-top: 20px; border-top: 1px solid #eee;">
                <strong>Test Accounts:</strong><br>
                A001 (author123) | R001 (reviewer123) | ADMIN1 (admin123)
            </div>
        </div>

        <?php else: ?>
        
        <!-- DASHBOARD -->
        <div class="card-header">
            <h2>System Dashboard</h2>
        </div>
        <div class="card-body" style="text-align: center;">
            <div style="background-color: #f8f9fa; padding: 15px; border-radius: 6px; margin-bottom: 25px;">
                <h3 style="margin: 0 0 5px 0; color: #0b1a45; font-size: 18px;">Welcome, <?= htmlspecialchars($_SESSION['user_id']) ?></h3>
                <span style="font-size: 13px; color: #666; text-transform: uppercase; letter-spacing: 1px;"><?= htmlspecialchars($_SESSION['role']) ?> Role</span>
            </div>
            
            <div style="margin-bottom: 30px;">
                <?php if ($_SESSION['role'] === 'Author'): ?>
                    <a href="modules/author.php" class="btn btn-primary dash-link">Author Profile</a>
                    <a href="modules/research_paper.php" class="btn btn-primary dash-link">Submit / View Papers</a>
                    <a href="modules/revision.php" class="btn btn-primary dash-link">Manage Revisions</a>
                <?php elseif ($_SESSION['role'] === 'Reviewer'): ?>
                    <a href="modules/reviewer.php" class="btn btn-primary dash-link">Reviewer Profile</a>
                    <a href="modules/review.php" class="btn btn-primary dash-link">Submit Assigned Reviews</a>
                <?php elseif ($_SESSION['role'] === 'Administrator'): ?>
                    <a href="modules/administrator.php" class="btn btn-primary dash-link">Admin Profile</a>
                    <a href="modules/research_field.php" class="btn btn-primary dash-link">Manage Research Fields</a>
                    <a href="modules/final_decision.php" class="btn btn-primary dash-link">Issue Final Decisions</a>
                <?php endif; ?>
            </div>
            
            <a href="index.php?action=logout" class="btn btn-danger dash-link" style="margin-bottom: 0;">Logout</a>
        </div>
        
        <?php endif; ?>
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