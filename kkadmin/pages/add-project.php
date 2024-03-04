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
    <link href="../public/vendor/select2/css/select2.min.css" rel="stylesheet" type="text/css"/>
    <link href="../public/vendor/tagify/dist/tagify.css" rel="stylesheet" type="text/css"/>
    <link class="main-css" href="../public/css/style.css" rel="stylesheet">
</head>
<body>

    <style>
        #editor {
            height: 380px !important;
        }
    </style>

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
                        <li class="breadcrumb-item"><a href="project.php">Projets</a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Ajout de projet</a></li>
                    </ol>
                </div>

                <div class="row">
                    <div class="col-xl-12">
                        <div class="filter cm-content-box box-primary">
                            <div class="content-title SlideToolHeader">
                                <div class="cpa">
                                    <i class="fa-solid fa-list me-1"></i>Enregistrement d'un projet
                                </div>
                                <div class="tools">
                                    <a href="javascript:void(0);" class="expand handle"><i class="fa fa-angle-down"></i></a>
                                </div>
                            </div>
                            <div class="cm-content-body form excerpt">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-xl-12 mx-auto">
                                            <form method="post" action="traitement-project.php" enctype="multipart/form-data">
                                                <div class="row">
                                                    <div class="position-relative col-xl-6 mb-3">
                                                        <label class="form-label">Intitulé du projet</label>
                                                        <input type="text" name="title" class="form-control">
                                                    </div>
                                                    <div class="position-relative col-xl-6 mb-3">
                                                        <label class="form-label">Lien du projet</label>
                                                        <input type="text" name="link" class="form-control" placeholder="Vers projet externe" id="imageOrVideo2url">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-xl-6 mb-3">
                                                        <div class="position-relative">
                                                            <label class="form-label">Image ou vidéo</label>
                                                            <input type="file" name="imageOrVideo1" class="form-control mb-2" accept=".png, .jpg, .jpeg, .mp4, .avi, .mkv">
                                                        </div>
                                                    </div>

                                                    <div class="col-xl-6 mb-3">
                                                        <label class="form-label">Catégories</label>
                                                        <select class="default-select form-control wide" name="categories[]">
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

                                                            // Récupérer les catégories depuis la base de données
                                                            $sql = "SELECT id, name FROM category";
                                                            $result = $conn->query($sql);

                                                            // Afficher les options du menu déroulant
                                                            if ($result->num_rows > 0) {
                                                                while ($row = $result->fetch_assoc()) {
                                                                    echo '<option value="' . $row["id"] . '">' . $row["name"] . '</option>';
                                                                }
                                                            }

                                                            // Fermer la connexion à la base de données
                                                            $conn->close();
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="filter cm-content-box box-primary">
                                                    <div class="content-title SlideToolHeader">
                                                        <div class="cpa">
                                                            <i class="fa-solid fa-list me-1"></i>Vue d'ensemble
                                                        </div>
                                                        <div class="tools">
                                                            <a href="javascript:void(0);" class="expand handle"><i class="fa fa-angle-down"></i></a>
                                                        </div>
                                                    </div>
                                                    <div class="cm-content-body form excerpt">
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-xl-12 mx-auto">
                                                                    <div class="row">
                                                                        <div class="col-xl-6 mb-3">
                                                                            <label for="overview_title" class="form-label">Titre</label>
                                                                            <div class="position-relative">
                                                                                <input type="text" name="overview_title" class="form-control mb-2" required>
                                                                            </div>

                                                                            <label for="client" class="form-label">Client</label>
                                                                            <div class="position-relative">
                                                                                <input type="text" name="client" class="form-control mb-2" required>
                                                                            </div>

                                                                            <div class="position-relative mb-3">
                                                                                <label for="tagify" class="form-label">Services</label>
                                                                                <input name='tagify' class="form-control h-auto" placeholder="Virgules pour séparer" required>
                                                                            </div>

                                                                            <div class="row">
                                                                                <div class="col-xl-6 mb-3">
                                                                                    <div class="position-relative">
                                                                                        <label for="imageOrVideo3" class="form-label">Image</label>

                                                                                        <input type="file" name="imageOrVideo3" class="form-control mb-2" accept=".png, .jpg, .jpeg, .mp4, .avi, .mkv">
                                                                                    </div>
                                                                                </div>

                                                                                <div class="col-xl-6 mb-3">
                                                                                    <div class="position-relative">
                                                                                        <label for="imageOrVideo3url" class="form-label">Image (URL)</label>
                                                                                        <input type="text" name="imageOrVideo3url" class="form-control mb-2" placeholder="Saisissez l'URL" id="imageOrVideo3url">
                 
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-xl-6 mb-3">
                                                                            <label for="overview_description" class="form-label">Description</label>
                                                                            <textarea name="overview_description" id="" class="form-control" required></textarea>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>


                                                <div class="filter cm-content-box box-primary">
                                                    <div class="content-title SlideToolHeader">
                                                        <div class="cpa">
                                                            <i class="fa-solid fa-list me-1"></i>Besoin business
                                                        </div>
                                                        <div class="tools">
                                                            <a href="javascript:void(0);" class="expand handle"><i class="fa fa-angle-down"></i></a>
                                                        </div>
                                                    </div>
                                                    <div class="cm-content-body form excerpt">
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-xl-12 mx-auto">
                                                                    <div class="row">
                                                                        <div class="col-xl-6 mb-3">
                                                                            <label class="form-label">Titre</label>
                                                                            <div class="position-relative">
                                                                                <input type="text" name="need_title" class="form-control mb-2">
                                                                            </div>

                                                                            <label class="form-label">Label</label>
                                                                            <div class="position-relative">
                                                                                <input type="text" name="need_label" class="form-control mb-2">
                                                                            </div>

                                                                            <div class="position-relative">
                                                                                <label class="form-label">Image ou vidéo (URL)</label>
                                                                                <input type="text" name="imageOrVideo4url" class="form-control mb-2" placeholder="Saisissez l'URL" id="imageOrVideo4url">
                                                                                
                                                                                <label class="form-check-label" for="uploadFileCheckbox4">
                                                                                    Ou
                                                                                </label>

                                                                                <input type="file" name="imageOrVideo4" class="form-control mb-2" accept=".png, .jpg, .jpeg, .mp4, .avi, .mkv">
                                                                            </div>

                                                                        </div>

                                                                        <div class="col-xl-6 mb-3">
                                                                            <label class="form-label">Description</label>
                                                                            <textarea name="need_description" id="" class="form-control"></textarea>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="filter cm-content-box box-primary">
                                                    <div class="content-title SlideToolHeader">
                                                        <div class="cpa">
                                                            <i class="fa-solid fa-list me-1"></i>Problèmes & Solutions
                                                        </div>
                                                        <div class="tools">
                                                            <a href="javascript:void(0);" class="expand handle"><i class="fa fa-angle-down"></i></a>
                                                        </div>
                                                    </div>
                                                    <div class="cm-content-body form excerpt">
                                                        <div class="card-body">
                                                            <div id="rowContainer">
                                                                <div class="col-md-1">
                                                                    <button type="button" class="btn btn-success btn-add">+</button>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-xl-6 mb-3">
                                                                        <label class="form-label">Problème</label>
                                                                        <textarea name="problem[]" class="form-control" rows="1"></textarea>
                                                                    </div>
                                                                    <div class="col-xl-6 mb-3">
                                                                        <label class="form-label">Solution</label>
                                                                        <textarea name="solution[]" class="form-control" rows="1"></textarea>
                                                                    </div>
                                                                    <div class="col-md-1">
                                                                        <button type="button" class="btn btn-danger btn-remove" style="display:none">-</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="filter cm-content-box box-primary">
                                                    <div class="content-title SlideToolHeader">
                                                        <div class="cpa">
                                                            <i class="fa-solid fa-list me-1"></i>Recherches
                                                        </div>
                                                        <div class="tools">
                                                            <a href="javascript:void(0);" class="expand handle"><i class="fa fa-angle-down"></i></a>
                                                        </div>
                                                    </div>
                                                    <div class="cm-content-body form excerpt">
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-xl-12 mx-auto">
                                                                    <div class="row">
                                                                        <div class="col-xl-6 mb-3">
                                                                            <label class="form-label">Titre</label>
                                                                            <div class="position-relative">
                                                                                <input type="text" name="search_title" class="form-control mb-2">
                                                                            </div>

                                                                            <label class="form-label">Label</label>
                                                                            <div class="position-relative">
                                                                                <input type="text" name="search_label" class="form-control mb-2">
                                                                            </div>

                                                                            <div class="position-relative">
                                                                                <label class="form-label">Image ou vidéo (URL)</label>
                                                                                <input type="text" name="imageOrVideo5url" class="form-control mb-2" placeholder="Saisissez l'URL" id="imageOrVideo5url">
                                                                                <label class="form-check-label" for="uploadFileCheckbox5">
                                                                                    Ou
                                                                                </label>

                                                                                <input type="file" name="imageOrVideo5" class="form-control mb-2" accept=".png, .jpg, .jpeg, .mp4, .avi, .mkv">
                                                                            </div>

                                                                        </div>

                                                                        <div class="col-xl-6 mb-3">
                                                                            <label class="form-label">Description</label>
                                                                            <textarea name="search_description" id="" class="form-control"></textarea>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="filter cm-content-box box-primary">
                                                    <div class="content-title SlideToolHeader">
                                                        <div class="cpa">
                                                            <i class="fa-solid fa-list me-1"></i>Design
                                                        </div>
                                                        <div class="tools">
                                                            <a href="javascript:void(0);" class="expand handle"><i class="fa fa-angle-down"></i></a>
                                                        </div>
                                                    </div>
                                                    <div class="cm-content-body form excerpt">
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-xl-12 mx-auto">
                                                                    <div class="row">
                                                                        <div class="col-xl-6 mb-3">
                                                                            <label class="form-label">Titre</label>
                                                                            <div class="position-relative">
                                                                                <input type="text" name="design_title" class="form-control mb-2">
                                                                            </div>

                                                                            <label class="form-label">Label</label>
                                                                            <div class="position-relative">
                                                                                <input type="text" name="design_label" class="form-control mb-2">
                                                                            </div>

                                                                            <div class="position-relative">
                                                                                <label class="form-label">Image ou vidéo (URL)</label>
                                                                                <input type="text" name="imageOrVideo6url" class="form-control mb-2" placeholder="Saisissez l'URL" id="imageOrVideo6url">
                                                                                
                                                                                <label class="form-check-label" for="uploadFileCheckbox6">
                                                                                    Ou
                                                                                </label>

                                                                                <input type="file" name="imageOrVideo6" class="form-control mb-2" accept=".png, .jpg, .jpeg, .mp4, .avi, .mkv">
                                                                            </div>

                                                                        </div>

                                                                        <div class="col-xl-6 mb-3">
                                                                            <label class="form-label">Description</label>
                                                                            <textarea name="design_description" id="" class="form-control"></textarea>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="filter cm-content-box box-primary">
                                                    <div class="content-title SlideToolHeader">
                                                        <div class="cpa">
                                                            <i class="fa-solid fa-list me-1"></i>Résultats
                                                        </div>
                                                        <div class="tools">
                                                            <a href="javascript:void(0);" class="expand handle"><i class="fa fa-angle-down"></i></a>
                                                        </div>
                                                    </div>
                                                    <div class="cm-content-body form excerpt">
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-xl-12 mx-auto">
                                                                    <div class="row">
                                                                        <div class="col-xl-6 mb-3">
                                                                            <label class="form-label">Titre</label>
                                                                            <div class="position-relative">
                                                                                <input type="text" name="result_title" class="form-control mb-2">
                                                                            </div>

                                                                            <label class="form-label">Label</label>
                                                                            <div class="position-relative">
                                                                                <input type="text" name="result_label" class="form-control mb-2">
                                                                            </div>

                                                                            <div class="position-relative">
                                                                                <label class="form-label">Image ou vidéo (URL)</label>
                                                                                <input type="text" name="imageOrVideo7url" class="form-control mb-2" placeholder="Saisissez l'URL" id="imageOrVideo7url">
                                                                                
                                                                                <label class="form-check-label" for="uploadFileCheckbox7">
                                                                                    Ou
                                                                                </label>

                                                                                <input type="file" name="imageOrVideo7" class="form-control mb-2" accept=".png, .jpg, .jpeg, .mp4, .avi, .mkv">
                                                                            </div>

                                                                        </div>

                                                                        <div class="col-xl-6 mb-3">
                                                                            <label class="form-label">Description</label>
                                                                            <textarea name="result_description" id="" class="form-control"></textarea>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

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
    <!-- vendors -->
    <script src="../public/vendor/global/global.min.js" type="text/javascript"></script>
    <script src="../public/vendor/bootstrap-select/dist/js/bootstrap-select.min.js" type="text/javascript"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/41.1.0/classic/ckeditor.js"></script>
    <!-- <script src="../public/vendor/ckeditor/ckeditor.js" type="text/javascript"></script> -->
    <script src="../public/vendor/select2/js/select2.full.min.js" type="text/javascript"></script>
    <script src="../public/js/plugins-init/select2-init.js" type="text/javascript"></script> 
    <script src="../public/vendor/datatables/js/jquery.dataTables.min.js" type="text/javascript"></script>
    <script src="../public/js/plugins-init/datatables.init.js" type="text/javascript"></script>
    <script src="../public/js/dashboard/cms.js" type="text/javascript"></script>
    <script src="../public/vendor/tagify/dist/tagify.js" type="text/javascript"></script>
    <script src="../public/js/custom.min.js" type="text/javascript"></script>
    <script src="../public/js/deznav-init.js" type="text/javascript"></script><!-- 
    <script src="../public/js/demo.js" type="text/javascript"></script> -->

 <!--    <script>
        document.addEventListener('DOMContentLoaded', function() {
            ClassicEditor
                .create(document.querySelector('#editor1'))
                .catch(error => {
                    console.error(error);
            });

            ClassicEditor
                .create(document.querySelector('#editor2'))
                .catch(error => {
                    console.error(error);
            });

            ClassicEditor
                .create(document.querySelector('#editor3'))
                .catch(error => {
                    console.error(error);
            });

            ClassicEditor
                .create(document.querySelector('#editor4'))
                .catch(error => {
                    console.error(error);
            });

            ClassicEditor
                .create(document.querySelector('#editor5'))
                .catch(error => {
                    console.error(error);
            });
        });
    </script> -->




