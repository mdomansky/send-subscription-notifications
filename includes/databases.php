<?php

$mysqli = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_DBNAME);
if (mysqli_connect_errno()) {
    addLog("Failed to connect to MySQL: " . mysqli_connect_error());
    exit();
}
