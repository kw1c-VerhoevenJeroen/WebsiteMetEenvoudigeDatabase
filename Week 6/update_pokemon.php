<?php
require_once '../includes/mysql-functions.php';

startConnection('pokemondb');

/* Gegevens ophalen */
$number  = $_POST['number'] ?? '';
$name    = trim($_POST['name'] ?? '');
$type1   = trim($_POST['type1'] ?? '');
$type2   = trim($_POST['type2'] ?? '');
$ability = trim($_POST['ability'] ?? '');
$picture = trim($_POST['picture'] ?? '');

/* Validatie */
if (
    $number === '' ||
    $name === '' ||
    $type1 === '' ||
    $ability === '' ||
    $picture === '' ||
    !is_numeric($number)
) {
    header('Location: pokemon_overzicht.php');
    exit;
}

/* Nummer veilig maken */
$number = (int)$number;

/* Quotes escapen */
$name    = addslashes($name);
$type1   = addslashes($type1);
$type2   = addslashes($type2);
$ability = addslashes($ability);
$picture = addslashes($picture);

/* Query uitvoeren */
$query = "UPDATE pokemon
          SET name = '$name',
              type1 = '$type1',
              type2 = '$type2',
              ability = '$ability',
              picture = '$picture'
          WHERE number = $number";

executeInsertQuery($query);

/* Terug naar overzicht */
header('Location: pokemon_overzicht.php');
exit;
?>