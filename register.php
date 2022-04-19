<?php
require "includes/recaptchaValid.php";

if (
isset($_POST['email']) &&
isset($_POST['password']) &&
isset($_POST['confirm-password']) &&
isset($_POST['pseudo']) &&
isset($_POST['g-recaptcha-response'])
) {
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Email pas bon !';
    }
    if (!preg_match('/^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])(?=.*[ !"#\$%&\'()*+,\-.\/:;<=>?@[\\\\\]\^_`{\|}~]).{8,4096}$/u', $_POST['password'])) {
        $errors[] = 'Votre mot de passe doit contenir au moins 1 min, 1 maj, un chiffre, un caractère spécial et doit avoir au minimum 8 caractères.';
    }
    if ($_POST['password'] != $_POST['confirm-password']) {
        $errors[] = 'La confirmation ne correspond pas au mot de passe !';
    }
    if (!preg_match('/^.{1,50}$/iu', $_POST['pseudo'])) {
        $errors[] = 'Le pseudonyme doit être compris entre 1 et 50 caractères ';
    }
    if (!recaptchaValid($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR'])) {
        $errors[] = 'Captcha invalide !';
    }
    if (!isset($errors)) {

        require 'includes/tryCatch.php';

        $insertNewUser = $db->prepare("INSERT INTO users(email, password, pseudo, register_date) VALUES(?, ?, ?, ?)");

        $querySuccess = $insertNewUser->execute([
            $_POST['email'],
            password_hash("$_POST[password]", PASSWORD_BCRYPT),
            $_POST['pseudo'],
            date("Y-m-d H:i:s")
        ]);

        $insertNewUser->closeCursor();

        if ($querySuccess) {

            $successMsg = 'Le compte a bien été crée !';
        }
        else {

            $errors[] = 'Problème, veuillez ré-essayer';
        }

    }
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <script src="https://www.google.com/recaptcha/api.js"></script>
    <?php
require "includes/head.html";
?>
    <title>Inscription</title>
</head>
<body>
    <?php
require "includes/nav.html";
?>





<div class="col-12 col-md-8 offset-md-2 py-5">
                <h1 class="pb-4 text-center">Créer un compte sur Wikifruit</h1>

                <div class="col-12 col-md-6 mx-auto">
                <?php
// Affichage des erreurs s'il y en a
if (isset($errors)) {
    foreach ($errors as $error) {
        echo '<p class="alert alert-danger">' . $error . '</p>';
    }
}
// Affichage du message de succès s'il existe, sinon on affiche le formulaire
if (isset($successMsg)) {
    echo '<p class="alert alert-success">' . $successMsg . '</p>';
}
else {
?>
                        <form action="register.php" method="POST" data-form-type="register">

                            <div class="mb-3">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input id="email" type="text" name="email" class="form-control"  data-form-type="email">
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Mot de passe <span class="text-danger">*</span></label>
                                <input id="password" type="password" name="password" class="form-control"  data-form-type="password,new">
                            </div>
                            <div class="mb-3">
                                <label for="confirm-password" class="form-label">Confirmation mot de passe <span class="text-danger">*</span></label>
                                <input id="confirm-password" type="password" name="confirm-password" class="form-control" data-form-type="password,confirmation">
                            </div>
                            <div class="mb-3">
                                <label for="pseudo" class="form-label">Pseudonyme <span class="text-danger">*</span></label>
                                <input id="pseudo" type="text" name="pseudo" class="form-control"  data-form-type="other">
                            </div>
                            <div class="mb-3">
                                <p class="mb-2">Captcha <span class="text-danger">*</span></p>
                                <div class="g-recaptcha" data-sitekey="6LcRYHcfAAAAADq41o5IVcwHE7IpKsf8lVR_BMc3"></div>
                            </div>
                            <div>
                                <input value="Créer mon compte" type="submit" class="btn btn-success col-12" data-form-type="action,register">
                            </div>

                            <p class="text-danger mt-4">* Champs obligatoires</p>

                        </form>
                                        </div>

            </div>
<?php
}?>
</body>
</html>





