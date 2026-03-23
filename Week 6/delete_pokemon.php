    <?php

        require_once '../includes/mysql-functions.php';

        startConnection('pokemondb');

        // Controleer of number bestaat en geldig is
        if (isset($_GET['number']) && is_numeric($_GET['number'])) {

            $number = (int) $_GET['number'];

            // Prepare statement
            $stmt = $conn->prepare("DELETE FROM pokemon WHERE number = ?");

            if ($stmt === false) {
                die("Fout bij voorbereiden van de query.");
            }

            // Bind parameter (i = integer)
            $stmt->bind_param("i", $number);

            // Execute
            $stmt->execute();

            // Sluit statement
            $stmt->close();
        }

        // Sluit verbinding
        $conn->close();

        // Redirect terug naar overzicht
        header("Location: pokemon_overzicht.php");

        exit;
    ?>
