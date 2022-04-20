<?php
session_start();

// Connexion à la BDD
require 'includes/tryCatch.php';
if(isset($_GET['name'])){

    $getFruits = $db->prepare(" SELECT * FROM fruits INNER JOIN users ON users.id = fruits.user_id WHERE fruits.name LIKE ? ");

    $getFruits->execute([
        '%'.$_GET['name'].'%',
    ]);

} else {

    $getFruits = $db->query("SELECT * FROM fruits");
}

$fruits = $getFruits->fetchAll(PDO::FETCH_ASSOC);

$getFruits->closeCursor();


// echo '<pre>';
// print_r($getFruits);
// print_r($fruits);
// echo '</pre>';
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

    <?php require 'includes/nav.php';


foreach ($fruits as $fruit) {
?>
    <div class="container-fluid">

    <div class="row">

        <div class="col-12 col-md-8 offset-md-2 py-5">
            <h1 class="pb-4 text-center">Liste des Fruits</h1>

            <div class="d-flex justify-content-center flex-wrap">
                        <div class="card fruit-card">
                                <?php    echo '<img src="images/' . htmlspecialchars($fruit['picture_name']) . '" alt="">';?>
                                <h2 class="card-title h5 text-center"><?php    echo htmlspecialchars($fruit['name']);?></h2>
                                <p class="text-info text-center my-0">Pays d'origine : <?php    echo htmlspecialchars($fruit['origin']);?></p>
                                <p class="text-primary text-center">Posté par : <?php    echo htmlspecialchars($fruit['pseudo']);?></p>
                                <hr>
                                <p class="card-text text-center"><?php    echo htmlspecialchars($fruit['description']);?></p>
                            </div>
                        </div>
                        </div>
        </div>

    </div>

</div>
<?php
}


?>




</body>
</html>



