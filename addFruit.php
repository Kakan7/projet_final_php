<?php
session_start();
// print_r($_POST);
// print_r($_FILES);
// print_r($_SESSION);
$maxFileSize = 5242880;

$allowedMIMETypes = [
    'png' => 'image/png',
    'jpg' => 'image/jpeg',
];

if (
isset($_POST['name']) &&
isset($_POST['origin']) &&
isset($_FILES['picture']) &&
isset($_POST['description'])
) {
    if (!preg_match('/^.{1,50}$/iu', $_POST['name'])) {
        $errors[] = 'Le nom doit être compris entre 1 et 50 caractères ';
    }
    if (!preg_match('/^fr|de|es|jp$/iu', $_POST['origin'])) {
        $errors[] = 'Choix invalide';
    }
    if (!isset($errors)) {
        if ($_FILES['picture']['size'] > 0) {

            $fileErrorCode = $_FILES['picture']['error'];

            if ($fileErrorCode == 1 || $fileErrorCode == 2 || $_FILES['picture']['size'] > $maxFileSize) {
                $errors[] = 'Le fichier est trop volumineux.';

            }
            elseif ($fileErrorCode == 3) {
                $errors[] = 'Le fichier n\'a pas été chargé correctement, veuillez ré-essayer.';

            }
            elseif ($fileErrorCode == 4) {
                $errors[] = 'Merci d\'envoyer un fichier !';

            }
            elseif ($fileErrorCode == 6 || $fileErrorCode == 7 || $fileErrorCode == 8) {
                $errors[] = 'Problème serveur, veuillez ré-essayer plus tard.';

            }
            elseif ($fileErrorCode == 0) {


                $fileMIMEType = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $_FILES['picture']['tmp_name']);

                if (!in_array($fileMIMEType, $allowedMIMETypes)) {
                    $errors[] = 'Seuls les fichiers jpg, png sont autorisés !';
                }

            }
            else {

                $errors[] = 'Problème inconnu';
            }

            if (!isset($errors)) {

                $ext = array_search($fileMIMEType, $allowedMIMETypes);

                do {
                    $newFileName = md5(random_bytes(50)) . '.' . $ext;
                } while (file_exists('images/' . $newFileName));

                move_uploaded_file($_FILES['picture']['tmp_name'], 'images/' . $newFileName);

                $successMsg = 'Merci ' . $_SESSION['user']['pseudo'] . ', votre image a bien été envoyée !';

            }
        }
        else {
            $newFileName = 'no-photo.png';
        }
        if (
        isset($_POST['description'])) {
            if (mb_strlen($_POST['description']) > 0) {
                if (!preg_match('/^.{5,20000}$/iu', $_POST['description'])) {
                    $errors[] = 'La description doit être comprise entre 5 et 20 000 caractères ';
                }
            }
        }
        else {
            $_POST['description'] = null;
        }


    }
    if (!isset($errors)) {

        require 'includes/tryCatch.php';

        $insertNewUser = $db->prepare("INSERT INTO fruits(name, origin, description, picture_name, user_id) VALUES(?, ?, ?, ?, ?)");

        $querySuccess = $insertNewUser->execute([
            $_POST['name'],
            $_POST['origin'],
            $_POST['description'],
            $newFileName,
            $_SESSION['user']['id'],

        ]);

        $insertNewUser->closeCursor();

        if ($querySuccess) {

            $successMsg = 'Le fruit a bien été crée !';
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
<?php
require "includes/head.php";
?>
    <title>Liste</title>
</head>
<body>

    <?php require 'includes/nav.php'; ?>

    <div class="container-fluid">

        <div class="row">

            <div class="col-12 col-md-8 offset-md-2 py-5">
                <?php
if (isset($errors)) {
    foreach ($errors as $error) {
        echo '<p class="alert alert-danger">' . $error . '</p>';
    }
}
if (isset($successMsg)) {
    echo '<p class="success">' . htmlspecialchars($successMsg) . '</p>';
}
else { ?>
                <h1 class="pb-4 text-center">Ajouter un fruit</h1>

                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nom <span class="text-danger">*</span></label>
                            <input placeholder="Banane" id="name" type="text" name="name" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="origin" class="form-label">Pays d'origine <span class="text-danger">*</span></label>
                            <select id="origin" name="origin" class="form-select" data-form-type="address,country">
                                <option selected="" disabled="">Sélectionner un pays</option>
                                <option value="fr">France</option><option value="de">Allemagne</option><option value="es">Espagne</option><option value="jp">Japon</option>                            </select>
                        </div>
                        <input type="hidden" name="MAX_FILE_SIZE" value="5242880">
                        <div class="mb-3">
                            <label for="picture" class="form-label">Photo</label>
                            <input type="file" class="form-control" id="picture" name="picture" accept="image/png, image/jpeg">
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description <span class="text-danger"></span></label>
                            <textarea class="form-control" name="description" id="description" cols="30" rows="10" placeholder="Description..."></textarea>
                        </div>
                        <div>
                            <input value="Créer le fruit" type="submit" class="btn btn-primary col-12" data-dashlane-rid="1e6f137a70f71902" data-form-type="action">
                        </div>

                        <p class="text-danger mt-4">* Champs obligatoires</p>

                    </form>
<?php
}?>
                                        </div>
            </div>

        </div>

    </div>
</body>
</html>



