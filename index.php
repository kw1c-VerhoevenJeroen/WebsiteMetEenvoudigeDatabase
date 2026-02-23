<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Student toevoegen</title>
</head>
<body>

    <h2>Student toevoegen</h2>

    <?php
        if(!empty($_GET['message']))
        {
            echo "<h2>" . $_GET['message'] . "</h2>";
        }
    ?>

    <form method="post" action="verwerk.php">

        <label for="voornaam">Voornaam:</label><br>
        <input type="text" id="voornaam" name="voornaam"><br><br>

        <label for="achternaam">Achternaam:</label><br>
        <input type="text" id="achternaam" name="achternaam"><br><br>

        <input type="submit" value="Versturen">

    </form>

</body>
</html>