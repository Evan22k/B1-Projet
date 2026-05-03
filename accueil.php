<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GalácticosIT</title>
</head>

<body>
    <div class="page">
        <img src="GalacticosIT.png" alt="Logo GalácticosIT" class="logo">
        <div class="card">
            <h1>GalácticosIT</h1>
            <div class="badge">Gestion du parc informatique</div>
            <div class="divider"></div>
            <p class="description">
                Fondée en 2016 à Châteaulin, GalácticosIT est une entreprise informatique
                spécialisée dans la gestion et la maintenance de parcs informatiques.<br>
                Le nom GalácticosIT mêle notre passion pour le football et l'astronomie à notre métier : l'informatique.
                Comme les étoiles du Real Madrid ou celles de notre galaxie, chaque machine du parc mérite d'être suivie avec précision.<br>
                Notre équipe met tout son savoir-faire pour assurer le suivi, l'inventaire et l'administration des équipements informatiques.
            </p>
            <p class="description">
                Notre application permet de centraliser l'ensemble du parc informatique :
                ajouter, consulter, modifier et supprimer des machines en temps réel,
                avec une gestion des accès par rôles pour sécuriser les opérations.
            </p>
            <a href="connexion.php" class="btn-connexion">Se connecter</a>
        </div>
        <p class="footer">&copy; 2026 GalácticosIT — Tous droits réservés</p>
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
    }

    .page {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 20px 20px 40px 20px;
        position: relative;
    }

    .logo {
        width: 270px;
        margin-bottom: 10px;
        filter: drop-shadow(0 4px 24px rgba(161, 102, 217, 0.5));
    }

    .card {
        background: white;
        border-radius: 20px;
        padding: 40px 48px;
        max-width: 640px;
        width: 100%;
        text-align: center;
        box-shadow: 0 30px 60px rgba(0, 0, 0, 0.25);
        margin-top: 10px;

    }

    .card h1 {
        font-size: 28px;
        color: #5b1fae;
        margin-bottom: 10px;
        letter-spacing: 0.5px;
    }

    .badge {
        display: inline-block;
        background: #ede9fe;
        color: #7c3aed;
        font-size: 12px;
        font-weight: 700;
        padding: 4px 12px;
        border-radius: 20px;
        margin-bottom: 20px;
        letter-spacing: 0.05em;
        text-transform: uppercase;
    }

    .divider {
        width: 50px;
        height: 3px;
        background: radial-gradient(circle, #a166d9 0%, #5b1fae 100%);
        border-radius: 2px;
        margin: 0 auto 24px auto;
    }

    .description {
        font-size: 15px;
        color: #555;
        line-height: 1.8;
        margin-bottom: 24px;
        text-align: left;
    }

    .btn-connexion {
        margin-left: auto;
        border-radius: 6px;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.2s ease;
        margin-top: 8px;
        position: absolute;
        top: 20px;
        right: 24px;
        padding: 9px 20px;
        font-size: 14px;
        background: white;
        color: #5b1fae;
        font-family: Trebuchet MS, Verdana, sans-serif;
        padding: 6px 14px;
        background: rgba(255, 255, 255, 0.2);
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.5);
        font-size: 13px;
        text-decoration: none;
    }

    .btn-connexion:hover {
        transform: translateY(-1px);
        box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
    }

    .footer {
        margin-top: 30px;
        color: rgba(255, 255, 255, 0.5);
        font-size: 12px;
    }
</style>