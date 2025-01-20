<?php
$nr_indeksu = '164428';
$nrGrupy = '3';
echo 'Remigiusz ' . $nr_indeksu . ' grupa ' . $nrGrupy . ' <br /><br />';
echo 'Zastosowanie metody include() <br />';

echo 'Przykład zastosowania metody include() i require_once():<br />';
echo 'include():<br />';
include('included_file.php');

echo 'require_once():<br />';
require_once('included_file.php');

echo '<br />Przykład zastosowania warunków if, else, elseif, switch:<br />';
$number = 10;

if ($number < 10) {
    echo 'Liczba jest mniejsza niż 10<br />';
} elseif ($number == 10) {
    echo 'Liczba jest równa 10<br />';
} else {
    echo 'Liczba jest większa niż 10<br />';
}

$day = 'Monday';
switch ($day) {
    case 'Monday':
        echo 'Dziś jest poniedziałek<br />';
        break;
    case 'Tuesday':
        echo 'Dziś jest wtorek<br />';
        break;
    default:
        echo 'Nie wiem, jaki jest dziś dzień<br />';
        break;
}

echo '<br />Przykład zastosowania pętli while() i for():<br />';
echo 'Pętla while():<br />';
$i = 0;
while ($i < 5) {
    echo 'i = ' . $i . '<br />';
    $i++;
}

echo 'Pętla for():<br />';
for ($j = 0; $j < 5; $j++) {
    echo 'j = ' . $j . '<br />';
}

echo '<br />Przykład zastosowania typów zmiennych $_GET, $_POST, $_SESSION:<br />';
echo '$_GET:<br />';
if (isset($_GET['example'])) {
    echo 'Przekazano przez $_GET: ' . $_GET['example'] . '<br />';
}

echo '$_POST:<br />';
if (isset($_POST['example'])) {
    echo 'Przekazano przez $_POST: ' . $_POST['example'] . '<br />';
}

echo '$_SESSION:<br />';
session_start();
$_SESSION['example'] = 'Sesyjna wartość';
if (isset($_SESSION['example'])) {
    echo 'Przekazano przez $_SESSION: ' . $_SESSION['example'] . '<br />';
}
?>
