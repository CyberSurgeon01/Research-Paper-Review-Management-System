<?php session_start(); ?>
<?php include '../includes/header.php'; ?>
<?php include '../includes/navbar.php'; ?>

<div class="card">
    <div class="card-header">
        <h2>Reviewer</h2>
    </div>
    <div class="card-body">
        <form action="" method="post">
            <div class="form-group">
                <label for="reviewer_id">Reviewer ID:</label>
                <input type="text" id="reviewer_id" name="reviewer_id" placeholder="Enter reviewer ID">
            </div>
            <div class="form-group">
                <label for="reviewer_name">Reviewer Name:</label>
                <input type="text" id="reviewer_name" name="reviewer_name" placeholder="Enter reviewer name">
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" placeholder="Enter email">
            </div>
            <div class="form-group">
                <label for="expertise">Expertise:</label>
                <input type="text" id="expertise" name="expertise" placeholder="Enter expertise">
            </div>
            <div class="form-group">
                <label for="designation">Designation:</label>
                <input type="text" id="designation" name="designation" placeholder="Enter designation">
            </div>
            <div class="form-group">
                <label for="affiliation">Affiliation:</label>
                <input type="text" id="affiliation" name="affiliation" placeholder="Enter affiliation">
            </div>
            <div class="form-group">
                <label for="phone_number">Phone Number:</label>
                <input type="tel" id="phone_number" name="phone_number" placeholder="Enter phone number">
            </div>
            
            <div class="button-group">
                <button type="button" class="btn btn-primary">Login</button>
                <button type="submit" class="btn btn-primary" name="action" value="update">Update Profile</button>
                <button type="submit" class="btn btn-primary" name="action" value="accept">Accept Invitation</button>
                <button type="submit" class="btn btn-danger" name="action" value="submit_review">Submit Review</button>
            </div>
        </form>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
