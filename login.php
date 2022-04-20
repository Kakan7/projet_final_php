<?php
session_start();
if (isset($_SESSION['user'])) {

    header('Location: index.php');
    die();

}
require "includes/recaptchaValid.php";

if (
isset($_POST['email']) &&
isset($_POST['password'])
) {

    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Email pas bon !';
    }

    if (!preg_match('/^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])(?=.*[ !"#\$%&\'()*+,\-.\/:;<=>?@[\\\\\]\^_`{\|}~]).{8,4096}$/u', $_POST['password'])) {
        $errors[] = 'Votre mot de passe doit contenir au moins 1 min, 1 maj, un chiffre, un caractère spécial et doit avoir au minimum 8 caractères.';
    }

    if (!isset($errors)) {

        require 'includes/tryCatch.php';

        $findEmail = $db->prepare("SELECT * FROM users WHERE email =?");

        $querySuccess = $findEmail->execute([
            $_POST['email'],
        ]);
        $user = $findEmail->fetch();
        $findEmail->closeCursor();

        if (!empty($user)) {


            if (password_verify($_POST['password'], $user['password'])) {
                $successMsg = 'Connexion';

                $_SESSION['user'] = [
                    'pseudo' => $user['pseudo'],
                    'email' => $user['email'],
                    'register_date' => $user['register_date'],
                ];
            }
            else {
                $errors[] = 'Mot de passe incorrect';
            }

        }
        else {

            $errors[] = 'Email inexistant';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <script src="https://www.google.com/recaptcha/api.js"></script>
    <?php
require "includes/head.php";
?>
    <title>Connexion</title>
</head>
<body>
    <?php
require "includes/nav.php";
if (isset($successMsg)) {
    echo '<p class="alert alert-success">' . $successMsg . '</p>';
}
else {
?>

<div class="container-fluid">

<div class="row">

    <div class="col-12 col-md-8 offset-md-2 py-5">
        <h1 class="pb-4 text-center">Connexion</h1>

        <div class="col-12 col-md-6 offset-md-3">

    <?php if (isset($errors)) {
        foreach ($errors as $error) {
            echo '<p class="alert alert-danger">' . $error . '</p>';
        }
    }
?>
                <form action="login.php" method="POST">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input id="email" type="text" name="email" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Mot de passe</label>
                        <input id="password" type="password" name="password" class="form-control">
                    </div>
                    <div>
                        <input value="Connexion" type="submit" class="btn btn-primary col-12">
                    </div>
                </form>

        </div>

    </div>

</div>

</div>
<?php
}
?>
</body>
</html>
