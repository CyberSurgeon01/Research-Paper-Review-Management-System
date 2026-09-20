<?php
session_start();
$module = 'final_decision';
$primary_key = 'decision_id';

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
    
    if ($id) {
        $_SESSION[$module][$id] = $data;
        
        // Workflow 4: Admin issues Final Decision -> updates paper status
        if (!empty($data['paper_id']) && isset($_SESSION['research_paper'][$data['paper_id']])) {
            $new_status = $data['final_status'];
            if ($new_status === 'Further Revision') {
                $new_status = 'Revision Required';
            }
            $_SESSION['research_paper'][$data['paper_id']]['status'] = $new_status;
        }
    }
}

function updateRecord($id, $data) {
    global $module;
    unset($data['action']);
    if (isset($_SESSION[$module][$id])) {
        $_SESSION[$module][$id] = array_merge($_SESSION[$module][$id], $data);
        
        // Workflow 4: Sync to paper on update as well
        if (!empty($_SESSION[$module][$id]['paper_id']) && isset($_SESSION['research_paper'][$_SESSION[$module][$id]['paper_id']])) {
            $new_status = $_SESSION[$module][$id]['final_status'] ?? '';
            if ($new_status === 'Further Revision') {
                $new_status = 'Revision Required';
            }
            if ($new_status) {
                $_SESSION['research_paper'][$_SESSION[$module][$id]['paper_id']]['status'] = $new_status;
            }
        }
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
?>

<?php $view = $_GET['view'] ?? 'list'; ?>
<?php include '../includes/header.php'; ?>
<?php include '../includes/navbar.php'; ?>

<?php if ($view === 'form'): ?>


<div class="card">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; padding-right: 30px;">
        <h2>Final Decision Form</h2>
        <a href="?view=list" class="btn btn-primary" style="background-color: #6c757d; text-decoration: none;">&#8592; Back to List</a>
    </div>
    <div class="card-body">
        <form action="" method="post">
            <div class="form-group">
                <label for="decision_id">Decision ID:</label>
                <input type="text" id="decision_id" name="decision_id" placeholder="Enter decision ID">
            </div>
            <div class="form-group">
                <label for="paper_id">Paper ID:</label>
                <input type="text" id="paper_id" name="paper_id" placeholder="Enter paper ID">
            </div>
            <div class="form-group">
                <label for="admin_id">Admin ID:</label>
                <input type="text" id="admin_id" name="admin_id" placeholder="Enter admin ID">
            </div>
            <div class="form-group">
                <label for="final_status">Final Status:</label>
                <select id="final_status" name="final_status">
                    <option value="">Select final status</option>
                    <option value="Accepted">Accepted</option>
                    <option value="Further Revision">Further Revision</option>
                    <option value="Rejected">Rejected</option>
                </select>
            </div>
            <div class="form-group">
                <label for="decision_date">Decision Date:</label>
                <input type="text" id="decision_date" name="decision_date" placeholder="dd-mm-yyyy">
            </div>
            <div class="form-group">
                <label for="comments">Comments:</label>
                <textarea id="comments" name="comments" placeholder="Enter final remarks"></textarea>
            </div>
            
            <div class="button-group">
                <button type="submit" class="btn btn-primary" name="action" value="save">Save Decision</button>
                <button type="submit" class="btn btn-primary" name="action" value="update">Update Decision</button>
                <button type="reset" class="btn btn-danger">Reset</button>
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
        <a href="?view=form" class="btn btn-primary" style="background-color: #28a745; text-decoration: none;">+ Add New Decision</a>
    </div>
    <div class="card-body" style="overflow-x: auto;">
        <div style="margin-bottom: 15px;">
            <input type="text" id="searchInput" placeholder="Search records..." style="padding: 10px; width: 100%; max-width: 400px; border: 1px solid #ccc; border-radius: 4px;" onkeyup="searchTable()">
        </div>
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="background-color: #f4f5f7; border-bottom: 2px solid #ccc;">
                    <?php if(!empty($records)): ?>
                        <?php foreach(array_keys(reset($records)) as $key): ?>
                            <th style="padding: 10px; border: 1px solid #eee;"><?= htmlspecialchars(ucwords(str_replace('_', ' ', $key))) ?></th>
                        <?php endforeach; ?>
                    <th style="padding: 10px; border: 1px solid #eee; text-align: center; width: 60px;">Actions</th>
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
                    <td style="padding: 10px; border: 1px solid #eee; text-align: center;">
                        <button type=\"button\" class="btn btn-primary\" style="padding: 4px 8px; font-size: 11px;\" onclick='fillForm(<?= json_encode($rec, JSON_HEX_APOS | JSON_HEX_QUOT) ?>)'>Edit</button>
                    </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>


<?php endif; ?>

<script>
function searchTable() {
    let input = document.getElementById("searchInput").value.toUpperCase();
    let rows = document.querySelectorAll("tbody tr");
    rows.forEach(row => {
        let textContent = row.textContent || row.innerText;
        row.style.display = textContent.toUpperCase().indexOf(input) > -1 ? "" : "none";
    });
}
</script>
<?php include '../includes/footer.php'; ?>
