USE my_database;

-- Deux utilisateurs (le mot de passe est fictif pour l'instant)
INSERT INTO utilisateur (pseudo, email, mot_de_passe, role, est_valide, humeur, date_creation)
VALUES ('amel', 'amel@test.ch', 'test', 'admin', TRUE, 'Sur un nuage', NOW()),
       ('alizee', 'alizee@test.ch', 'test', 'membre', TRUE, 'En mode Frutiger Aero', NOW());

-- Deux articles écrits par l'admin (id 1)
INSERT INTO article (titre, contenu, image_couverture, date_publication, id_categorie, id_auteur)
VALUES ('Le retour du Y2K', 'Les tendances des années 2000 reviennent...', 'y2k.jpg', NOW(), 1, 1),
       ('Tokyo en 3 jours', 'Itinéraire pour découvrir Tokyo...', 'tokyo.jpg', NOW(), 2, 1);

-- Un quiz avec deux aesthetics possibles
INSERT INTO quiz (titre, description) VALUES ('Quel aesthetic es-tu ?', 'Découvre ton style des années 2000');

INSERT INTO aesthetic (nom, description, id_quiz)
VALUES ('Frutiger Aero', 'Bulles, ciel bleu et optimisme', 1),
       ('Y2K', 'Paillettes, rose et strass', 1);

-- Une question avec deux réponses
INSERT INTO question (texte, ordre, id_quiz) VALUES ('Ta couleur préférée ?', 1, 1);

INSERT INTO reponse (texte, id_question, id_aesthetic)
VALUES ('Bleu ciel', 1, 1),
       ('Rose bonbon', 1, 2);

-- Deux musiques et une image
INSERT INTO musique (titre, artiste, genre, lien)
VALUES ('Toxic', 'Britney Spears', 'Pop', 'LOREZ1kAm5c'),
       ('Crazy in Love', 'Beyoncé', 'R&B', 'ViwtNLUqkMY');

INSERT INTO image (titre, fichiergit status, id_categorie) VALUES ('Tenue Y2K', 'tenue.jpg', 1);

-- Des favoris et un résultat de quiz pour Alizée (id 2)
INSERT INTO musique_favorite (id_utilisateur, id_musique, date_ajout) VALUES (2, 1, NOW());
INSERT INTO image_favorite (id_utilisateur, id_image, date_ajout) VALUES (2, 1, NOW());
INSERT INTO resultat_quiz (date, id_utilisateur, id_quiz, id_aesthetic) VALUES (NOW(), 2, 1, 1);
