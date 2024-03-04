<?php
        // Démarrer la session si ce n'est pas déjà fait
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    // Vérifier si l'utilisateur est connecté
    if (isset($_SESSION['user'])) {
        $userName = $_SESSION['user']['name'];
        $userRole = $_SESSION['user']['role'];
        $userId = $_SESSION['user']['id'];
    } else {
        header("Location: ../page-login.php");
        exit();
    }

    // Vérifier le rôle de l'utilisateur
    $allowed_roles = ['Super-Admin', 'Admin', 'Blogger'];
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
                <div class="form-head d-flex mb-4 mb-md-5 align-items-start">
                    <ul class="d-flex align-items-center flex-wrap">
                        <!-- <li><a href="blog-category.html" class="btn btn-primary">Catégorie d'articles </a></li> -->
                    </ul>
                    <a href="add-blog.php" class="btn btn-primary ms-auto">+ Ajouter un article</a>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Liste des articles de blog</h4>
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

                                    // Récupérer les données de la table blog
                                    $sql = "SELECT id, title, content, date_pub FROM blog ORDER BY date_pub DESC";
                                    $result = $conn->query($sql);

                                    // Fermer la connexion à la base de données
                                    $conn->close();
                                ?>

                                <table id="responsiveTable" class="display responsive nowrap w-100">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Titre</th>
                                            <th>Contenu</th>
                                            <th>Date de publication</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if ($result->num_rows > 0) {
                                            $count = 1;
                                            while ($row = $result->fetch_assoc()) {
                                                $title_short = strlen($row["title"]) > 25 ? substr($row["title"], 0, 25) . '...' : $row["title"];
                                                $content_short = strlen($row["content"]) > 25 ? substr($row["content"], 0, 25) . '...' : $row["content"];
                                                ?>
                                                <tr>
                                                    <td><?php echo $count; ?></td>
                                                    <td title="<?php echo htmlspecialchars($row["title"]); ?>"><?php echo $title_short; ?></td>
                                                    <td title="<?php echo htmlspecialchars($row["content"]); ?>"><?php echo $content_short; ?></td>                                                    
                                                    <td><?php echo $row["date_pub"]; ?></td>
                                                    <td class="text-end">
                                                        <a href="update-blog.php?id=<?php echo $row["id"]; ?>" class="btn btn-success btn-sm content-icon" title="Modifier">
                                                            <i class="fa-solid fa-pen-to-square"></i>
                                                        </a>
                                                        <!-- <a href="javascript:void(0);" class="btn btn-warning btn-sm content-icon" title="Désactiver">
                                                            <i class="fa-solid fa-eye"></i>
                                                        </a> -->
                                                        <a href="javascript:void(0);" class="btn btn-danger btn-sm content-icon" title="Supprimer" onclick="confirmDelete(<?php echo $row['id']; ?>)">
                                                            <i class="fa-solid fa-trash"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                                <?php
                                                $count++;
                                            }
                                        } else {
                                            ?>
                                            <tr>
                                                <td colspan="5">Aucun enregistrement trouvé</td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>

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
    <script src="../public/vendor/sweetalert2/dist/sweetalert2.min.js" type="text/javascript"></script>
    <script src="../public/js/plugins-init/sweetalert.init.js" type="text/javascript"></script>

    <script>
    function confirmDelete(blogId) {
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
                window.location.href = "delete-blog.php?id=" + blogId;
            }
        });
    }
</script>  

</body>

</html>