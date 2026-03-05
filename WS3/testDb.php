<?php
    require_once '../includes/mysql-functions.php';

    startConnection("pokemondb");

    $zoekWaarde = $_GET['zoek'] ?? '';

    if(!empty($zoekWaarde))
    {
        $sql = "SELECT * 
                FROM pokemon 
                WHERE name LIKE '%" . $zoekWaarde . "%' 
                ORDER BY name DESC
                LIMIT 50    
                OFFSET 0";

//        echo $sql;
    }
    else
    {
        $sql = "SELECT * FROM pokemon";
    }

    $result = executeSelect($sql);
?>
<html>
<head>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

    <form method="get">
        Zoek op naam:
        <input type="text" name="zoek">
        <input type="submit" value="Filter">
    </form>

    <table>
        <tr>
            <th>Nr</th>
            <th>Afbeelding</th>
            <th>Naam</th>
        </tr>
        <?php
        $teller = 1;
            foreach($result as $row)
            {
                echo "<tr>";
                    echo "<td>";
                    echo $teller;
                    echo "</td>";
                    echo "<td>";
                    echo "<img src='" . $row["picture"] . "' style='width: 30px;'>";
                    echo "</td>";
                    echo "<td>";
                    echo $row["name"];
                    echo "</td>";
                echo "</tr>";
                $teller++;
            }
        ?>
    </table>
</body>
</html>
