<?php

set_include_path("./src");

session_start();

require_once('/users/22011193/private/mysql_config.php');
require_once("src/model/Animal.php");
require_once("src/Router.php");
require_once("src/model/AnimalStorage.php");
require_once("src/model/AnimalStorageSession.php");
require_once("src/model/AnimalStorageMySQL.php");





try {
    // creer une connexion PDO
    $pdo = new PDO(
        "mysql:host=" . MYSQL_HOST . ";dbname=" . MYSQL_DB . ";charset=utf8",
        MYSQL_USER,
        MYSQL_PASSWORD
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
    $animalStorage = new AnimalStorageMySQL($pdo);

    $router = new Router();
    $router->main($animalStorage);

} catch (Exception $e) {
    // gestion des erreurs
    echo "<p>Erreur : " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p>Vérifiez vos paramètres de connexion ou vos droits d'accès.</p>";
}
?>