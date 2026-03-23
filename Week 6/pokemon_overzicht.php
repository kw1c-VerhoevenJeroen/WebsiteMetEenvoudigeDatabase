<?php
require_once '../includes/mysql-functions.php';

startConnection('pokemondb');

/* 1) Filters uitlezen uit de URL (GET) */
$type    = $_GET['type'] ?? '';
$ability = $_GET['ability'] ?? '';

/* 2) Basis query */
$query = "SELECT name, number, type1, type2, ability, picture 
          FROM pokemon";

/* 3) WHERE dynamisch opbouwen (type + ability) */
$conditions = [];

if ($type !== '') {
    $conditions[] = "type1 = '$type'";
}
if ($ability !== '') {
    $conditions[] = "ability = '$ability'";
}

if (count($conditions) > 0) {
    $query .= " WHERE " . implode(" AND ", $conditions);
}

/* 4) Sorteren op naam i.p.v. number */
$query .= " ORDER BY name";

/* 5) Query uitvoeren */
$result = executeSelect($query);

/* 6) Unieke lijsten maken voor dropdowns */
$types = executeSelect("SELECT DISTINCT type1 FROM pokemon ORDER BY type1");
$abilities = executeSelect("SELECT DISTINCT ability FROM pokemon ORDER BY ability");
?>
<!doctype html>
<html lang="nl">
<head>
    <meta charset="utf-8">
    <title>Pokémon overzicht</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<h1>Pokémon overzicht</h1>
<p class="muted">Filter op type en/of ability. Laat leeg voor alles.</p>

<!-- FILTERFORMULIER -->
<form method="get" action="" class="filterForm">
    <div>
        <label for="type">Type</label>
        <select name="type" id="type">
            <option value="">-- Alle types --</option>
            <?php foreach ($types as $t): ?>
                <option value="<?= htmlspecialchars($t['type1']) ?>"
                    <?= ($type === $t['type1']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($t['type1']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div>
        <label for="ability">Ability</label>
        <select name="ability" id="ability">
            <option value="">-- Alle abilities --</option>
            <?php foreach ($abilities as $a): ?>
                <option value="<?= htmlspecialchars($a['ability']) ?>"
                    <?= ($ability === $a['ability']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($a['ability']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="actions">
        <button type="submit">Filter</button>
        <!-- RESET: link zonder querystring -->
        <a href="pokemon_overzicht.php">
            <button type="button">Reset</button>
        </a>
    </div>
</form>

<a href="pokemon_toevoegen.php">Toevoegen Pokémon</a>

<!-- RESULTATEN -->
<table>
    <tr>
        <th>Afbeelding</th>
        <th>Name</th>
        <th>Number</th>
        <th>Type1</th>
        <th>Type2</th>
        <th>Ability</th>
        <th>Verwijder</th>
    </tr>

    <?php if (count($result) === 0): ?>
        <tr>
            <td colspan="5">Geen Pokémon gevonden met deze filters.</td>
        </tr>
    <?php else: ?>
        <?php foreach ($result as $row): ?>
            <tr>
                <td><img class="pokemonImage"src="<?= $row['picture'] ?>"</td>
                <td><?= $row['name'] ?></td>
                <td><?= (int)$row['number'] ?></td>
                <td><?= $row['type1'] ?></td>
                <td><?= $row['type2'] ?? '-' ?></td>
                <td><?= $row['ability'] ?></td>
                <td>
                    <a href="delete_pokemon.php?number=<?= $row['number']; ?>" onclick="return confirm('Weet u zeker dat u deze regel wilt verwijderen?');">
                        Verwijder
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php endif; ?>
</table>

</body>
</html>