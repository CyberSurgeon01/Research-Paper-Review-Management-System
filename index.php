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
    <title>RPRMS - Welcome</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body {
            background-color: #f4f5f7;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: stretch;
        }
        .hero {
            background-color: #0b1a45;
            color: #ffffff;
            padding: 60px 20px;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .hero h1 {
            font-size: 38px;
            margin-bottom: 20px;
            font-weight: bold;
        }
        .hero p {
            font-size: 18px;
            max-width: 800px;
            margin: 0 auto;
            line-height: 1.6;
            color: #d1d8f0;
        }
        .landing-container {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            gap: 40px;
            max-width: 1200px;
            margin: 50px auto;
            padding: 0 20px;
            flex-wrap: wrap;
        }
        .features {
            flex: 1;
            min-width: 350px;
            background: #ffffff;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }
        .features h3 {
            color: #0b1a45;
            border-bottom: 2px solid #0b1a45;
            padding-bottom: 12px;
            margin-top: 0;
            font-size: 22px;
            margin-bottom: 25px;
        }
        .features ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .features li {
            margin-bottom: 20px;
            display: flex;
            align-items: flex-start;
            font-size: 15px;
            line-height: 1.5;
            color: #444;
        }
        .features li::before {
            content: '✓';
            color: #28a745;
            font-weight: bold;
            margin-right: 15px;
            font-size: 18px;
            margin-top: -2px;
        }
        .login-section {
            flex: 0 0 420px;
            width: 100%;
        }
        /* Override generic card margin for landing */
        .login-section .card {
            margin: 0;
            width: 100%;
            box-shadow: 0 6px 16px rgba(0,0,0,0.08);
            border: none;
        }
        /* Dashboard styling override */
        .dashboard-container {
            width: 100%;
            max-width: 650px;
            margin: 50px auto;
        }
    </style>
</head>
<body>

    <?php if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']): ?>
    <!-- HERO SECTION -->
    <div class="hero">
        <h1>Research Paper Review Management System</h1>
        <p>A centralized platform for authors to submit cutting-edge research, reviewers to provide peer-reviewed feedback, and administrators to orchestrate the publication workflow seamlessly.</p>
    </div>

    <!-- MAIN LANDING CONTENT -->
    <div class="landing-container">
        
        <!-- FEATURES INFO -->
        <div class="features">
            <h3>System Capabilities</h3>
            <ul>
                <li><strong>Streamlined Submissions:</strong> Authors can effortlessly submit research papers, upload revisions, and track publication status in real-time.</li>
                <li><strong>Expert Peer Review:</strong> A dedicated portal allows reviewers to securely access assigned manuscripts and submit detailed evaluation scores and recommendations.</li>
                <li><strong>Administrative Oversight:</strong> Complete administrative control for assigning reviewers, tracking review progress, and issuing final publication decisions.</li>
                <li><strong>Automated Workflows:</strong> Smart status tracking automatically progresses papers from 'Submitted' to 'Under Review' to 'Final Decision'.</li>
                <li><strong>Role-Based Security:</strong> Strict access control ensures that sensitive research data remains private and securely partitioned.</li>
            </ul>
        </div>

        <!-- LOGIN CARD -->
        <div class="login-section">
            <div class="card">
                <div class="card-header" style="background-color: #0b1a45; padding: 20px;">
                    <h2 style="font-size: 24px;">Secure Portal Access</h2>
                </div>
                <div class="card-body">
                    <form action="index.php" method="post">
                        <?php if (isset($error)): ?>
                            <div style="background-color: #ffe6e6; border-left: 4px solid #ff4d4d; color: #cc0000; padding: 12px; margin-bottom: 20px; font-size: 14px; border-radius: 4px;">
                                <?= htmlspecialchars($error) ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="form-group" style="flex-direction: column; align-items: flex-start; margin-bottom: 20px;">
                            <label for="user_id" style="margin-bottom: 8px;">User ID</label>
                            <input type="text" id="user_id" name="user_id" placeholder="Enter your ID (e.g., A001)" required style="width: 100%;">
                        </div>
                        
                        <div class="form-group" style="flex-direction: column; align-items: flex-start; margin-bottom: 25px;">
                            <label for="password" style="margin-bottom: 8px;">Password</label>
                            <div style="width: 100%; position: relative; display: flex;">
                                <input type="password" id="password" name="password" placeholder="Enter your password" required style="width: 100%; padding-right: 35px;">
                                <span id="togglePassword" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); cursor: pointer; opacity: 0.6; user-select: none;" title="Toggle Password Visibility">
                                    👁️
                                </span>
                            </div>
                        </div>

                        <button type="submit" name="login" value="1" class="btn btn-primary" style="width: 100%; padding: 12px; font-size: 16px;">Secure Login</button>
                    </form>
                    
                    <div style="margin-top: 30px; padding: 15px; background-color: #f8f9fa; border: 1px dashed #ced4da; font-size: 13px; color: #495057; border-radius: 6px;">
                        <strong>Mock System Test Credentials:</strong><br><br>
                        • <strong>Author:</strong> ID = A001, Pass = author123<br>
                        • <strong>Reviewer:</strong> ID = R001, Pass = reviewer123<br>
                        • <strong>Administrator:</strong> ID = ADMIN1, Pass = admin123
                    </div>
                </div>
            </div>
        </div>

    </div>

    <?php else: ?>
    
    <!-- DASHBOARD VIEW FOR LOGGED IN USERS -->
    <div class="dashboard-container">
        <div class="card" style="width: 100%;">
            <div class="card-header">
                <h2>System Dashboard</h2>
            </div>
            <div class="card-body">
                <div style="text-align: center;">
                    <h3 style="margin-top: 0; color: #0b1a45;">Welcome back, <?= htmlspecialchars($_SESSION['user_id']) ?></h3>
                    <p style="margin-bottom: 30px; color: #555;">Current Active Role: <strong><?= htmlspecialchars($_SESSION['role']) ?></strong></p>
                    
                    <div class="button-group" style="flex-direction: column; align-items: center; gap: 12px;">
                        <?php if ($_SESSION['role'] === 'Author'): ?>
                            <a href="modules/author.php" class="btn btn-primary" style="width: 280px; text-decoration: none; padding: 12px;">Update Author Profile</a>
                            <a href="modules/research_paper.php" class="btn btn-primary" style="width: 280px; text-decoration: none; padding: 12px;">Submit / View Papers</a>
                            <a href="modules/revision.php" class="btn btn-primary" style="width: 280px; text-decoration: none; padding: 12px;">Manage Document Revisions</a>
                        <?php elseif ($_SESSION['role'] === 'Reviewer'): ?>
                            <a href="modules/reviewer.php" class="btn btn-primary" style="width: 280px; text-decoration: none; padding: 12px;">Update Reviewer Profile</a>
                            <a href="modules/review.php" class="btn btn-primary" style="width: 280px; text-decoration: none; padding: 12px;">Submit Assigned Reviews</a>
                        <?php elseif ($_SESSION['role'] === 'Administrator'): ?>
                            <a href="modules/administrator.php" class="btn btn-primary" style="width: 280px; text-decoration: none; padding: 12px;">Update Admin Profile</a>
                            <a href="modules/research_field.php" class="btn btn-primary" style="width: 280px; text-decoration: none; padding: 12px;">Manage Research Fields</a>
                            <a href="modules/final_decision.php" class="btn btn-primary" style="width: 280px; text-decoration: none; padding: 12px;">Issue Final Decisions</a>
                        <?php endif; ?>
                        
                        <a href="index.php?action=logout" class="btn btn-danger" style="width: 280px; text-decoration: none; margin-top: 25px; padding: 12px;">Secure Logout</a>
                    </div>
                </div>
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