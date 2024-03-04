<?php
// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
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
    ini_set('display_errors', 1);


    // Récupérer les données du formulaire
    $name = mysqli_real_escape_string($conn, $_POST["name"]);
    $role = mysqli_real_escape_string($conn, $_POST["role"]);
    $content = mysqli_real_escape_string($conn, $_POST["content"]);


    // Traitement de l'image
    $image = ""; // Initialisez la variable image

    if (!empty($_FILES["image"]["name"])) {
        // Specify the target directory and file path
        $targetDir = "/opt/lampp/htdocs/websmart/kkadmin/public/images/testimonials/";
        $targetFile = $targetDir . basename($_FILES["image"]["name"]);

        // Move the uploaded file to the specified directory
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
            $image = $_FILES["image"]["name"];
        } else {
            echo "Erreur lors de l'envoi du fichier.";
            exit();
        }
    }

    // Construire la requête SQL
    $sql = "INSERT INTO testimonials (name, role, image, content) VALUES ('$name', '$role', '$image', '$content')";

    // Exécuter la requête SQL
    if ($conn->query($sql) === TRUE) {
        header("Location: testimonials.php?success=true");
        exit();
    } else {
        echo "Erreur lors de l'enregistrement: " . $conn->error;
    }

    // Fermer la connexion
    $conn->close();
}
?>
