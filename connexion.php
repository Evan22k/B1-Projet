<?php
require_once 'bdd.php';

$error = isset($_GET['error']) ? "Identifiant ou mot de passe incorrecte." : '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identifiant = trim($_POST['identifiant'] ?? '');
    $mdp = $_POST['mdp'] ?? '';

    if ($identifiant === '' || $mdp === '') {
        $error = "Veuillez remplir tous les champs.";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE identifiant = ?");
        $stmt->execute([$identifiant]);
        $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($utilisateur && $mdp === $utilisateur['mdp']) {
            header("Location: accueil.php?utilisateur={$utilisateur['idUtilisateur']}");
            exit;

            // --- Génération du code temporaire à chaque connexion ---
            // $tempCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            // $stmt = $pdo->prepare("UPDATE users SET temp_password = ?, temp_updated_at = NOW() WHERE id = ?");
            // $stmt->execute([$tempCode, $user['id']]);

            // Redirection vers la page OTP avec l'ID utilisateur  
        } else {
            header("Location: connexion.php?error=1");
            exit;
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

</head>

<body>
    <img src="GalacticosIT.png" alt="Logo" class="logo">

    <div class="container">
        <h1>Connexion</h1>

        <?php if ($error): ?>
            <div class="error"><?= ($error) ?></div>
        <?php endif; ?>

        <form method="post">
            <div class="form-group">
                <label>Identifiant</label>
                <input type="text" name="identifiant" required>
            </div>
            <div class="form-group">
                <label>Mot de passe</label>
                <input type="password" name="mdp" required>
            </div>
            <button type="submit" class="btn">Se connecter</button>
        </form>

        <!-- <div class="link">
            <a href="inscription.php">Pas de compte ? S'inscrire</a>
        </div> -->
    </div>
</body>

<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: Trebuchet MS, Verdana, sans-serif;
        background: radial-gradient(circle, #a166d9 0%, #5b1fae 100%);
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: flex-start;
        padding: 80px 20px 20px 20px;
        background-size: 600% 600%;
    }


    .container {
        background: white;
        padding: 30px;
        border-radius: 20px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        width: 100%;
        max-width: 400px;
        backdrop-filter: blur(10px);
    }

    .logo {
        display: block;
        margin: 0 auto 30px auto;
        width: 200px;
    }

    h1 {
        text-align: center;
        color: #a166d9;
        margin-bottom: 30px;
        font-size: 40px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    label {
        display: block;
        margin-bottom: 8px;
        color: #555;
        font-weight: 500;
    }

    input {
        width: 100%;
        padding: 12px;
        border: 2px solid #e1e5e9;
        border-radius: 12px;
        font-size: 16px;
        transition: all 0.3s ease;
        background: rgba(255, 255, 255, 0.8);
    }

    input:focus {
        outline: none;
        border-color: #a166d9;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        /* transform: translateY(-2px); */
    }

    .btn {
        font-family: Trebuchet MS, Verdana, sans-serif;
        width: 100%;
        padding: 12px;
        background: radial-gradient(circle, #a166d9 0%, #5b1fae 100%);
        color: white;
        border: none;
        border-radius: 12px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
    }

    .error {
        background: red;
        color: white;
        padding: 12px;
        border-radius: 12px;
        margin-bottom: 20px;
        text-align: center;
    }

    .link {
        text-align: center;
        margin-top: 20px;
    }

    .link a {
        color: #a166d9;
        text-decoration: none;
        font-weight: 500;
        transition: color 0.2s ease;
    }

    .link a:hover {
        color: #a166d9;
        text-decoration: underline;
    }
</style>

</html>