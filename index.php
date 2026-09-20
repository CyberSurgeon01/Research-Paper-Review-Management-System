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
        
        header("Location: index.php");
        exit;
    } else {
        $error = "Invalid User ID or Password.";
    }
}

// Handle Signup
$signup_success = "";
$signup_error = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['signup'])) {
    $aid = trim($_POST['author_id'] ?? '');
    $aname = trim($_POST['author_name'] ?? '');
    $aemail = trim($_POST['email'] ?? '');
    $aaff = trim($_POST['affiliation'] ?? '');
    $aphone = trim($_POST['phone_number'] ?? '');
    $apass = trim($_POST['password'] ?? '');

    if ($aid === '' || $aname === '' || $aemail === '' || $apass === '') {
        $signup_error = "Please fill in all required fields.";
    } elseif (isset($_SESSION['author'][$aid])) {
        $signup_error = "Author ID already exists. Please choose a different ID.";
    } else {
        $_SESSION['author'][$aid] = [
            'author_id' => $aid,
            'author_name' => $aname,
            'email' => $aemail,
            'affiliation' => $aaff,
            'phone_number' => $aphone,
            'password' => $apass
        ];
        $signup_success = "Account created successfully! You can now log in.";
    }
}

$page = $_GET['page'] ?? 'login';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RPRMS — Research Paper Review Management System</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body {
            justify-content: center;
            background: linear-gradient(135deg, #eef2f5 0%, #dce3ea 100%);
            min-height: 100vh;
        }

        /* Brand */
        .brand-title {
            text-align: center;
            margin-bottom: 35px;
            color: #0b1a45;
        }
        .brand-title h1 {
            font-size: 36px;
            margin: 0 0 6px 0;
            letter-spacing: 1px;
        }
        .brand-title p {
            font-size: 13px;
            color: #7a8399;
            text-transform: uppercase;
            letter-spacing: 2.5px;
            margin: 0;
        }

        /* Card */
        .login-card {
            width: 440px !important;
            margin: 0 auto !important;
            border-radius: 10px !important;
            box-shadow: 0 12px 30px rgba(0,0,0,0.1) !important;
            overflow: hidden;
        }
        .login-card .card-header {
            padding: 22px 20px;
            background-color: #0b1a45;
            border-radius: 0;
        }
        .login-card .card-header h2 {
            font-size: 20px;
            font-weight: 500;
        }
        .login-card .card-body {
            padding: 35px 40px 40px;
        }

        /* Tabs */
        .auth-tabs {
            display: flex;
            border-bottom: 2px solid #eee;
            margin-bottom: 25px;
        }
        .auth-tabs a {
            flex: 1;
            text-align: center;
            padding: 12px 0;
            text-decoration: none;
            font-weight: bold;
            font-size: 14px;
            color: #999;
            border-bottom: 3px solid transparent;
            margin-bottom: -2px;
            transition: all 0.2s ease;
        }
        .auth-tabs a:hover {
            color: #0b1a45;
        }
        .auth-tabs a.active {
            color: #0b1a45;
            border-bottom-color: #0b1a45;
        }

        /* Form */
        .form-group {
            flex-direction: column;
            align-items: stretch;
            margin-bottom: 18px;
        }
        .form-group label {
            flex: none;
            margin-bottom: 6px;
            font-size: 13px;
            color: #444;
            font-weight: 600;
        }
        .form-group input {
            padding: 11px 14px;
            border: 1px solid #ccd1d9;
            border-radius: 6px;
            background-color: #fcfcfc;
            transition: all 0.2s ease;
            font-size: 14px;
        }
        .form-group input:focus {
            outline: none;
            border-color: #0b1a45;
            background-color: #fff;
            box-shadow: 0 0 0 3px rgba(11, 26, 69, 0.08);
        }

        .btn-login {
            width: 100%;
            padding: 13px;
            font-size: 15px;
            border-radius: 6px;
            margin-top: 8px;
            font-weight: bold;
            letter-spacing: 0.5px;
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
            right: 14px;
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

        /* Alert boxes */
        .alert-error {
            background-color: #fdf2f2;
            color: #d9534f;
            padding: 11px;
            border-radius: 6px;
            margin-bottom: 18px;
            font-size: 13px;
            text-align: center;
            border: 1px solid #fadcd9;
        }
        .alert-success {
            background-color: #f0fdf4;
            color: #28a745;
            padding: 11px;
            border-radius: 6px;
            margin-bottom: 18px;
            font-size: 13px;
            text-align: center;
            border: 1px solid #bbf7d0;
        }

        /* Dashboard Links */
        .dash-link {
            display: block;
            width: 100%;
            margin-bottom: 12px;
            padding: 12px;
            text-align: center;
            text-decoration: none;
            border-radius: 6px;
            box-sizing: border-box;
        }

        /* Footer */
        .landing-footer {
            text-align: center;
            margin-top: 30px;
            font-size: 12px;
            color: #aaa;
        }
    </style>
</head>
<body>

    <div class="brand-title">
        <h1>RPRMS</h1>
        <p>Research Paper Review Management System</p>
    </div>

    <div class="card login-card">
        <?php if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']): ?>
        
        <div class="card-header">
            <h2><?= $page === 'signup' ? 'Author Registration' : 'Secure Login' ?></h2>
        </div>
        <div class="card-body">

            <!-- TABS -->
            <div class="auth-tabs">
                <a href="?page=login" class="<?= $page !== 'signup' ? 'active' : '' ?>">Login</a>
                <a href="?page=signup" class="<?= $page === 'signup' ? 'active' : '' ?>">Sign Up</a>
            </div>

            <?php if ($page === 'signup'): ?>
            <!-- SIGNUP FORM -->
            <?php if ($signup_success): ?>
                <div class="alert-success"><?= htmlspecialchars($signup_success) ?></div>
            <?php endif; ?>
            <?php if ($signup_error): ?>
                <div class="alert-error"><?= htmlspecialchars($signup_error) ?></div>
            <?php endif; ?>

            <form action="index.php?page=signup" method="post">
                <div class="form-group">
                    <label for="s_author_id">Author ID *</label>
                    <input type="text" id="s_author_id" name="author_id" placeholder="e.g., A003" required>
                </div>
                <div class="form-group">
                    <label for="s_author_name">Full Name *</label>
                    <input type="text" id="s_author_name" name="author_name" placeholder="Enter your full name" required>
                </div>
                <div class="form-group">
                    <label for="s_email">Email *</label>
                    <input type="email" id="s_email" name="email" placeholder="you@example.com" required>
                </div>
                <div class="form-group">
                    <label for="s_affiliation">Affiliation</label>
                    <input type="text" id="s_affiliation" name="affiliation" placeholder="University / Organization">
                </div>
                <div class="form-group">
                    <label for="s_phone">Phone Number</label>
                    <input type="tel" id="s_phone" name="phone_number" placeholder="01XXXXXXXXX">
                </div>
                <div class="form-group">
                    <label for="s_password">Password *</label>
                    <div class="pwd-wrapper">
                        <input type="password" id="s_password" name="password" placeholder="Create a password" required>
                        <span class="pwd-toggle" onclick="togglePwd('s_password', this)" title="Toggle Visibility">👁️</span>
                    </div>
                </div>
                <button type="submit" name="signup" value="1" class="btn btn-primary btn-login" style="background-color: #28a745;">Create Account</button>
            </form>

            <?php else: ?>
            <!-- LOGIN FORM -->
            <?php if (isset($error)): ?>
                <div class="alert-error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <?php if ($signup_success): ?>
                <div class="alert-success"><?= htmlspecialchars($signup_success) ?></div>
            <?php endif; ?>

            <form action="index.php" method="post">
                <div class="form-group">
                    <label for="user_id">User ID</label>
                    <input type="text" id="user_id" name="user_id" placeholder="Enter your ID" required>
                </div>
                
                <div class="form-group" style="margin-bottom: 28px;">
                    <label for="password">Password</label>
                    <div class="pwd-wrapper">
                        <input type="password" id="password" name="password" placeholder="Enter your password" required>
                        <span id="togglePassword" class="pwd-toggle" title="Toggle Visibility">👁️</span>
                    </div>
                </div>

                <button type="submit" name="login" value="1" class="btn btn-primary btn-login">Login</button>
            </form>

            <div style="margin-top: 25px; text-align: center; font-size: 13px; color: #888; padding-top: 18px; border-top: 1px solid #eee;">
                Don't have an account? <a href="?page=signup" style="color: #0b1a45; font-weight: bold; text-decoration: none;">Sign up as Author</a>
            </div>
            <?php endif; ?>

        </div>

        <?php else: ?>
        
        <!-- DASHBOARD -->
        <div class="card-header">
            <h2>System Dashboard</h2>
        </div>
        <div class="card-body" style="padding: 35px 40px;">
            <div style="background-color: #f8f9fa; padding: 15px; border-radius: 6px; margin-bottom: 25px; text-align: center;">
                <h3 style="margin: 0 0 5px 0; color: #0b1a45; font-size: 18px;">Welcome, <?= htmlspecialchars($_SESSION['user_id']) ?></h3>
                <span style="font-size: 13px; color: #666; text-transform: uppercase; letter-spacing: 1px;"><?= htmlspecialchars($_SESSION['role']) ?> Portal</span>
            </div>

            <?php if ($_SESSION['role'] === 'Author'): ?>
            
            <p style="font-size: 12px; color: #999; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 12px; font-weight: bold;">Account</p>
            <a href="modules/author.php" class="btn btn-primary dash-link" style="display: flex; align-items: center; gap: 10px; justify-content: flex-start; padding-left: 20px; text-decoration: none;">👤 My Profile</a>
            
            <p style="font-size: 12px; color: #999; text-transform: uppercase; letter-spacing: 1px; margin: 20px 0 12px; font-weight: bold;">Research</p>
            <a href="modules/research_paper.php" class="btn btn-primary dash-link" style="display: flex; align-items: center; gap: 10px; justify-content: flex-start; padding-left: 20px; text-decoration: none;">📄 Submit / View Papers</a>
            <a href="modules/revision.php" class="btn btn-primary dash-link" style="display: flex; align-items: center; gap: 10px; justify-content: flex-start; padding-left: 20px; text-decoration: none;">🔄 Manage Revisions</a>

            <?php elseif ($_SESSION['role'] === 'Reviewer'): ?>
            
            <p style="font-size: 12px; color: #999; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 12px; font-weight: bold;">Account</p>
            <a href="modules/reviewer.php" class="btn btn-primary dash-link" style="display: flex; align-items: center; gap: 10px; justify-content: flex-start; padding-left: 20px; text-decoration: none;">👤 My Profile</a>
            
            <p style="font-size: 12px; color: #999; text-transform: uppercase; letter-spacing: 1px; margin: 20px 0 12px; font-weight: bold;">Reviews</p>
            <a href="modules/review.php" class="btn btn-primary dash-link" style="display: flex; align-items: center; gap: 10px; justify-content: flex-start; padding-left: 20px; text-decoration: none;">📝 Assigned Reviews</a>

            <?php elseif ($_SESSION['role'] === 'Administrator'): ?>
            
            <p style="font-size: 12px; color: #999; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 12px; font-weight: bold;">Account</p>
            <a href="modules/administrator.php" class="btn btn-primary dash-link" style="display: flex; align-items: center; gap: 10px; justify-content: flex-start; padding-left: 20px; text-decoration: none;">👤 My Profile</a>
            
            <p style="font-size: 12px; color: #999; text-transform: uppercase; letter-spacing: 1px; margin: 20px 0 12px; font-weight: bold;">User Management</p>
            <a href="modules/add_reviewer.php" class="btn btn-primary dash-link" style="display: flex; align-items: center; gap: 10px; justify-content: flex-start; padding-left: 20px; text-decoration: none; background-color: #28a745;">➕ Add New Reviewer</a>
            
            <p style="font-size: 12px; color: #999; text-transform: uppercase; letter-spacing: 1px; margin: 20px 0 12px; font-weight: bold;">Management</p>
            <a href="modules/research_field.php" class="btn btn-primary dash-link" style="display: flex; align-items: center; gap: 10px; justify-content: flex-start; padding-left: 20px; text-decoration: none;">📚 Manage Research Fields</a>
            <a href="modules/final_decision.php" class="btn btn-primary dash-link" style="display: flex; align-items: center; gap: 10px; justify-content: flex-start; padding-left: 20px; text-decoration: none;">⚖️ Issue Final Decisions</a>

            <?php endif; ?>

            <div style="margin-top: 25px; padding-top: 20px; border-top: 1px solid #eee;">
                <a href="index.php?action=logout" class="btn btn-danger dash-link" style="margin-bottom: 0; text-decoration: none;">Logout</a>
            </div>
        </div>
        
        <?php endif; ?>
    </div>

    <div class="landing-footer">
        &copy; <?= date('Y') ?> RPRMS — All Rights Reserved
    </div>

    <script>
        // Login page toggle
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

        // Generic toggle for signup
        function togglePwd(inputId, el) {
            const inp = document.getElementById(inputId);
            if (inp.type === 'password') {
                inp.type = 'text';
                el.innerHTML = '🙈';
            } else {
                inp.type = 'password';
                el.innerHTML = '👁️';
            }
        }
    </script>
</body>
</html>