<script>
    function toggleFileInput(fileInputId) {
        var checkbox = document.getElementById('uploadFileCheckbox' + fileInputId.substr(-1));
        var fileInput = document.getElementById(fileInputId);

        if (checkbox.checked) {
            fileInput.style.display = 'block';
        } else {
            fileInput.style.display = 'none';
        }
    }
</script>

    <script>


        <!-- tagify.js -->

        if(jQuery('input[name=tagify]').length > 0){

        // The DOM element you wish to replace with Tagify
            var input = document.querySelector('input[name=tagify]');

            // initialize Tagify on the above input node reference
            new Tagify(input);
            
        }
    </script>


<!-- 
<script>
    var rowCount = 1;

    function updateLabel(labelId, value) {
        var label = document.getElementById(labelId);
        label.innerText = value;
    }

    function cloneRow(rowId) {
        var originalRow = document.querySelector('[data-row-id="' + rowId + '"]');
        var newRow = originalRow.cloneNode(true);

        rowCount++;
        var newRowCount = rowCount;

        // Update IDs and names in the cloned row
        newRow.setAttribute('data-row-id', newRowCount);

        var inputs = newRow.querySelectorAll('input[type="text"], textarea');
        inputs.forEach(function(input) {
            var oldName = input.getAttribute('name');
            var newName = oldName.replace(rowId, newRowCount);
            input.setAttribute('name', newName);
            input.value = '';

            // You may also need to update other attributes or IDs if needed
        });

        // Append the new row
        document.getElementById('rowContainer').appendChild(newRow);
    }

    function removeRow(element) {
        var row = element.closest('.row');
        row.remove();
    } -->
