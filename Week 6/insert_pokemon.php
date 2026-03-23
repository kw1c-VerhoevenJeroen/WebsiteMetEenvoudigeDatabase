<?php
require_once '../includes/mysql-functions.php';

startConnection('pokemondb');

/* Gegevens ophalen */
$name    = trim($_POST['name'] ?? '');
$number  = $_POST['number'] ?? '';
$type1   = trim($_POST['type1'] ?? '');
$type2   = trim($_POST['type2'] ?? '');
$ability = trim($_POST['ability'] ?? '');
$picture = trim($_POST['picture'] ?? '');

/* Validatie */
if (
    $name === '' ||
    $number === '' ||
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
$query = "INSERT INTO pokemon (name, number, type1, type2, ability, picture)
          VALUES ('$name', $number, '$type1', '$type2', '$ability', '$picture')";

executeInsertQuery($query);

/* Terug naar overzicht */
header('Location: pokemon_overzicht.php');
exit;
?>