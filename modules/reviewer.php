<?php
session_start();
require_once '../includes/mock_seeder.php';
$module = 'reviewer';
$primary_key = 'reviewer_id';

if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in'] || $_SESSION['role'] !== 'Reviewer') {
    header("Location: ../index.php");
    exit;
}

if (!isset($_SESSION[$module])) {
    $_SESSION[$module] = [];
}

function updateRecord($id, $data) {
    global $module;
    unset($data['action']);
    if (isset($_SESSION[$module][$id])) {
        $_SESSION[$module][$id] = array_merge($_SESSION[$module][$id], $data);
    }
}

function getRecordDetails($id) {
    global $module;
    return $_SESSION[$module][$id] ?? null;
}

$success_msg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update') {
    $id = $_POST[$primary_key] ?? '';
    if ($id) {
        updateRecord($id, $_POST);
        $success_msg = "Profile updated successfully!";
    }
}

$current_record = getRecordDetails($_SESSION['user_id']);
?>
<?php include '../includes/header.php'; ?>
<?php include '../includes/navbar.php'; ?>

<div class="card">
    <div class="card-header">
        <h2>Reviewer Profile</h2>
    </div>
    <div class="card-body">
        <form action="" method="post">
            <div class="form-group">
                <label for="reviewer_id">Reviewer ID:</label>
                <input type="text" id="reviewer_id" name="reviewer_id" value="<?= htmlspecialchars($current_record['reviewer_id'] ?? '') ?>" readonly style="background-color: #eee;">
            </div>
            <div class="form-group">
                <label for="reviewer_name">Reviewer Name:</label>
                <input type="text" id="reviewer_name" name="reviewer_name" value="<?= htmlspecialchars($current_record['reviewer_name'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($current_record['email'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label for="expertise">Expertise:</label>
                <input type="text" id="expertise" name="expertise" value="<?= htmlspecialchars($current_record['expertise'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label for="designation">Designation:</label>
                <input type="text" id="designation" name="designation" value="<?= htmlspecialchars($current_record['designation'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label for="affiliation">Affiliation:</label>
                <input type="text" id="affiliation" name="affiliation" value="<?= htmlspecialchars($current_record['affiliation'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label for="phone_number">Phone Number:</label>
                <input type="tel" id="phone_number" name="phone_number" value="<?= htmlspecialchars($current_record['phone_number'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" value="<?= htmlspecialchars($current_record['password'] ?? '') ?>">
            </div>

            <div class="button-group">
                <button type="submit" class="btn btn-primary" name="action" value="update">Update Profile</button>
                <button type="reset" class="btn btn-danger">Reset</button>
            </div>
        </form>
    </div>
</div>

<?php if ($success_msg): ?>
    <div id="toast" style="position: fixed; top: 20px; right: 20px; background-color: #28a745; color: white; padding: 15px 25px; border-radius: 6px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); display: flex; align-items: center; gap: 12px; z-index: 9999; font-weight: bold; font-size: 14px; animation: slideIn 0.3s ease-out forwards, fadeOut 0.5s ease-in forwards 2.5s;">
        <span style="font-size: 18px;">✓</span> <?= htmlspecialchars($success_msg) ?>
    </div>
    <style>
        @keyframes slideIn { from { transform: translateX(150%); } to { transform: translateX(0); } }
        @keyframes fadeOut { from { opacity: 1; } to { opacity: 0; visibility: hidden; } }
    </style>
<?php endif; ?>

<?php include '../includes/footer.php'; ?>
