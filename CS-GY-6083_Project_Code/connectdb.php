<?php
$mysqli = new mysqli("host.docker.internal", "root", "PASSWORD", "issue_tracker", 3306);

/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}
?>
