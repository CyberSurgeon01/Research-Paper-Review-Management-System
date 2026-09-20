<?php
session_start();
require_once '../includes/mock_seeder.php';

if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in'] || $_SESSION['role'] !== 'Reviewer') {
    header("Location: ../index.php");
    exit;
}

$success_msg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $review_id = $_POST['review_id'] ?? '';
    $action = $_POST['action'];
    
    if (isset($_SESSION['review'][$review_id])) {
        if ($action === 'accept') {
            $_SESSION['review'][$review_id]['assignment_status'] = 'Accepted';
            $success_msg = "You have accepted the review assignment. It has been moved to your Assigned Reviews.";
        } elseif ($action === 'decline') {
            $_SESSION['review'][$review_id]['assignment_status'] = 'Declined';
            $success_msg = "You have declined the review assignment.";
            
            // Optionally, reset paper status if no other active reviews exist for it
            // (Skipping complex logic for mock prototype, just marking declined)
        }
    }
}

// Get pending invitations for this reviewer
$invitations = array_filter($_SESSION['review'] ?? [], function($r) {
    return (isset($r['reviewer_id']) && $r['reviewer_id'] === $_SESSION['user_id'] && isset($r['assignment_status']) && $r['assignment_status'] === 'Invited');
});
?>
<?php include '../includes/header.php'; ?>
<?php include '../includes/navbar.php'; ?>

<div class="card" style="margin-top: 20px; width: 90%; max-width: 1200px;">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; padding-right: 30px;">
        <h2>Pending Review Invitations</h2>
        <a href="../index.php" class="btn btn-primary" style="background-color: #6c757d; text-decoration: none;">&#8592; Dashboard</a>
    </div>
    <div class="card-body" style="overflow-x: auto;">
        
        <?php if ($success_msg): ?>
            <div style="background-color: #f0fdf4; color: #28a745; padding: 12px; border-radius: 6px; margin-bottom: 20px; text-align: center; border: 1px solid #bbf7d0;">
                <?= htmlspecialchars($success_msg) ?>
            </div>
        <?php endif; ?>

        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="background-color: #f4f5f7; border-bottom: 2px solid #ccc;">
                    <th style="padding: 10px; border: 1px solid #eee;">Paper ID</th>
                    <th style="padding: 10px; border: 1px solid #eee;">Paper Title</th>
                    <th style="padding: 10px; border: 1px solid #eee;">Field</th>
                    <th style="padding: 10px; border: 1px solid #eee;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($invitations as $r): ?>
                <?php 
                    $paper = $_SESSION['research_paper'][$r['paper_id']] ?? [];
                    $field_id = $paper['field_id'] ?? '';
                    $field_name = $_SESSION['research_field'][$field_id]['field_name'] ?? $field_id;
                ?>
                <tr>
                    <td style="padding: 10px; border: 1px solid #eee;"><?= htmlspecialchars($r['paper_id']) ?></td>
                    <td style="padding: 10px; border: 1px solid #eee;">
                        <?= htmlspecialchars($paper['paper_title'] ?? 'Unknown Paper') ?>
                        <br><small style="color: #666;"><?= htmlspecialchars(substr($paper['abstract_text'] ?? '', 0, 80)) ?>...</small>
                    </td>
                    <td style="padding: 10px; border: 1px solid #eee;"><?= htmlspecialchars($field_name) ?></td>
                    <td style="padding: 10px; border: 1px solid #eee; white-space: nowrap;">
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="review_id" value="<?= htmlspecialchars($r['review_id']) ?>">
                            <button type="submit" name="action" value="accept" class="btn btn-primary" style="background-color: #28a745; padding: 6px 12px; font-size: 12px;">Accept</button>
                        </form>
                        <form method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to decline this review?');">
                            <input type="hidden" name="review_id" value="<?= htmlspecialchars($r['review_id']) ?>">
                            <button type="submit" name="action" value="decline" class="btn btn-danger" style="padding: 6px 12px; font-size: 12px;">Decline</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($invitations)): ?>
                <tr>
                    <td colspan="4" style="padding: 20px; text-align: center; color: #777;">You have no pending review invitations at this time.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../includes/footer.php'; ?>

