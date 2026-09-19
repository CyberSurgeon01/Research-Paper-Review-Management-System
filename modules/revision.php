<?php session_start(); ?>
<?php include '../includes/header.php'; ?>
<?php include '../includes/navbar.php'; ?>

<div class="card">
    <div class="card-header">
        <h2>Revision</h2>
    </div>
    <div class="card-body">
        <form action="" method="post">
            <div class="form-group">
                <label for="revision_id">Revision ID:</label>
                <input type="text" id="revision_id" name="revision_id" placeholder="Enter revision ID">
            </div>
            <div class="form-group">
                <label for="paper_id">Paper ID:</label>
                <input type="text" id="paper_id" name="paper_id" placeholder="Enter paper ID">
            </div>
            <div class="form-group">
                <label for="version_number">Version Number:</label>
                <input type="number" id="version_number" name="version_number" placeholder="Enter version number">
            </div>
            <div class="form-group">
                <label for="upload_date">Upload Date:</label>
                <input type="date" id="upload_date" name="upload_date">
            </div>
            <div class="form-group">
                <label for="revised_file">Revised File:</label>
                <input type="text" id="revised_file" name="revised_file" placeholder="Enter revised file path">
            </div>
            <div class="form-group">
                <label for="remarks">Remarks:</label>
                <textarea id="remarks" name="remarks" placeholder="Enter remarks"></textarea>
            </div>
            
            <div class="button-group">
                <button type="submit" class="btn btn-primary" name="action" value="upload">Upload Revision</button>
                <button type="submit" class="btn btn-primary" name="action" value="update">Update Revision</button>
                <button type="submit" class="btn btn-danger" name="action" value="delete">Delete Revision</button>
                <button type="button" class="btn btn-primary">Get Revision List</button>
            </div>
        </form>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
