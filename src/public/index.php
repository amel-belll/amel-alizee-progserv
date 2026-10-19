<?php
require __DIR__ . '/../utils/autoloader.php';

// Création d'une instance de Database (connexion à la base de données)
$database = new Database();

// Récupération de toutes les catégories
$sql = "SELECT * FROM categorie";

$stmt = $database->getPdo()->prepare($sql);

$stmt->execute();

$categories = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zoomy</title>
</head>

<body>
    <h1>Zoomy</h1>
    <nav>
        <a href="articles/">Articles</a>
    </nav>

    <p>Le site est en ligne et connecté à la base de données.</p>

    <h2>Catégories</h2>

    <ul>
        <?php foreach ($categories as $categorie) { ?>
            <li><?= htmlspecialchars($categorie['nom']) ?></li>
        <?php } ?>
    </ul>

    <footer>
        <p>Projet réalisé dans le cadre du cours ProgServ2 à la HEIG-VD.</p>
    </footer>
</body>

</html>
