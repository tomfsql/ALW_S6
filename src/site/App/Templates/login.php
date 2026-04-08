<?php
// http://localhost:50180/login.php
require_once "App/Utilities/FileStorage.php";
require_once "App/Repositories/UserRepository.php";
require_once "App/Repositories/SaveRepository.php";

use App\Repositories\UserRepository;
use App\Repositories\SaveRepository;

$error = null;
$success = null;

// Initialisation des dépôts
$userRepo = new UserRepository("Data/users.json");
$saveRepo = new SaveRepository("Data/Saves/", "Data/initialSave.json");

if (isset($_SESSION['username'])) {
    $success = "Vous êtes déjà connecté en tant que " . htmlspecialchars($_SESSION['username']) . " !";
}
else if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $error = "Veuillez remplir tous les champs.";
    } else {
        $user = $userRepo->get($username);

        if ($user && password_verify($password, $user->password_hash)) {
            $success = "Connexion réussie !";
            $_SESSION['username'] = $username;
            $saveRepo->load($username);
            header("Location: /dashboard");
        } else {
            $error = "Identifiant ou mot de passe incorrect";
            http_response_code(403);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<link rel="stylesheet" href="/ALW/alw-projet/src/site/Public/style.css">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
</head>
<?php if ($success !== null): ?>
        <div class="loginForm" style="text-align: center;">
            <p style="color: #4AF626; font-weight: bold;"><?= $success ?></p>
            <p><a href="index.php?page=dashboard">Tableau de bord</a></p>
        </div>

    <?php else: ?>
        <form action="index.php?page=login" method="POST" class="loginForm">
            <h2>Connexion</h2>

            <input type="text" name="username" placeholder="Nom d'utilisateur" required autocomplete="off" 
                   value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">

            <input type="password" name="password" placeholder="Mot de passe" required autocomplete="off">

            <?php if ($error !== null): ?>
                <div class="error"><?= $error ?></div>
            <?php endif; ?>

            <button type="submit">Se connecter</button>
        </form>
    <?php endif; ?>
</body>
</html>