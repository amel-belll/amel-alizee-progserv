<?php

// Démarre la session (doit être tout en haut, avant tout affichage)

session_start();

require __DIR__ . '/../../utils/autoloader.php';

require_once __DIR__ . '/../../utils/langue.php';

use Utilisateurs\UtilisateursManager;

$title = $textes['onglet_connexion'];

$racine = '../';

$page = 'connexion';

// Par défaut : aucune erreur, champ e-mail vide

$erreurs = [];

$email = '';

// On traite le formulaire seulement s'il a été envoyé

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email']);

    $motDePasse = $_POST['mot_de_passe'];

    try {

        $utilisateursManager = new UtilisateursManager();

        // Validation côté serveur : lance une exception si une donnée est invalide

        $utilisateursManager->validerConnexion($email, $motDePasse);

        // On cherche le compte avec cet e-mail

        $utilisateur = $utilisateursManager->getUtilisateurParEmail($email);

        // Le compte n'existe pas ou le mot de passe est faux

        if ($utilisateur === null || !password_verify($motDePasse, $utilisateur->getMotDePasse())) {

            throw new Exception('erreur_connexion');
        }

        // Connexion réussie : on garde les infos dans la session

        $_SESSION['utilisateur_id'] = $utilisateur->getId();

        $_SESSION['pseudo'] = $utilisateur->getPseudo();

        $_SESSION['role'] = $utilisateur->getRole();

        // Redirection vers l'accueil

        header('Location: ../index.php');

        exit();
    } catch (Exception $e) {

        // Le message de l'exception est le nom d'une clé de traduction

        $cle = $e->getMessage();

        if (isset($textes[$cle])) {

            $erreurs[] = $textes[$cle];
        } else {

            $erreurs[] = $textes['erreur_generale'];
        }
    }
}


?>

<!DOCTYPE html>
<html lang="<?= htmlspecialchars($langue) ?>">

<?php require __DIR__ . '/../../components/head.php'; ?>

<body>
    <?php require __DIR__ . '/../../components/header.php'; ?>

    <main>
        <section class="aero-window">
            <div class="aero-titlebar">
                <span class="titre"><?= htmlspecialchars($textes['connexion_titre']) ?></span>
                <span class="win-btns" aria-hidden="true"><span class="win-btn">–</span><span class="win-btn">□</span><span class="win-btn close">✕</span></span>
            </div>

            <div class="fenetre-corps">
                <h1><?= htmlspecialchars($textes['connexion_h1']) ?></h1>

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
                    <label for="email"><?= htmlspecialchars($textes['champ_email']) ?></label>
                    <input type="email" id="email" name="email" class="aero-input" value="<?= htmlspecialchars($email) ?>" required>

                    <label for="mot_de_passe"><?= htmlspecialchars($textes['champ_mot_de_passe']) ?></label>
                    <input type="password" id="mot_de_passe" name="mot_de_passe" class="aero-input" required>

                    <button type="submit" class="gel-btn"><?= htmlspecialchars($textes['connexion_bouton']) ?></button>
                </form>

                <p><?= htmlspecialchars($textes['connexion_pas_compte']) ?> <a href="inscription.php"><?= htmlspecialchars($textes['connexion_lien_inscription']) ?></a></p>
            </div>
        </section>
    </main>

    <?php require __DIR__ . '/../../components/footer.php'; ?>
</body>

</html>
