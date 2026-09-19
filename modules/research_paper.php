<?php
session_start();
$module = 'research_paper';
$primary_key = 'paper_id';

if (!isset($_SESSION[$module])) {
    $_SESSION[$module] = [];
}

// CRUD Functions
function addRecord($data) {
    global $module, $primary_key;
    $id = !empty($data[$primary_key]) ? $data[$primary_key] : uniqid();
    unset($data['action']);
    if (isset($_SESSION['role']) && $_SESSION['role'] === 'Author') {
        $data['author_id'] = $_SESSION['user_id'];
    }
    
    // Workflow 1: Author submits paper -> status = 'Submitted'
    $data['status'] = 'Submitted';
    if (empty($data['version_number'])) {
        $data['version_number'] = 1;
    }
    
    if ($id) {
        $_SESSION[$module][$id] = $data;
    }
}

function updateRecord($id, $data) {
    global $module;
    unset($data['action']);
    if (isset($_SESSION['role']) && $_SESSION['role'] === 'Author') {
        $data['author_id'] = $_SESSION['user_id'];
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
if (isset($_SESSION['role']) && $_SESSION['role'] === 'Author') {
    $records = array_filter($records, function($r) {
        return (isset($r['author_id']) && $r['author_id'] === $_SESSION['user_id']);
    });
}
?>
<?php include '../includes/header.php'; ?>
<?php include '../includes/navbar.php'; ?>

<div class="card">
    <div class="card-header">
        <h2>Research Paper</h2>
    </div>
    <div class="card-body">
        <form action="" method="post">
            <div class="form-group">
                <label for="paper_id">Paper ID:</label>
                <input type="text" id="paper_id" name="paper_id" placeholder="Enter paper ID">
            </div>
            <div class="form-group">
                <label for="author_id">Author ID:</label>
                <input type="text" id="author_id" name="author_id" placeholder="Enter author ID">
            </div>
            <div class="form-group">
                <label for="field_id">Field ID:</label>
                <input type="text" id="field_id" name="field_id" placeholder="Enter field ID">
            </div>
            <div class="form-group">
                <label for="paper_title">Paper Title:</label>
                <input type="text" id="paper_title" name="paper_title" placeholder="Enter paper title">
            </div>
            <div class="form-group">
                <label for="abstract_text">Abstract Text:</label>
                <textarea id="abstract_text" name="abstract_text" placeholder="Enter abstract text"></textarea>
            </div>
            <div class="form-group">
                <label for="keywords">Keywords:</label>
                <input type="text" id="keywords" name="keywords" placeholder="Enter keywords (comma separated)">
            </div>
            <div class="form-group">
                <label for="submission_date">Submission Date:</label>
                <input type="text" id="submission_date" name="submission_date" placeholder="dd-mm-yyyy">
            </div>
            <div class="form-group">
                <label for="status">Status:</label>
                <select id="status" name="status">
                    <option value="">Select status</option>
                    <option value="Submitted">Submitted</option>
                    <option value="Under Review">Under Review</option>
                    <option value="Accepted">Accepted</option>
                    <option value="Rejected">Rejected</option>
                </select>
            </div>
            <div class="form-group">
                <label for="file_path">File Path:</label>
                <input type="text" id="file_path" name="file_path" placeholder="Enter file path">
            </div>
            <div class="form-group">
                <label for="version_number">Version Number:</label>
                <input type="number" id="version_number" name="version_number" placeholder="Enter version number">
            </div>
            
            <div class="button-group">
                <button type="submit" class="btn btn-primary" name="action" value="submit">Submit Paper</button>
                <button type="submit" class="btn btn-primary" name="action" value="update">Update Paper</button>
                <button type="submit" class="btn btn-danger" name="action" value="withdraw">Withdraw Paper</button>
                <button type="button" class="btn btn-primary">Get Paper Details</button>
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

<div class="card" style="margin-top: 20px; width: 90%; max-width: 1200px;">
    <div class="card-header">
        <h2><?= ucfirst(str_replace('_', ' ', $module)) ?> Records</h2>
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

<?php include '../includes/footer.php'; ?>
