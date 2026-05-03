<?php
require_once 'bdd.php';

$userId = $_GET['utilisateur'] ?? null;
$token  = $_GET['token'] ?? null;
$idPc   = $_GET['id'] ?? null;

verifierToken($userId, $token);

$stmt = $pdo->prepare("SELECT identifiant FROM utilisateur WHERE idUtilisateur = ?");
$stmt->execute([$userId]);
$utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$utilisateur) {
    header('Location: connexion.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM pc WHERE idPc = ?");
$stmt->execute([$idPc]);
$pc = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$pc) {
    header("Location: listePC.php?utilisateur=$userId&token=$token");
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails PC</title>
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
            max-width: 820px;
            margin: 120px auto 0 auto;
            display: flex;
            gap: 36px;
            align-items: center;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .pc-icon-wrapper {
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 14px;
        }

        .pc-icon {
            width: 150px;
            height: 150px;
            background: radial-gradient(circle, #ede9fe, #ddd6fe);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .pc-icon svg {
            width: 100px;
            height: 100px;
        }

        .pc-name-label {
            font-size: 15px;
            font-weight: 700;
            color: #5b1fae;
            text-align: center;
        }

        .divider {
            width: 1px;
            align-self: stretch;
            background: #ede9fe;
            flex-shrink: 0;
        }

        .pc-infos {
            flex: 1;
        }

        .pc-infos h2 {
            font-size: 18px;
            color: #2d1a4a;
            margin-bottom: 18px;
            padding-bottom: 10px;
            border-bottom: 2px solid #ede9fe;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .info-item {
            background: #f9f6ff;
            border-radius: 10px;
            padding: 12px 14px;
        }

        .info-item .label {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.07em;
            color: #a166d9;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .info-item .value {
            font-size: 14px;
            color: #2d1a4a;
            font-weight: 600;
        }

        .btn-modifier {
            font-family: Trebuchet MS, Verdana, sans-serif;
            display: inline-block;
            margin-top: 20px;
            padding: 9px 20px;
            background: radial-gradient(circle, #a166d9 0%, #5b1fae 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
        }

        @media (max-width: 600px) {
            .card {
                flex-direction: column;
                padding: 24px;
            }

            .divider {
                display: none;
            }

            .info-grid {
                grid-template-columns: 1fr;
            }
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
            <div style="display: flex; flex-direction: column; text-align: right; line-height: 1.2;">
                <span>Connecté : <strong><?= htmlspecialchars($utilisateur['identifiant']) ?></strong></span>
                <!-- Affichage du rôle pour tout le monde -->
                <?php if (strtolower($utilisateur['nomRole'] ?? '') === 'admin'): ?>
                    <span style="font-size: 11px; color: rgba(255,255,255,0.7); font-style: italic;">Administrateur</span>
                <?php else: ?>
                    <span style="font-size: 11px; color: rgba(255,255,255,0.7); font-style: italic;">Utilisateur</span>
                <?php endif; ?>
            </div>

            <!-- Bouton ajout utilisateur (Admin uniquement) -->
            <?php if (strtolower($utilisateur['nomRole'] ?? '') === 'admin'): ?>
                <a href="ajouterUtilisateur.php?utilisateur=<?= $userId ?>&token=<?= $token ?>" class="btn-admin">Ajouter un utilisateur</a>
            <?php endif; ?>

            <a href="logout.php" class="btn-logout">Déconnexion</a>
        </div>
    </div>

    <a href="listePC.php?utilisateur=<?= $userId ?>&token=<?= $token ?>" class="btn-retour">← Retour à la liste</a>

    <div class="card">
        <div class="pc-icon-wrapper">
            <div class="pc-icon">
                <svg viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect x="10" y="10" width="80" height="55" rx="6" fill="#5b1fae" />
                    <rect x="15" y="15" width="70" height="45" rx="4" fill="#ede9fe" />
                    <rect x="40" y="65" width="20" height="7" rx="2" fill="#5b1fae" />
                    <rect x="28" y="72" width="44" height="6" rx="3" fill="#a166d9" />
                    <rect x="25" y="24" width="50" height="4" rx="2" fill="#c4b5fd" />
                    <rect x="25" y="32" width="35" height="4" rx="2" fill="#c4b5fd" />
                    <rect x="25" y="40" width="42" height="4" rx="2" fill="#c4b5fd" />
                    <rect x="25" y="48" width="28" height="4" rx="2" fill="#c4b5fd" />
                </svg>
            </div>
            <div class="pc-name-label"><?= htmlspecialchars($pc['nomPC']) ?></div>
        </div>

        <div class="divider"></div>

        <div class="pc-infos">
            <h2>Détails du PC :</h2>
            <div class="info-grid">
                <div class="info-item">
                    <div class="label">Nom du PC</div>
                    <div class="value"><?= htmlspecialchars($pc['nomPC']) ?></div>
                </div>
                <div class="info-item">
                    <div class="label">Système d'exploitation</div>
                    <div class="value"><?= htmlspecialchars($pc['systemeExploitation']) ?></div>
                </div>
                <div class="info-item">
                    <div class="label">RAM</div>
                    <div class="value"><?= htmlspecialchars($pc['ram']) ?> Go</div>
                </div>
                <div class="info-item">
                    <div class="label">CPU</div>
                    <div class="value"><?= htmlspecialchars($pc['cpu']) ?></div>
                </div>
                <div class="info-item">
                    <div class="label">Carte graphique</div>
                    <div class="value"><?= htmlspecialchars($pc['carteGraphique']) ?></div>
                </div>
                <div class="info-item">
                    <div class="label">Carte mère</div>
                    <div class="value"><?= htmlspecialchars($pc['carteMere']) ?></div>
                </div>
            </div>
            <a href="modifier.php?id=<?= $pc['idPc'] ?>&utilisateur=<?= $userId ?>&token=<?= $token ?>" class="btn-modifier">Modifier ce PC</a>
        </div>
    </div>

</body>

</html>