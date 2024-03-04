<?php
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

// Vérifier le rôle de l'utilisateur
$allowed_roles = ['Super-Admin', 'Admin'];
if (!in_array($userRole, $allowed_roles)) {
    header('Location: unauthorized.php');
    exit();
}

// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
    $awardsId = $_POST['awardsId'];
    $name = $_POST['name'];
    $total = $_POST['total'];
    $description = $_POST['description'];

    // Traitement de l'image
    $imageName = $_FILES['image']['name'];
    $imageTmpName = $_FILES['image']['tmp_name'];
    $imageSize = $_FILES['image']['size'];
    $imageError = $_FILES['image']['error'];

    if ($imageError === UPLOAD_ERR_OK) {
        // Vérifier que le fichier est une image
        $imageFileType = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($imageFileType, $allowedExtensions)) {
            // Déplacer l'image vers le répertoire souhaité
            $targetDirectory = "../public/images/awards/";
            $targetPath = $targetDirectory . $imageName;

            if (move_uploaded_file($imageTmpName, $targetPath)) {
                // Mettre à jour le champ de l'image dans la base de données
                $sqlUpdateImage = "UPDATE awards SET image=? WHERE id=?";
                $stmtUpdateImage = $conn->prepare($sqlUpdateImage);
                $stmtUpdateImage->bind_param("si", $imageName, $awardsId);
                $stmtUpdateImage->execute();
                $stmtUpdateImage->close();
            } else {
                // Gérer l'erreur de téléchargement de l'image
                header("Location: awards-update.php?id=$awardsId&error=image_upload_failed");
                exit();
            }
        } else {
            // Gérer l'erreur si le fichier n'est pas une image valide
            header("Location: awards-update.php?id=$awardsId&error=invalid_image_type");
            exit();
        }
    }

    // Préparer et exécuter la requête de mise à jour (sans l'image si elle n'a pas été téléchargée)
    $sql = "UPDATE awards SET name=?, total=?, description=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sisi", $name, $total, $description, $awardsId);

    if ($stmt->execute()) {
        // Fermer la connexion à la base de données
        $stmt->close();
        $conn->close();

        // Rediriger avec un message de succès si la mise à jour est réussie
        header("Location: awards.php");
        exit();
    } else {
        // Fermer la connexion à la base de données
        $stmt->close();
        $conn->close();

        // Rediriger avec un message d'erreur si la mise à jour échoue
        header("Location: awards-update.php?id=$awardsId&error=true");
        exit();
    }
} else {
    // Rediriger si le formulaire n'a pas été soumis
    header("Location: awards-update.php");
    exit();
}
?>
