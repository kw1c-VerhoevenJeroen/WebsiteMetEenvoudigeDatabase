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

    /*
     * Functie die een selecyt doet met 1 parameter
     */
    function executePreparedSelect1(string $sql, string $type = "", $param = null): array
    {
        global $conn;

        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("Prepare-fout: " . $conn->error);
        }

        // Alleen binden als er echt een parameter is meegegeven
        if ($param !== null) {
            $stmt->bind_param($type, $param); // bv. "s", $name
        }

        $stmt->execute();

        $result = $stmt->get_result();
        if (!$result) {
            die("get_result() niet beschikbaar.");
        }

        $data = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $data ?: [];
    }

    function executePreparedInsertPokemon($name, $number, $type1, $type2, $ability, $image)
    {
        global $conn;

        $sql = "INSERT INTO pokemon
                (name, number, type1, type2, ability, picture)
                VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            die("Prepare fout: " . $conn->error);
        }

        // s = string, i = integer
        $stmt->bind_param("sissss", $name, $number, $type1, $type2, $ability, $image);

        $stmt->execute();

        if ($stmt->error) {
            die("Execute fout: " . $stmt->error);
        }

        $stmt->close();
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