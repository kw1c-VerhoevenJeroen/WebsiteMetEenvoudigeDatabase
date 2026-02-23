<?php

//echo "<pre>";
print_r($_POST);
exit;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $voornaam = $_POST["voornaam"];
    $achternaam = $_POST["achternaam"];

    echo "<h3>Ingevoerde gegevens:</h3>";
    echo "Voornaam: " . $voornaam . "<br>";
    echo "Achternaam: " . $achternaam;
}
else
{
    header('Location: index.php?message=Je mag deze pagina niet rechtstreeks aanroepen');
}
?>