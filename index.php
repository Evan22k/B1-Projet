<?php
require_once 'bdd.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: connexion.php');
    exit;
}

$stmt = $pdo->prepare("SELECT identifiant FROM utilisateur WHERE idUtilisateur = ?");
$stmt->execute([$_SESSION['user_id']]);
$utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$utilisateur) {
    // utilisateur supprimé ou problème → on déconnecte
    header('Location: logout.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Accueil</title>

</head>

<body>
    <div class="container">
        <h1>Bienvenue, <?= ($utilisateur['identifiant']) ?> !</h1>
        <div class="info">
            <p><strong>Nom d'utilisateur :</strong> <?= ($utilisateur['identifiant']) ?></p>
        </div>
        <a href="logout.php" class="btn-logout">Se déconnecter</a>
    </div>
</body>

<style>
    body {
        font-family: Arial, sans-serif;
        background: #e53935;
        padding: 20px;
    }

    .container {
        max-width: 600px;
        margin: 0 auto;
        background: #e53935;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    h1 {
        margin-bottom: 15px;
    }

    .info {
        background: #e53935;
        padding: 15px;
        border-radius: 6px;
    }

    .btn-logout {
        display: inline-block;
        margin-top: 15px;
        padding: 8px 15px;
        background: #e53935;
        color: #fff;
        text-decoration: none;
        border-radius: 4px;
    }
</style>

</html>