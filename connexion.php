<?php
require('config.php');
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SECPRO - Connexion</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-image: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('img/rm373batch4-15.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            backdrop-filter: blur(5px);
        }

        .btn-primary {
            background-color: #2b94da79;
            color: #E0F0FF;
        }

        .text-primary {
            color: #134e6f;
        }

        .btn-primary:hover {
            background-color: #1a6fa3;
        }
    </style>
</head>

<body class="min-h-screen text-gray-300 bg-gray-900 flex flex-col">
    <div class="flex-grow flex items-center justify-center px-4">
        <div class="w-full max-w-md">
            <header class="text-center mb-8">
                <h1 class="text-4xl font-bold text-[#1a6fa3]">SECPRO</h1>
            </header>

            <div class="bg-gray-800 bg-opacity-75 p-8 rounded-lg shadow-lg">
                <p class="text-center mb-6">Connectez-vous avec :</p>
                <div class="space-y-4">
                    <a href="https://accounts.google.com/o/oauth2/v2/auth?scope=email+profile&access_type=online&response_type=code&redirect_uri=<?= urlencode(/*Url de redirection */)?>&client_id=<?= GOOGLE_ID ?>"
                        class="btn-primary w-full flex items-center justify-center py-3 px-4 rounded-md text-lg transition duration-300">
                        <i class="fab fa-google mr-3 text-xl"></i>
                        Se connecter avec Google
                    </a>
                    <a href="https://www.linkedin.com/oauth/v2/authorization?response_type=code&client_id=<?=LINKEDIN_ID?>&redirect_uri=<?=urlencode(/*Url de redirection */)?>&state=foobar&scope=openid+email+profile"
                        class="btn-primary w-full flex items-center justify-center py-3 px-4 rounded-md text-lg transition duration-300">
                        <i class="fab fa-linkedin mr-3 text-xl"></i>
                        Se connecter avec LinkedIn
                    </a>
                    <a href="https://github.com/login/oauth/authorize?client_id=<?= urlencode(GITHUB_ID) ?>&redirect_uri=<?= urlencode(/*Url de redirection */) ?>&scope=<?= urlencode('user user:email') ?>"
                        class="btn-primary w-full flex items-center justify-center py-3 px-4 rounded-md text-lg transition duration-300">
                        <i class="fab fa-github mr-3 text-xl"></i>
                        Se connecter avec GitHub
                    </a>

                </div>
            </div>
        </div>
    </div>

    <footer class="py-6 text-center">
        <p>&copy; 2024 SECPRO. Tous droits réservés.</p>
    </footer>
</body>

</html>