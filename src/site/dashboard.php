<?php

require_once "App/Repositories/GameConfigRepository.php";


if (isset($_SESSION['username']) && session_status() == PHP_SESSION_ACTIVE){
    $error = null;
    $gameRepo = new GameConfigRepository(__DIR__ ."/Data/Config/game_config.json");
    $saveRepo = new SaveRepository("Data/Saves/", "Data/initialSave.json");
    $configContent = file_get_contents("Data/Config/game_config_extended.json");
    $userContent = file_get_contents(__DIR__ ."/Data/Saves/bean.json");
    $decoded_content = json_decode($userContent);
    $success = "Vous êtes déjà connecté en tant que " . htmlspecialchars($_SESSION['username']) . " !";
}
else{
    $error = "Contenu inaccessible";
    $_SESSION = [];
    if(session_status() === PHP_SESSION_ACTIVE){
        session_destroy();
    }
    header("Location:index.php?page=login");
    exit;
}



// exemples d'utilisation :
// $user = $repo->get($login);
// $users = $repo->getAll();

// var_dump($_SERVER);
// var_dump($_SERVER['REQUEST_METHOD']);



?>
<!DOCTYPE html>
<html lang="fr">
<link rel="stylesheet" href="/ALW/alw-projet/src/site/Public/style.css">
<head>
    <meta charset="UTF-8">
    <title>Maquette Ferme Manager</title>

    <!-- Intégration du JS (Partie 2.1) -->
    <!-- <script src="Public/JS/FermeEngine.js" defer></script> -->
    <!-- <script src="Public/JS/main.js" defer></script> -->
</head>

<body>
    <?php if($error == null ) { ?>
        <h1>Ferme Manager</h1>
        <div style="text-align: right; margin-bottom: 20px;">
            <a href="index.php?page=logout" class="logout-btn" style="color: red; text-decoration: none; font-weight: bold;">
                Se déconnecter
            </a>
        </div>


        <p style="color: #4AF626; font-weight: bold;"><?= $success ?></p>


        <section id="inventory">
            <h2>Inventaire</h2>
            <?php
                $products = $gameRepo->getProducts();
                foreach ($products as $productName => $product) {
                    echo "<article id='product-$productName'>";
                    echo "<h3>{$product->icon} {$product->name}</h3>";
                    echo "<div>Stock : <output class='stock'>{$product->quantity}</output></div>";
                    echo "</article>";
                }
            ?>

            <article id="product-ble">
                <h3>🌾 Blé</h3>
                <div>Stock : <output class="stock">0</output></div>
            </article>
        </section>

        <hr>

        <section id="buildings">
            <h2>Bâtiments</h2>

            <?php


                $buildings = $gameRepo->getBuildings();
                foreach ($buildings as $buildingName => $building) {
                    echo "<article id='product-$buildingName'>";
                    echo "<h3>{$building->icon} {$building->name}</h3>";
                    echo "<div>Stock : <output class='stock'>0</output></div>";
                    echo "</article>";
                }
            ?>

            <article id="building-champ_ble">
                <h3>Champ de blé (Niv. <output class="level">1</output>)</h3>

                <button class="harvest">Récolter</button>

                <button class="upgrade">
                    Améliorer <br>
                    Coût : <output class="cost">10 🌾</output>
                </button>
            </article>
        </section>
        <?php } else { ?>
            <div class="error"><?php echo $error; ?></div>
            <p><a href="login.php">Se connecter</a></p>
        <?php } ?>
    </body>

    </html>