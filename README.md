This project uses a MySQL database named projet, created and managed using phpMyAdmin. The database is connected to the PHP backend using:

require_once 'cnx.php';

->Tables Overview
The database consists of the following core tables:

etudiants
Stores student information.

CREATE TABLE etudiants (
    id_etudiant INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(50) NOT NULL,
    prenom VARCHAR(50) NOT NULL,
    annee_etude VARCHAR(20) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    filiere VARCHAR(100) NOT NULL
);

projets
Contains projects submitted by students.

CREATE TABLE projets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(255),
    description TEXT,
    categorie VARCHAR(100),
    type_projet ENUM('stage', 'module'),
    id_etudiant INT,
    date_soumission DATETIME DEFAULT CURRENT_TIMESTAMP,
    validé BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (id_etudiant) REFERENCES etudiants(id)
);

livrables
Stores uploaded files related to each project.

CREATE TABLE livrables (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_projet INT,
    nom_fichier VARCHAR(255),
    type_fichier VARCHAR(50),
    chemin_fichier VARCHAR(255),
    FOREIGN KEY (id_projet) REFERENCES projets(id)
);

commentaires
Stores comments on projects from students, professors, or supervisors.

CREATE TABLE commentaires (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    id_projet INT(11) NOT NULL,
    contenu TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    date_commentaire DATETIME DEFAULT CURRENT_TIMESTAMP,
    auteur_type ENUM('professeur', 'encadrant', 'etudiant') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    auteur_id INT(11) NOT NULL,
    FOREIGN KEY (id_projet) REFERENCES projets(id) ON DELETE CASCADE
);
