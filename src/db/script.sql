USE my_database;

CREATE TABLE utilisateur (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pseudo VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    mot_de_passe VARCHAR(255) NOT NULL,
    role ENUM('membre', 'admin') NOT NULL DEFAULT 'membre',
    est_valide BOOLEAN NOT NULL DEFAULT FALSE,
    token_validation VARCHAR(100),
    token_reinitialisation VARCHAR(100),
    avatar VARCHAR(255),
    humeur VARCHAR(150),
    description TEXT,
    date_creation DATETIME NOT NULL
);

CREATE TABLE categorie (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(50) UNIQUE NOT NULL
);

CREATE TABLE article (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(255) NOT NULL,
    contenu TEXT NOT NULL,
    image_couverture VARCHAR(255),
    date_publication DATETIME NOT NULL,
    id_categorie INT NOT NULL,
    id_auteur INT NOT NULL,
    FOREIGN KEY (id_categorie) REFERENCES categorie (id),
    FOREIGN KEY (id_auteur) REFERENCES utilisateur (id)
);

CREATE TABLE quiz (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(255) NOT NULL,
    description TEXT,
    image VARCHAR(255)
);

CREATE TABLE aesthetic (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    description TEXT,
    image VARCHAR(255),
    id_quiz INT NOT NULL,
    FOREIGN KEY (id_quiz) REFERENCES quiz (id)
);

CREATE TABLE question (
    id INT AUTO_INCREMENT PRIMARY KEY,
    texte VARCHAR(255) NOT NULL,
    ordre INT NOT NULL,
    id_quiz INT NOT NULL,
    FOREIGN KEY (id_quiz) REFERENCES quiz (id)
);

CREATE TABLE reponse (
    id INT AUTO_INCREMENT PRIMARY KEY,
    texte VARCHAR(255) NOT NULL,
    id_question INT NOT NULL,
    id_aesthetic INT NOT NULL,
    FOREIGN KEY (id_question) REFERENCES question (id),
    FOREIGN KEY (id_aesthetic) REFERENCES aesthetic (id)
);

CREATE TABLE resultat_quiz (
    id INT AUTO_INCREMENT PRIMARY KEY,
    date DATETIME NOT NULL,
    id_utilisateur INT NOT NULL,
    id_quiz INT NOT NULL,
    id_aesthetic INT NOT NULL,
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateur (id),
    FOREIGN KEY (id_quiz) REFERENCES quiz (id),
    FOREIGN KEY (id_aesthetic) REFERENCES aesthetic (id)
);

CREATE TABLE musique (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(255) NOT NULL,
    artiste VARCHAR(255) NOT NULL,
    genre VARCHAR(100),
    pochette VARCHAR(255),
    lien VARCHAR(255) NOT NULL
);

CREATE TABLE image (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(255) NOT NULL,
    fichier VARCHAR(255) NOT NULL,
    id_categorie INT NOT NULL,
    FOREIGN KEY (id_categorie) REFERENCES categorie (id)
);

CREATE TABLE musique_favorite (
    id_utilisateur INT NOT NULL,
    id_musique INT NOT NULL,
    date_ajout DATETIME NOT NULL,
    PRIMARY KEY (id_utilisateur, id_musique),
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateur (id),
    FOREIGN KEY (id_musique) REFERENCES musique (id)
);

CREATE TABLE image_favorite (
    id_utilisateur INT NOT NULL,
    id_image INT NOT NULL,
    date_ajout DATETIME NOT NULL,
    PRIMARY KEY (id_utilisateur, id_image),
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateur (id),
    FOREIGN KEY (id_image) REFERENCES image (id)
);

INSERT INTO categorie (nom) VALUES ('Mode'), ('Voyages'), ('Design');

