<?php
/**
 * MIGRATION — À exécuter UNE SEULE FOIS puis supprimer.
 * Hashe les mots de passe existants stockés en clair.
 */
require_once 'bdd.php';

$stmt = $pdo->query("SELECT idUtilisateur, mdp FROM utilisateur");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

$count = 0;
foreach ($users as $user) {
    if (strpos($user['mdp'], '$2y$') !== 0) {
        $hashed = password_hash($user['mdp'], PASSWORD_DEFAULT);
        $upd = $pdo->prepare("UPDATE utilisateur SET mdp = ? WHERE idUtilisateur = ?");
        $upd->execute([$hashed, $user['idUtilisateur']]);
        $count++;
    }
}

echo "Migration terminée : $count mot(s) de passe hashé(s). Supprimez ce fichier maintenant.";