<?php
// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "kksmartcom_db";

$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("La connexion à la base de données a échoué : " . $conn->connect_error);
}

error_reporting(E_ALL);
ini_set('display_errors', '1');

// Traitement du formulaire de mise à jour
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Vérifier s'il s'agit d'une requête de mise à jour d'utilisateur
    if (isset($_POST["userId"], $_POST["name"], $_POST["identity"], $_POST["role"], $_POST["userPassword"])) {
        $userId = $_POST["userId"];
        $name = $_POST["name"];
        $identity = $_POST["identity"];
        $role = $_POST["role"];
        $userPassword = $_POST["userPassword"];

        // Échapper les valeurs pour éviter les injections SQL
        $userId = mysqli_real_escape_string($conn, $userId);
        $name = mysqli_real_escape_string($conn, $name);
        $identity = mysqli_real_escape_string($conn, $identity);
        $role = mysqli_real_escape_string($conn, $role);
        $userPassword = mysqli_real_escape_string($conn, $userPassword);

        // Requête de mise à jour
        $sql = "UPDATE users SET name='$name', identity='$identity', role='$role', password='$userPassword' WHERE id='$userId'";

        if ($conn->query($sql) === TRUE) {
            header("Location: users.php");
            exit();
        } else {
            echo "Erreur lors de la mise à jour de l'utilisateur : " . $conn->error;
        }
    }
}

// Fermer la connexion à la base de données
$conn->close();
?>
