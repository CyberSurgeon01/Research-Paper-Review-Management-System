<?php session_start(); ?>
<?php include '../includes/header.php'; ?>
<?php include '../includes/navbar.php'; ?>

<div class="card">
    <div class="card-header">
        <h2>Author</h2>
    </div>
    <div class="card-body">
        <form action="" method="post">
            <div class="form-group">
                <label for="author_id">Author ID:</label>
                <input type="text" id="author_id" name="author_id">
            </div>
            <div class="form-group">
                <label for="author_name">Author Name:</label>
                <input type="text" id="author_name" name="author_name">
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email">
            </div>
            <div class="form-group">
                <label for="affiliation">Affiliation:</label>
                <input type="text" id="affiliation" name="affiliation">
            </div>
            <div class="form-group">
                <label for="phone_number">Phone Number:</label>
                <input type="tel" id="phone_number" name="phone_number">
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password">
            </div>
            
            <div class="button-group">
                <button type="reset" class="btn btn-danger">Reset</button>
                <button type="submit" class="btn btn-primary">Save / Register</button>
            </div>
        </form>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
