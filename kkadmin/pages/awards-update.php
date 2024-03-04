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

// Récupérer l'ID du prix à modifier
$awards_id = isset($_GET['id']) ? $_GET['id'] : 0;

// Récupérer les données du prix à modifier
$sql = "SELECT * FROM awards WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $awards_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

/* var_dump($row); */

// Fermer la connexion à la base de données
$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <!-- Title -->
    <title>KKSMARTCOM Admin</title>

    <!-- Meta -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="author" content="DexignZone">
    <meta name="robots" content="">

    <!-- Mobile Specific -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" sizes="16x16" href="../public/images/favicon.png">

    <link href="../public/vendor/datatables/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css"/>
    <link href="../public/vendor/bootstrap-datepicker-master/css/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css"/>
    <link href="../public/vendor/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet">
    <link href="../public/vendor/select2/css/select2.min.css" rel="stylesheet" type="text/css"/>
    <link href="../public/vendor/tagify/dist/tagify.css" rel="stylesheet" type="text/css"/>
    <link class="main-css" href="../public/css/style.css" rel="stylesheet">
</head>
<body>

    <!--*******************
        Preloader start
    ********************-->
    <div id="preloader">
        <div class="lds-ripple">
            <div></div>
            <div></div>
        </div>
    </div>
    <!--*******************
        Preloader end
    ********************-->

    <!--**********************************
        Main wrapper start
    ***********************************-->
    <div id="main-wrapper">

        <!--**********************************
            Nav header start
        ***********************************-->
        <?php include('../layout/header-logo.php'); ?> 
        <!--**********************************
            Nav header end
        ***********************************-->

        <!--**********************************
            Header start
        ***********************************-->
        <?php include('../layout/header.php'); ?>       
        <!--**********************************
            Header end
        ***********************************-->

        <!--**********************************
            Sidebar start
        ***********************************-->
        <?php include('../layout/sidebar.php'); ?>       
        <!--**********************************
            Sidebar end
        ***********************************-->
                
        <!--**********************************
            Content body start
        ***********************************-->

        <div class="content-body default-height">
            <div class="container-fluid">
    <div class="page-titles">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="awards-list.php">Prix Awards</a></li>
            <li class="breadcrumb-item active"><a href="javascript:void(0)">Modification d'un prix</a></li>
        </ol>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <div class="filter cm-content-box box-primary">
                <div class="content-title SlideToolHeader">
                    <div class="cpa">
                        <i class="fa-solid fa-edit me-1"></i>Modification d'un prix    
                    </div>
                    <div class="tools">
                        <a href="javascript:void(0);" class="expand handle"><i class="fa fa-angle-down"></i></a>
                    </div>
                </div>
                <div class="cm-content-body form excerpt">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl-6 mx-auto">
                                <form method="post" action="update-awards-traitement.php" enctype="multipart/form-data">
                                    <input type="hidden" name="awardsId" value="<?php echo $row['id']; ?>">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Nom du prix :</label>
                                        <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($row["name"]); ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="total" class="form-label">Nombre total :</label>
                                        <input type="number" class="form-control" id="totalInput" name="total" min="1" value="<?php echo $row["total"]; ?>" required>
                                        <div id="notification" style="color: red; font-weight: italic"></div>
                                    </div>
                                    <div>
                                        <div class="position-relative mb-3">
                                            <label class="form-label">
                                                Image ou logo<span style="color: red;"> *</span>
                                            </label>
                                            <input type="file" name="image" class="form-control mb-2" accept=".png, .jpg, .jpeg, .mp4, .avi, .mkv">
                                            <img src="../public/images/awards/<?php echo $row["image"]; ?>" alt="Image actuelle" width="50">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="description" class="form-label">Description du prix :</label>
                                        <textarea class="form-control" id="description" name="description" rows="4" required><?php echo htmlspecialchars($row["description"]); ?></textarea>
                                    </div>

                                    <div class="modal-footer justify-content-between">
                                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

        <!--**********************************
            Content body end
        ***********************************-->

        <!--**********************************
            Footer start
        ***********************************-->
        <?php include('../layout/footer.php'); ?>      
        <!--**********************************
            Footer end
        ***********************************-->


    </div>
    <!--**********************************
        Main wrapper end
    ***********************************-->

    <!--**********************************
        Scripts
    ***********************************-->
    <!-- Required vendors -->
    <script src="../public/vendor/global/global.min.js" type="text/javascript"></script>
    <script src="../public/vendor/bootstrap-select/dist/js/bootstrap-select.min.js" type="text/javascript"></script>
    <script src="../public/vendor/ckeditor/ckeditor.js" type="text/javascript"></script>
    <script src="../public/vendor/select2/js/select2.full.min.js" type="text/javascript"></script>
    <script src="../public/js/plugins-init/select2-init.js" type="text/javascript"></script>
    <script src="../public/vendor/datatables/js/jquery.dataTables.min.js" type="text/javascript"></script>
    <script src="../public/js/plugins-init/datatables.init.js" type="text/javascript"></script>
    <script src="../public/js/dashboard/cms.js" type="text/javascript"></script>
    <script src="../public/vendor/tagify/dist/tagify.js" type="text/javascript"></script>
    <script src="../public/js/custom.min.js" type="text/javascript"></script>
    <script src="../public/js/deznav-init.js" type="text/javascript"></script>
    <script src="../public/js/demo.js" type="text/javascript"></script>
            
    <script>
        
        var totalInput = document.getElementById("totalInput");
        var notification = document.getElementById("notification");

        totalInput.addEventListener("blur", function() {
            // Vérifiez si la valeur n'est pas un nombre supérieur à zéro
            if (!totalInput.checkValidity() || totalInput.value <= 0) {
                // Affichez une notification et remettez la valeur à zéro
                notification.innerHTML = "Veuillez entrer un nombre supérieur à zéro.";
                totalInput.value = 1;
            } else {
                // Effacez la notification s'il n'y a pas d'erreur
                notification.innerHTML = "";
            }
        });
    </script>

</body>

</html>
