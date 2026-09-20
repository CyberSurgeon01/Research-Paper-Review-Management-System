<?php
session_start();
require_once '../includes/mock_seeder.php';

if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in'] || $_SESSION['role'] !== 'Administrator') {
    header("Location: ../index.php");
    exit;
}

$success_msg = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['assign'])) {
    $paper_id = $_POST['paper_id'] ?? '';
    $reviewer_id = $_POST['reviewer_id'] ?? '';
    
    if ($paper_id && $reviewer_id) {
        $review_id = 'RV' . rand(100, 999); // Generate random review ID
        
        // Create an empty review assignment
        $_SESSION['review'][$review_id] = [
            'review_id' => $review_id,
            'paper_id' => $paper_id,
            'reviewer_id' => $reviewer_id,
            'review_date' => '',
            'review_score' => '',
            'recommendation' => 'Pending',
            'reviewer_comments' => '',
            'assignment_status' => 'Invited'
        ];
        
        // Update paper status to Under Review
        if (isset($_SESSION['research_paper'][$paper_id])) {
            $_SESSION['research_paper'][$paper_id]['status'] = 'Under Review';
        }
        
        $success_msg = "Paper $paper_id successfully assigned to Reviewer $reviewer_id!";
    }
}

$view = $_GET['view'] ?? 'list';

// Get papers needing review
$pending_papers = array_filter($_SESSION['research_paper'] ?? [], function($p) {
    return in_array($p['status'], ['Submitted', 'Revision Required']);
});
?>
<?php include '../includes/header.php'; ?>
<?php include '../includes/navbar.php'; ?>

<?php if ($view === 'form'): ?>
<div class="card">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; padding-right: 30px;">
        <h2>Assign Paper to Reviewer</h2>
        <a href="?view=list" class="btn btn-primary" style="background-color: #6c757d; text-decoration: none;">&#8592; Back to Assignments</a>
    </div>
    <div class="card-body">
        <?php if ($success_msg): ?>
            <div style="background-color: #f0fdf4; color: #28a745; padding: 12px; border-radius: 6px; margin-bottom: 20px; text-align: center; border: 1px solid #bbf7d0;">
                <?= htmlspecialchars($success_msg) ?>
            </div>
        <?php endif; ?>

        <form action="" method="post">
            <div class="form-group">
                <label for="paper_id">Select Paper *</label>
                <select id="paper_id" name="paper_id" required>
                    <option value="">-- Choose a Pending Paper --</option>
                    <?php foreach ($pending_papers as $p): ?>
                        <option value="<?= htmlspecialchars($p['paper_id']) ?>">
                            <?= htmlspecialchars($p['paper_id'] . ' - ' . substr($p['paper_title'], 0, 40) . '...') ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php if (empty($pending_papers)): ?>
                    <small style="color: #888; margin-top: 5px; display: block;">No papers are currently pending assignment.</small>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <label for="reviewer_id">Select Reviewer *</label>
                <select id="reviewer_id" name="reviewer_id" required>
                    <option value="">-- Choose a Reviewer --</option>
                    <?php foreach ($_SESSION['reviewer'] ?? [] as $r): ?>
                        <option value="<?= htmlspecialchars($r['reviewer_id']) ?>">
                            <?= htmlspecialchars($r['reviewer_id'] . ' - ' . $r['reviewer_name'] . ' (' . $r['expertise'] . ')') ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="button-group">
                <button type="submit" name="assign" value="1" class="btn btn-primary" style="background-color: #28a745;">Assign Reviewer</button>
            </div>
        </form>
    </div>
</div>
<?php else: ?>
<div class="card" style="margin-top: 20px; width: 90%; max-width: 1200px;">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; padding-right: 30px;">
        <h2>Current Review Assignments</h2>
        <div>
            <a href="?view=form" class="btn btn-primary" style="background-color: #28a745; text-decoration: none; margin-right: 10px;">+ Assign New Review</a>
            <a href="../index.php" class="btn btn-primary" style="background-color: #6c757d; text-decoration: none;">&#8592; Dashboard</a>
        </div>
    </div>
    <div class="card-body" style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="background-color: #f4f5f7; border-bottom: 2px solid #ccc;">
                    <th style="padding: 10px; border: 1px solid #eee;">Review ID</th>
                    <th style="padding: 10px; border: 1px solid #eee;">Paper ID</th>
                    <th style="padding: 10px; border: 1px solid #eee;">Reviewer ID</th>
                    <th style="padding: 10px; border: 1px solid #eee;">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach (array_reverse($_SESSION['review'] ?? []) as $r): ?>
                <tr>
                    <td style="padding: 10px; border: 1px solid #eee;"><?= htmlspecialchars($r['review_id']) ?></td>
                    <td style="padding: 10px; border: 1px solid #eee;"><?= htmlspecialchars($r['paper_id']) ?></td>
                    <td style="padding: 10px; border: 1px solid #eee;"><?= htmlspecialchars($r['reviewer_id']) ?></td>
                    <td style="padding: 10px; border: 1px solid #eee;">
                        <?php if (isset($r['assignment_status']) && $r['assignment_status'] === 'Invited'): ?>
                            <span class="badge badge-pending" style="background-color: #17a2b8; color: #fff;">Invited</span>
                        <?php elseif (isset($r['assignment_status']) && $r['assignment_status'] === 'Accepted'): ?>
                            <span class="badge badge-pending" style="background-color: #ffc107; color: #000;">In Progress</span>
                        <?php elseif (isset($r['assignment_status']) && $r['assignment_status'] === 'Declined'): ?>
                            <span class="badge badge-rejected">Declined</span>
                        <?php else: ?>
                            <span class="badge badge-accepted">Completed</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>

<?php include '../includes/footer.php'; ?>

