<?php
    // Vérifier si l'ID du projet est présent dans l'URL
    if (isset($_GET['id'])) {
        // Récupérer l'ID du projet depuis l'URL
        $project_id = $_GET['id'];

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

        // Préparation de la requête de suppression
        $sql = "DELETE FROM projects WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $project_id);

        // Exécution de la requête
        if ($stmt->execute()) {
            // Redirection vers la page de projet avec un message de succès
            header("Location: project.php?success=deleted");
            exit();
        } else {
            // En cas d'erreur, rediriger avec un message derreur
            header("Location: project.php?error=delete_failed");
            exit();
        }

        // Fermer la connexiona
        $stmt->close();
        $conn->close();
    } else {
        // Redirection si l'ID du projet n'est pas présent dans l'URL
        header("Location: project.php");
        exit();
    }
?>
