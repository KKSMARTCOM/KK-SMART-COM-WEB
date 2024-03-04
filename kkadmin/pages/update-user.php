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

// Récupérer l'ID du user à modifier
$user_id = isset($_GET['id']) ? $_GET['id'] : 0;

// Récupérer les données du user à modifier
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

// Fermer la connexion à la base de données
$conn->close();
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
			<li class="breadcrumb-item"><a href="users.php">Utilisateur</a></li>
			<li class="breadcrumb-item active"><a href="javascript:void(0)">Modification d'un utilisateur</a></li>
		</ol>
	</div>

	<div class="row">
		<div class="col-xl-12">
			<div class="filter cm-content-box box-primary">
				<div class="content-title SlideToolHeader">
					<div class="cpa">
						<i class="fa-solid fa-edit me-1"></i>Modification d'un utilisateur	
					</div>
					<div class="tools">
						<a href="javascript:void(0);" class="expand handle"><i class="fa fa-angle-down"></i></a>
					</div>
				</div>
				<div class="cm-content-body form excerpt">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl-6 mx-auto">
                                <form method="post" action="update-user-traitement.php" id="updateUserForm">
                                    <input type="hidden" name="userId" value="<?php echo $row['id']; ?>">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Nom:</label>
                                        <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($row["name"]); ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="identity" class="form-label">Identifiant:</label>
                                        <input type="text" class="form-control" id="identity" name="identity" value="<?php echo htmlspecialchars($row["identity"]); ?>" required>
                                    </div>
                                    <div class="row">
                                        <div class="col-xl-6 mb-3">
                                            <label for="role" class="form-label d-block">Rôle:</label>
                                            <select class="form-control" id="role" name="role" required>
                                                <?php
                                                $roles = array("Super-Admin", "Admin", "Blogger", "Editor");
                                                foreach ($roles as $role) {
                                                    $selected = ($row['role'] == $role) ? 'selected' : '';
                                                    echo '<option value="' . $role . '" ' . $selected . '>' . $role . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-xl-6 mb-3">
                                            <label for="dz-password" class="form-label">Mot de passe:</label>
                                            <input type="password" class="form-control" id="dz-password" min="5" name="userPassword" value="<?php echo htmlspecialchars($row["password"]); ?>" required>
                                        </div>
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