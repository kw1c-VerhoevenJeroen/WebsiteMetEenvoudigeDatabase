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
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pokémon overzicht</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container py-4">
    <h1 class="mb-2">Pokémon overzicht</h1>
    <p class="text-muted">Filter op type en/of ability. Laat leeg voor alles.</p>

    <!-- FILTERFORMULIER -->
    <form method="get" action="" class="row g-3 align-items-end mb-4">
        <div class="col-md-4">
            <label for="type" class="form-label">Type</label>
            <select name="type" id="type" class="form-select">
                <option value="">-- Alle types --</option>
                <?php foreach ($types as $t): ?>
                    <option value="<?= htmlspecialchars($t['type1']) ?>"
                            <?= ($type === $t['type1']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($t['type1']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-md-4">
            <label for="ability" class="form-label">Ability</label>
            <select name="ability" id="ability" class="form-select">
                <option value="">-- Alle abilities --</option>
                <?php foreach ($abilities as $a): ?>
                    <option value="<?= htmlspecialchars($a['ability']) ?>"
                            <?= ($ability === $a['ability']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($a['ability']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-md-4 d-flex gap-2">
            <button type="submit" class="btn btn-primary">Filter</button>
            <a href="pokemon_overzicht.php" class="btn btn-outline-secondary">Reset</a>
        </div>
    </form>

    <div class="mb-3">
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addModal">
            Toevoegen Pokémon
        </button>
    </div>

    <!-- RESULTATEN -->
    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle">
            <thead class="table-light">
            <tr>
                <th>Afbeelding</th>
                <th>Name</th>
                <th>Number</th>
                <th>Type1</th>
                <th>Type2</th>
                <th>Ability</th>
                <th>Wijzig</th>
                <th>Verwijder</th>
            </tr>
            </thead>
            <tbody>
            <?php if (count($result) === 0): ?>
                <tr>
                    <td colspan="8">Geen Pokémon gevonden met deze filters.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($result as $row): ?>
                    <tr>
                        <td>
                            <img
                                    src="<?= htmlspecialchars($row['picture']) ?>"
                                    alt="<?= htmlspecialchars($row['name']) ?>"
                                    class="img-thumbnail"
                                    style="height: 60px;"
                            >
                        </td>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= (int)$row['number'] ?></td>
                        <td><?= htmlspecialchars($row['type1']) ?></td>
                        <td><?= !empty($row['type2']) ? htmlspecialchars($row['type2']) : '-' ?></td>
                        <td><?= htmlspecialchars($row['ability']) ?></td>
                        <td>
                            <button
                                    type="button"
                                    class="btn btn-warning btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editModal"
                                    data-number="<?= (int)$row['number'] ?>"
                                    data-name="<?= htmlspecialchars($row['name']) ?>"
                                    data-type1="<?= htmlspecialchars($row['type1']) ?>"
                                    data-type2="<?= htmlspecialchars($row['type2']) ?>"
                                    data-ability="<?= htmlspecialchars($row['ability']) ?>"
                                    data-picture="<?= htmlspecialchars($row['picture']) ?>"
                            >
                                Wijzig
                            </button>
                        </td>
                        <td>
                            <button
                                    type="button"
                                    class="btn btn-danger btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#deleteModal"
                                    data-number="<?= (int)$row['number'] ?>"
                                    data-name="<?= htmlspecialchars($row['name']) ?>"
                            >
                                Verwijder
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- MODAL TOEVOEGEN -->
<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="insert_pokemon.php" method="post" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addModalLabel">Pokémon toevoegen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Sluiten"></button>
            </div>

            <div class="modal-body">
                <div class="mb-3">
                    <label for="add-name" class="form-label">Naam</label>
                    <input type="text" name="name" id="add-name" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="add-number" class="form-label">Nummer</label>
                    <input type="number" name="number" id="add-number" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="add-type1" class="form-label">Type 1</label>
                    <input type="text" name="type1" id="add-type1" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="add-type2" class="form-label">Type 2</label>
                    <input type="text" name="type2" id="add-type2" class="form-control">
                </div>

                <div class="mb-3">
                    <label for="add-ability" class="form-label">Ability</label>
                    <input type="text" name="ability" id="add-ability" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="add-picture" class="form-label">Afbeelding URL</label>
                    <input type="text" name="picture" id="add-picture" class="form-control" required>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuleren</button>
                <button type="submit" class="btn btn-success">Opslaan</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL WIJZIGEN -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="update_pokemon.php" method="post" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Pokémon wijzigen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Sluiten"></button>
            </div>

            <div class="modal-body">
                <div class="mb-3">
                    <label for="edit-number" class="form-label">Nummer</label>
                    <input type="number" name="number" id="edit-number" class="form-control" readonly>
                </div>

                <div class="mb-3">
                    <label for="edit-name" class="form-label">Naam</label>
                    <input type="text" name="name" id="edit-name" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="edit-type1" class="form-label">Type 1</label>
                    <input type="text" name="type1" id="edit-type1" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="edit-type2" class="form-label">Type 2</label>
                    <input type="text" name="type2" id="edit-type2" class="form-control">
                </div>

                <div class="mb-3">
                    <label for="edit-ability" class="form-label">Ability</label>
                    <input type="text" name="ability" id="edit-ability" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="edit-picture" class="form-label">Afbeelding URL</label>
                    <input type="text" name="picture" id="edit-picture" class="form-control" required>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuleren</button>
                <button type="submit" class="btn btn-warning">Opslaan wijzigingen</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL VERWIJDEREN -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Pokémon verwijderen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Sluiten"></button>
            </div>

            <div class="modal-body">
                Weet u zeker dat u <strong id="deletePokemonName"></strong> wilt verwijderen?
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuleren</button>
                <a href="#" id="confirmDeleteBtn" class="btn btn-danger">Ja, verwijderen</a>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const editModal = document.getElementById('editModal');
        const deleteModal = document.getElementById('deleteModal');

        const editNumber = document.getElementById('edit-number');
        const editName = document.getElementById('edit-name');
        const editType1 = document.getElementById('edit-type1');
        const editType2 = document.getElementById('edit-type2');
        const editAbility = document.getElementById('edit-ability');
        const editPicture = document.getElementById('edit-picture');

        const deletePokemonName = document.getElementById('deletePokemonName');
        const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');

        editModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;

            editNumber.value = button.getAttribute('data-number');
            editName.value = button.getAttribute('data-name');
            editType1.value = button.getAttribute('data-type1');
            editType2.value = button.getAttribute('data-type2');
            editAbility.value = button.getAttribute('data-ability');
            editPicture.value = button.getAttribute('data-picture');
        });

        deleteModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;

            const number = button.getAttribute('data-number');
            const name = button.getAttribute('data-name');

            deletePokemonName.textContent = name;
            confirmDeleteBtn.href = 'delete_pokemon.php?number=' + encodeURIComponent(number);
        });
    });
</script>

</body>
</html>