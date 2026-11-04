<?php
require __DIR__ . '/../../utils/autoloader.php';

$title = 'Inscription | Zoomy';
$racine = '../';
$page = 'inscription';
?>

<!DOCTYPE html>
<html lang="fr">

<?php require __DIR__ . '/../../components/head.php'; ?>

<body>
    <?php require __DIR__ . '/../../components/header.php'; ?>

    <main>
        <section class="aero-window">
            <div class="aero-titlebar">
                <span class="titre">Inscription</span>
                <span class="win-btns" aria-hidden="true"><span class="win-btn">–</span><span class="win-btn">□</span><span class="win-btn close">✕</span></span>
            </div>

            <div class="fenetre-corps">
                <h1>Crée ton compte</h1>

                <form action="inscription.php" method="POST" class="formulaire">
                    <label for="pseudo">Pseudo</label>
                    <input type="text" id="pseudo" name="pseudo" class="aero-input" required minlength="3" maxlength="30">

                    <label for="email">E-mail</label>
                    <input type="email" id="email" name="email" class="aero-input" required>

                    <label for="mot_de_passe">Mot de passe</label>
                    <input type="password" id="mot_de_passe" name="mot_de_passe" class="aero-input" required minlength="8">

                    <button type="submit" class="gel-btn green">S'inscrire</button>
                </form>
            </div>
        </section>
    </main>

    <?php require __DIR__ . '/../../components/footer.php'; ?>
</body>

</html>
