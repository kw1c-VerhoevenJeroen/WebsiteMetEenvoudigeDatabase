<?php
/**
 * User: Jeroen Verhoeven
 * Date: 15-11-2024
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

    // Uitvoeren van een query
    function executeQuery($sql)
    {
        global $conn;

        // Controleer of de verbinding geldig is
        if (!$conn) {
            die("Verbindingsfout: " . mysqli_connect_error());
        }

        // Uitvoeren van een SQL-query en controleren of het succesvol is
        $result = $conn->query($sql);

        if ($result === false) {
            // Foutcontrole voor de query
            die("Query-fout: " . $conn->error);
        }

        // Controleer of er resultaten zijn en retourneer de data als een array
        if ($result instanceof mysqli_result && $result->num_rows > 0) {
            $data = [];
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            return $data;
        } else {
            // Geen resultaten gevonden
            return [];
        }
    }

    // Uitvoeren van een insert-query
    function executeInsertQuery($query)
    {
        global $conn;

        // Uitvoeren van een SQL-query en controleren op fouten
        if ($conn->query($query) === TRUE) {
            // Geef het ID van de laatste toegevoegde regel terug als bevestiging
            return $conn->insert_id;
        } else {
            // Geef de foutmelding terug voor debugging
            return "Fout: " . $conn->error;
        }
    }

    // Uitvoeren van een update-query
    function executeUpdateQuery($query)
    {
        global $conn;

        // Voer de query uit
        if ($conn->query($query) === TRUE) {
            return $conn->affected_rows;
        } else {
            echo "Fout bij het bijwerken van het record: " . $conn->error;
        }
    }
?>