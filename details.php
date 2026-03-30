<?php
require_once 'bdd.php';
 
$userId = $_GET['utilisateur'] ?? null;
$idPc = $_GET['id'] ?? null;

$stmt = $pdo->prepare("SELECT identifiant FROM utilisateur WHERE idUtilisateur = ?");
$stmt->execute([$userId]);
$utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("SELECT * FROM pc WHERE idPc = ?");
$stmt->execute([$idPc]);
$pc = $stmt->fetch(PDO::FETCH_ASSOC);
 
if (!$pc) {
    header("Location: accueil.php?utilisateur=$userId");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails PC</title>
</head>
<body>
<div class="header">
    <h1>Détails du PC</h1>
    <div class="header-right">
        <span>Connecté : <strong><?= $utilisateur['identifiant'] ?></strong></span>
        <a href="./accueil.php" class="btn">Retour</a>
    </div>
</div>
 
    <a href="accueil.php?utilisateur=<?= $userId ?>" class="btn-retour" style="display:inline-flex; max-width:820px; margin: 0 auto 20px auto; width:100%;">
        ← Retour à la liste
    </a>
 
    <div class="card">
 
        <!-- Icône PC (gauche) -->
        <div class="pc-icon-wrapper">
            <div class="pc-icon">
                <svg viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <!-- Écran -->
                    <rect x="10" y="10" width="80" height="55" rx="6" fill="#5b1fae"/>
                    <rect x="15" y="15" width="70" height="45" rx="4" fill="#ede9fe"/>
                    <!-- Pied écran -->
                    <rect x="40" y="65" width="20" height="7" rx="2" fill="#5b1fae"/>
                    <!-- Base -->
                    <rect x="28" y="72" width="44" height="6" rx="3" fill="#a166d9"/>
                    <!-- Détail écran -->
                    <rect x="25" y="24" width="50" height="4" rx="2" fill="#c4b5fd"/>
                    <rect x="25" y="32" width="35" height="4" rx="2" fill="#c4b5fd"/>
                    <rect x="25" y="40" width="42" height="4" rx="2" fill="#c4b5fd"/>
                    <rect x="25" y="48" width="28" height="4" rx="2" fill="#c4b5fd"/>
                </svg>
            </div>
            <div class="pc-name-label"><?= ($pc['nomPC']) ?></div>
        </div>
 
        <!-- Séparateur -->
        <div class="divider"></div>
 
        <!-- Informations (droite) -->
        <div class="pc-infos">
            <h2>Fiche technique</h2>
            <div class="info-grid">
                <div class="info-item">
                    <div class="label">Nom du PC</div>
                    <div class="value"><?= ($pc['nomPC']) ?></div>
                </div>
                <div class="info-item">
                    <div class="label">Système d'exploitation</div>
                    <div class="value"><?= ($pc['systemeExploitation']) ?></div>
                </div>
                <div class="info-item">
                    <div class="label">RAM</div>
                    <div class="value"><?= ($pc['ram']) ?> Go</div>
                </div>
                <div class="info-item">
                    <div class="label">CPU</div>
                    <div class="value"><?= ($pc['cpu']) ?></div>
                </div>
                <div class="info-item">
                    <div class="label">Carte graphique</div>
                    <div class="value"><?= ($pc['carteGraphique']) ?></div>
                </div>
                <div class="info-item">
                    <div class="label">Carte mère</div>
                    <div class="value"><?= ($pc['carteMere']) ?></div>
                </div>
            </div>
        </div>
 
    </div>

    




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
            align-items: center;
            margin-bottom: 20px;
        }

        .btn {
            font-family: Trebuchet MS, Verdana, sans-serif;
            padding: 6px 14px;
            background: rgba(255,255,255,0.2);
            color: white;
            border: 1px solid rgba(255,255,255,0.5);
            border-radius: 6px;
            font-size: 13px;
            text-decoration: none;
        }

        .header h1 {
            color: white;
            justify-content: center;
            font-size: 26px;
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
            background: rgba(255,255,255,0.2);
            color: white;
            border: 1px solid rgba(255,255,255,0.5);
            border-radius: 6px;
            font-size: 13px;
            text-decoration: none;
        }

        .btn-logout:hover { background: rgba(255,255,255,0.3); }

        .container {
            background: white;
            border-radius: 10px;
            padding: 20px;
            width: 90%;
            margin: 200px auto 0 auto;        
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

        tbody tr:last-child { border-bottom: none; }

        tbody tr:hover { background: #f9f9f9; }

        tbody td {
            padding: 10px 12px;
            color: #333;
        }

        .actions { display: flex; gap: 6px; }

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

        .empty {
            text-align: center;
            padding: 40px;
            color: #999;
        }

          /* === Icône PC === */
          .pc-icon-wrapper {
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 12px;
        }
 
        .pc-icon {
            width: 140px;
            height: 140px;
            background: radial-gradient(circle, #ede9fe, #ddd6fe);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
 
        .pc-icon svg {
            width: 90px;
            height: 90px;
        }
 
        .pc-name-label {
            font-size: 15px;
            font-weight: 700;
            color: #5b1fae;
            text-align: center;
        }
 
        /* === Séparateur === */
        .divider {
            width: 1px;
            align-self: stretch;
            background: #ede9fe;
            flex-shrink: 0;
        }
 
        /* === Infos === */
        .pc-infos {
            flex: 1;
        }
 
        .pc-infos h2 {
            font-size: 20px;
            color: #2d1a4a;
            margin-bottom: 20px;
        }
 
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
        }
 
        .info-item {
            background: #f9f6ff;
            border-radius: 10px;
            padding: 12px 14px;
            border-left: 3px solid #a166d9;
        }
 
        .info-item .label {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #a166d9;
            font-weight: 700;
            margin-bottom: 4px;
        }
 
        .info-item .value {
            font-size: 14px;
            color: #2d1a4a;
            font-weight: 600;
        }
 
        @media (max-width: 600px) {
            .card {
                flex-direction: column;
                padding: 24px;
            }
            .divider { display: none; }
            .info-grid { grid-template-columns: 1fr; }
        }
    </style>
</body>
</html>