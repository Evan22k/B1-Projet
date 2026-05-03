<?php

// Config BDD
$db_host = "localhost";
$db_name = "bd_projetb1";
$db_user = "root";
$db_pass = "";

try {
    $pdo = new PDO(
        "mysql:host=$db_host;dbname=$db_name;charset=utf8mb4",
        $db_user,
        $db_pass
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Clé secrète pour les tokens HMAC
define('SECRET_KEY', 'galacticos_secret_2024_!xK9#mP');

/**
 * Génère un token HMAC pour un utilisateur
 */
function genererToken(int $userId): string {
    return hash_hmac('sha256', $userId, SECRET_KEY);
}

/**
 * Vérifie que le token correspond à l'userId
 * Redirige vers connexion.php si invalide
 */
function verifierToken(?string $userId, ?string $token): void {
    if (!$userId || !$token || !ctype_digit($userId)) {
        header('Location: connexion.php');
        exit;
    }
    $tokenAttendu = genererToken((int)$userId);
    if (!hash_equals($tokenAttendu, $token)) {
        header('Location: connexion.php');
        exit;
    }
}
