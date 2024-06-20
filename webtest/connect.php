<?php 
    $server = 'localhost';
    $user = 'root';
    $pass = '1234';
    $db = 'dulieuwebtest';
    $conn = new mysqLi($server, $user, $pass, $db);
    //var_dump($conn);
    if($conn) {
        mysqLi_query($conn, "SET NAMES 'utf8' ");
        //echo "ket noi thanh cong";
    }
    else echo "That bai";
?>