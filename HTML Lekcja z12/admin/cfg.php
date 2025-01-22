<?php

$dbhost = 'localhost';
$dbuser = 'admin';
$dbpass = 'admin';
$dbname = 'moja_strona_2';

$login = "admin";
$pass = "admin";

$link = mysqli_connect($dbhost, $dbuser, $dbpass);

if (!$link) {
    die('<b>Przerwane połączenie:</b>' . mysqli_connect_error());
}

echo " Połączono z bazą danych $baza! ";
?>