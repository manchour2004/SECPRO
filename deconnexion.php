<?php
session_start();
session_unset(); // Détruire toutes les sessions
session_destroy(); // Détruire toutes les sessions
header('Location: index.php'); // Rediriger vers la page de connexion
exit() ;
?>
