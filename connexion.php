<?php
session_start(); // nécessaire pour stocker l'utilisateur connecté

// Connexion à la BDD
try {
    $pdo = new PDO("mysql:host=localhost;dbname=ec-authentification;charset=utf8", "myroot", "root123*");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    die("Erreur PDO : " . $e->getMessage());
}

// Traitement du formulaire
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    // Vérifier si l'utilisateur existe
    $stmt = $pdo->prepare("SELECT * FROM authentification WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Vérifier le mot de passe
        if (password_verify($password, $user['password'])) {
            // Identifiants corrects → créer session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];

            // Redirection vers une autre page (ex: dashboard.php)
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Mot de passe incorrect.";
        }
    } else {
        $error = "Utilisateur non trouvé.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
    <title>Connexion</title>
</head>

<body>
    <div class="containerBody">
        <h1>Page de connexion</h1>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        <form method="POST">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Mot de passe" required>
            <button type="submit info">Se connecter</button>
            <p>Pas de compte? <a href="register.php">S'inscrire</a></p>

        </form>
    </div>
</body>

</html>