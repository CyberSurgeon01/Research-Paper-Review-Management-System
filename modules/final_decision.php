<?php session_start(); ?>
<?php include '../includes/header.php'; ?>
<?php include '../includes/navbar.php'; ?>

<div class="card">
    <div class="card-header">
        <h2>Final Decision</h2>
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

<?php include '../includes/footer.php'; ?>
