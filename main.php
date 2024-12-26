<?php
error_reporting(E_ALL); // Affiche toutes les erreurs
ini_set('display_errors', 1); // Active l'affichage des erreurs
require 'db.php';

// Récupération des informations utilisateur
$userinfo = $conn->prepare("SELECT * FROM oauth_utilisateurs WHERE id = :id");
$userinfo->execute(['id' => $_SESSION['id']]);
$userinfo = $userinfo->fetch();

// Récupération des mots de passe de l'utilisateur
$stmt = $conn->prepare("SELECT * FROM mots_de_passe WHERE utilisateur_id = :id");
$stmt->execute(['id' => $_SESSION['id']]);
$pwd = $stmt->fetchAll();

// Fonction pour générer un mot de passe
function genererMotDePasse($longueur, $inclureMajuscules, $inclureChiffres, $inclureSymboles)
{
    $caracteres = 'abcdefghijklmnopqrstuvwxyz';
    if ($inclureMajuscules)
        $caracteres .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    if ($inclureChiffres)
        $caracteres .= '0123456789';
    if ($inclureSymboles)
        $caracteres .= '!@#$%^&*()_+{}[]|:;<>,.?/~';

    $motDePasse = '';
    for ($i = 0; $i < $longueur; $i++) {
        $motDePasse .= $caracteres[rand(0, strlen($caracteres) - 1)];
    }
    return $motDePasse;
}

// Initialiser les variables
$motDePasseGenere = '';

// Traiter la génération de mot de passe
if (isset($_POST['generer'])) {
    $longueur = isset($_POST['longueur']) ? intval($_POST['longueur']) : 12;
    $inclureMajuscules = isset($_POST['majuscules']);
    $inclureChiffres = isset($_POST['chiffres']);
    $inclureSymboles = isset($_POST['symboles']);

    $motDePasseGenere = genererMotDePasse($longueur, $inclureMajuscules, $inclureChiffres, $inclureSymboles);
}

// Fonction pour chiffrer un mot de passe
function chiffrerMotDePasse($motDePasse)
{
    global $userinfo;
    $cle = $userinfo['email']; // Clé de chiffrement (doit être bien protégée)
    $iv = random_bytes(openssl_cipher_iv_length('aes-256-cbc')); // Vecteur d'initialisation
    $hash = openssl_encrypt($motDePasse, 'aes-256-cbc', $cle, 0, $iv);
    return base64_encode($hash . "::" . $iv);
}

// Fonction pour déchiffrer un mot de passe
function dechiffrerMotDePasse($motDePasseChiffre)
{
    global $userinfo;
    $cle = $userinfo['email']; // Clé de chiffrement (doit être bien protégée)
    list($hash, $iv) = explode("::", base64_decode($motDePasseChiffre));
    return openssl_decrypt($hash, 'aes-256-cbc', $cle, 0, $iv);
}

