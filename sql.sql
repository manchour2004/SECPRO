
-- Table pour la gestion OAuth
CREATE TABLE oauth_utilisateurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fournisseur ENUM('google', 'github', 'linkedin') NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    nom VARCHAR(100),
    prenom VARCHAR(100),
    photo_profil VARCHAR(255),
);

-- Table pour stocker les mots de passe sécurisés
CREATE TABLE mots_de_passe (
    id INT AUTO_INCREMENT PRIMARY KEY,
    url_site VARCHAR(255), 
    nom_utilisateur VARCHAR(100), 
    mot_de_passe_chiffre VARCHAR(255) NOT NULL, 
    FOREIGN KEY (utilisateur_id) REFERENCES oauth_utilisateurs(id),
);
