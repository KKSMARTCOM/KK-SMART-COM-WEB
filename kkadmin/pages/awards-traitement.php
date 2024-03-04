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

    // Récupérer les données du formulaire
    $name = $_POST["name"];
    $description = $_POST["description"];
    $content = $_POST["content"];
    $total = $_POST["total"];

    // Traitement de l'image
    $image = "";

    if (!empty($_FILES["image"]["name"])) {
        $targetDir = "/opt/lampp/htdocs/websmart/kkadmin/public/images/awards/";
        $targetFile = $targetDir . basename($_FILES["image"]["name"]);

        // Move the uploaded file to the specified directory
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
            $image = $_FILES["image"]["name"];
        } else {
            echo "Erreur lors de l'envoi du fichier.";
            exit();
        }
    }

    // Construire la requête SQL avec une déclaration préparée
    $sql = "INSERT INTO awards (name, description, total, content, image) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $name, $description, $total, $content, $image);

    // Exécuter la requête préparée
    if ($stmt->execute()) {
        header("Location: awards.php?success=true");
        exit();
    } else {
        echo "Erreur lors de l'enregistrement du prix award: " . $stmt->error;
    }

    // Fermer la connexion
    $stmt->close();
    $conn->close();
}
?>
