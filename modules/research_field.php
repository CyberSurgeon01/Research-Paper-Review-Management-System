<?php session_start(); ?>
<?php include '../includes/header.php'; ?>
<?php include '../includes/navbar.php'; ?>

<div class="card">
    <div class="card-header">
        <h2>ResearchField</h2>
    </div>
    <div class="card-body">
        <form action="" method="post">
            <div class="form-group">
                <label for="field_id">Field ID:</label>
                <input type="text" id="field_id" name="field_id">
            </div>
            <div class="form-group">
                <label for="field_name">Field Name:</label>
                <input type="text" id="field_name" name="field_name">
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea id="description" name="description"></textarea>
            </div>
            
            <div class="button-group">
                <button type="submit" class="btn btn-danger" name="action" value="add">Add Field</button>
                <button type="submit" class="btn btn-primary" name="action" value="update">Update Field</button>
                <button type="submit" class="btn btn-danger" name="action" value="delete">Delete Field</button>
                <button type="button" class="btn btn-primary">Get Field List</button>
            </div>
        </form>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
