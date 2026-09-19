<?php session_start(); ?>
<?php include '../includes/header.php'; ?>
<?php include '../includes/navbar.php'; ?>

<div class="card">
    <div class="card-header">
        <h2>Review</h2>
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
                <input type="date" id="review_date" name="review_date">
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

<?php include '../includes/footer.php'; ?>
