<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SECPRO - Sécurité des Mots de Passe</title>
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
        .input-focus:focus {
            border-color: #1a6fa3;
            outline: none;
        }
    </style>
</head>
<body class="min-h-screen text-gray-300 bg-gray-900">
    <div id="acc"></div>
    <div class="px-4">
        <header class="hadow-lg w-full sticky top-0 z-50 transition-all duration-300" >
            <div class="flex justify-between items-center px-4 py-2 bg-gray-800 bg-opacity-75 my-4 rounded-full shadow-md">
                <h1 class="text-3xl font-bold text-[#1a6fa3]">SECPRO</h1>
                <nav>
                    <ul class="flex space-x-4">
                        <li><a href="#acc" class="hover:text-[#1a6fa3]">Accueil</a></li>
                        <li><a href="#fonctionalite" class="hover:text-[#1a6fa3]">Fonctionalité</a></li>
                        <li><a href="#propos" class="hover:text-[#1a6fa3]">A propos</a></li>
                    </ul>
                </nav>
            </div>
        </header>

        <main class="py-20">
            <section class="text-center mb-20" id="fonctionalite">
                <h1 class="text-5xl font-bold text-white mb-4">Sécurisez vos comptes en ligne</h1>
                <p class="text-xl mb-8">Générez des mots de passe forts et uniques pour chaque site web</p>
                <div class="space-x-4">
                    <a href="#conn" class="btn-primary  text-white font-bold py-3 px-6 rounded-full text-lg transition duration-300">
                        Commencer maintenant
                    </a>
                 
                </div>
            </section>

            <section class="grid md:grid-cols-3 gap-8 mb-20 px-4" id="">
                <div class="bg-gray-800 bg-opacity-75 p-6 rounded-lg text-center">
                    <i class="fas fa-lock text-4xl text-primary mb-4"></i>
                    <h2 class="text-2xl font-bold text-white mb-2">Sécurité renforcée</h2>
                    <p>Créez des mots de passe complexes pour une protection optimale de vos comptes.</p>
                </div>
                <div class="bg-gray-800 bg-opacity-75 p-6 rounded-lg text-center">
                    <i class="fas fa-sync-alt text-4xl text-green-500 mb-4"></i>
                    <h2 class="text-2xl font-bold text-white mb-2">Générateur intelligent</h2>
                    <p>Utilisez notre algorithme avancé pour générer des mots de passe uniques.</p>
                </div>
                <div class="bg-gray-800 bg-opacity-75 p-6 rounded-lg text-center">
                    <i class="fas fa-shield-alt text-4xl text-yellow-500 mb-4"></i>
                    <h2 class="text-2xl font-bold text-white mb-2">Stockage sécurisé</h2>
                    <p>Gardez vos mots de passe en sécurité avec notre système de stockage chiffré.</p>
                </div>
            </section>

            <section class="text-center mb-20" id="propos">
                <h2 class="text-3xl font-bold text-white mb-4">Pourquoi choisir SECPRO ?</h2>
                <p class="text-xl mb-8">Notre solution offre une sécurité de pointe pour tous vos comptes en ligne</p>
                <ul class="grid md:grid-cols-2 gap-4 text-left max-w-2xl mx-auto">
                    <li class="flex items-center">
                        <i class="fas fa-check-circle text-primary mr-2"></i>
                        Génération de mots de passe forts
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-check-circle text-primary mr-2"></i>
                        Stockage sécurisé des mots de passe
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-check-circle text-primary mr-2"></i>
                        Interface utilisateur intuitive
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-check-circle text-primary mr-2"></i>
                        Synchronisation multi-appareils
                    </li>
                </ul>
            </section>

            <section class="text-center" id="conn">
                <h2 class="text-3xl font-bold text-white mb-4">Prêt à sécuriser vos comptes ?</h2>
                <p class="text-xl mb-8">Commencez dès maintenant à générer des mots de passe sûrs et uniques</p>
                <div class="space-x-4">
                    <a href="connexion.php" class="btn-primary text-white font-bold py-3 px-6 rounded-full text-lg transition duration-300">
                        Se connecter
                    </a>
                </div>
            </section>
        </main>

        <footer class="py-6 text-center">
            <p>&copy; 2023 SECPRO. Tous droits réservés.</p>
        </footer>
    </div>
</body>
</html>