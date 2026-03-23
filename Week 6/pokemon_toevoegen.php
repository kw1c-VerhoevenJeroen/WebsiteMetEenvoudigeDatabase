<?php

    require_once "../includes/mysql-functions.php";

    startConnection('pokemondb');

    if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        $name = $_POST['name'];
        $number = $_POST['number'];
        $type1 = $_POST['type1'];
        $type2 = $_POST['type2'];
        $ability = $_POST['ability'];
        $image = $_POST['image'];

        executePreparedInsertPokemon($name, $number, $type1, $type2, $ability, $image);

        header("Location: pokemon_overzicht.php");
    }

?>
<!doctype html>
<html lang="nl">
<head>
    <meta charset="utf-8">
    <title>Pokémon toevoegen</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

    <h1>Toevoegen Pokémon</h1>
    <form method="post">
        Name:<br>
        <input type="text" name="name"><br>

        Number:<br>
        <input type="number" name="number"><br>

        Type1:<br>
        <input type="text" name="type1"><br>

        Type2:<br>
        <input type="text" name="type2"><br>

        Ability:<br>
        <input type="text" name="ability"><br><br>

        Afbeelding<br>
        <input type="text" name="image"><br><br>

        <button type="submit">Opslaan</button>
    </form>

</body>
</html>
