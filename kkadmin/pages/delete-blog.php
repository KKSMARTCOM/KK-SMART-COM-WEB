<?php
    // Suppression du blog avec l'ID spécifié
    if (isset($_GET['id'])) {
        $blogId = $_GET['id'];


        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "kksmartcom_db";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("La connexion à la base de données a échoué : " . $conn->connect_error);
        }

        // Requête de suppression
        $sql = "DELETE FROM blog WHERE id = $blogId";

        if ($conn->query($sql) === TRUE) {
            // Suppression réussie, redirigez vers la page principale avec un message de confirmation
            header("Location: article.php?delete_success=true");
            exit();
        } else {
            echo "Erreur lors de la suppression du blog : " . $conn->error;
        }

        $conn->close();
    }
?>
