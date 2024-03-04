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

    // Démarrer la session si ce n'est pas déjà fait
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    // Vérifier si l'utilisateur est connecté
    if (isset($_SESSION['user'])) {
        $userName = $_SESSION['user']['name'];
        $userRole = $_SESSION['user']['role'];
    } else {
        header("Location: ../page-login.php");
        exit();
    }

    error_reporting(E_ALL);
    ini_set('display_errors', '1');

    // Traitement du formulaire
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Vérifier s'il s'agit d'une catégorie
        if (isset($_POST["nomCategorie"])) {
            $nomCategorie = $_POST["nomCategorie"];

            $sql = "INSERT INTO category (name) VALUES ('$nomCategorie')";

            if ($conn->query($sql) === TRUE) {
                header("Location: category.php?success=true");
                exit();
            } else {
                echo "Erreur lors de l'enregistrement de la catégorie : " . $conn->error;
            }
        }
    }

    var_dump($_SESSION['user']['id']);
    // article de blog
    if (isset($_POST["title"])) {
        // Get and sanitize form data
        $title = mysqli_real_escape_string($conn, $_POST["title"]);
        $link = mysqli_real_escape_string($conn, $_POST["link"]);
        $category = mysqli_real_escape_string($conn, $_POST["category"]);
        $tags = isset($_POST["tagify"]) ? mysqli_real_escape_string($conn, $_POST["tagify"]) : "";
        $content = mysqli_real_escape_string($conn, $_POST["content"]);
        $date_pub = date('Y-m-d H:i:s');

        // Initialize image variable
        $image = "";

        // Check if an image file is uploaded
        if (!empty($_FILES["image"]["name"])) {
            // Specify the target directory and file path
            $targetDir = "/opt/lampp/htdocs/websmart/kkadmin/public/images/blog/";
            $targetFile = $targetDir . basename($_FILES["image"]["name"]);

            // Move the uploaded file to the specified directory
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
                $image = $_FILES["image"]["name"];
            } else {
                echo "Erreur lors de l'envoi du fichier.";
                exit();
            }
        }


        // Build the SQL query based on the availability of the image
        $sql = "INSERT INTO blog (title, link, category_id, tag, content, date_pub, user";
        if (!empty($image)) {
            $sql .= ", image";
        }
        $sql .= ") VALUES ('$title', '$link', '$category', '$tags', '$content', '$date_pub', '$userName'";
        if (!empty($image)) {
            $sql .= ", '$image'";
        }
        $sql .= ")";

        // Execute the SQL query
        if ($conn->query($sql) === TRUE) {
            header("Location: article.php?success=true");
            exit();
        } else {
            echo "Erreur lors de l'enregistrement de l'article de blog : " . $conn->error;
        }
    }


    $conn->close();
?>
