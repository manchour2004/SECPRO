<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'vendor/autoload.php';
require 'config.php';
require 'db.php';

use GuzzleHttp\Client;

// Configuration du client HTTP
$client = new Client([
    'timeout' => 2.0,
    'verify' => __DIR__ . '/cacert.pem',
]);

try {
    // Demande d'autorisation OAuth
    $response = $client->request('GET', 'https://www.linkedin.com/oauth/v2/authorization');
    $discoveryJSON = json_decode($response->getBody());
    $tokenEndpoint = 'https://www.linkedin.com/oauth/v2/accessToken';
    $userinfoEndpoint = 'https://api.linkedin.com/v2/userinfo';

    // Échange du code d'autorisation contre un jeton d'accès
    $response = $client->request('POST', $tokenEndpoint, [
        'form_params' => [
            'code' => $_GET['code'],
            'client_id' => LINKEDIN_ID,
            'client_secret' => LINKEDIN_SECRET,
            'redirect_uri' => '', // Assurez-vous que cette URL correspond à celle enregistrée
            'grant_type' => 'authorization_code'
        ]
    ]);

    $responseBody = $response->getBody()->getContents();
    $responseParams = json_decode($responseBody, true);

    if (!isset($responseParams['access_token'])) {
        throw new Exception('Access token not found in the response. Response: ' . $responseBody);
    }

    $accessToken = $responseParams['access_token'];

    // Récupération des informations utilisateur
    $response = $client->request('GET', $userinfoEndpoint, [
        'headers' => [
            'Authorization' => 'Bearer ' . $accessToken
        ]
    ]);

    $response = json_decode($response->getBody());
    if (isset($response->email_verified) && $response->email_verified) {
        // Vérification si l'utilisateur existe déjà dans la base de données
        $stmt = $conn->prepare("SELECT * FROM oauth_utilisateurs WHERE email = :email and fournisseur = 'linkedin'");
        $stmt->execute(['email' => $response->email]);
        $result = $stmt->fetch();
        if ($result) {
            // Utilisateur existant, mise à jour de la session
            $_SESSION['id'] = $result['id'];
            $_SESSION['photo_profil'] = $response->picture;
            header('Location: /SECPRO/main.php');
        } else {
            // Nouvel utilisateur, insertion dans la base de données
            $stmt = $conn->prepare("INSERT INTO oauth_utilisateurs (email, nom, prenom, fournisseur) VALUES (:email, :nom, :prenom, 'linkedin')");
            $stmt->execute([
                'email' => $response->email,
                'nom' => $response->family_name,
                'prenom' => $response->given_name,
            ]);
            $_SESSION['photo_profil'] = $response->picture;
            $_SESSION['id'] = $conn->lastInsertId();
            header('Location: /SECPRO/main.php');
        }
    } else {
        echo 'Email not verified or property does not exist.';
    }
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
?>