<?php
require __DIR__ . '/../utils/autoloader.php';

$title = 'Accueil | Zoomy';
$racine = './';

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

<?php require __DIR__ . '/../components/head.php'; ?>

<body>
    <?php require __DIR__ . '/../components/header.php'; ?>

    <h1>Zoomy</h1>

    <p>Le site est en ligne et connecté à la base de données.</p>

    <h2>Catégories</h2>

    <ul>
        <?php foreach ($categories as $categorie) { ?>
            <li><?= htmlspecialchars($categorie['nom']) ?></li>
        <?php } ?>
    </ul>

    <?php require __DIR__ . '/../components/footer.php'; ?>
</body>

</html>
