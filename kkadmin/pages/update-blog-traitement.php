<?php

// Modification de l'article de blog
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["article_id"])) {
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
    $article_id = mysqli_real_escape_string($conn, $_POST["article_id"]);
    $title = mysqli_real_escape_string($conn, $_POST["title"]);
    $category = mysqli_real_escape_string($conn, $_POST["category"]);
    $tags = isset($_POST["tagify"]) ? mysqli_real_escape_string($conn, $_POST["tagify"]) : "";
    $content = mysqli_real_escape_string($conn, $_POST["content"]);

        // Vérifier si un nouveau image a été téléchargé
        if (!empty($_FILES["image"]["name"])) {
            // Déplacer le fichier téléchargé vers le répertoire des images
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
                // Mettre à jour le chemin du image dans la base de données
                $image = $_FILES["image"]["name"];
            } else {
                echo "Erreur lors de l'envoi du fichier.";
                exit();
            }
        } else {
            // Aucun nouveau image téléchargé, conserver le image existant dans la base de données
            $image = htmlspecialchars($_POST["existing_image"]); // Ajoutez un champ caché dans le formulaire pour stocker le chemin du image existant
        }

    // Construire la requête SQL en fonction de la disponibilité de l'image
    $sql = "UPDATE blog SET title = '$title', category_id = '$category', tag = '$tags', content = '$content'";
    if (!empty($image)) {
        $sql .= ", image = '$image'";
    }
    $sql .= " WHERE id = '$article_id'";

    // Exécuter la requête SQL
    if ($conn->query($sql) === TRUE) {
        header("Location: article.php?success=true");
        exit();
    } else {
        echo "Erreur lors de la modification de l'article de blog : " . $conn->error;
    }

    // Fermer la connexion à la base de données
    $conn->close();
}


?>
