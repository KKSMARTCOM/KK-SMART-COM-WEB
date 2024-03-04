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
			<li class="breadcrumb-item"><a href="article.php">Blog</a></li>
			<li class="breadcrumb-item active"><a href="javascript:void(0)">Ajout d'article de blog</a></li>
		</ol>
	</div>

	<div class="row">
		<div class="col-xl-12">
			<div class="filter cm-content-box box-primary">
				<div class="content-title SlideToolHeader">
					<div class="cpa">
						<i class="fa-solid fa-list me-1"></i>Enregistrement d'un article	
					</div>
					<div class="tools">
						<a href="javascript:void(0);" class="expand handle"><i class="fa fa-angle-down"></i></a>
					</div>
				</div>
				<div class="cm-content-body form excerpt">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl-6">
                                <form method="post" action="traitement.php" enctype="multipart/form-data">
                                    <div class="mb-3">
                                        <label class="form-label">Titre</label>
                                        <input type="text" name="title" class="form-control" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Lien spécifique</label>
                                        <input type="text" name="link" class="form-control" placeholder="Lien vers article existant">
                                    </div>

                                    <div class="row">
                                        <div class="col-xl-6 mb-3">
                                            <label class="form-label">Etiquettes</label>
                                            <input name='tagify' class="form-control h-auto" placeholder="Virgules pour séparer">
                                        </div>

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

                                        // Requête SQL pour récupérer les catégories par ordre alphabétique
                                        $sql = "SELECT id, name FROM category ORDER BY name ASC";
                                        $result = $conn->query($sql);

                                        // Fermer la connexion à la base de données
                                        $conn->close();
                                        ?>

                                        <!-- Utiliser les résultats pour créer les options du menu déroulant -->
                                        <div class="col-xl-6 mb-3">
                                            <label class="form-label">Catégorie</label>
                                            <select name="category" id="">
                                                <option value="">Sélectionner</option>
                                                <?php
                                                // Vérifier si la requête a retourné des résultats
                                                if ($result->num_rows > 0) {
                                                    // Boucler à travers les résultats et afficher chaque catégorie
                                                    while ($row = $result->fetch_assoc()) {
                                                        echo '<option value="' . $row["id"] . '">' . $row["name"] . '</option>';
                                                    }
                                                } else {
                                                    echo '<option value="">Aucune catégorie trouvée</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>

                                    </div>

                                    
                                        <div class="position-relative">
                                            <label class="form-label">Image</label>
                                            <input type="file" name="image" class="form-control mb-2" accept=".png, .jpg, .jpeg, .mp4, .avi, .mkv">
                                        </div>
                                    
                                    </div>

                                    <div class="col-xl-6">
                                        <label class="form-label mt-3 mt-xl-0">Article</label>
                                        <div class="custom-ekeditor mb-3">
                                            <textarea name="content" id="editor" class="form-control" rows="5"></textarea><br>
                                        <div class="form-text pt-1">
                                            Oui, un blog par semaine éloigne l'ennui pour toujours! 😄
                                        </div>
                                    </div>
                                    <div></div>

                                    <div class="text-end">
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
    <script src="https://cdn.ckeditor.com/ckeditor5/41.1.0/classic/ckeditor.js"></script>
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
        function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#imagePreview').css('background-image', 'url('+e.target.result +')');
                $('#imagePreview').hide();
                $('#imagePreview').fadeIn(650);
            }
            reader.readAsDataURL(input.files[0]);
        }
        }
        $("#imageUpload").on('change',function() {

        readURL(this);
        });
        $('.remove-img').on('click', function() {
        var imageUrl = "public/images/no-img-avatar.png";
        $('.avatar-preview, #imagePreview').removeAttr('style');
        $('#imagePreview').css('background-image', 'url(' + imageUrl + ')');
        });

        <!-- tagify.js -->

        if(jQuery('input[name=tagify]').length > 0){

        // The DOM element you wish to replace with Tagify
            var input = document.querySelector('input[name=tagify]');

            // initialize Tagify on the above input node reference
            new Tagify(input);
            
        }
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            ClassicEditor
                .create(document.querySelector('#editor'))
                .catch(error => {
                    console.error(error);
            });
        });
    </script>

</body>

</html>