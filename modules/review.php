<?php
session_start();
$module = 'review';
$primary_key = 'review_id';

if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header("Location: ../index.php");
    exit;
}


if (!isset($_SESSION[$module])) {
    $_SESSION[$module] = [];
}

// CRUD Functions
function addRecord($data) {
    global $module, $primary_key;
    $id = !empty($data[$primary_key]) ? $data[$primary_key] : uniqid();
    unset($data['action']);
    if (isset($_SESSION['role']) && $_SESSION['role'] === 'Reviewer') {
        $data['reviewer_id'] = $_SESSION['user_id'];
    }
    
    if ($id) {
        $_SESSION[$module][$id] = $data;
        
        // Workflow 2: Admin assigns reviewer -> paper status changes to 'Under Review'
        if (!empty($data['paper_id']) && isset($_SESSION['research_paper'][$data['paper_id']])) {
            $_SESSION['research_paper'][$data['paper_id']]['status'] = 'Under Review';
        }
    }
}

function updateRecord($id, $data) {
    global $module;
    unset($data['action']);
    if (isset($_SESSION['role']) && $_SESSION['role'] === 'Reviewer') {
        $data['reviewer_id'] = $_SESSION['user_id'];
    }
    if (isset($_SESSION[$module][$id])) {
        $_SESSION[$module][$id] = array_merge($_SESSION[$module][$id], $data);
    }
}

function deleteRecord($id) {
    global $module;
    if (isset($_SESSION[$module][$id])) {
        unset($_SESSION[$module][$id]);
    }
}

function getRecordList() {
    global $module;
    return $_SESSION[$module];
}

function getRecordDetails($id) {
    global $module;
    return $_SESSION[$module][$id] ?? null;
}

$success_msg = "";

// Handle POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    $id = $_POST[$primary_key] ?? '';
    
    if (in_array($action, ['add', 'submit', 'save', 'upload', 'accept'])) {
        addRecord($_POST);
        $success_msg = "Record added successfully!";
    } elseif ($action === 'update') {
        updateRecord($id, $_POST);
        $success_msg = "Record updated successfully!";
    } elseif (in_array($action, ['delete', 'withdraw'])) {
        deleteRecord($id);
        $success_msg = "Record deleted successfully!";
    }
}

$records = getRecordList();
if (isset($_SESSION['role']) && $_SESSION['role'] === 'Reviewer') {
    $records = array_filter($records, function($r) {
        return (isset($r['reviewer_id']) && $r['reviewer_id'] === $_SESSION['user_id']);
    });
}
?>

<?php $view = $_GET['view'] ?? 'list'; ?>
<?php include '../includes/header.php'; ?>
<?php include '../includes/navbar.php'; ?>

<?php if ($view === 'form'): ?>


<div class="card">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; padding-right: 30px;">
        <h2>Review Form</h2>
        <a href="?view=list" class="btn btn-primary" style="background-color: #6c757d; text-decoration: none;">&#8592; Back to List</a>
    </div>
    <div class="card-body">
        <form action="" method="post">
            <div class="form-group">
                <label for="review_id">Review ID:</label>
                <input type="text" id="review_id" name="review_id">
            </div>
            <div class="form-group">
                <label for="paper_id">Paper ID:</label>
                <input type="text" id="paper_id" name="paper_id">
            </div>
            <div class="form-group">
                <label for="reviewer_id">Reviewer ID:</label>
                <input type="text" id="reviewer_id" name="reviewer_id">
            </div>
            <div class="form-group">
                <label for="review_date">Review Date:</label>
                <input type="text" id="review_date" name="review_date" placeholder="dd-mm-yyyy">
            </div>
            <div class="form-group">
                <label for="review_score">Review Score:</label>
                <input type="number" id="review_score" name="review_score">
            </div>
            <div class="form-group">
                <label for="recommendation">Recommendation:</label>
                <input type="text" id="recommendation" name="recommendation">
            </div>
            <div class="form-group">
                <label for="reviewer_comments">Reviewer Comments:</label>
                <textarea id="reviewer_comments" name="reviewer_comments"></textarea>
            </div>
            
            <div class="button-group">
                <button type="submit" class="btn btn-primary" name="action" value="add">Add Review</button>
                <button type="submit" class="btn btn-primary" name="action" value="update">Update Review</button>
                <button type="submit" class="btn btn-danger" name="action" value="delete">Delete Review</button>
                <button type="button" class="btn btn-primary">Get Review Details</button>
            </div>
        </form>
    </div>
</div>


<?php else: ?>
<?php if ($success_msg): ?>
    <div id="toast" style="position: fixed; top: 20px; right: 20px; background-color: #28a745; color: white; padding: 15px 25px; border-radius: 6px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); display: flex; align-items: center; gap: 12px; z-index: 9999; font-weight: bold; font-size: 14px; animation: slideIn 0.3s ease-out forwards, fadeOut 0.5s ease-in forwards 2.5s;">
        <span style="font-size: 18px;">✓</span> <?= htmlspecialchars($success_msg) ?>
    </div>
    <style>
        @keyframes slideIn { from { transform: translateX(150%); } to { transform: translateX(0); } }
        @keyframes fadeOut { from { opacity: 1; } to { opacity: 0; visibility: hidden; } }
    </style>
<?php endif; ?>

<div class="card" style="margin-top: 20px; width: 90%; max-width: 1200px;">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; padding-right: 30px;">
        <h2><?= ucfirst(str_replace('_', ' ', $module)) ?> Records</h2>
        <a href="?view=form" class="btn btn-primary" style="background-color: #28a745; text-decoration: none;">+ Add New Review</a>
    </div>
    <div class="card-body" style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="background-color: #f4f5f7; border-bottom: 2px solid #ccc;">
                    <?php if(!empty($records)): ?>
                        <?php foreach(array_keys(reset($records)) as $key): ?>
                            <th style="padding: 10px; border: 1px solid #eee;"><?= htmlspecialchars(ucwords(str_replace('_', ' ', $key))) ?></th>
                        <?php endforeach; ?>
                    <th style=\"padding: 10px; border: 1px solid #eee; text-align: center; width: 60px;\">Actions</th>
                    <?php else: ?>
                        <th style="padding: 10px;">No records found.</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach($records as $rec): ?>
                    <tr>
                        <?php foreach($rec as $key => $val): ?>
                            <td style="padding: 10px; border: 1px solid #eee;"><?= htmlspecialchars($val) ?></td>
                        <?php endforeach; ?>
                    <td style=\"padding: 10px; border: 1px solid #eee; text-align: center;\">
                        <button type=\"button\" class=\"btn btn-primary\" style=\"padding: 4px 8px; font-size: 11px;\" onclick='fillForm(<?= json_encode($rec, JSON_HEX_APOS | JSON_HEX_QUOT) ?>)'>Edit</button>
                    </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php endif; ?>
<?php include '../includes/footer.php'; ?>
