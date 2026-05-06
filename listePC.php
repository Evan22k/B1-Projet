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

            <?php if (strtolower($utilisateur['nomRole'] ?? '') === 'admin'): ?>
                <a href="ajouterUtilisateur.php?utilisateur=<?= $userId ?>&token=<?= $token ?>" class="btn-admin">Ajouter un utilisateur</a>
            <?php endif; ?>

            <a href="logout.php" class="btn-logout">Déconnexion</a>
        </div>
    </div>

    <div class="container">
        <div class="container-header">
            <h2>Liste des machines du parc informatique (<span id="compteur-pc"><?= count($pcs) ?></span>)</h2>
            <div class="header-actions">
                <input type="text" id="global-search" class="search-bar" placeholder="Rechercher (Nom, OS, CPU...)">
                <a href="ajouterPC.php?utilisateur=<?= $userId ?>&token=<?= $token ?>" class="btn-ajouter">+ Ajouter un PC</a>
            </div>
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
            <tbody id="table-body">
                <?php if (empty($pcs)): ?>
                    <tr class="empty-row">
                        <td colspan="8" class="empty">Aucun PC enregistré.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($pcs as $pc): ?>
                        <tr class="data-row">
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

<script>
    // Script de recherche globale en temps réel
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('global-search');
        const rows = document.querySelectorAll('.data-row');
        const compteur = document.getElementById('compteur-pc');

        searchInput.addEventListener('input', function(e) {
            const searchValue = e.target.value.toLowerCase().trim();
            let pcVisibles = 0;

            rows.forEach(row => {
                // On récupère tout le texte de la ligne et on cherche dedans
                const rowText = row.textContent.toLowerCase();
                
                if (rowText.includes(searchValue)) {
                    row.style.display = '';
                    pcVisibles++;
                } else {
                    row.style.display = 'none';
                }
            });

            // Mettre à jour le compteur dynamique
            compteur.textContent = pcVisibles;
        });
    });
</script>

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
    }

    .btn-admin:hover {
        background: #f0f0f0;
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

    /* Nouveau style pour le bloc recherche + bouton */
    .header-actions {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .search-bar {
        font-family: Trebuchet MS, Verdana, sans-serif;
        padding: 7px 12px;
        width: 250px;
        border: 1px solid #ccc;
        border-radius: 8px;
        font-size: 13px;
        outline: none;
        transition: border-color 0.2s;
    }

    .search-bar:focus {
        border-color: #5b1fae;
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