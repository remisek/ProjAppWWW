<?php
    $dbhost = 'localhost';
    $dbuser = 'root';
    $dbpass = '';
    $baza = 'moja_strona';

    $login = 'root';
    $pass = 'admin123';

    $link = mysqli_connect($dbhost, $dbuser, $dbpass);

    if (!$link) {
        die('<b>Przerwane połączenie:</b>' . mysqli_connect_error());
    }
    
    echo " Połączono z bazą danych $baza! ";
?>