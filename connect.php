<?php
error_reporting(E_ALL); // Affiche toutes les erreurs
ini_set('display_errors', 1); // Active l'affichage des erreurs

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
    // Récupération de la configuration OpenID de Google
    $response = $client->request('GET', 'https://accounts.google.com/.well-known/openid-configuration');
    $discoveryJSON = json_decode($response->getBody());
    $tokenEndpoint = $discoveryJSON->token_endpoint;
    $userinfoEndpoint = $discoveryJSON->userinfo_endpoint;

    // Échange du code d'autorisation contre un jeton d'accès
    $response = $client->request('POST', $tokenEndpoint, [
        'form_params' => [
            'code' => $_GET['code'],
            'client_id' => GOOGLE_ID,
            'client_secret' => GOOGLE_SECRET,
            'redirect_uri' => '', // Assurez-vous que cette URL correspond à celle enregistrée
            'grant_type' => 'authorization_code'
        ]
    ]);

    $accessToken = json_decode($response->getBody())->access_token;

    // Récupération des informations utilisateur
    $response = $client->request('GET', $userinfoEndpoint, [
        'headers' => [
            'Authorization' => 'Bearer ' . $accessToken
        ]
    ]);

    $response = json_decode($response->getBody());

    if (isset($response->email_verified) && $response->email_verified) {
        // Vérification si l'utilisateur existe déjà dans la base de données
        $stmt = $conn->prepare("SELECT * FROM oauth_utilisateurs WHERE email = :email and fournisseur = 'google'");
        $stmt->execute(['email' => $response->email]);
        $result = $stmt->fetch();
        if ($result) {
            // Utilisateur existant, mise à jour de la session
            $_SESSION['id'] = $result['id'];
            $_SESSION['photo_profil'] = $response->picture;
            header('Location: /SECPRO/main.php');
        } else {
            // Nouvel utilisateur, insertion dans la base de données
            $stmt = $conn->prepare("INSERT INTO oauth_utilisateurs (email, nom, prenom, fournisseur) VALUES (:email, :nom, :prenom, 'google')");
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
} catch (GuzzleHttp\Exception\ClientException $e) {
    echo 'Request failed: ' . $e->getMessage();
}
?>