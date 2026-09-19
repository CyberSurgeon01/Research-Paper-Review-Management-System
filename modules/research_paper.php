<?php session_start(); ?>
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
                <input type="date" id="submission_date" name="submission_date">
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

<?php include '../includes/footer.php'; ?>
