<?php

session_start();

require __DIR__ . '/../../utils/autoloader.php';

require_once __DIR__ . '/../../utils/langue.php';

use Utilisateurs\UtilisateursManager;

$title = $textes['onglet_inscription'];

$racine = '../';

$page = 'inscription';

// Par défaut : aucune erreur, pas de message de succès, champs vides

$erreurs = [];

$succes = null;

$pseudo = '';

$email = '';

// On traite le formulaire seulement s'il a été envoyé

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Récupération des données du formulaire (trim enlève les espaces au début et à la fin)

    $pseudo = trim($_POST['pseudo']);

    $email = trim($_POST['email']);

    $motDePasse = $_POST['mot_de_passe'];

    // Validation côté serveur

    if (strlen($pseudo) < 3 || strlen($pseudo) > 30) {

        $erreurs[] = $textes['erreur_pseudo_longueur'];
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $erreurs[] = $textes['erreur_email_invalide'];
    }

    if (strlen($motDePasse) < 8) {

        $erreurs[] = $textes['erreur_mot_de_passe_longueur'];
    }

    // Si les données sont correctes, on passe à la base de données

    if (empty($erreurs)) {

        try {

            $utilisateursManager = new UtilisateursManager();

            // Vérification que le pseudo et l'e-mail ne sont pas déjà utilisés

            if ($utilisateursManager->pseudoExiste($pseudo)) {

                $erreurs[] = $textes['erreur_pseudo_utilise'];
            }

            if ($utilisateursManager->emailExiste($email)) {

                $erreurs[] = $textes['erreur_email_utilise'];
            }

            // Tout est bon : on hache le mot de passe et on enregistre

            if (empty($erreurs)) {

                $motDePasseHache = password_hash($motDePasse, PASSWORD_DEFAULT);

                $utilisateursManager->addUtilisateur($pseudo, $email, $motDePasseHache);

                $succes = $textes['inscription_succes'];

                // On vide les champs du formulaire

                $pseudo = '';

                $email = '';
            }
        } catch (Exception $e) {

            // En cas de problème avec la base, on affiche un message simple (sans détails techniques)

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
                <span class="titre"><?= htmlspecialchars($textes['inscription_titre']) ?></span>
                <span class="win-btns" aria-hidden="true"><span class="win-btn">–</span><span class="win-btn">□</span><span class="win-btn close">✕</span></span>
            </div>

            <div class="fenetre-corps">
                <h1><?= htmlspecialchars($textes['inscription_h1']) ?></h1>

                <!-- Message de succès -->
                <?php if ($succes !== null) { ?>
                    <p class="message succes"><?= htmlspecialchars($succes) ?></p>
                <?php } ?>

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

                <form action="inscription.php" method="POST" class="formulaire">
                    <label for="pseudo"><?= htmlspecialchars($textes['champ_pseudo']) ?></label>
                    <input type="text" id="pseudo" name="pseudo" class="aero-input" value="<?= htmlspecialchars($pseudo) ?>" required minlength="3" maxlength="30">

                    <label for="email"><?= htmlspecialchars($textes['champ_email']) ?></label>
                    <input type="email" id="email" name="email" class="aero-input" value="<?= htmlspecialchars($email) ?>" required>

                    <label for="mot_de_passe"><?= htmlspecialchars($textes['champ_mot_de_passe']) ?></label>
                    <input type="password" id="mot_de_passe" name="mot_de_passe" class="aero-input" required minlength="8">

                    <button type="submit" class="gel-btn green"><?= htmlspecialchars($textes['inscription_bouton']) ?></button>
                </form>
            </div>
        </section>
    </main>

    <?php require __DIR__ . '/../../components/footer.php'; ?>
</body>

</html>
