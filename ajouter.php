<?php
require_once 'bdd.php';

$userId = $_GET['utilisateur'] ?? null;
$token  = $_GET['token'] ?? null;

verifierToken($userId, $token);

$stmt = $pdo->prepare("SELECT identifiant FROM utilisateur WHERE idUtilisateur = ?");
$stmt->execute([$userId]);
$utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$utilisateur) {
    header('Location: connexion.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nomPC               = trim($_POST['nomPC'] ?? '');
    $systemeExploitation = trim($_POST['systemeExploitation'] ?? '');
    $ram                 = trim($_POST['ram'] ?? '');
    $cpu                 = trim($_POST['cpu'] ?? '');
    $carteGraphique      = trim($_POST['carteGraphique'] ?? '');
    $carteMere           = trim($_POST['carteMere'] ?? '');

    if (!$nomPC || !$systemeExploitation || !$ram || !$cpu || !$carteGraphique || !$carteMere) {
        $error = "Tous les champs sont obligatoires.";
    } elseif (!is_numeric($ram) || (int)$ram <= 0) {
        $error = "La RAM doit être un nombre entier positif.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO pc (nomPC, systemeExploitation, ram, cpu, carteGraphique, carteMere) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$nomPC, $systemeExploitation, (int)$ram, $cpu, $carteGraphique, $carteMere]);
        header("Location: listePC.php?utilisateur=$userId&token=$token");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un PC</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: Trebuchet MS, Verdana, sans-serif;
            background: radial-gradient(circle, #a166d9 0%, #5b1fae 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 10px;
            position: relative;
        }

        .header-left img { height: 120px; margin-top: -25px; margin-left: -15px; }

        .header-center {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            text-align: center;
        }

        .header-center h1 { color: white; font-size: 26px; margin-top: 80px; }

        .header-right {
            display: flex;
            align-items: center;
            gap: 12px;
            color: white;
            font-size: 14px;
        }

        .btn-logout {
            font-family: Trebuchet MS, Verdana, sans-serif;
            padding: 6px 14px;
            background: rgba(255,255,255,0.2);
            color: white;
            border: 1px solid rgba(255,255,255,0.5);
            border-radius: 6px;
            font-size: 13px;
            text-decoration: none;
        }

        .btn-retour {
            font-family: Trebuchet MS, Verdana, sans-serif;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 16px;
            background: rgba(255,255,255,0.2);
            color: white;
            border: 1px solid rgba(255,255,255,0.5);
            border-radius: 8px;
            font-size: 13px;
            text-decoration: none;
            margin-bottom: 20px;
        }

        .btn-retour:hover { background: rgba(255,255,255,0.3); }

        .card {
            background: white;
            border-radius: 16px;
            padding: 36px;
            max-width: 600px;
            margin: 120px auto 0 auto;
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }

        .card h2 {
            font-size: 18px;
            color: #2d1a4a;
            margin-bottom: 24px;
            padding-bottom: 10px;
            border-bottom: 2px solid #ede9fe;
        }

        .form-group { margin-bottom: 16px; }

        label {
            display: block;
            margin-bottom: 6px;
            color: #555;
            font-weight: 600;
            font-size: 13px;
        }

        input, select {
            width: 100%;
            padding: 10px 12px;
            border: 2px solid #e1e5e9;
            border-radius: 10px;
            font-size: 14px;
            font-family: Trebuchet MS, Verdana, sans-serif;
            transition: border-color 0.2s;
        }

        input:focus, select:focus {
            outline: none;
            border-color: #a166d9;
            box-shadow: 0 0 0 3px rgba(161, 102, 217, 0.1);
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .btn-submit {
            font-family: Trebuchet MS, Verdana, sans-serif;
            width: 100%;
            padding: 12px;
            background: radial-gradient(circle, #a166d9 0%, #5b1fae 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 8px;
            transition: all 0.2s;
        }

        .btn-submit:hover {
            transform: translateY(-1px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }

        .error {
            background: #fee2e2;
            color: #b91c1c;
            padding: 12px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 13px;
        }
    </style>
</head>
<body>

<div class="header">
    <div class="header-left">
        <img src="GalacticosIT.png" alt="Logo">
    </div>
    <div class="header-center">
        <h1>Ajouter un PC</h1>
    </div>
    <div class="header-right">
        <span>Connecté : <strong><?= htmlspecialchars($utilisateur['identifiant']) ?></strong></span>
        <a href="logout.php" class="btn-logout">Déconnexion</a>
    </div>
</div>

<a href="listePC.php?utilisateur=<?= $userId ?>&token=<?= $token ?>" class="btn-retour">← Retour à la liste</a>

<div class="card">
    <h2>Nouveau PC</h2>

    <?php if ($error): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post">
        <div class="form-group">
            <label>Nom du PC</label>
            <input type="text" name="nomPC" value="<?= htmlspecialchars($_POST['nomPC'] ?? '') ?>" required>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Système d'exploitation</label>
                <select name="systemeExploitation">
                    <option value="Windows 10">Windows 10</option>
                    <option value="Windows 11">Windows 11</option>
                    <option value="Ubuntu">Ubuntu</option>
                    <option value="Debian">Debian</option>
                    <option value="macOS">macOS</option>
                    <option value="Autre">Autre</option>
                </select>
            </div>
            <div class="form-group">
                <label>RAM (Go)</label>
                <input type="number" name="ram" min="1" value="<?= htmlspecialchars($_POST['ram'] ?? '') ?>" required>
            </div>
        </div>

        <div class="form-group">
            <label>CPU</label>
            <input type="text" name="cpu" value="<?= htmlspecialchars($_POST['cpu'] ?? '') ?>" required>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Carte graphique</label>
                <input type="text" name="carteGraphique" value="<?= htmlspecialchars($_POST['carteGraphique'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label>Carte mère</label>
                <input type="text" name="carteMere" value="<?= htmlspecialchars($_POST['carteMere'] ?? '') ?>" required>
            </div>
        </div>

        <button type="submit" class="btn-submit">Ajouter le PC</button>
    </form>
</div>

</body>
</html>
