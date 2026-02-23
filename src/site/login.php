<?php
// http://localhost:50180/login.php
session_start();
require_once "Utils/User.php";
require_once "Utils/FileStorage.php";
require_once "Utils/UserRepository.php";
require_once "Utils/SaveRepository.php";
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
            session_status(PHP_SESSION_ACTIVE);

            $saveRepo->load($username);
        } else {
            $error = "Identifiant ou mot de passe incorrect";
            http_response_code : 403;
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
            <p><a href="logout.php">Se déconnecter</a></p>
        </div>

    <?php else: ?>
        <form action="" method="POST" class="loginForm">
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