<?php
    /*  Database connection using PDO   */
    $host = "localhost";
    $dbn = "st_users";
    $usn = "aubrey";       
    $pword = "mota3t";
    /*  Connect to database */
    $link = new PDO("mysql:host=$host;dbname=$dbn", $usn, $pword);
?>
