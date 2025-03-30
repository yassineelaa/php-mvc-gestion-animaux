# php-mvc-gestion-animaux
Gestion des Animaux - Application PHP

Ce projet est une application web en PHP permettant de gérer une base de données d'animaux. Il a été développé dans le cadre d’un projet universitaire avec l'architecture MVCR (Modèle, Vue, Contrôleur, Routeur) pour assurer une meilleure modularité, une maintenance facilitée et une évolutivité du système.
Objectif

Ce site permet aux utilisateurs d’ajouter, modifier et supprimer des animaux dans une base de données. L'application offre aussi une API pour interagir avec les informations des animaux, en respectant des normes de sécurité comme la validation des fichiers et la protection contre les attaques courantes.
Fonctionnalités principales

    Gestion des animaux : Ajouter, modifier et supprimer des animaux.

    Informations complètes sur les animaux : Nom, âge, espèce et chemin d'accès de l'image.

    Téléversement sécurisé d'images : Les utilisateurs peuvent uploader des images pour chaque animal avec une validation des types de fichiers (PNG, JPEG).

    Sécurité renforcée : Protection contre les attaques telles que les injections SQL, XSS, et CSRF.

    API : Récupération des données sur les animaux via une API.

Prérequis

Avant de démarrer, assurez-vous de disposer des éléments suivants :

    Un serveur web fonctionnel comme Apache ou Nginx.

    PHP version 7.4 ou supérieure.

    MySQL pour la gestion de la base de données.

Installation
1. Récupérer le projet

Clonez ce dépôt depuis GitHub sur votre machine locale :

git clone https://github.com/tonutilisateur/nom_du_projet.git

Allez dans le répertoire du projet :

cd nom_du_projet

2. Configurer la connexion MySQL

Dans le fichier mysql_config.php, modifiez les paramètres suivants pour les adapter à votre configuration :

<?php
define('MYSQL_HOST', 'mysql:host=votre_hote_mysql;'); 
define('MYSQL_PORT', 'port=votre_port;'); 
define('MYSQL_DB', 'dbname=votre_db'); 
define('MYSQL_USER', 'votre_utilisateur');  
define('MYSQL_PASSWORD', 'votre_motdepasse'); 
?>

3. Créer la table MySQL

Créez la table dans votre base de données en exécutant le fichier SQL fourni dans le projet, avec la structure suivante :

CREATE TABLE animaux (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    species VARCHAR(255) NOT NULL,
    age INT NOT NULL,
    imagePath VARCHAR(255) NOT NULL
);

4. Lancer le projet

Une fois la configuration terminée, vous pouvez héberger et tester le projet sur votre serveur local ou sur un service d’hébergement comme XAMPP, WAMP ou un autre.
API

Les appels API doivent être effectués avec les paramètres suivants :

    api.php?collection=animaux : pour récupérer la liste complète des animaux avec seulement le nom et l'ID.

    api.php?collection=animaux&id=1 : pour obtenir toutes les informations d'un animal avec l'ID spécifié.

Sécurité

L'application inclut des fonctionnalités de sécurité essentielles :

    Validation des fichiers uploadés : Seuls les fichiers avec des extensions sécurisées (PNG, JPEG) peuvent être téléchargés.

    Prévention des injections SQL et XSS : Les données d'entrée sont filtrées pour empêcher toute tentative de manipulation de la base de données.

    Protection contre les attaques CSRF : Des mesures sont mises en place pour éviter les attaques par falsification de requêtes inter-sites.

Notes supplémentaires

    Les routes du projet peuvent être personnalisées via le fichier router.php.

    Assurez-vous que toutes les modifications du modèle soient également répercutées dans la base de données et dans le fichier Model.php pour éviter toute incohérence.
