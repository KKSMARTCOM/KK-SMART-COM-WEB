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
    $description = mysqli_real_escape_string($conn, $_POST["description"]);

    // Traitement de l'image
    $image = ""; // Initialisez la variable image

    if (!empty($_FILES["image"]["name"])) {
        // Specify the target directory and file path
        $targetDir = "/opt/lampp/htdocs/websmart/kkadmin/public/images/clients/";
        $targetFile = $targetDir . basename($_FILES["image"]["name"]);

        // Move the uploaded file to the specified directory
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
            $image = $_FILES["image"]["name"];
        } else {
            echo "Erreur lors de l'envoi du fichier.";
            exit();
        }
    }

    // Traitement du champ nom-image
    $nomImage = ""; // Initialisez la variable nomImage

    if (!empty($_FILES["nom-image"]["name"])) {
        $nomImage = basename($_FILES["nom-image"]["name"]);

        $dossierDestination = "/opt/lampp/htdocs/websmart/kkadmin/public/images/clients/";
    
        $cheminImageDestination = $dossierDestination . $nomImage;
        if (move_uploaded_file($_FILES["nom-image"]["tmp_name"], $cheminImageDestination)) {
            echo "L'image a été téléchargée avec succès.";
        } else {
            echo "Erreur lors du téléchargement de l'image.";
        }
    }
    // Construire la requête SQL
    $sql = "INSERT INTO clients (name, description, image, nom_image) VALUES ('$name', '$description', '$image', '$nomImage')";

    // Exécuter la requête SQL
    if ($conn->query($sql) === TRUE) {
        header("Location: clients.php?success=true");
        exit();
    } else {
        echo "Erreur lors de l'enregistrement du client : " . $conn->error;
    }

    // Fermer la connexion
    $conn->close();
}
?>
