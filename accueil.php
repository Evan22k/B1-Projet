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

$stmt = $pdo->query("SELECT * FROM pc");
$pcs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventaire</title>
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
            margin-bottom: 10px;
            position: relative;
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
            gap: 12px;
            color: white;
            font-size: 14px;
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
        }

        .btn-logout:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .container {
            background: white;
            border-radius: 10px;
            padding: 20px;
            width: 90%;
            margin: 140px auto 0 auto;
        }

        .container-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .container-header h2 {
            font-size: 16px;
            color: #333;
        }

        .btn-ajouter {
            font-family: Trebuchet MS, Verdana, sans-serif;
            padding: 7px 16px;
            background: radial-gradient(circle, #a166d9 0%, #5b1fae 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }

        thead tr {
            border-bottom: 2px solid #ddd;
        }

        thead th {
            padding: 10px 12px;
            text-align: left;
            color: #555;
            font-weight: 600;
        }

        tbody tr {
            border-bottom: 1px solid #eee;
        }

        tbody tr:last-child {
            border-bottom: none;
        }

        tbody tr:hover {
            background: #f9f9f9;
        }

        tbody td {
            padding: 10px 12px;
            color: #333;
        }

        .actions {
            display: flex;
            gap: 6px;
        }

        .btn-detail {
            font-family: Trebuchet MS, Verdana, sans-serif;
            padding: 5px 12px;
            background: #ede9fe;
            color: #5b21b6;
            border: none;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
        }

        .btn-supprimer {
            font-family: Trebuchet MS, Verdana, sans-serif;
            padding: 5px 12px;
            background: #fee2e2;
            color: #b91c1c;
            border: none;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
        }

        .btn-supprimer:hover {
            background: #fca5a5;
        }

        .empty {
            text-align: center;
            padding: 40px;
            color: #999;
        }
    </style>
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
            <span>Connecté : <strong><?= htmlspecialchars($utilisateur['identifiant']) ?></strong></span>
            <a href="logout.php" class="btn-logout">Déconnexion</a>
        </div>
    </div>

    <div class="container">
        <div class="container-header">
            <h2>Liste des machines du parc informatique (<?= count($pcs) ?>)</h2>
            <a href="ajouter.php?utilisateur=<?= $userId ?>&token=<?= $token ?>" class="btn-ajouter">+ Ajouter un PC</a>
        </div>

        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nom</th>
                    <th>OS</th>
                    <th>RAM</th>
                    <th>CPU</th>
                    <th>Carte graphique</th>
                    <th>Carte mère</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($pcs)): ?>
                    <tr>
                        <td colspan="8" class="empty">Aucun PC enregistré.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($pcs as $pc): ?>
                        <tr>
                            <td><?= htmlspecialchars($pc['idPc']) ?></td>
                            <td><?= htmlspecialchars($pc['nomPC']) ?></td>
                            <td><?= htmlspecialchars($pc['systemeExploitation']) ?></td>
                            <td><?= htmlspecialchars($pc['ram']) ?> Go</td>
                            <td><?= htmlspecialchars($pc['cpu']) ?></td>
                            <td><?= htmlspecialchars($pc['carteGraphique']) ?></td>
                            <td><?= htmlspecialchars($pc['carteMere']) ?></td>
                            <td>
                                <div class="actions">
                                    <a href="details.php?id=<?= $pc['idPc'] ?>&utilisateur=<?= $userId ?>&token=<?= $token ?>" class="btn-detail">Détails</a>
                                    <a href="supprimer.php?id=<?= $pc['idPc'] ?>&utilisateur=<?= $userId ?>&token=<?= $token ?>"
                                       class="btn-supprimer"
                                       onclick="return confirm('Supprimer ce PC ?')">Supprimer</a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</body>

</html>
