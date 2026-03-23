<?php

    require_once '../includes/functions.php';

    $foutmelding = "";

    if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        $user = $_POST['gebruikersnaam'];
        $password = $_POST['wachtwoord'];

        $foutmelding = checkLogin($user, $password);

    }
?>

<!doctype html>
<html lang="nl">
<head>
    <meta charset="utf-8">
    <title>Pokémon overzicht</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

        <h2>Meld je aan</h2>

        <?php
            if (!empty($foutmelding))
            {
                echo '<h1>' . $foutmelding . '</h1>';
            }
        ?>

        <form method="post">

            Gebruikersnaam:<br>
            <input type="text" name="gebruikersnaam" required><br>

            Wachtwoord:<br>
            <input type="password" name="wachtwoord" required><br><br>

            <button type="submit">Aanmelden</button>

        </form>
</body>
</html>