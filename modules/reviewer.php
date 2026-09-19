<?php
session_start();
$module = 'reviewer';
$primary_key = 'reviewer_id';

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
    }
}

function updateRecord($id, $data) {
    global $module;
    unset($data['action']);
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
<?php include '../includes/header.php'; ?>
<?php include '../includes/navbar.php'; ?>

<div class="card">
    <div class="card-header">
        <h2>Reviewer</h2>
    </div>
    <div class="card-body">
        <?php
$current_record = [];
if (isset($_SESSION['logged_in']) && $_SESSION['role'] === 'Reviewer') {
    $current_record = getRecordDetails($_SESSION['user_id']);
}
?>
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
            </div>
        </form>
    </div>
</div>


<?php if ($success_msg): ?>
<script>alert("<?= $success_msg ?>");</script>
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
