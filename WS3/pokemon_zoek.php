<?php
require_once '../includes/mysql-functions.php';

// Maak verbinding (pas db-naam aan als jouw functie dat zo verwacht)
startConnection('pokemondb');

// Zoekterm uit GET
$q = $_GET['q'] ?? '';

// ⚠️ ONVEILIG: gebruikersinput wordt direct in SQL geplakt (SQL-injectie mogelijk)
$sql = "SELECT name, number, type1, type2, ability
        FROM pokemon
        WHERE name LIKE '%$q%'
        ORDER BY number";

//echo $sql;
//exit;

$rows = executeSelect($sql);
?>
<!doctype html>
<html lang="nl">
<head>
    <meta charset="utf-8">
    <title>Pokémon zoeken (onveilig)</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<h1>Pokémon zoeken (onveilig)</h1>
<p class="muted">Zoek op naam. Deze pagina is expres kwetsbaar voor SQL-injectie (voor de workshop).</p>

<form method="get" action="">
    <label for="q">Naam:</label>
    <input id="q" name="q" type="text" value="<?= htmlspecialchars($q) ?>" placeholder="bijv. char">
    <button type="submit">Zoek</button>
    <a href="pokemon_zoek.php"><button type="button">Reset</button></a>
</form>

<p class="muted">
    Gebruikte SQL (ter uitleg): <code><?= htmlspecialchars($sql) ?></code>
</p>

<table>
    <tr>
        <th>Name</th>
        <th>Number</th>
        <th>Type1</th>
        <th>Type2</th>
        <th>Ability</th>
    </tr>

    <?php if (empty($rows)): ?>
        <tr><td colspan="5">Geen resultaten.</td></tr>
    <?php else: ?>
        <?php foreach ($rows as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= (int)$row['number'] ?></td>
                <td><?= htmlspecialchars($row['type1']) ?></td>
                <td><?= htmlspecialchars($row['type2'] ?? '-') ?></td>
                <td><?= htmlspecialchars($row['ability']) ?></td>
            </tr>
        <?php endforeach; ?>
    <?php endif; ?>
</table>

</body>
</html>