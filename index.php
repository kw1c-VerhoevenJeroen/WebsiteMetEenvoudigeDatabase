<?php

/*
    Verbinding met de database
    Eerst maken een paar variabelen aan (strings)
*/
$host = "localhost";
$user = "root";
$pass = "";
$db   = "test"; // naam van de database

// Probeer nu te verbinden met de database
$conn = new mysqli($host, $user, $pass, $db);

// Controleer of verbinding gelukt is
if ($conn->connect_error) {
    die("Verbinding mislukt: " . $conn->connect_error);
}

// SQL query: alleen voornaam en achternaam ophalen
$sql = "SELECT Voornaam, Achternaam FROM studenten";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Studentenlijst</title>
</head>
<body>

<h2>Studenten</h2>

<table border="1" cellpadding="5">

    <tr>
        <th>Voornaam</th>
        <th>Achternaam</th>
    </tr>

    <?php

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["Voornaam"] . "</td>";
            echo "<td>" . $row["Achternaam"] . "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='2'>Geen studenten gevonden</td></tr>";
    }

    $conn->close();
    ?>

</table>

</body>
</html>
