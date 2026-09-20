<?php
session_start();
require_once '../includes/mock_seeder.php';

if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in'] || $_SESSION['role'] !== 'Administrator') {
    header("Location: ../index.php");
    exit;
}

$success_msg = "";
$error_msg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_reviewer'])) {
    $rid = trim($_POST['reviewer_id'] ?? '');
    $rname = trim($_POST['reviewer_name'] ?? '');
    $remail = trim($_POST['email'] ?? '');
    $rexpertise = trim($_POST['expertise'] ?? '');
    $rdesignation = trim($_POST['designation'] ?? '');
    $raffiliation = trim($_POST['affiliation'] ?? '');
    $rphone = trim($_POST['phone_number'] ?? '');
    $rpass = trim($_POST['password'] ?? '');

    if ($rid === '' || $rname === '' || $remail === '' || $rpass === '') {
        $error_msg = "Please fill in all required fields.";
    } elseif (isset($_SESSION['reviewer'][$rid])) {
        $error_msg = "Reviewer ID '$rid' already exists. Please choose a different ID.";
    } else {
        $_SESSION['reviewer'][$rid] = [
            'reviewer_id' => $rid,
            'reviewer_name' => $rname,
            'email' => $remail,
            'expertise' => $rexpertise,
            'designation' => $rdesignation,
            'affiliation' => $raffiliation,
            'phone_number' => $rphone,
            'password' => $rpass
        ];
        $success_msg = "Reviewer account '$rid' created successfully! They can now log in.";
    }
}
?>
<?php $view = $_GET['view'] ?? 'list'; ?>
<?php include '../includes/header.php'; ?>
<?php include '../includes/navbar.php'; ?>

<div class="card">
    <?php if ($view === "form"): ?>
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; padding-right: 30px;">
        <h2>Add New Reviewer</h2>
        <a href="?view=list" class="btn btn-primary" style="background-color: #6c757d; text-decoration: none;">&#8592; Back to List</a>
    </div>
    <div class="card-body">
        <?php if ($success_msg): ?>
            <div style="background-color: #f0fdf4; color: #28a745; padding: 12px; border-radius: 6px; margin-bottom: 20px; font-size: 14px; text-align: center; border: 1px solid #bbf7d0;">
                <?= htmlspecialchars($success_msg) ?>
            </div>
        <?php endif; ?>
        <?php if ($error_msg): ?>
            <div style="background-color: #fdf2f2; color: #d9534f; padding: 12px; border-radius: 6px; margin-bottom: 20px; font-size: 14px; text-align: center; border: 1px solid #fadcd9;">
                <?= htmlspecialchars($error_msg) ?>
            </div>
        <?php endif; ?>

        <form action="" method="post">
            <div class="form-group">
                <label for="reviewer_id">Reviewer ID *</label>
                <input type="text" id="reviewer_id" name="reviewer_id" placeholder="e.g., R003" required>
            </div>
            <div class="form-group">
                <label for="reviewer_name">Full Name *</label>
                <input type="text" id="reviewer_name" name="reviewer_name" placeholder="Dr. Full Name" required>
            </div>
            <div class="form-group">
                <label for="email">Email *</label>
                <input type="email" id="email" name="email" placeholder="reviewer@example.com" required>
            </div>
            <div class="form-group">
                <label for="expertise">Expertise</label>
                <input type="text" id="expertise" name="expertise" placeholder="e.g., Machine Learning">
            </div>
            <div class="form-group">
                <label for="designation">Designation</label>
                <input type="text" id="designation" name="designation" placeholder="e.g., Professor">
            </div>
            <div class="form-group">
                <label for="affiliation">Affiliation</label>
                <input type="text" id="affiliation" name="affiliation" placeholder="University / Organization">
            </div>
            <div class="form-group">
                <label for="phone_number">Phone Number</label>
                <input type="tel" id="phone_number" name="phone_number" placeholder="01XXXXXXXXX">
            </div>
            <div class="form-group">
                <label for="password">Password *</label>
                <input type="text" id="password" name="password" placeholder="Set a password for the reviewer" required>
            </div>
            
            <div class="button-group">
                <button type="submit" name="add_reviewer" value="1" class="btn btn-primary" style="background-color: #28a745;">Create Reviewer Account</button>
                <button type="reset" class="btn btn-danger">Reset</button>
            </div>
        </form>
    </div>
    </div>
</div>
<?php else: ?>
<div class="card" style="margin-top: 20px; width: 90%; max-width: 1200px;">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; padding-right: 30px;">
        <h2>Reviewer Directory</h2>
        <div>
            <a href="?view=form" class="btn btn-primary" style="background-color: #28a745; text-decoration: none; margin-right: 10px;">+ Add New Reviewer</a>
            <a href="../index.php" class="btn btn-primary" style="background-color: #6c757d; text-decoration: none;">&#8592; Dashboard</a>
        </div>
    </div>
    <div class="card-body" style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="background-color: #f4f5f7; border-bottom: 2px solid #ccc;">
                    <th style="padding: 10px; border: 1px solid #eee;">ID</th>
                    <th style="padding: 10px; border: 1px solid #eee;">Name</th>
                    <th style="padding: 10px; border: 1px solid #eee;">Email</th>
                    <th style="padding: 10px; border: 1px solid #eee;">Expertise</th>
                    <th style="padding: 10px; border: 1px solid #eee;">Affiliation</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($_SESSION['reviewer'] as $r): ?>
                <tr>
                    <td style="padding: 10px; border: 1px solid #eee;"><?= htmlspecialchars($r['reviewer_id']) ?></td>
                    <td style="padding: 10px; border: 1px solid #eee;"><?= htmlspecialchars($r['reviewer_name']) ?></td>
                    <td style="padding: 10px; border: 1px solid #eee;"><?= htmlspecialchars($r['email']) ?></td>
                    <td style="padding: 10px; border: 1px solid #eee;"><?= htmlspecialchars($r['expertise'] ?? 'N/A') ?></td>
                    <td style="padding: 10px; border: 1px solid #eee;"><?= htmlspecialchars($r['affiliation'] ?? 'N/A') ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>

<?php include '../includes/footer.php'; ?>

