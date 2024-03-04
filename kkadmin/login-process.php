<?php
// Connexion à la base de données
$host = "localhost";
$dbname = "kksmartcom_db";
$username = "root";
$password = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("La connexion à la base de données a échoué : " . $e->getMessage());
}

// Démarrer la session si ce n'est pas déjà fait
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les identifiants du formulaire
    $identity = $_POST["identity"];
    $password = $_POST["password"];


    // Vérifier les identifiants dans la base de données
    $stmt = $pdo->prepare("SELECT name, role FROM users WHERE identity = ? AND password = ?");
    $stmt->execute([$identity, $password]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Identifiants valides, stocker les informations de l'utilisateur dans la session
        $_SESSION['user'] = [
            'name' => $user['name'], 
            'role' => $user['role'], 
        ];
    
        // Redirection en fonction du rôle
        if ($user['role'] == 'Blogger') {
            // Rediriger vers la page d'articles
            header("Location: pages/article.php");
            exit();
        } elseif ($user['role'] == 'Editor') {
            // Rediriger vers la page des projets
            header("Location: pages/project.php");
            exit();
        } else {
            // Rediriger vers index.php pour les autres rôles (Super-Admin, Admin, etc.)
            header("Location: pages/index.php");
            exit();
        }
    } else {
        // Identifiants invalides, rediriger vers la page de connexion avec un message d'erreur
        header("Location: page-login.php?error=1");
        exit();
    }
}
?>



