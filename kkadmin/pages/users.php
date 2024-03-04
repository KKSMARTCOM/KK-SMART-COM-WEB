<?php
// Démarrer la session si ce n'est pas déjà fait
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Vérifier si l'utilisateur est connecté
if (isset($_SESSION['user'])) {
    $userName = $_SESSION['user']['name'];
    $userRole = $_SESSION['user']['role'];

    // Vérifier le rôle de l'utilisateur
    $allowed_roles = ['Super-Admin'];
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

    // Récupérer les données de la table users
    $sql = "SELECT id, name, identity, role FROM users";
    $result = $conn->query($sql);
} else {
    header("Location: ../../page-login.php");
    exit();
}

// Récupérer les détails de l'utilisateur à modifier s'il y a un ID dans la requête GET
$userDetails = array();
if (isset($_GET['id'])) {
    $userId = $_GET['id'];
    $sqlUserDetails = "SELECT * FROM users WHERE id = $userId";
    $resultUserDetails = $conn->query($sqlUserDetails);

    if ($resultUserDetails->num_rows > 0) {
        $userDetails = $resultUserDetails->fetch_assoc();
    }
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
                    <a href="#" class="btn btn-primary ms-auto" data-bs-toggle="modal" data-bs-target="#modalGrid">+ Ajouter un utilisateur</a>
                </div>

                <div class="modal fade" id="modalGrid">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Ajouter un utilisateur</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <!-- Formulaire pour ajouter un utilisateur -->
                                <form method="post" action="saveUser.php" id="addUserForm">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Nom:</label>
                                        <input type="text" class="form-control" id="name" name="name" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="identity" class="form-label">Identifiant:</label>
                                        <input type="text" class="form-control" id="identity" name="identity" required>
                                    </div>
                                    <div class="row">
                                        <div class="col-xl-6 mb-3">
                                            <label for="role" class="form-label d-block">Rôle:</label>
                                            <select class="form-control" id="role" name="role" required>
                                                <?php
                                                $roles = array("Super-Admin", "Admin", "Blogger", "Editor");
                                                foreach ($roles as $role) {
                                                    echo '<option value="' . $role . '">' . $role . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-xl-6 mb-3">
                                            <label for="password" class="form-label">Mot de passe:</label>
                                            <input type="password" class="form-control" id="dz-password" min="5" name="userPassword" required>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">Fermer</button>
                                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                                    </div>
                                </form>


                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Liste des utilisateurs</h4>
                            </div>
                            <div class="card-body">
                               
                                <table id="responsiveTable" class="display responsive nowrap w-100">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nom</th>
                                            <th>Identifiant</th>
                                            <th>Rôle</th>
                                            <th>Action</th>
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
                                                    <td><?php echo htmlspecialchars($row["name"]); ?></td>
                                                    <td><?php echo htmlspecialchars($row["identity"]); ?></td>
                                                    <td><?php echo htmlspecialchars($row["role"]); ?></td>
                                                    <td class="text-end">
                                                        <?php
                                                        // Vérifier si le rôle est différent de "Super-Admin"
                                                        if ($row["role"] != "Super-Admin") {
                                                            ?>
                                                            <!-- Ajoutez une classe spécifique pour identifier l'utilisateur -->
                                                            <a href="update-user.php?id=<?php echo $row["id"]; ?>" class="btn btn-success btn-sm content-icon" title="Modifier">
                                                                <i class="fa-solid fa-pen-to-square"></i>
                                                            </a>
                                                            <a href="javascript:void(0);" class="btn btn-danger btn-sm content-icon" title="Supprimer" onclick="confirmDelete(<?php echo $row['id']; ?>)">
                                                                <i class="fa-solid fa-trash"></i>
                                                            </a>
                                                            <?php
                                                        }
                                                        ?>
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
    // JavaScript pour mettre à jour les champs du formulaire lors de l'ouverture du modal
    $('#modalUpdate').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var userId = button.data('userid');
        var userName = button.data('username');
        var userIdentity = button.data('useridentity');
        var userRole = button.data('userrole');

        // Mettre à jour les valeurs dans le formulaire
        $('#updateUserId').val(userId);
        $('#name').val(userName);
        $('#identity').val(userIdentity);
        $('#role').val(userRole);
    });
</script>


    <script>
    function confirmDelete(userId) {
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
                window.location.href = "delete-user.php?id=" + userId;
            }
        });
    }
</script>  

</body>

</html>