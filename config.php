<?php
// config.php
// Oracle 10g Database Connection Template

/* 
 * =========================================================
 * ORACLE DATABASE CONFIGURATION
 * =========================================================
 * 
 * Uncomment the code below and update the credentials when 
 * you are ready to switch from PHP Sessions to Oracle 10g.
 * 
 */

/*
$db_username = 'YOUR_ORACLE_USERNAME';
$db_password = 'YOUR_ORACLE_PASSWORD';
$db_connection_string = 'localhost/XE'; // Usually 'localhost/XE' or 'localhost/orcl' for local 10g

$conn = oci_connect($db_username, $db_password, $db_connection_string);

if (!$conn) {
    $e = oci_error();
    die("Database Connection Failed: " . htmlentities($e['message'], ENT_QUOTES));
}

// Helper function to execute queries easily
function executeQuery($conn, $sql, $params = []) {
    $stid = oci_parse($conn, $sql);
    if (!$stid) {
        $e = oci_error($conn);
        die("Parse Error: " . htmlentities($e['message'], ENT_QUOTES));
    }
    
    foreach ($params as $key => $val) {
        // oci_bind_by_name requires variables by reference
        oci_bind_by_name($stid, $key, $params[$key]);
    }
    
    $r = oci_execute($stid);
    if (!$r) {
        $e = oci_error($stid);
        die("Execution Error: " . htmlentities($e['message'], ENT_QUOTES));
    }
    
    return $stid;
}
*/
?>
