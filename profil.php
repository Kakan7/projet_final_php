<?php
session_start();
if (!isset($_SESSION['user'])) {

    header('Location: index.php');
    die();

}else{

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <script src="https://www.google.com/recaptcha/api.js"></script>
    <?php
require "includes/head.php";
?>
    <title>Profil</title>
</head>
<body>
    <?php
require "includes/nav.php";

?>

<div class="container-fluid">
        <div class="row">
            <div class="col-12 col-md-8 offset-md-2 py-5">
                <h1 class="pb-4 text-center">Mon Profil</h1>
                <div class="row">
                    <div class="col-md-6 col-12 offset-md-3 my-4">
                        <ul class="list-group">
                            <li class="list-group-item"><strong>Email</strong> : <?php echo htmlspecialchars($_SESSION['user']['email']) ;?></li>
                            <li class="list-group-item"><strong>Pseudo</strong> : <?php echo htmlspecialchars($_SESSION['user']['pseudo']) ;?></li>
                            <li class="list-group-item"><strong>Date d'inscription</strong> :<?php echo htmlspecialchars($_SESSION['user']['register_date']) ;?></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
?>


<?php
}

?>
</body>
</html>
