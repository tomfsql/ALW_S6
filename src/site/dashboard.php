<?php

require_once "Utils/GameConfigRepository.php";
require_once "Data/Config/game_config_extended.json";
require_once "Data/saves/bean.json";

session_start();
$error = null;

$gameRepo = new GameConfigRepository("Data/gameconfig.json");
$saveRepo = new SaveRepository("Data/Saves/", "Data/initialSave.json");
$configContent = file_get_contents("Data/Config/game_config_extended.json");
$userContent = file_get_contents("Data/saves/bean.json");


// exemples d'utilisation :
// $user = $repo->get($login);
// $users = $repo->getAll();

// var_dump($_SERVER);
// var_dump($_SERVER['REQUEST_METHOD']);



?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Maquette Ferme Manager</title>
    <style>
        /* Styles indicatifs (non imposés) */
        article {
            border: 1px solid #ccc;
            padding: 10px;
            margin: 5px;
            display: inline-block;
            width: 200px;
            vertical-align: top;
        }

        .icon {
            font-size: 2em;
        }
    </style>

    <!-- Intégration du JS (Partie 2.1) -->
    <!-- <script src="Public/JS/FermeEngine.js" defer></script> -->
    <!-- <script src="Public/JS/main.js" defer></script> -->
</head>

<body>
    <h1>Ferme Manager</h1>

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


            $products = $gameRepo->getBuildings();
            foreach ($products as $productName => $product) {
                echo "<article id='product-$productName'>";
                echo "<h3>{$product->icon} {$product->name}</h3>";
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
</body>

</html>