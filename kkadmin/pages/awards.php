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
			<li class="breadcrumb-item active"><a href="javascript:void(0)">Prix Awards</a></li>
		</ol>
	</div>

	<div>
        <?php echo $message; ?>
    </div>
	
	<div class="row">
		<div class="col-xl-12">
			<div class="row">
				<div class="col-xl-5">
					<div class="filter cm-content-box box-primary">
						<div class="content-title SlideToolHeader">
							<div class="cpa">
								Ajouter un prix
							</div>
                                <div style="font-style: italic; font-size: 11px;">
                                    (*): Champs obligatoires.
                                </div>
							<div class="tools">
								<a href="javascript:void(0);" class="expand handle"><i class="fa fa-angle-down"></i></a>
							</div>
						</div>
						<div class="cm-content-body form excerpt">
							<div class="card-body">
								<form method="post" action="awards-traitement.php" enctype="multipart/form-data">
									<div class="mb-3">
										<label for="nomCategorie" class="form-label">Nom<span style="color: red;"> *</span></label>
										<input type="text" name="name" id="name" class="form-control" placeholder="Intitulé du prix award" required>
									</div>
                                    <div class="mb-3">
                                        <label for="nomCategorie" class="form-label">Nombre total<span style="color: red;"> *</span></label>
                                        <input type="number" name="total" min="1" id="totalInput" class="form-control" placeholder="Prix total obtenu" required>
                                        <div id="notification" style="color: red; font-weight: italic"></div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label mt-3 mt-xl-0">Description du prix<span style="color: red;"> *</span></label>
                                        <div class="custom-ekeditor mb-3">
                                            <textarea name="description" class="form-control" rows="2" required></textarea>
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

                                        // Requête pour récupérer le dernier contenu de la base de données
                                        $contentQuery = "SELECT content FROM awards WHERE content IS NOT NULL ORDER BY id DESC LIMIT 1";
                                        $contentResult = $conn->query($contentQuery);

                                        if ($contentResult->num_rows > 0) {
                                            // Si le champ content a une valeur, utilisez cette valeur
                                            $contentRow = $contentResult->fetch_assoc();
                                            $content = $contentRow['content'];
                                        } else {
                                            // Si le champ content n'a pas de valeur, initialisez à une chaîne vide ou à une valeur par défaut
                                            $content = "";
                                        }

                                        $conn->close();
                                    ?>

                                    <!-- Champ de formulaire avec la valeur du dernier contenu -->
                                    <div class="mb-3">
                                        <label for="content" class="form-label">Contenu commun</label>
                                        <textarea name="content" class="form-control" rows="3"><?php echo htmlspecialchars($content); ?></textarea>
                                    </div>

                                    <div>
                                        <div class="position-relative mb-3">
                                            <label class="form-label">
                                                Image ou logo<span style="color: red;"> *</span>
                                            </label>
                                            <input type="file" name="image" class="form-control mb-2" accept=".png, .jpg, .jpeg, .mp4, .avi, .mkv" required>             
                                        </div>
                                    </div></div><br>
									<div>
										<button type="submit" class="btn btn-outline-success btn-sm">Enregistrer</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
				<div class="col-xl-7">
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

                                    // Récupérer les données de la table awards
                                    $sql = "SELECT * FROM awards";
                                    $result = $conn->query($sql);

                                    // Fermer la connexion à la base de données
                                    $conn->close();
                                ?>
								<div class="table-responsive">
									<table class="table table-striped verticle-middle table-responsive-sm">
										<thead>
											<tr>
												<th scope="col" class="text-black">No</th>
												<th scope="col" class="text-black">Nom</th>
                                                <th scope="col" class="text-black">Total</th>
                                                <th scope="col" class="text-black">Image</th>
												<th scope="col" class="text-black text-end">Actions</th>
											</tr>
										</thead>
										<tbody>
                                            <?php
                                                if ($result->num_rows > 0) {
                                                    $count = 1;
                                                    while ($row = $result->fetch_assoc()) {
                                                        ?>
                                                        <tr>
                                                            <td><?php echo $count; ?></td>
                                                            <td>
                                                                <?php echo $row["name"]; ?>
                                                            </td>
                                                            <td><?php echo $row["total"]; ?>
                                                            </td>
                                                            <td>
                                                                <img src="../public/images/awards/<?php echo $row["image"]; ?>" alt="Image" width="50">
                                                            </td>
                                                            <td class="text-end">
                                                                <a href="awards-update.php?id=<?php echo $row["id"]; ?>" class="btn btn-success btn-sm content-icon" title="Modifier">
                                                                    <i class="fa-solid fa-pen-to-square"></i>
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
                                        <tfoot>
                                            <tr>
                                                <td colspan="2" class="text-end"><strong>Total</strong></td>
                                                <td id="totalSum" class="text-black"></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                        </tfoot>

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

    <script>
        // Fonction pour calculer et afficher automatiquement le total
        function calculateAndDisplayTotal() {
            var totalSumElement = document.getElementById("totalSum");
            var totalSum = 0;

            // Sélectionnez toutes les cellules dans la colonne "Total"
            var totalCells = document.querySelectorAll('tbody td:nth-child(3)');

            // Parcourez les cellules et ajoutez les valeurs à la somme
            totalCells.forEach(function(cell) {
                totalSum += parseInt(cell.textContent || 0);
            });

            // Mettez à jour l'élément de total dans le pied du tableau
            totalSumElement.textContent = totalSum;
        }

        // Appeler la fonction lors du chargement de la page
        window.addEventListener('load', calculateAndDisplayTotal);
    </script>


</body>

</html>