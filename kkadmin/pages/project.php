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
    $allowed_roles = ['Super-Admin', 'Admin', 'Editor'];
    if (!in_array($userRole, $allowed_roles)) {
        header('Location: unauthorized.php');
        exit();
    }
?>

<!DOCTYPE html>
<html lang="fr">

<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
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
    <link class="main-css" href="../public/css/style.css" rel="stylesheet">
    <link href="../public/vendor/sweetalert2/dist/sweetalert2.min.css" rel="stylesheet" type="text/css"/>
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
			<li class="breadcrumb-item"><a href="../pages/index.php">Accueil</a></li>
			<li class="breadcrumb-item active"><a href="javascript:void(0)">Projets réalisés</a></li>
		</ol>
	</div>


                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Liste des projets</h4>
                                <a href="add-project.php" class="btn btn-primary ms-auto">+ Ajouter un projet</a>

                            </div>
                            <div class="card-body">
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

                                // Récupérer les données de la table projects avec jointure sur overview
                                $sql = "SELECT projects.*, overview.client FROM projects LEFT JOIN overview ON projects.id = overview.id_project";
                                $result = $conn->query($sql);

                                // Afficher les projets
                                if ($result->num_rows > 0) {
                                    ?>
                                    <table id="responsiveTable" class="display responsive nowrap w-100">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Intitulé du projet</th>
                                                <th>Client</th>
                                                <th>Images</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $count = 1;
                                            while ($row = $result->fetch_assoc()) {
                                                ?>
                                                <tr>
                                                    <td><?php echo $count; ?></td>
                                                    <td>
                                                        <?php
                                                        $title = $row["title"];
                                                        $title_short = strlen($title) > 20 ? substr($title, 0, 20) . '...' : $title;
                                                        ?>
                                                        <span title="<?php echo htmlspecialchars($title); ?>"><?php echo $title_short; ?></span>
                                                    </td>
                                                    <td><?php echo $row["client"]; ?></td>
                                                    <td>
                                                        <?php
                                                        // Récupérer le champ d'images de la base de données
                                                        $imageOrVideo = $row["imageOrVideo"];

                                                        // Vérifier si une image est présente
                                                        if (!empty($imageOrVideo)) {
                                                            $imageUrl = "/websmart/kkadmin/public/images/projects/" . $imageOrVideo;
                                                            echo '<img src="' . $imageUrl . '" alt="Image" width="50" style="margin-right: 0.5rem;">';
                                                        } else {
                                                            echo 'Aucune image disponible';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td class="text-end">
                                                        <a href="update-project.php?id=<?php echo $row["id"]; ?>" class="btn btn-success btn-sm content-icon" title="Modifier">
                                                            <i class="fa-solid fa-pen-to-square"></i>
                                                        </a>
                                                    <!-- <a href="javascript:void(0);" class="btn btn-warning btn-sm content-icon" title="Désactiver">
                                                            <i class="fa-solid fa-eye"></i>
                                                        </a> -->
                                                       <!--  <a href="javascript:void(0);" class="btn btn-danger btn-sm content-icon" title="Supprimer" onclick="confirmDelete(<?php echo $row['id']; ?>)">
                                                            <i class="fa-solid fa-trash"></i>
                                                        </a> -->
                                                    </td>
                                                </tr>
                                                <?php
                                                $count++;
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                    <?php
                                } else {
                                    ?>
                                    <p>Aucun enregistrement trouvé</p>
                                    <?php
                                }
                                // Fermer la connexion à la base de données
                                $conn->close();
                                ?>
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
	<script src="../public/vendor/datatables/js/jquery.dataTables.min.js" type="text/javascript"></script>
	<script src="../public/js/plugins-init/datatables.init.js" type="text/javascript"></script>
	<script src="../public/js/custom.min.js" type="text/javascript"></script>
	<script src="../public/js/deznav-init.js" type="text/javascript"></script>
	<script src="../public/js/demo.js" type="text/javascript"></script>
    <script src="../public/js/dashboard/cms.js" type="text/javascript"></script>
    <script src="../public/vendor/sweetalert2/dist/sweetalert2.min.js" type="text/javascript"></script>
    <script src="../public/js/plugins-init/sweetalert.init.js" type="text/javascript"></script>

    <script>
        function confirmDelete(projectId) {
            // Utilisez SweetAlert pour une confirmation plus élégante
            Swal.fire({
                title: 'Êtes-vous sûr?',
                text: 'Vous ne pourrez pas revenir en arrière!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Oui, supprimer!',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    // L'utilisateur a cliqué sur "Oui", redirigez vers la page de traitement de suppression avec l'ID du blog
                    window.location.href = "delete-project.php?id=" + projectId;
                }
            });
        }
    </script> 

</body>

</html>