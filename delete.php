<?php
require_once 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['_method']) && $_POST['_method'] === 'DELETE') {
        $id = $_POST['id'];
        $id_u = $_SESSION['id'];
        
        if ($id && $id_u) {
            try {
                // Préparation de la requête de suppression
                $stmt = $conn->prepare("
                   DELETE FROM mots_de_passe WHERE id = :id AND utilisateur_id = :util_id;
                ");
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->bindParam(':util_id', $id_u, PDO::PARAM_INT);
                $stmt->execute();
                // Redirection après suppression
                header('Location: main.php');
                exit();
            } catch (Exception $e) {
                die('Erreur lors de la suppression : ' . $e->getMessage());
            }
        } else {
            die('Tous les champs sont obligatoires.');
        }
    } else {
        die('Méthode non autorisée.');
    }
} else {
    die('Requête invalide.');
}
?>