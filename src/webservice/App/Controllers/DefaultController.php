<?php

namespace App\Controllers;

use CPE\Framework\AbstractController;

$loader = new \Twig\Loader\FilesystemLoader(dirname(__DIR__) .'/Templates');
$twig = new \Twig\Environment($loader);

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

        $loader = new \Twig\Loader\FilesystemLoader(dirname(__DIR__) .'/Templates');
        $twig = new \Twig\Environment($loader);

        $lastUsername = $_POST['username'] ?? '';
        echo $twig->render('login.html.twig', ['error' => null,
            'username' => $lastUsername]);
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

        $loader = new \Twig\Loader\FilesystemLoader(dirname(__DIR__) .'/Templates');
        $twig = new \Twig\Environment($loader);
        echo $twig->render('dashboard.html.twig', ['error' => null, // Ou votre variable d'erreur si elle existe
            'products' => $products,
            'buildings' => $buildings]);
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
