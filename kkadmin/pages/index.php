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
                
                <div class="row">
                    <div>
                        <div class="card">
                            <div class="card-body row justify-content-center">
                                <div class="col-xl-3">
                                    <li class="list-group-item d-flex justify-content-between align-items-center">Abonnées<span class="badge badge-primary badge-pill">14</span></li>
                                </div>
                                <div class="col-xl-3">
                                    <li class="list-group-item d-flex justify-content-between align-items-center">Articles<span class="badge badge-primary badge-pill">14</span></li>
                                </div>
                                <div class="col-xl-3">
                                    <li class="list-group-item d-flex justify-content-between align-items-center">Vues totales<span class="badge badge-success badge-pill">14</span></li>
                                </div><!-- 
                                <div class="col-xl-3">
                                    <li class="list-group-item d-flex justify-content-between align-items-center">Abonnées<span class="badge badge-primary badge-pill">14</span></li>
                                </div>
                                <div class="col-xl-3">
                                    <li class="list-group-item d-flex justify-content-between align-items-center">Articles<span class="badge badge-primary badge-pill">14</span></li>
                                </div> -->
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div>
                    Les vues
                        <div class="card">
                            <div class="card-body row justify-content-center">
                                <div class="col-xl-3">
                                    <li class="list-group-item d-flex justify-content-between align-items-center">Aujourd'hui<span class="badge badge-success badge-pill">14</span></li>
                                </div>
                                <div class="col-xl-3">
                                    <li class="list-group-item d-flex justify-content-between align-items-center"></i>Hier<span class="badge badge-success badge-pill">14</span></li>
                                </div>
                                <div class="col-xl-3">
                                    <li class="list-group-item d-flex justify-content-between align-items-center">Ce mois<span class="badge badge-success badge-pill">14</span></li>
                                </div>
                                <div class="col-xl-3">
                                    <li class="list-group-item d-flex justify-content-between align-items-center">Mois dernier<span class="badge badge-success badge-pill">14</span></li>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="row">
                            <div class="col-xl-6 col-lg-12 col-sm-12">
                                <select id="selectChart_1" class="mb-2 mt-3">
                                    <option value="now">Maintenant</option>
                                    <option value="yesterday">Hier</option>
                                    <option value="3days">Les 3 derniers jours</option>
                                    <option value="7days">Les 7 derniers jours</option>
                                    <option value="1months">Le dernier mois</option>
                                    <option value="3months">Les 3 derniers mois</option>
                                    <option value="6months">Les 6 derniers mois</option>
                                    <option value="all">Toutes les périodes</option>
                                </select>
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">Dernier Article</h4>
                                    </div>
                                    <div class="card-body">
                                        <canvas id="barChart_2"></canvas>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-12 col-sm-12">
                                <select id="selectChart_2" class="mb-2 mt-3">
                                    <option value="now">Maintenant</option>
                                    <option value="yesterday">Hier</option>
                                    <option value="3days">Les 3 derniers jours</option>
                                    <option value="7days">Les 7 derniers jours</option>
                                    <option value="1months">Le dernier mois</option>
                                    <option value="3months">Les 3 derniers mois</option>
                                    <option value="6months">Les 6 derniers mois</option>
                                    <option value="all">Toutes les périodes</option>
                                </select>
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">Tous les articles</h4>
                                    </div>
                                    <div class="card-body">
                                        <canvas id="lineChart_2"></canvas>
                                    </div>
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
	<script src="../public/vendor/datatables/js/jquery.dataTables.min.js" type="text/javascript"></script>
	<script src="../public/js/plugins-init/datatables.init.js" type="text/javascript"></script>
	<script src="../public/js/custom.min.js" type="text/javascript"></script>
	<script src="../public/js/deznav-init.js" type="text/javascript"></script>
	<script src="../public/js/demo.js" type="text/javascript"></script>
    <script src="../public/vendor/sweetalert2/dist/sweetalert2.min.js" type="text/javascript"></script>
    <script src="../public/js/plugins-init/sweetalert.init.js" type="text/javascript"></script>
    <script src="../public/vendor/chart/chart.bundle.min.js" type="text/javascript"></script>
    <script src="../public/js/plugins-init/chartjs-init.js" type="text/javascript"></script>

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