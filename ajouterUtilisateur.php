<?php
require_once 'bdd.php';

$userId = $_GET['utilisateur'] ?? null;
$token  = $_GET['token'] ?? null;

verifierToken($userId, $token);

$stmt = $pdo->prepare("
    SELECT u.identifiant, r.nomRole 
    FROM utilisateur u
    LEFT JOIN role r ON u.idRole = r.idRole
    WHERE u.idUtilisateur = ?
");
$stmt->execute([$userId]);
$utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$utilisateur || strtolower($utilisateur['nomRole']) !== 'admin') {
    header("Location: listePC.php?utilisateur=$userId&token=$token");
    exit;
}

$stmtRoles = $pdo->query("SELECT idRole, nomRole FROM role");
$rolesDispo = $stmtRoles->fetchAll(PDO::FETCH_ASSOC);

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identifiant = trim($_POST['identifiant'] ?? '');
    $mdp = $_POST['mdp'] ?? '';
    $confirmerMDP = $_POST['confirmerMDP'] ?? '';
    $idRole = $_POST['idRole'] ?? '';

    if ($identifiant === '' || $mdp === '' || $idRole === '') {
        $error = "Tous les champs sont obligatoires.";
    } elseif ($mdp !== $confirmerMDP) {
        $error = "Les mots de passe ne correspondent pas.";
    } elseif (strlen($mdp) < 6) {
        $error = "Le mot de passe doit contenir au moins 6 caractères.";
    } else {
        $stmt = $pdo->prepare("SELECT idUtilisateur FROM utilisateur WHERE identifiant = ?");
        $stmt->execute([$identifiant]);
        if ($stmt->rowCount() > 0) {
            $error = "Identifiant déjà utilisé.";
        } else {

            $stmt = $pdo->prepare("INSERT INTO utilisateur (identifiant, mdp, idRole) VALUES (?, ?, ?)");
            $stmt->execute([$identifiant, $mdp, $idRole]);

            header("Location: listePC.php?utilisateur=$userId&token=$token");
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
    <title>Ajouter un utilisateur</title>
</head>

<body>

    <div class="header">
        <div class="header-left">
            <img src="GalacticosIT.png" alt="Logo">
        </div>

        <div class="header-center">
            <h1>Inventaire des PC</h1>
        </div>

        <div class="header-right">
            <div style="display: flex; flex-direction: column; text-align: right; line-height: 1.2;">
                <span>Connecté : <strong><?= htmlspecialchars($utilisateur['identifiant']) ?></strong></span>
                <?php if (strtolower($utilisateur['nomRole'] ?? '') === 'admin'): ?>
                    <span style="font-size: 11px; color: rgba(255,255,255,0.7); font-style: italic;">Administrateur</span>
                <?php else: ?>
                    <span style="font-size: 11px; color: rgba(255,255,255,0.7); font-style: italic;">Utilisateur</span>
                <?php endif; ?>
            </div>

            <a href="logout.php" class="btn-logout">Déconnexion</a>
        </div>
    </div>

    <a href="listePC.php?utilisateur=<?= $userId ?>&token=<?= $token ?>" class="btn-retour">← Retour à la liste</a>

    <div class="card">
        <h2>Nouvel utilisateur</h2>

        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="post">

            <div class="form-row">
                <div class="form-group">
                    <label>Identifiant</label>
                    <input type="text" name="identifiant" value="<?= htmlspecialchars($_POST['identifiant'] ?? '') ?>" required>
                </div>

                <div class="form-group">
                    <label>Rôle</label>
                    <select name="idRole" required>
                        <option value="">Sélectionnez un rôle...</option>
                        <?php foreach ($rolesDispo as $role): ?>
                            <option value="<?= htmlspecialchars($role['idRole']) ?>" <?= (isset($_POST['idRole']) && $_POST['idRole'] == $role['idRole']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($role['nomRole']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Mot de passe</label>
                    <input type="password" name="mdp" required>
                </div>

                <div class="form-group">
                    <label>Confirmer le mot de passe</label>
                    <input type="password" name="confirmerMDP" required>
                </div>
            </div>

            <button type="submit" class="btn-submit">Créer l'utilisateur</button>
        </form>
    </div>

</body>

</html>

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
        padding: 20px;
    }

    .header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 20px;
        position: relative;
        width: 100%;
    }

    .header-left img {
        height: 120px;
        margin-top: -25px;
        margin-left: -15px;
    }

    .header-center {
        position: absolute;
        left: 50%;
        transform: translateX(-50%);
        text-align: center;
    }

    .header-center h1 {
        color: white;
        font-size: 26px;
        margin-top: 80px;
    }

    .header-right {
        display: flex;
        align-items: center;
        gap: 15px;
        color: white;
        font-size: 14px;
    }

    .user-info {
        display: flex;
        flex-direction: column;
        text-align: right;
        line-height: 1.2;
    }

    .user-role {
        font-size: 11px;
        color: rgba(255, 255, 255, 0.7);
        font-style: italic;
    }

    .btn-admin {
        font-family: Trebuchet MS, Verdana, sans-serif;
        padding: 6px 14px;
        background: white;
        color: #5b1fae;
        border: 1px solid white;
        border-radius: 6px;
        font-size: 13px;
        font-weight: bold;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .btn-admin:hover {
        background: #f0f0f0;
        box-shadow: 0 0 10px rgba(255, 255, 255, 0.3);
        transform: translateY(-1px);
    }

    .btn-logout {
        font-family: Trebuchet MS, Verdana, sans-serif;
        padding: 6px 14px;
        background: rgba(255, 255, 255, 0.2);
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.5);
        border-radius: 6px;
        font-size: 13px;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .btn-logout:hover {
        background: rgba(255, 255, 255, 0.3);
    }

    .btn-retour {
        font-family: Trebuchet MS, Verdana, sans-serif;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 16px;
        background: rgba(255, 255, 255, 0.2);
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.5);
        border-radius: 8px;
        font-size: 13px;
        text-decoration: none;
        margin-bottom: 20px;
    }

    .btn-retour:hover {
        background: rgba(255, 255, 255, 0.3);
    }

    .card {
        background: white;
        border-radius: 16px;
        padding: 36px;
        max-width: 600px;
        margin: 120px auto 0 auto;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    }

    .card h2 {
        font-size: 18px;
        color: #2d1a4a;
        margin-bottom: 24px;
        padding-bottom: 10px;
        border-bottom: 2px solid #ede9fe;
    }

    .form-group {
        margin-bottom: 16px;
    }

    label {
        display: block;
        margin-bottom: 6px;
        color: #555;
        font-weight: 600;
        font-size: 13px;
    }

    input,
    select {
        width: 100%;
        padding: 10px 12px;
        border: 2px solid #e1e5e9;
        border-radius: 10px;
        font-size: 14px;
        font-family: Trebuchet MS, Verdana, sans-serif;
        transition: border-color 0.2s;
        background: rgba(255, 255, 255, 0.8);
    }

    input:focus,
    select:focus {
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