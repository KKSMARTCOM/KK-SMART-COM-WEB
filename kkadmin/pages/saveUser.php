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

// Traitement du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Vérifier s'il s'agit d'un utilisateur
    if (isset($_POST["name"], $_POST["identity"], $_POST["role"], $_POST["userPassword"])) {
        $name = $_POST["name"];
        $identity = $_POST["identity"];
        $role = $_POST["role"];
        $userPassword = $_POST["userPassword"];

        // Échapper les valeurs pour éviter les injections SQL
        $name = mysqli_real_escape_string($conn, $name);
        $identity = mysqli_real_escape_string($conn, $identity);
        $role = mysqli_real_escape_string($conn, $role);
        $userPassword = mysqli_real_escape_string($conn, $userPassword);

        $sql = "INSERT INTO users (name, identity, role, password) VALUES ('$name', '$identity', '$role', '$userPassword')";

        if ($conn->query($sql) === TRUE) {
            header("Location: users.php"); 
            exit();
        } else {
            echo "Erreur lors de l'enregistrement de l'utilisateur : " . $conn->error;
        }
    }
}

$conn->close();
?>

