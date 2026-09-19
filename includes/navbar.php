<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<nav>
    <ul>
        <li><a href="../index.php">Home / Dashboard</a></li>
        <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']): ?>
            <li><a href="../index.php?action=logout" style="color: #ffb3b3;">Logout (<?= htmlspecialchars($_SESSION['role']) ?>)</a></li>
        <?php endif; ?>
    </ul>
</nav>
