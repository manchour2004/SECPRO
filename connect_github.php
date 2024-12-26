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
    $response = $client->request('GET', 'https://github.com/login/oauth/authorize');
    $discoveryJSON = json_decode($response->getBody());
    $tokenEndpoint = 'https://github.com/login/oauth/access_token';
    $userinfoEndpoint = 'https://api.github.com/user';

    // Échange du code d'autorisation contre un jeton d'accès
    $response = $client->request('POST', $tokenEndpoint, [
        'form_params' => [
            'code' => $_GET['code'],
            'client_id' => GITHUB_ID,
            'client_secret' => GITHUB_SECRET,
            'redirect_uri' => '', // Assurez-vous que cette URL correspond à celle enregistrée
        ]
    ]);

    $responseBody = $response->getBody()->getContents();
    parse_str($responseBody, $responseParams);

    if (!isset($responseParams['access_token'])) {
        throw new Exception('Access token not found in the response. Response: ' . $responseBody);
    }

    $accessToken = $responseParams['access_token'];

    // Récupération des informations utilisateur
    $response = $client->request('GET', $userinfoEndpoint, [
        'headers' => [
            'Authorization' => 'token ' . $accessToken
        ]
    ]);

    $response = json_decode($response->getBody());
    if (isset($response->login) && $response->login) {
        // Vérification si l'utilisateur existe déjà dans la base de données
        $stmt = $conn->prepare("SELECT * FROM oauth_utilisateurs WHERE email = :email");
        $stmt->execute(['email' => $response->login]);
        $result = $stmt->fetch();
        if ($result) {
            // Utilisateur existant, mise à jour de la session
            $_SESSION['id'] = $result['id'];
            $_SESSION['photo_profil'] = $response->avatar_url;
            header('Location: /SECPRO/main.php');
        } else {
            // Nouvel utilisateur, insertion dans la base de données
            $stmt = $conn->prepare("INSERT INTO oauth_utilisateurs (email, nom, fournisseur) VALUES (:email, :nom, :fournisseur)");
            $stmt->execute([
                'email' => $response->login,
                'nom' => $response->login,
                'fournisseur' => 'github'
            ]);
            $_SESSION['photo_profil'] = $response->avatar_url;
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