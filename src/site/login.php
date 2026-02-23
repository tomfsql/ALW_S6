<?php
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

            $saveRepo->load($username);
        } else {
            $error = "Identifiant ou mot de passe incorrect";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <style>
        /*
            Palette :
            main blue #0bbbef
            dark blue #0086b7
            light blue #c7f8ff
            brown #783300
        */
        :root {
            color-scheme: dark;
            background-color: #333;
            font-family: sans;
        }
        a, a:visited {
            color: #783300;
        }
        code, pre {
            color: #4AF626;
            background-color: black;
            font-weight: bold;
            padding: .1em .3em;
            border-radius: .3em;
        }
        .loginForm {
            background-color: black;
            width: 25em;
            border: .1em solid #0086b7;
            border-radius: .5em;
            margin: 2em auto;
            padding: 1em;
        }
        h1, h2, h3, h4, h5, h6 {
            color: #0bbbef;
        }
        form h2 {
            margin: 0 0 .5em;
        }
        form button {
            margin: .5em 0 0;
        }
        input {
            display: block;
            width: 100%;
            box-sizing: border-box;
            margin: .5em 0;
            font-size: 1em;
        }
        button {
            font-size: 1em;
        }
        .error {
            border-radius: .2em;
            background-color: darkred;
            padding: 0 .5em;
            font-style: italic;
        }
        .error::before {
            content: "⚠ ";
            font-weight: bold;
        }
    </style>
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