</script>

<script>
    $(document).ready(function () {
        $(".btn-add").click(function () {
            var clone = $("#rowContainer .row:first").clone();
            clone.find("textarea").val(""); // Efface le texte des nouveaux champs
            clone.find(".btn-add").hide();
            clone.find(".btn-remove").show();
            $("#rowContainer").append(clone);
        });

        $("#rowContainer").on("click", ".btn-remove", function () {
            $(this).closest(".row").remove();
        });
    });
</script>

<script>
    function isValidURL(url) {
        const regex = /^(?:http(s)?:\/\/)?[\w.-]+(?:\.[\w.-]+)+[/\w .-]*/;
        return regex.test(url);
    }

    const imageOrVideo2url = document.getElementById('imageOrVideo2url'); 
    const imageOrVideo3url = document.getElementById('imageOrVideo3url');
    const imageOrVideo4url = document.getElementById('imageOrVideo4url');
    const imageOrVideo5url = document.getElementById('imageOrVideo5url');
    const imageOrVideo6url = document.getElementById('imageOrVideo6url');
    const imageOrVideo7url = document.getElementById('imageOrVideo7url');

    // Vérifier l'URL lorsque l'utilisateur quitte le champ de texte
    [ imageOrVideo2url,  imageOrVideo3url, imageOrVideo4url, imageOrVideo5url, imageOrVideo6url, imageOrVideo7url].forEach(input => {
        input.addEventListener('blur', () => {
            if (!isValidURL(input.value)) {
                alert('Veuillez entrer une URL valide.');
                input.value = ''; // Vider la valeur du champ de texte
                input.focus();
            }
        });
    });
</script>





</body>

</html>