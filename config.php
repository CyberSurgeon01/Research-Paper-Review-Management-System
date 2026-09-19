<?php
// config.php
$conn = @oci_connect('username', 'password', 'localhost/XE');
if (!$conn) {
    $e = oci_error();
    // Intentionally not terminating script here to allow the UI logic to output the error string nicely
    // die("Database Connection Failed: " . htmlentities($e['message'], ENT_QUOTES));
}
?>