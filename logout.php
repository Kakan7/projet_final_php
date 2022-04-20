<?php
session_start();

if(isset($_SESSION['user'])){

    unset($_SESSION['user']);

    $successMsg = 'Vous avez bien été déconnecté !';



}else{

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <?php
require "includes/head.php";
?>
    <title>Déconnexion</title>
</head>
<body>
    <?php require 'includes/nav.php';

    if(isset($successMessage)){
        echo '<p style="color:green;">' . $successMessage . '<p>';
    } else {
        echo '<p style="color:red;">Vous devez être connecté pour venir sur cette page !</p>';
    }

    ?>

</body>
</html>
<?php
}
?>