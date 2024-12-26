<?php
session_start();
function connect()
{
    try {
        $conn = new PDO('mysql:host=localhost;dbname=secpro', 'root', 'root');
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
    }
    return $conn;
}

$conn = connect();
?>