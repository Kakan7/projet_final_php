<?php

// echo basename($_SERVER['PHP_SELF']);

?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">Wikifruit</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria- controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                <li class="nav-item">
                    <a class="nav-link<?php if(preg_match('/^\/projet_php\/index.php$/m' , $_SERVER['PHP_SELF'])){echo ' active';} ?>" aria-current="page" href="index.php">Accueil</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link<?php if(preg_match('/^\/projet_php\/fruitlist.php$/m' , $_SERVER['PHP_SELF'])){echo ' active';} ?>" href="fruitlist.php">Liste des Fruits</a>
                </li>
                <?php
if (!isset($_SESSION['user'])) { ?>
                <li class="nav-item"></li>
                <a class="nav-link<?php if(preg_match('/^\/projet_php\/register.php$/m' , $_SERVER['PHP_SELF'])){echo ' active';} ?>" href="register.php">Inscription</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link<?php if(preg_match('/^\/projet_php\/login.php$/m' , $_SERVER['PHP_SELF'])){echo ' active';} ?>" href="login.php">Connexion</a>
                </li>
    <?php
    } else { ?>
                <li class="nav-item">
                    <a class="nav-link <?php if(preg_match('/^\/projet_php\/profil.php$/m' , $_SERVER['PHP_SELF'])){echo ' active';} ?>" href="profil.php">Profil</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link<?php if(preg_match('/^\/projet_php\/addFruit.php$/m' , $_SERVER['PHP_SELF'])){echo ' active';} ?>" href="addFruit.php">Ajouter un fruit</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link<?php if(preg_match('/^\/projet_php\/logout.php$/m' , $_SERVER['PHP_SELF'])){echo ' active';} ?>" href="logout.php">Deconnexion</a>
                </li>

<?php
}
?>

            </ul>
            <form class="d-flex" action="search.php" method="GET">
                <input class="form-control me-2" type="search" name="name" placeholder="Chercher un fruit" aria-label="Search">
                <button class="btn btn-outline-success" type="submit" name="q"><i class="fa fa-search"
                        aria-hidden="true"></i></button>
            </form>
        </div>
    </div>
</nav>