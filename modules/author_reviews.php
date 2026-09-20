<?php
session_start();
require_once '../includes/mock_seeder.php';

if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in'] || $_SESSION['role'] !== 'Author') {
    header("Location: ../index.php");
    exit;
}

// Find all papers belonging to this author
$author_papers = array_filter($_SESSION['research_paper'] ?? [], function($p) {
    return $p['author_id'] === $_SESSION['user_id'];
});
$author_paper_ids = array_column($author_papers, 'paper_id');

// Find all reviews that belong to those papers
$author_reviews = array_filter($_SESSION['review'] ?? [], function($r) use ($author_paper_ids) {
    return in_array($r['paper_id'], $author_paper_ids) && $r['recommendation'] !== 'Pending';
});
?>
<?php include '../includes/header.php'; ?>
<?php include '../includes/navbar.php'; ?>

<div class="card" style="margin-top: 20px; width: 90%; max-width: 1200px;">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; padding-right: 30px;">
        <h2>My Paper Reviews</h2>
        <a href="../index.php" class="btn btn-primary" style="background-color: #6c757d; text-decoration: none;">&#8592; Dashboard</a>
    </div>
    <div class="card-body" style="overflow-x: auto;">
        <div style="margin-bottom: 15px;">
            <input type="text" id="searchInput" placeholder="Search reviews..." style="padding: 10px; width: 100%; max-width: 400px; border: 1px solid #ccc; border-radius: 4px;" onkeyup="searchTable()">
        </div>
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="background-color: #f4f5f7; border-bottom: 2px solid #ccc;">
                    <th style="padding: 10px; border: 1px solid #eee;">Paper ID</th>
                    <th style="padding: 10px; border: 1px solid #eee;">Paper Title</th>
                    <th style="padding: 10px; border: 1px solid #eee;">Review Date</th>
                    <th style="padding: 10px; border: 1px solid #eee;">Score</th>
                    <th style="padding: 10px; border: 1px solid #eee;">Recommendation</th>
                    <th style="padding: 10px; border: 1px solid #eee;">Comments</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($author_reviews as $r): ?>
                <tr>
                    <td style="padding: 10px; border: 1px solid #eee;"><?= htmlspecialchars($r['paper_id']) ?></td>
                    <td style="padding: 10px; border: 1px solid #eee;"><?= htmlspecialchars($_SESSION['research_paper'][$r['paper_id']]['paper_title'] ?? '') ?></td>
                    <td style="padding: 10px; border: 1px solid #eee;"><?= htmlspecialchars($r['review_date']) ?></td>
                    <td style="padding: 10px; border: 1px solid #eee;"><?= htmlspecialchars($r['review_score']) ?></td>
                    <td style="padding: 10px; border: 1px solid #eee;"><?= htmlspecialchars($r['recommendation']) ?></td>
                    <td style="padding: 10px; border: 1px solid #eee;"><?= htmlspecialchars($r['reviewer_comments']) ?></td>
                </tr>
                <?php endforeach; ?>
                <?php if(empty($author_reviews)): ?>
                <tr>
                    <td colspan="6" style="padding: 10px; text-align: center;">No reviews available for your papers yet.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

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
