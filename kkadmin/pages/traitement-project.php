<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "kksmartcom_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("La connexion à la base de données a échoué : " . $conn->connect_error);
}

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['user'])) {
    $userName = $_SESSION['user']['name'];
    $userRole = $_SESSION['user']['role'];
} else {
    header("Location: ../page-login.php");
    exit();
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $conn->begin_transaction();

        $uploadPath = '/opt/lampp/htdocs/websmart/kkadmin/public/images/projects/';

        // Vérifier si le dossier de destination existe, sinon le créer
        if (!file_exists($uploadPath) && !is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        $imageOrVideo1Path = $uploadPath . basename($_FILES['imageOrVideo1']['name']);

        // Déplacer le fichier téléchargé vers le dossier spécifié
        if (!move_uploaded_file($_FILES['imageOrVideo1']['tmp_name'], $imageOrVideo1Path)) {
            throw new Exception("Erreur lors du déplacement du fichier vers le dossier de destination.");
        }

        $title = $_POST['title'];
        $link = $_POST['link'];
        $user_name = $userName;
        $imageOrVideo1 = $_FILES['imageOrVideo1']['name'];
        $categories = isset($_POST['categories']) ? $_POST['categories'] : array();

        $sqlProjects = "INSERT INTO projects (title, link, user_name, imageOrVideo, id_category) VALUES (?, ?, ?, ?, ?)";
        $stmtProjects = $conn->prepare($sqlProjects);

        $categoriesString = implode(",", $categories);

        $stmtProjects->bind_param("sssss", $title, $link, $user_name, $imageOrVideo1, $categoriesString);

        if (!$stmtProjects->execute()) {
            throw new Exception("Erreur lors de l'enregistrement du projet : " . $stmtProjects->error);
        }

        $projectId = $stmtProjects->insert_id;

        // Overview
        $overviewTitle = $_POST['overview_title'];
        $overviewService = isset($_POST['tagify']) ? $_POST['tagify'] : array();
        $imageOrVideo3 = null;
        $imageOrVideo3url = null;
        $overviewDescription = $_POST['overview_description'];
        $client = $_POST['client'];

        if ($_FILES['imageOrVideo3']['name']) {
            // Un fichier est téléchargé, traitons-le
            $imageOrVideo3 = $_FILES['imageOrVideo3']['name'];

            // Suite du code pour déplacer le fichier, etc.
            $uploadPathOverview = '/opt/lampp/htdocs/websmart/kkadmin/public/images/overview/';
            if (!file_exists($uploadPathOverview) && !is_dir($uploadPathOverview)) {
                mkdir($uploadPathOverview, 0777, true);
            }

            $imageOrVideo3Path = $uploadPathOverview . basename($imageOrVideo3);

            if (!move_uploaded_file($_FILES['imageOrVideo3']['tmp_name'], $imageOrVideo3Path)) {
                throw new Exception("Erreur lors du déplacement du fichier vers le dossier de destination pour la section Overview.");
            }
        }

        $sqlOverview = "INSERT INTO overview (id_project, title, service, imageOrVideo, imageOrVideoUrl, description, client) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmtOverview = $conn->prepare($sqlOverview);

        $stmtOverview->bind_param("issssss", $projectId, $overviewTitle, $overviewService, $imageOrVideo3, $imageOrVideo3url, $overviewDescription, $client);

        if (!$stmtOverview->execute()) {
            throw new Exception("Erreur lors de l'enregistrement des données dans la table overview : " . $stmtOverview->error);
        }

        $stmtOverview->close();

        // Business need
        $needTitle = $_POST['need_title'];
        $needLabel = $_POST['need_label'];
        $imageOrVideo4 = null;
        $imageOrVideo4url = null;
        $needDescription = $_POST['need_description'];

        if ($_FILES['imageOrVideo4']['name']) {
            // Un fichier est téléchargé, traitons-le
            $imageOrVideo4 = $_FILES['imageOrVideo4']['name'];

            // Suite du code pour déplacer le fichier, etc.
            $uploadPathNeed = '/opt/lampp/htdocs/websmart/kkadmin/public/images/need/';
            if (!file_exists($uploadPathNeed) && !is_dir($uploadPathNeed)) {
                mkdir($uploadPathNeed, 0777, true);
            }

            $imageOrVideo4Path = $uploadPathNeed . basename($imageOrVideo4);

            if (!move_uploaded_file($_FILES['imageOrVideo4']['tmp_name'], $imageOrVideo4Path)) {
                throw new Exception("Erreur lors du déplacement du fichier vers le dossier de destination pour la section Business need.");
            }
        }

        $sqlNeed = "INSERT INTO need (id_project, title, label, imageOrVideo, imageOrVideoUrl, description) VALUES (?, ?, ?, ?, ?, ?)";
        $stmtNeed = $conn->prepare($sqlNeed);

        $stmtNeed->bind_param("isssss", $projectId, $needTitle, $needLabel, $imageOrVideo4, $imageOrVideo4url, $needDescription);

        if (!$stmtNeed->execute()) {
            throw new Exception("Erreur lors de l'enregistrement des données dans la table need : " . $stmtNeed->error);
        }

        // Problem & solution
        $problem = $_POST['problem'];
        $solution = $_POST['solution'];

        $sqlProblem = "INSERT INTO problem (id_project, problem, solution) VALUES (?, ?, ?)";
        $stmtProblem = $conn->prepare($sqlProblem);
        $stmtProblem->bind_param("iss", $projectId, $problem, $solution);

        if (!$stmtProblem->execute()) {
            throw new Exception("Erreur lors de l'enregistrement des données dans la table problem : " . $stmtProblem->error);
        }

        // Recherches
        $searchTitle = $_POST['search_title'];
        $searchLabel = $_POST['search_label'];
        $imageOrVideo5 = null;
        $imageOrVideo5url = null;
        $searchDescription = $_POST['search_description'];

        if ($_FILES['imageOrVideo5']['name']) {
            // Un fichier est téléchargé, traitons-le
            $imageOrVideo5 = $_FILES['imageOrVideo5']['name'];

            // Suite du code pour déplacer le fichier, etc.
            $uploadPathSearch = '/opt/lampp/htdocs/websmart/kkadmin/public/images/search/';
            if (!file_exists($uploadPathSearch) && !is_dir($uploadPathSearch)) {
                mkdir($uploadPathSearch, 0777, true);
            }

            $imageOrVideo5Path = $uploadPathSearch . basename($imageOrVideo5);

            if (!move_uploaded_file($_FILES['imageOrVideo5']['tmp_name'], $imageOrVideo5Path)) {
                throw new Exception("Erreur lors du déplacement du fichier vers le dossier de destination pour la section Recherches.");
            }
        }

        $sqlSearch = "INSERT INTO search (id_project, title, label, imageOrVideo, imageOrVideoUrl, description) VALUES (?, ?, ?, ?, ?, ?)";
        $stmtSearch = $conn->prepare($sqlSearch);

        $stmtSearch->bind_param("isssss", $projectId, $searchTitle, $searchLabel, $imageOrVideo5, $imageOrVideo5url, $searchDescription);

        if (!$stmtSearch->execute()) {
            throw new Exception("Erreur lors de l'enregistrement des données dans la table search : " . $stmtSearch->error);
        }

        // Design
        $designTitle = $_POST['design_title'];
        $designLabel = $_POST['design_label'];
        $imageOrVideo6 = null;
        $imageOrVideo6url = null;
        $designDescription = $_POST['design_description'];

        if ($_FILES['imageOrVideo6']['name']) {
            // Un fichier est téléchargé, traitons-le
            $imageOrVideo6 = $_FILES['imageOrVideo6']['name'];

            // Suite du code pour déplacer le fichier, etc.
            $uploadPathDesign = '/opt/lampp/htdocs/websmart/kkadmin/public/images/design/';
            if (!file_exists($uploadPathDesign) && !is_dir($uploadPathDesign)) {
                mkdir($uploadPathDesign, 0777, true);
            }

            $imageOrVideo6Path = $uploadPathDesign . basename($imageOrVideo6);

            if (!move_uploaded_file($_FILES['imageOrVideo6']['tmp_name'], $imageOrVideo6Path)) {
                throw new Exception("Erreur lors du déplacement du fichier vers le dossier de destination pour la section Design.");
            }
        }

        $sqlDesign = "INSERT INTO design (id_project, title, label, imageOrVideo, imageOrVideoUrl, description) VALUES (?, ?, ?, ?, ?, ?)";
        $stmtDesign = $conn->prepare($sqlDesign);

        $stmtDesign->bind_param("isssss", $projectId, $designTitle, $designLabel, $imageOrVideo6, $imageOrVideo6url, $designDescription);

        if (!$stmtDesign->execute()) {
            throw new Exception("Erreur lors de l'enregistrement des données dans la table design : " . $stmtDesign->error);
        }

        // Résultats
        $resultTitle = $_POST['result_title'];
        $resultLabel = $_POST['result_label'];
        $imageOrVideo7 = null;
        $imageOrVideo7url = null;
        $resultDescription = $_POST['result_description'];

        if ($_FILES['imageOrVideo7']['name']) {
            // Un fichier est téléchargé, traitons-le
            $imageOrVideo7 = $_FILES['imageOrVideo7']['name'];

            // Suite du code pour déplacer le fichier, etc.
            $uploadPathResults = '/opt/lampp/htdocs/websmart/kkadmin/public/images/results/';
            if (!file_exists($uploadPathResults) && !is_dir($uploadPathResults)) {
                mkdir($uploadPathResults, 0777, true);
            }

            $imageOrVideo7Path = $uploadPathResults . basename($imageOrVideo7);

            if (!move_uploaded_file($_FILES['imageOrVideo7']['tmp_name'], $imageOrVideo7Path)) {
                throw new Exception("Erreur lors du déplacement du fichier vers le dossier de destination pour la section Résultats.");
            }
        }

        $sqlResults = "INSERT INTO result (id_project, title, label, imageOrVideo, imageOrVideoUrl, description) VALUES (?, ?, ?, ?, ?, ?)";
        $stmtResults = $conn->prepare($sqlResults);

        $stmtResults->bind_param("isssss", $projectId, $resultTitle, $resultLabel, $imageOrVideo7, $imageOrVideo7url, $resultDescription);

        if (!$stmtResults->execute()) {
            throw new Exception("Erreur lors de l'enregistrement des données dans la table results : " . $stmtResults->error);
        }

        // Fin du code...

        $conn->commit();
        echo "Le projet a été enregistré avec succès !";

        header("Location: project.php");
        exit();
    } catch (Exception $e) {
        $conn->rollback();
        echo "Erreur : " . $e->getMessage();
    }
}

$conn->close();
?>