// Traiter la sauvegarde de mot de passe
if (isset($_POST['sauvegarder'])) {
    $site = $_POST['site'];
    $motDePasse = $_POST['mot_de_passe'];
    $username = $_POST['username'];

    if (!empty($site) && !empty($motDePasse) && !empty($username)) {
        $hashBase64 = chiffrerMotDePasse($motDePasse);
        $stmt = $conn->prepare("INSERT INTO mots_de_passe (utilisateur_id, url_site, mot_de_passe_chiffre, nom_utilisateur) VALUES (:id_utilisateur, :site, :mdp, :username)");
        $stmt->execute([
            'id_utilisateur' => $_SESSION['id'],
            'site' => $site,
            'mdp' => $hashBase64,
            'username' => $username,
        ]);
        header('Location: main.php');
        $message = 'Mot de passe sauvegardé avec succès.';
    } else {
        $message = 'Veuillez remplir tous les champs.';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SECPRO - Générateur de Mot de Passe</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background-image: linear-gradient(rgba(10, 25, 47, 0.9), rgba(10, 25, 47, 0.9)), url('img/rm373batch4-15.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            backdrop-filter: blur(5px);
            color: #E0F0FF;
        }

        .btn-primary {
            background-color: #134e6f;
            color: #E0F0FF;
        }

        .btn-primary:hover {
            background-color: #1a6fa3;
        }

        .input-focus:focus {
            border-color: #1a6fa3;
            outline: none;
        }
    </style>
</head>

<body class="min-h-screen text-gray-300 bg-gray-900">
    <div class="container mx-auto px-4">
        <header>
            <div
                class="flex justify-between items-center px-4 py-2 bg-gray-800 bg-opacity-75 my-4 rounded-full shadow-md">
                <h1 class="text-3xl font-bold text-[#1a6fa3]">SECPRO</h1>
                <nav>
                    <div class="flex space-x-4 items-center">
                        <div>
                            <?= $userinfo['prenom'] . ' ' . $userinfo['nom'] ?>
                        </div>
                        <div class="mt-1">
                            <img src="<?= $_SESSION['photo_profil'] ?>" alt="Profile Picture"
                                class="h-10 w-10 rounded-full">
                        </div>
                        <div>
                            <a href="deconnexion.php" class="btn-primary text-white font-bold py-2 px-4 rounded">
                                <i class="fa-solid fa-arrow-right-from-bracket"></i>
                            </a>
                        </div>
                        <div></div>
                    </div>
                </nav>
            </div>
        </header>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 m-4 mt-8">
            <div>
                <div class="bg-gray-800 bg-opacity-75 p-6 rounded-lg mb-8 shadow-md">
                    <h2 class="text-2xl font-bold mb-4 text-[#E0F0FF]">Générateur de mot de passe</h2>
                    <form method="post" class="space-y-4">
                        <div>
                            <label for="longueur" class="block text-[#1a6fa3]">Longueur du mot de passe: <span
                                    id="longueur-value" class="font-bold">12</span></label>
                            <input type="range" id="longueur" name="longueur" min="8" max="32" value="12"
                                class="w-full bg-[#1a6fa3]">
                        </div>
                        <div class="flex items-center space-x-2">
                            <input type="checkbox" id="majuscules" name="majuscules" checked
                                class="rounded text-[#1a6fa3]">
                            <label for="majuscules" class="text-gray-300">Inclure des majuscules</label>
                        </div>
                        <div class="flex items-center space-x-2">
                            <input type="checkbox" id="chiffres" name="chiffres" checked class="rounded text-[#1a6fa3]">
                            <label for="chiffres" class="text-gray-300">Inclure des chiffres</label>
                        </div>
                        <div class="flex items-center space-x-2">
                            <input type="checkbox" id="symboles" name="symboles" checked class="rounded text-[#1a6fa3]">
                            <label for="symboles" class="text-gray-300">Inclure des symboles</label>
                        </div>
                        <button type="submit" name="generer"
                            class="w-full btn-primary text-white font-bold py-2 px-4 rounded">
                            Générer un mot de passe
                        </button>
                    </form>
                </div>
                <div class="bg-gray-800 bg-opacity-75 p-6 rounded-lg shadow-md">
                    <h2 class="text-2xl font-bold mb-4 text-[#E0F0FF]">Mot de passe généré</h2>
                    <form method="post" class="space-y-4" action="main.php">
                        <?php if ($motDePasseGenere != ''): ?>
                            <input type="text" name="mot_de_passe"
                                value="<?php echo htmlspecialchars($motDePasseGenere); ?>" readonly
                                class="w-full mb-4 bg-gray-700 text-[#E0F0FF] border-gray-600 rounded p-2 input-focus">
                        <?php endif; ?>
                        <input type="text" name="site" placeholder="Url du site"
                            class="w-full mb-4 bg-gray-700 text-[#E0F0FF] border-gray-600 rounded p-2 input-focus">
                        <input type="text" name="username" placeholder="Identifiant"
                            class="w-full mb-4 bg-gray-700 text-[#E0F0FF] border-gray-600 rounded p-2 input-focus">
                        <button type="submit" name="sauvegarder"
                            class="w-full btn-primary text-white font-bold py-2 px-4 rounded">
                            Sauvegarder
                        </button>
                    </form>
                </div>
            </div>
            <div class="bg-gray-800 bg-opacity-75 p-6 rounded-lg shadow-md">
                <h1 class="text-left mb-4 font-bold text-2xl text-[#E0F0FF]">Mes Mots de Passe</h1>
                <div class="grid grid-cols-1 md:grid-cols-2 md:space-x-2 text-lg" style="font-family:monospace;">
                    <?php if (!empty($pwd)): ?>
                        <?php foreach ($pwd as $mdp): ?>
                            <div class="bg-gray-700 rounded-lg p-4 mb-4">
                                <div class="mb-2">
                                    <span class="text-gray-300 font-semibold">Site :</span>
                                    <br class="hidden md:block">
                                    <span class="text-[#E0F0FF]"><?= htmlspecialchars($mdp['url_site']); ?></span>
                                </div>
                                <div class="mb-2">
                                    <span class="text-gray-300 font-semibold">Identifiant :</span>
                                    <br class="hidden md:block">
                                    <span class="text-[#E0F0FF]"><?= htmlspecialchars($mdp['nom_utilisateur']); ?></span>
                                </div>
                                <div class="flex items-center space-x-2 text-extrabold text-gray-500 password-dots w-full"
                                    data-password="<?= htmlspecialchars(dechiffrerMotDePasse($mdp['mot_de_passe_chiffre'])); ?>"
                                    id="password-<?= $mdp['id']; ?>">
                                    <?= str_repeat('•', 12); ?>
                                </div>
                                <div class="flex items-center space-x-2 mt-2">
                                    <button class="text-[#1a6fa3] hover:text-blue-700 toggle-password"
                                        data-id="<?= $mdp['id']; ?>">
                                        <i class="fa-solid fa-eye" id="toggle-password-<?= $mdp['id']; ?>"></i>
                                    </button>
                                    <form action="delete.php" method="POST" class="inline">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="id" value="<?= $mdp['id']; ?>">
                                        <button type="submit" class="text-red-500 hover:text-red-700">
                                            <i class="fa-sharp fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-gray-400">Aucun mot de passe enregistré.</p>
                    <?php endif; ?>
                </div>
            </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const longueurSlider = document.getElementById('longueur');
            const longueurValue = document.getElementById('longueur-value');

            longueurSlider.addEventListener('input', function () {
                longueurValue.textContent = this.value;
            });

            document.querySelectorAll('.toggle-password').forEach(button => {
                button.addEventListener('click', () => {
                    const passwordId = button.dataset.id;
                    const togglePasswordIcon = document.getElementById('toggle-password-' + passwordId);
                    const passwordDiv = document.getElementById('password-' + passwordId);
                    const password = passwordDiv.dataset.password;
                    if (passwordDiv.textContent === '••••••••••••') {
                        passwordDiv.textContent = password;
                        togglePasswordIcon.classList.replace('fa-eye', 'fa-eye-slash');
                    } else {
                        passwordDiv.textContent = '••••••••••••';
                        togglePasswordIcon.classList.replace('fa-eye-slash', 'fa-eye');
                    }
                });
            });
        });
    </script>
</body>

</html>