USE my_database;

-- Deux utilisateurs (le mot de passe est fictif pour l'instant)
INSERT INTO utilisateur (pseudo, email, mot_de_passe, role, est_valide, humeur, date_creation)
VALUES ('amel', 'amel@test.ch', 'test', 'admin', TRUE, 'Sur un nuage', NOW()),
       ('alizee', 'alizee@test.ch', 'test', 'membre', TRUE, 'En mode Frutiger Aero', NOW());

-- Deux articles écrits par l'admin (id 1)
INSERT INTO article (titre, contenu, image_couverture, date_publication, id_categorie, id_auteur)
VALUES ('Le retour du Y2K', 'Les tendances des années 2000 reviennent...', 'y2k.jpg', NOW(), 1, 1),
       ('Tokyo en 3 jours', 'Itinéraire pour découvrir Tokyo...', 'tokyo.jpg', NOW(), 2, 1);

-- Le quiz
INSERT INTO quiz (titre, description, image) VALUES ('Quel aesthetic es-tu ?', 'Découvre ton style des années 2000', 'quiz-aesthetic.jpg');

-- Les 6 aesthetics du quiz
INSERT INTO aesthetic (id, nom, description, image, id_quiz) VALUES
(1, 'Frutiger Aero', 'Bulles, ciel bleu, herbe verte et interfaces brillantes : un optimisme technologique tout en douceur.', 'aesthetic-frutiger-aero.jpg', 1),
(2, 'Y2K Tuscan Luxe', 'Tons dorés, bijoux, bougies parfumées et maison à l''italienne : le glamour chaleureux des années 2000.', 'aesthetic-y2k.jpg', 1),
(3, 'Cyber Trance', 'Chrome, bleu électrique et téléphones à clapet : un futur brillant, rapide et terriblement cool.', 'aesthetic-cyber-trance.jpg', 1),
(4, 'DORFic', 'Orange vif, formes abstraites, notes de musique et poissons rouges : l''art numérique joyeux du milieu des années 2000.', 'aesthetic-dorfic.jpg', 1),
(5, 'Frutiger Metro', 'Rose fuchsia, silhouettes qui dansent, étoiles et enceintes : l''énergie de la ville et de la fête.', 'aesthetic-frutiger-metro.jpg', 1),
(6, 'Wacky Pomo', 'Couleurs qui jurent, formes bizarres et humour absurde : le chaos créatif des années 90.', 'aesthetic-wacky-pomo.jpg', 1);

-- Les 10 questions
INSERT INTO question (id, texte, ordre, id_quiz) VALUES
(1, 'Ta couleur préférée ?', 1, 1),
(2, 'Ton fond d''écran idéal ?', 2, 1),
(3, 'L''objet que tu ne quittes jamais ?', 3, 1),
(4, 'Ta soirée parfaite ?', 4, 1),
(5, 'La musique dans tes écouteurs ?', 5, 1),
(6, 'Ton accessoire signature ?', 6, 1),
(7, 'Ta chambre idéale ?', 7, 1),
(8, 'Ta destination de rêve ?', 8, 1),
(9, 'Ton animal fétiche ?', 9, 1),
(10, 'Ton émoticône MSN préférée ?', 10, 1);

-- Les réponses (aesthetic : 1 = Frutiger Aero, 2 = Y2K Tuscan Luxe, 3 = Cyber Trance,
--    4 = DORFic, 5 = Frutiger Metro, 6 = Wacky Pomo)
INSERT INTO reponse (texte, id_question, id_aesthetic) VALUES
('Bleu ciel et vert pomme', 1, 1),
('Doré et terracotta', 1, 2),
('Bleu électrique et argent chromé', 1, 3),
('Orange vitaminé', 1, 4),
('Rose fuchsia et noir', 1, 5),
('Toutes les couleurs en même temps', 1, 6),

('Une bulle d''eau sur une prairie ensoleillée', 2, 1),
('Un coucher de soleil sur une villa toscane', 2, 2),
('Des sphères en chrome qui flottent dans l''espace', 2, 3),
('Des vagues orange avec des notes de musique', 2, 4),
('Des silhouettes qui dansent sous des étoiles roses', 2, 5),
('Une spirale hypnotique et une télé qui sourit', 2, 6),

('Un lecteur MP3 transparent', 3, 1),
('Un sac de créateur', 3, 2),
('Un téléphone à clapet argenté', 3, 3),
('Une console de poche orange', 3, 4),
('Un casque avec de grosses basses', 3, 5),
('Une lampe à lave', 3, 6),

('Regarder les nuages au bord d''un lac', 4, 1),
('Un dîner aux chandelles dans une belle maison', 4, 2),
('Danser jusqu''au matin en club sous les néons bleus', 4, 3),
('Créer des montages sur l''ordinateur', 4, 4),
('Un concert en plein air avec tes amis', 4, 5),
('Une soirée jeux vidéo et dessins animés', 4, 6),

('Des sons relaxants avec des gouttes d''eau', 5, 1),
('Un slow langoureux des années 2000', 5, 2),
('De la trance qui fait vibrer', 5, 3),
('Une mélodie électro joyeuse', 5, 4),
('Un tube pop-rock à fond', 5, 5),
('Un générique de dessin animé complètement fou', 5, 6),

('Une souris d''ordinateur transparente', 6, 1),
('Des bracelets dorés empilés', 6, 2),
('Des lunettes futuristes argentées', 6, 3),
('Un bandeau orange dans les cheveux', 6, 4),
('Des étoiles brillantes sur la peau', 6, 5),
('Un appareil dentaire multicolore', 6, 6),

('Lumineuse, avec plein de plantes et un aquarium', 7, 1),
('Une coiffeuse avec des miroirs et des lumières dorées', 7, 2),
('Des néons bleus et des écrans partout', 7, 3),
('Des posters abstraits orange et jaunes', 7, 4),
('Des posters de groupes sur des murs roses', 7, 5),
('Des objets bizarres et des meubles de toutes les formes', 7, 6),

('Une île tropicale avec des dauphins', 8, 1),
('Une villa en Toscane', 8, 2),
('Tokyo la nuit', 8, 3),
('Un festival de musique en été', 8, 4),
('Une grande ville qui ne dort jamais', 8, 5),
('Un parc d''attractions complètement déjanté', 8, 6),

('Un papillon bleu', 9, 1),
('Un petit chien de sac à main', 9, 2),
('Un robot de compagnie', 9, 3),
('Un poisson rouge', 9, 4),
('Un chat noir', 9, 5),
('Un extraterrestre vert', 9, 6),

('Le soleil :)', 10, 1),
('Le cœur <3', 10, 2),
('Les lunettes de soleil 8-)', 10, 3),
('La note de musique ♪', 10, 4),
('L''étoile *', 10, 5),
('La langue tirée :P', 10, 6);

-- Deux musiques et une image
INSERT INTO musique (titre, artiste, genre, lien)
VALUES ('Toxic', 'Britney Spears', 'Pop', 'LOREZ1kAm5c'),
       ('Crazy in Love', 'Beyoncé', 'R&B', 'ViwtNLUqkMY');

INSERT INTO image (titre, fichier, id_categorie) VALUES ('Tenue Y2K', 'tenue.jpg', 1);

-- Des favoris et un résultat de quiz pour Alizée (id 2)
INSERT INTO musique_favorite (id_utilisateur, id_musique, date_ajout) VALUES (2, 1, NOW());
INSERT INTO image_favorite (id_utilisateur, id_image, date_ajout) VALUES (2, 1, NOW());
INSERT INTO resultat_quiz (date, id_utilisateur, id_quiz, id_aesthetic) VALUES (NOW(), 2, 1, 1);
