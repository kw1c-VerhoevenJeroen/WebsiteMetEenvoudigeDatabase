<?php
/**
 * User: Jeroen Verhoeven
 * Date: 2-3-2026
 * File: mysql-functions.php
 *
 * Bestand met functies voor bewerkingen in MySQL.
 */
// Een "leeg" $conn variabele aanmaken
$conn = null;

// Starten van een DB connectie
function startConnection($database)
{
    global $conn;

    $host = 'localhost';    // Standaard voor XAMPP
    $username = 'root';     // Standaard gebruikersnaam
    $password = '';         // Standaard is leeg

    // Verbinding maken met de MySQL-database
    $conn = new mysqli($host, $username, $password, $database);

    // Controleer de verbinding
    if ($conn->connect_error) {
        die("Verbinding mislukt: " . $conn->connect_error);
    }
}

function executeSelect($sql)
{
    global $conn;

    if (!$conn)
    {
        die("Geen databaseverbinding.");
    }

    $result = $conn->query($sql);

    if (!$result)
    {
        die("Query fout: " . $conn->error);
    }

    return $result->fetch_all(MYSQLI_ASSOC);
}

?>