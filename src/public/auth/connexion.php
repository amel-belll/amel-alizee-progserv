<?php
// Démarre la session (doit être tout en haut, avant tout affichage)
session_start();

require __DIR__ . '/../../utils/autoloader.php';

use Utilisateurs\UtilisateursManager;

$title = 'Connexion | Zoomy';
$racine = '../';
$page = 'connexion';

// Par défaut : aucune erreur, champ e-mail vide
$erreurs = [];
$email = '';

// On traite le formulaire seulement s'il a été envoyé
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $motDePasse = $_POST['mot_de_passe'];

    // Validation côté serveur
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erreurs[] = "L'adresse e-mail n'est pas valide.";
    }

    if (empty($motDePasse)) {
        $erreurs[] = "Le mot de passe est obligatoire.";
    }

    if (empty($erreurs)) {
        try {
            $utilisateursManager = new UtilisateursManager();

            // On cherche le compte avec cet e-mail
            $utilisateur = $utilisateursManager->getUtilisateurParEmail($email);

            // On vérifie que le compte existe ET que le mot de passe est correct
            if ($utilisateur !== null && password_verify($motDePasse, $utilisateur->getMotDePasse())) {
                // Connexion réussie : on garde les infos dans la session
                $_SESSION['utilisateur_id'] = $utilisateur->getId();
                $_SESSION['pseudo'] = $utilisateur->getPseudo();
                $_SESSION['role'] = $utilisateur->getRole();

                // Redirection vers l'accueil
                header('Location: ../index.php');
                exit();
            } else {
                $erreurs[] = "E-mail ou mot de passe incorrect.";
            }
        } catch (Exception $e) {
            $erreurs[] = "Une erreur est survenue, merci de réessayer plus tard.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<?php require __DIR__ . '/../../components/head.php'; ?>

<body>
    <?php require __DIR__ . '/../../components/header.php'; ?>

    <main>
        <section class="aero-window">
            <div class="aero-titlebar">
                <span class="titre">Connexion</span>
                <span class="win-btns" aria-hidden="true"><span class="win-btn">–</span><span class="win-btn">□</span><span class="win-btn close">✕</span></span>
            </div>

            <div class="fenetre-corps">
                <h1>Connecte-toi</h1>

                <!-- Liste des erreurs -->
                <?php if (!empty($erreurs)) { ?>
                    <div class="message erreur">
                        <ul>
                            <?php foreach ($erreurs as $erreur) { ?>
                                <li><?= htmlspecialchars($erreur) ?></li>
                            <?php } ?>
                        </ul>
                    </div>
                <?php } ?>

                <form action="connexion.php" method="POST" class="formulaire">
                    <label for="email">E-mail</label>
                    <input type="email" id="email" name="email" class="aero-input" value="<?= htmlspecialchars($email) ?>" required>

                    <label for="mot_de_passe">Mot de passe</label>
                    <input type="password" id="mot_de_passe" name="mot_de_passe" class="aero-input" required>

                    <button type="submit" class="gel-btn">Se connecter</button>
                </form>

                <p>Pas encore de compte ? <a href="inscription.php">Inscris-toi</a></p>
            </div>
        </section>
    </main>

    <?php require __DIR__ . '/../../components/footer.php'; ?>
</body>

</html>
