# Zoomy

Blog communautaire à l'esthétique des années 2000 (Skyblog, MSN, MySpace,
Frutiger Aero) avec des articles, des quiz de personnalité, des musiques du
moment et une galerie d'images.

> Projet réalisé dans le cadre du cours
> [Programmation serveur 2 (ProgServ2)](https://github.com/heig-vd-progserv-course/heig-vd-progserv2-course)
> à la [HEIG-VD](https://heig-vd.ch), année académique 2026-2027.

🌐 **Site en ligne :** <https://progserv2.amelontour.ch>

## Table des matières

- [Présentation du projet](#présentation-du-projet)
- [Membres du groupe](#Amel-et-Alizée)
- [Documentation du projet](#documentation-du-projet)
- [Technologies utilisées](#technologies-utilisées)
- [Structure du dépôt](#structure-du-dépôt)
- [Lancer le projet en local](#lancer-le-projet-en-local)
- [Déployer le projet sur Infomaniak](#déployer-le-projet-sur-infomaniak)
- [Organisation du travail](#organisation-du-travail)
- [Utilisation de l'IA](#utilisation-de-lia)
- [Conclusion](#conclusion)

## Présentation du projet

Le site propose :

- des **articles** classés par catégorie (Mode, Voyages, Design) ;
- des **quiz de personnalité** dont le résultat révèle un « aesthetic » ;
- une sélection de **musiques du moment** avec un lecteur intégré ;
- une **galerie d'images** d'inspiration.

Les visiteur·euses peuvent consulter tous les contenus publics. Avec un compte,
les membres peuvent passer les quiz, retrouver leurs résultats, mettre des
musiques et des images en favoris et personnaliser leur profil. Les
administrateur·ices gèrent l'ensemble des contenus.

Le détail des fonctionnalités se trouve dans le
[cahier des charges](./cahier-charges.md).

## Membres du groupe

| Membre | Rôles et contributions |
| ------ | ---------------------- |
| Amel Bellal | base de données, déploiement, page des articles, relecture des PR)_ |
| Alizée de Landerset | maquette, page des musiques, relecture des PR)_ |

## Documentation du projet

- [Cahier des charges](./cahier-charges.md)
- [Maquette Figma](https://scuff-global-39594034.figma.site/)
- Schéma de la base de données :
  - [Modèle conceptuel (MCD)](./docs/mcd.png)
  - [Modèle logique (MLD)](./docs/mld.png)
  - [Modèle physique (MPD)](./docs/mpd(1).png)
  - [Script de création des tables](./src/db/script.sql)
  - [Données de test](./src/db/donnees-test.sql)

## Technologies utilisées

- [PHP 8.5](https://www.php.net/), sans framework, en programmation orientée
  objet
- [MariaDB](https://mariadb.org/) pour la base de données
- [Docker Compose](https://docs.docker.com/compose/) pour l'environnement de
  développement (Apache, MariaDB, phpMyAdmin et Mailpit), fourni par le
  [template du cours](https://github.com/heig-vd-progserv-course/heig-vd-progserv2-course-php-template)
- [Infomaniak](https://www.infomaniak.com/) pour l'hébergement en production

## Structure du dépôt

```text
.
├── docs/                      Diagrammes de la base de données (MCD, MLD, MPD)
├── src/
│   ├── classes/               Classes PHP (chargées automatiquement)
│   │   ├── Database.php       Connexion à la base de données
│   │   └── DatabaseInterface.php
│   ├── config/
│   │   └── database.ini.example   Modèle du fichier de configuration
│   ├── db/
│   │   ├── script.sql         Création des tables
│   │   └── donnees-test.sql   Données de test
│   ├── public/                Pages accessibles depuis le navigateur
│   │   └── index.php          Page d'accueil
│   └── utils/
│       └── autoloader.php     Chargement automatique des classes
├── cahier-charges.md          Cahier des charges
├── compose.yaml               Configuration Docker Compose
└── README.md                  Ce fichier
```

Seul le dossier `src/public` est accessible depuis le navigateur. Les autres
dossiers (configuration, classes, scripts SQL) ne sont pas publics.

## Lancer le projet en local

### Prérequis

- [Docker Desktop](https://www.docker.com/products/docker-desktop/)
- [Visual Studio Code](https://code.visualstudio.com/) avec l'extension
  [Dev Containers](https://marketplace.visualstudio.com/items?itemName=ms-vscode-remote.remote-containers)

### Étapes

1. Cloner le dépôt et ouvrir le dossier du projet (celui qui contient ce
   fichier) dans Visual Studio Code, puis accepter d'ouvrir le projet dans le
   conteneur de développement.
2. Créer le fichier de configuration en copiant le modèle :

   ```bash
   cp src/config/database.ini.example src/config/database.ini
   ```

   Les valeurs par défaut correspondent à la base de données Docker et n'ont
   pas besoin d'être modifiées. Ce fichier est ignoré par Git.

3. Démarrer les services Docker :

   ```bash
   docker compose up -d
   ```

4. Ouvrir phpMyAdmin sur <http://localhost:9090> (utilisateur `username`, mot
   de passe `password`), sélectionner la base `my_database`, puis exécuter dans
   l'onglet SQL :
   1. le script `src/db/script.sql` (création des tables) ;
   2. le script `src/db/donnees-test.sql` (données de test, facultatif).
5. Ouvrir le site sur <http://localhost:8080/src/public/>.

Les e-mails envoyés par l'application en local peuvent être consultés avec
Mailpit sur <http://localhost:8025>.

Pour arrêter les services :

```bash
docker compose down
```

## Déployer le projet sur Infomaniak

1. Dans le Manager Infomaniak, créer une base de données MariaDB et un
   utilisateur avec les droits de lecture, d'écriture et d'administration.
   Noter l'hôte, le nom de la base, l'utilisateur et le mot de passe.
2. Ouvrir le phpMyAdmin d'Infomaniak, sélectionner la base et exécuter le
   script `src/db/script.sql`, en remplaçant d'abord la ligne
   `USE my_database;` par le nom de la base Infomaniak.
3. Ajouter un site PHP sur l'hébergement, choisir la dernière version de PHP
   (onglet « PHP | Apache ») et définir `src/public` comme répertoire racine
   (onglet « Général » des paramètres avancés).
4. Téléverser le dossier `src` dans le dossier du site avec un client SFTP
   (par exemple [FileZilla](https://filezilla-project.org/)).
5. Sur le serveur, modifier le fichier `src/config/database.ini` avec les
   informations de connexion d'Infomaniak.
6. Ouvrir l'adresse du site et vérifier que la page d'accueil s'affiche avec les
   catégories.

> [!WARNING]
>
> Lors des mises à jour suivantes, ne pas renvoyer le fichier
> `src/config/database.ini` local sur le serveur : il écraserait les
> identifiants d'Infomaniak.

## Organisation du travail

Le travail est organisé avec GitHub :

- chaque tâche fait l'objet d'une **issue** ;
- chaque issue est réalisée sur une **branche** dédiée ;
- le travail est intégré à `main` par une **pull request**, relue et approuvée
  par l'autre membre du groupe ;
- la branche `main` est protégée : une approbation est obligatoire avant toute
  fusion.

## Utilisation de l'IA

| Date | Outil | Utilisation | Pourquoi |
| ---- | ----- | ----------- | -------- |
| 11.10.2026 | Claude | Aide à la mise en place du fichier de configuration et au déploiement sur Infomaniak | Suivre pas à pas les étapes des cours ProgServ1 et ProgServ2 |
| 14.10.2026 | Claude | Rédaction des données de test et des requêtes SQL de vérification | Tester la base et les requêtes avant de coder en PHP |
| 14.10.2026 | Claude | Aide à la création de la classe `Database` et de l'autoloader, d'après la solution de l'exercice 1 du chapitre 04 du cours | Appliquer la programmation orientée objet à notre projet |
| 14.10.2026 | Claude | Restructuration du README | Présenter le projet et documenter son installation |

Le code généré ou proposé par l'IA a été relu, compris et testé par l'équipe
avant d'être intégré au projet.

## Conclusion

_À compléter en fin de projet : bilan, difficultés rencontrées et solutions
apportées._
