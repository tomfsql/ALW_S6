<?php

namespace App\Controllers;

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
        if (isset($_SESSION['username'])) {
            header("Location: /dashboard");
            exit;
        }

        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once dirname(__DIR__) . "/Repositories/UserRepository.php";
            require_once dirname(__DIR__) . "/Repositories/SaveRepository.php";

            $userRepo = new \App\Repositories\UserRepository(dirname(__DIR__, 2) . "/Data/users.json");
            $saveRepo = new \App\Repositories\SaveRepository(dirname(__DIR__, 2) . "/Data/Saves/", dirname(__DIR__, 2) . "/Data/initialSave.json");

            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            if (empty($username) || empty($password)) {
                $error = "Veuillez remplir tous les champs.";
            } else {
                $user = $userRepo->get($username);

                if ($user && password_verify($password, $user->password_hash)) {
                    $_SESSION['username'] = $username;
                    $saveRepo->load($username);

                    header("Location: /dashboard");
                    exit;
                } else {
                    $error = "Identifiant ou mot de passe incorrect";
                    http_response_code(403);
                }
            }
        }

        $this->app->view()->setParam('pageTitle', "Page de connexion !");
        $this->app->view()->setParam('error', $error);
        $this->app->view()->render('login.php');
    }

    public function dashboard()
    {
        if (!isset($_SESSION['username'])) {
            header("Location: /login");
            exit;
        }

        require_once dirname(__DIR__) . "/Repositories/GameConfigRepository.php";

        $gameConfigPath = dirname(__DIR__, 2) . "/Data/Config/game_config.json";
        $gameRepo = new \App\Repositories\GameConfigRepository($gameConfigPath);

        $products = $gameRepo->getProducts();
        $buildings = $gameRepo->getBuildings();
        $success = "Vous êtes déjà connecté en tant que " . htmlspecialchars($_SESSION['username']) . " !";

        $this->app->view()->setParam('pageTitle', "Tableau de bord !");
        $this->app->view()->setParam('successMessage', $success);
        $this->app->view()->setParam('products', $products);
        $this->app->view()->setParam('buildings', $buildings);

        $this->app->view()->render('dashboard.php');
    }

    public function test()
    {
        echo '<p>Cette page a reçu un paramètre nommé "nombre" et valant "' . $this->parameters['nombre'] . '"</p>
              <p>Contenu complet de <code>$this->parameters</code>:</p>
              <pre>';
        print_r($this->parameters);
    }

    public function logout()
     {
        if(session_status() === PHP_SESSION_NONE){
            session_start();
        }
        $_SESSION = [];

        if(session_status() === PHP_SESSION_ACTIVE){
            session_destroy();
        }
        header("Location: /login");
        exit;
     }

    public function error404()
    {
        http_response_code(404);
        $this->app->view()->render('404.tpl.php');
    }
}
