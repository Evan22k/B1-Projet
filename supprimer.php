<?php
require_once 'bdd.php';

$userId = $_GET['utilisateur'] ?? null;
$token  = $_GET['token'] ?? null;
$idPc   = $_GET['id'] ?? null;

verifierToken($userId, $token);

if (!$idPc || !ctype_digit((string)$idPc)) {
    header("Location: listePC.php?utilisateur=$userId&token=$token");
    exit;
}

$stmt = $pdo->prepare("DELETE FROM pc WHERE idPc = ?");
$stmt->execute([$idPc]);

header("Location: listePC.php?utilisateur=$userId&token=$token");
exit;
