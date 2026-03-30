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
    </style>
</body>
</html>