<?php
/**
 * SCRIPT DE MIGRATION - À exécuter UNE SEULE FOIS puis supprimer
 * Il hash les mots de passe existants stockés en clair dans la BDD.
 */
require_once 'bdd.php';

$stmt = $pdo->query("SELECT idUtilisateur, mdp FROM utilisateur");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

$count = 0;
foreach ($users as $user) {
    // Si le mdp n'est pas déjà hashé (un hash bcrypt commence par $2y$)
    if (strpos($user['mdp'], '$2y$') !== 0) {
        $hashed = password_hash($user['mdp'], PASSWORD_DEFAULT);
        $upd = $pdo->prepare("UPDATE utilisateur SET mdp = ? WHERE idUtilisateur = ?");
        $upd->execute([$hashed, $user['idUtilisateur']]);
        $count++;
    }
}

echo "Migration terminée : $count mot(s) de passe hashé(s). Supprimez ce fichier maintenant.";
