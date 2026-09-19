<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RPRMS - Home</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="card">
        <div class="card-header">
            <h2>System Navigation</h2>
        </div>
        <div class="card-body" style="text-align: center;">
            <p>Welcome to the Research Paper Review Management System</p>
            <div class="button-group" style="flex-direction: column; gap: 10px; align-items: center;">
                <a href="modules/author.php" class="btn btn-primary" style="width: 200px; text-decoration: none;">Author Module</a>
                <a href="modules/research_paper.php" class="btn btn-primary" style="width: 200px; text-decoration: none;">Research Paper Module</a>
                <a href="modules/research_field.php" class="btn btn-primary" style="width: 200px; text-decoration: none;">Research Field Module</a>
                <a href="modules/reviewer.php" class="btn btn-primary" style="width: 200px; text-decoration: none;">Reviewer Module</a>
                <a href="modules/review.php" class="btn btn-primary" style="width: 200px; text-decoration: none;">Review Module</a>
                <a href="modules/revision.php" class="btn btn-primary" style="width: 200px; text-decoration: none;">Revision Module</a>
                <a href="modules/administrator.php" class="btn btn-primary" style="width: 200px; text-decoration: none;">Administrator Module</a>
            </div>
        </div>
    </div>
</body>
</html>
