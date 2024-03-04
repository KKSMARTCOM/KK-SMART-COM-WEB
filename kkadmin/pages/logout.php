<?php
    // Démarrer la session si ce n'est pas déjà fait
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    // Détruire toutes les données de session
    session_destroy();

    // Rediriger vers la page de connexion
    header("Location: ../page-login.php");
    exit();
?>
