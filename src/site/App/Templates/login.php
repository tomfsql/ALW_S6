<!DOCTYPE html>
<html lang="fr">
<link rel="stylesheet" href="/ALW/alw-projet/src/site/Public/style.css">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
</head>
        <form action="/login" method="POST" class="loginForm">
            <h2>Connexion</h2>

            <input type="text" name="username" placeholder="Nom d'utilisateur" required autocomplete="off" 
                   value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">

            <input type="password" name="password" placeholder="Mot de passe" required autocomplete="off">

            <?php if (!empty($this->parameters['errors'])): ?>
                <div class="error"><?= $error ?></div>
            <?php endif; ?>

            <button type="submit">Se connecter</button>
        </form>
</body>
</html>