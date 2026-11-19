<?php
session_start();

require __DIR__ . '/../../utils/autoloader.php';
require_once __DIR__ . '/../../utils/langue.php';
require_once __DIR__ . '/../../utils/roles.php';

use Quiz\QuizManager;

$title = $textes['onglet_admin_quiz'];
$racine = '../';
$page = 'admin';

// Page réservée aux admins
exigerAdmin($racine);

$erreur = null;
$succes = null;
$quizListe = [];

// Message après un ajout ou une modification (envoyé par quiz-formulaire.php)
if (isset($_GET['succes']) && $_GET['succes'] === 'ajoute') {
    $succes = $textes['admin_quiz_ajoute'];
}

if (isset($_GET['succes']) && $_GET['succes'] === 'modifie') {
    $succes = $textes['admin_quiz_modifie'];
}

try {
    $quizManager = new QuizManager();

    // Le formulaire « Supprimer » a été envoyé
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_supprimer'])) {
        $idSupprimer = (int) $_POST['id_supprimer'];
        $quizManager->removeQuiz($idSupprimer);
        $succes = $textes['admin_quiz_supprime'];
    }

    // Liste des quiz (après la suppression éventuelle)
    $quizListe = $quizManager->getQuiz();
} catch (Exception $e) {
    $erreur = $textes['erreur_generale'];
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
                <span class="titre"><?= htmlspecialchars($textes['admin_quiz_titre']) ?></span>
                <span class="win-btns" aria-hidden="true"><span class="win-btn">–</span><span class="win-btn">□</span><span class="win-btn close">✕</span></span>
            </div>

            <div class="fenetre-corps">
                <p>
                    <a href="index.php" class="gel-btn ghost sm"><?= htmlspecialchars($textes['admin_titre']) ?></a>
                    <a href="quiz-formulaire.php" class="gel-btn green"><?= htmlspecialchars($textes['admin_ajouter']) ?></a>
                </p>

                <?php if ($erreur !== null) { ?>
                    <p class="message erreur"><?= htmlspecialchars($erreur) ?></p>
                <?php } ?>

                <?php if ($succes !== null) { ?>
                    <p class="message succes"><?= htmlspecialchars($succes) ?></p>
                <?php } ?>

                <table class="tableau-admin">
                    <thead>
                        <tr>
                            <th><?= htmlspecialchars($textes['admin_col_titre']) ?></th>
                            <th><?= htmlspecialchars($textes['admin_col_description']) ?></th>
                            <th><?= htmlspecialchars($textes['admin_col_actions']) ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($quizListe as $quiz) { ?>
                            <tr>
                                <td><?= htmlspecialchars($quiz->getTitre()) ?></td>
                                <td><?= htmlspecialchars($quiz->getDescription() ?? '') ?></td>
                                <td>
                                    <div class="actions-admin">
                                        <a href="quiz-formulaire.php?id=<?= htmlspecialchars($quiz->getId()) ?>" class="gel-btn sm ghost"><?= htmlspecialchars($textes['admin_modifier']) ?></a>
                                        <a href="aesthetics.php?quiz=<?= htmlspecialchars($quiz->getId()) ?>" class="gel-btn sm"><?= htmlspecialchars($textes['admin_bouton_aesthetics']) ?></a>
                                        <a href="questions.php?quiz=<?= htmlspecialchars($quiz->getId()) ?>" class="gel-btn sm"><?= htmlspecialchars($textes['admin_bouton_questions']) ?></a>

                                        <form action="quiz.php" method="POST" onsubmit="return confirm('<?= htmlspecialchars($textes['admin_confirmer_suppression_quiz']) ?>');">
                                            <input type="hidden" name="id_supprimer" value="<?= htmlspecialchars($quiz->getId()) ?>">
                                            <button type="submit" class="gel-btn sm red"><?= htmlspecialchars($textes['admin_supprimer']) ?></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <?php require __DIR__ . '/../../components/footer.php'; ?>
</body>

</html>
