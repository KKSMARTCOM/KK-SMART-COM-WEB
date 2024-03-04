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
    $allowed_roles = ['Super-Admin'];
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
			<li class="breadcrumb-item active"><a href="javascript:void(0)">Témoignages</a></li>
		</ol>
	</div>

	<div>
        <?php echo $message; ?>
    </div>
	
	<div class="row">
		<div class="col-xl-12">
			<div class="row">
				<div class="col-xl-4">
					<div class="filter cm-content-box box-primary">
						<div class="content-title SlideToolHeader">
							<div class="cpa">
								Ajouter un témoignage
							</div>
							<div class="tools">
								<a href="javascript:void(0);" class="expand handle"><i class="fa fa-angle-down"></i></a>
							</div>
						</div>
						<div class="cm-content-body form excerpt">
							<div class="card-body">
								<form method="post" action="testimonials-traitement.php" enctype="multipart/form-data">
									<div class="mb-3">
										<label for="nomCategorie" class="form-label">Auteur<span style="color: red;"> *</span></label>
										<input type="text" name="name" id="name" class="form-control" required>
									</div>
                                    <div class="mb-3">
										<label for="nomCategorie" class="form-label">Titre(domaine d'expertise)<span style="color: red;"> *</span></label>
										<input type="text" name="role" id="role" class="form-control" required>
									</div>
                                    <div>
                                        <div class="position-relative mb-3">
                                            <label class="form-label">
                                                Image ou logo<span style="color: red;"> *</span>
                                            </label>
                                            <input type="file" name="image" class="form-control mb-2" accept=".png, .jpg, .jpeg, .mp4, .avi, .mkv">             
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label mt-3 mt-xl-0">Contenu<span style="color: red;"> *</span></label>
                                        <div class="custom-ekeditor mb-3">
                                            <textarea name="content" class="form-control" rows="3"></textarea>
                                    </div></div>
                                    
									<div>
										<button type="submit" class="btn btn-outline-success btn-sm">Enregistrer</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
				<div class="col-xl-8">
					<div class="filter cm-content-box box-primary">
						<div class="content-title SlideToolHeader">
							<div class="cpa">
								Liste
							</div>
							<div class="tools">
								<a href="javascript:void(0);" class="expand handle"><i class="fa fa-angle-down"></i></a>
							</div>
						</div>
						<div class="cm-content-body publish-content form excerpt">
							<div class="card-body py-3">
								<div class="table-responsive">
									<table class="table table-striped verticle-middle table-responsive-sm">
										<thead>
											<tr>
												<th scope="col" class="text-black">No</th>
												<th scope="col" class="text-black">Auteur</th>
                                                <th scope="col" class="text-black">Titre</th>
                                                <th scope="col" class="text-black">Image</th>
                                                <th scope="col" class="text-black">Contenu</th>
												<th scope="col" class="text-black text-end">Actions</th>
											</tr>
										</thead>
										<tbody>
                                            <?php
                                            $servername = "localhost";
                                            $username = "root";
                                            $password = "";
                                            $dbname = "kksmartcom_db";

                                            $conn = new mysqli($servername, $username, $password, $dbname);

                                            // Vérifier la connexion
                                            if ($conn->connect_error) {
                                                die("La connexion à la base de données a échoué : " . $conn->connect_error);
                                            }

                                            // Récupérer la liste des catégories depuis la base de données
                                            $sql = "SELECT * FROM testimonials";
                                            $result = $conn->query($sql);

                                            // Vérifier si la requête a retourné des résultats
                                            if ($result->num_rows > 0) {
                                                $count = 1; // Compteur pour le numéro de catégorie
                                                while ($row = $result->fetch_assoc()) {
                                                    echo '<tr>
                                                            <td>' . $count . '</td>
                                                            <td>' . (strlen($row["name"]) > 8 ? substr($row["name"], 0, 8) . '...' : $row["name"]) . '</td>
                                                            <td>' . (strlen($row["role"]) > 8 ? substr($row["role"], 0, 8) . '...' : $row["role"]) . '</td>
                                                            <td><img src="../public/images/testimonials/' . $row["image"] . '" alt="Image" width="50"></td>
                                                            <td>' . (strlen($row["content"]) > 10 ? substr($row["content"], 0, 10) . '...' : $row["content"]) . '</td>
                                                            <td class="text-end">
                                                                <a href="javascript:void(0);" class="btn btn-success btn-sm content-icon" title="Modifier">
                                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                                </a>
                                                            </td>
                                                        </tr>';
                                                    $count++;
                                                }
                                            } else {
                                                echo '<tr>
                                                        <td colspan="6" class="text-center">Aucun témoignage trouvé</td>
                                                    </tr>';
                                            }
                                            $conn->close();
                                            ?>
                                        </tbody>

									</table>
								</div>
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
    <script src="../public/js/dashboard/cms.js" type="text/javascript"></script>

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

</body>

</html>