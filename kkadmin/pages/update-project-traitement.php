<?php

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] === "POST") {
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
    $project_id = htmlspecialchars($_POST["project_id"]);
    $company_name = htmlspecialchars($_POST["company_name"]);
    $project_title = htmlspecialchars($_POST["project_title"]);

    // Traitement de l'image
    $targetDir = "/opt/lampp/htdocs/kkadmin/public/images/projects/";
    $targetFile = $targetDir . basename($_FILES["logo"]["name"]);

    // Vérifier si un nouveau logo a été téléchargé
    if (!empty($_FILES["logo"]["name"])) {
        // Déplacer le fichier téléchargé vers le répertoire des logos
        if (move_uploaded_file($_FILES["logo"]["tmp_name"], $targetFile)) {
            // Mettre à jour le chemin du logo dans la base de données
            $logo = $_FILES["logo"]["name"];
        } else {
            echo "Erreur lors de l'envoi du fichier.";
            exit();
        }
    } else {
        // Aucun nouveau logo téléchargé, conserver le logo existant dans la base de données
        $logo = htmlspecialchars($_POST["existing_logo"]); // Ajoutez un champ caché dans le formulaire pour stocker le chemin du logo existant
    }

    // Préparation de la requête avec une déclaration préparée
    $sql = "UPDATE projects SET company_name = ?, project_title = ?, logo = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);

    // Liaison des paramètres
    $stmt->bind_param("sssi", $company_name, $project_title, $logo, $project_id);

    // Exécution de la requête
    if ($stmt->execute()) {
        // Succès
        header("Location: project.php?success=true");
        exit();
    } else {
        // Erreur
        echo "Erreur lors de la mise à jour du projet : " . $stmt->error;
    }

    // Fermer la déclaration préparée
    $stmt->close();

    // Fermer la connexion à la base de données
    $conn->close();
}
?>
