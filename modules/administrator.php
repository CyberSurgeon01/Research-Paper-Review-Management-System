<?php session_start(); ?>
<?php include '../includes/header.php'; ?>
<?php include '../includes/navbar.php'; ?>

<div class="card">
    <div class="card-header">
        <h2>Administrator</h2>
    </div>
    <div class="card-body">
        <form action="" method="post">
            <div class="form-group">
                <label for="admin_id">Admin ID:</label>
                <input type="text" id="admin_id" name="admin_id">
            </div>
            <div class="form-group">
                <label for="admin_name">Admin Name:</label>
                <input type="text" id="admin_name" name="admin_name">
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email">
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password">
            </div>
            <div class="form-group">
                <label for="role">Role:</label>
                <select id="role" name="role">
                    <option value="Super Admin">Super Admin</option>
                    <option value="Manager">Manager</option>
                    <option value="Editor">Editor</option>
                </select>
            </div>
            
            <div class="button-group">
                <button type="button" class="btn btn-primary">Login</button>
                <button type="button" class="btn btn-primary">Assign Reviewer</button>
                <button type="button" class="btn btn-primary">Monitor Review Progress</button>
                <button type="button" class="btn btn-primary">Issue Final Decision</button>
                <button type="button" class="btn btn-primary">Generate Report</button>
            </div>
        </form>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
