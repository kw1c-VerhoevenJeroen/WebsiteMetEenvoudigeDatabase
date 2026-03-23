<?php
    require_once '../includes/mysql-functions.php';

    startConnection('pokemondb');

    /* Nummer ophalen uit de URL */
    $number = $_GET['number'] ?? '';

    /* Valideren: alleen verwijderen als het een getal is */
    if ($number !== '' && is_numeric($number))
    {
        executeDeleteQuery($number);
    }

    /* Terug naar overzicht */
    header('Location: pokemon_overzicht.php');
    exit;
?>