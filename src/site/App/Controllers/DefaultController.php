<?php

namespace App\Controllers;

require_once __DIR__ ."/../Templates/login.php";
require_once __DIR__ ."/../Templates/dashboard.php";

use CPE\Framework\AbstractController;

class DefaultController extends AbstractController
{
    public function index()
    {
        $data = "Bonjour le monde !";
        $this->app->view()->setParam('pageTitle', $data);
        $this->app->view()->render('homepage.tpl.php');
    }

    public function login()
    {
        $data = "Page de connexion !";
        $this->app->view()->setParam('pageTitle', $data);
        $this->app->view()->render('login.php');
    }

        public function dashboard()
    {
        if (!isset($_SESSION['username'])) {
            header("Location: /login");
            exit;
        }
        require_once dirname(__DIR__) . "/Repositories/GameConfigRepository.php";
        require_once dirname(__DIR__) . "/Repositories/SaveRepository.php";

        $gameConfigPath = dirname(__DIR__, 2) . "/Data/Config/game_config.json";
        $gameRepo = new \App\Repositories\GameConfigRepository($gameConfigPath);

        // Récupération des données
        $products = $gameRepo->getProducts();
        $buildings = $gameRepo->getBuildings();
        $success = "Vous êtes déjà connecté en tant que " . htmlspecialchars($_SESSION['username']) . " !";

        // 3. Envoi des données à la vue
        $this->app->view()->setParam('pageTitle', "Tableau de bord !");
        $this->app->view()->setParam('successMessage', $success);
        $this->app->view()->setParam('products', $products);
        $this->app->view()->setParam('buildings', $buildings);
        $data = "Tableau de bord !";
        $this->app->view()->setParam('pageTitle', $data);
        $this->app->view()->render('dashboard.php');
    }

    public function test()
    {
        echo '<p>Cette page a reçu un paramètre nommé "nombre" et valant "' . $this->parameters['nombre'] . '"</p>
              <p>Contenu complet de <code>$this->parameters</code>:</p>
              <pre>';
        print_r($this->parameters);
    }

    public function error404()
    {
        http_response_code(404);
        $this->app->view()->render('404.tpl.php');
    }
}
