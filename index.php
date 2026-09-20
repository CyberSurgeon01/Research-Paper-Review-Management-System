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

    // Check user-created or updated accounts first
    if (isset($_SESSION['author'][$user_id]) && $_SESSION['author'][$user_id]['password'] === $password) {
        $role = 'Author';
    } elseif (isset($_SESSION['reviewer'][$user_id]) && $_SESSION['reviewer'][$user_id]['password'] === $password) {
        $role = 'Reviewer';
    } elseif (isset($_SESSION['administrator'][$user_id]) && $_SESSION['administrator'][$user_id]['password'] === $password) {
        $role = 'Administrator';
    } else {
        // Bulletproof fallback for default mock credentials
        if ($user_id === 'A001' && $password === 'author123') {
            $role = 'Author';
        } elseif ($user_id === 'R001' && $password === 'reviewer123') {
            $role = 'Reviewer';
        } elseif ($user_id === 'ADMIN1' && $password === 'admin123') {
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
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
        /* Dashboard Layout */
        .dashboard-card {
            width: 750px !important;
            max-width: 95vw !important;
        }
        .dash-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
            margin-bottom: 25px;
        }
        .dash-tile {
            background: #fff;
            border: 1px solid #e1e5eb;
            border-radius: 8px;
            padding: 20px;
            text-decoration: none;
            color: #333;
            display: flex;
            align-items: center;
            gap: 15px;
            transition: all 0.2s ease;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
        }
        .dash-tile:hover {
            border-color: #0b1a45;
            box-shadow: 0 6px 15px rgba(11, 26, 69, 0.08);
            transform: translateY(-2px);
        }
        .dash-tile .tile-icon {
            background: #f0f4f8;
            color: #0b1a45;
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
            transition: all 0.2s ease;
        }
        .dash-tile:hover .tile-icon {
            background: #0b1a45;
            color: #fff;
        }
        .dash-tile.success-tile .tile-icon {
            background: #e6f4ea;
            color: #1e8e3e;
        }
        .dash-tile.success-tile:hover .tile-icon {
            background: #1e8e3e;
            color: #fff;
        }
        .dash-tile.success-tile:hover {
            border-color: #1e8e3e;
            box-shadow: 0 6px 15px rgba(30, 142, 62, 0.1);
        }
        .dash-tile .tile-content {
            text-align: left;
        }
        .dash-tile .tile-content h4 {
            margin: 0 0 5px 0;
            font-size: 15px;
            color: #0b1a45;
        }
        .dash-tile .tile-content p {
            margin: 0;
            font-size: 12px;
            color: #777;
            line-height: 1.3;
        }
        .dash-section-title {
            font-size: 11px; 
            color: #888; 
            text-transform: uppercase; 
            letter-spacing: 2px; 
            margin: 30px 0 15px; 
            font-weight: 700;
            border-bottom: 1px solid #eee;
            padding-bottom: 8px;
            text-align: left;
        }
        .dash-section-title:first-of-type {
            margin-top: 0;
        }
    </style>
</head>
<body>

    <div class="brand-title">
        <h1>RPRMS</h1>
        <p>Research Paper Review Management System</p>
    </div>

    <div class="card login-card <?= isset($_SESSION['logged_in']) && $_SESSION['logged_in'] ? 'dashboard-card' : '' ?>">
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
            
            <div class="dash-section-title">Account</div>
            <div class="dash-grid">
                <a href="modules/author.php" class="dash-tile">
                    <div class="tile-icon"><i class="fa-solid fa-user"></i></div>
                    <div class="tile-content">
                        <h4>My Profile</h4>
                        <p>View and update your account details</p>
                    </div>
                </a>
            </div>
            
            <div class="dash-section-title">Research</div>
            <div class="dash-grid">
                <a href="modules/research_paper.php" class="dash-tile">
                    <div class="tile-icon"><i class="fa-solid fa-file-lines"></i></div>
                    <div class="tile-content">
                        <h4>Submit / View Papers</h4>
                        <p>Manage your research submissions</p>
                    </div>
                </a>
                <a href="modules/revision.php" class="dash-tile">
                    <div class="tile-icon"><i class="fa-solid fa-arrows-rotate"></i></div>
                    <div class="tile-content">
                        <h4>Manage Revisions</h4>
                        <p>Upload revised paper versions</p>
                    </div>
                </a>
            </div>

            <?php elseif ($_SESSION['role'] === 'Reviewer'): ?>
            
            <div class="dash-section-title">Account</div>
            <div class="dash-grid">
                <a href="modules/reviewer.php" class="dash-tile">
                    <div class="tile-icon"><i class="fa-solid fa-user"></i></div>
                    <div class="tile-content">
                        <h4>My Profile</h4>
                        <p>View and update your account details</p>
                    </div>
                </a>
            </div>
            
            <div class="dash-section-title">Reviews</div>
            <div class="dash-grid">
                <a href="modules/review.php" class="dash-tile">
                    <div class="tile-icon"><i class="fa-solid fa-pen-to-square"></i></div>
                    <div class="tile-content">
                        <h4>Assigned Reviews</h4>
                        <p>Evaluate papers and submit scores</p>
                    </div>
                </a>
            </div>

            <?php elseif ($_SESSION['role'] === 'Administrator'): ?>
            
            <div class="dash-section-title">Account</div>
            <div class="dash-grid">
                <a href="modules/administrator.php" class="dash-tile">
                    <div class="tile-icon"><i class="fa-solid fa-user-shield"></i></div>
                    <div class="tile-content">
                        <h4>Admin Profile</h4>
                        <p>View and update your admin details</p>
                    </div>
                </a>
            </div>
            
            <div class="dash-section-title">User Management</div>
            <div class="dash-grid">
                <a href="modules/add_reviewer.php" class="dash-tile success-tile">
                    <div class="tile-icon"><i class="fa-solid fa-user-plus"></i></div>
                    <div class="tile-content">
                        <h4 style="color: #1e8e3e;">Add New Reviewer</h4>
                        <p>Register new reviewer accounts</p>
                    </div>
                </a>
            </div>
            
            <div class="dash-section-title">Management</div>
            <div class="dash-grid">
                <a href="modules/research_field.php" class="dash-tile">
                    <div class="tile-icon"><i class="fa-solid fa-book"></i></div>
                    <div class="tile-content">
                        <h4>Manage Research Fields</h4>
                        <p>Configure paper categories</p>
                    </div>
                </a>
                <a href="modules/final_decision.php" class="dash-tile">
                    <div class="tile-icon"><i class="fa-solid fa-gavel"></i></div>
                    <div class="tile-content">
                        <h4>Issue Final Decisions</h4>
                        <p>Accept or reject reviewed papers</p>
                    </div>
                </a>
            </div>

            <?php endif; ?>

            <div style="margin-top: 30px; padding-top: 25px; border-top: 1px solid #eee; text-align: center;">
                <a href="index.php?action=logout" class="btn btn-danger" style="text-decoration: none; padding: 12px 30px; border-radius: 6px; font-weight: bold; letter-spacing: 0.5px; box-shadow: 0 4px 10px rgba(220,53,69,0.2);"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
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