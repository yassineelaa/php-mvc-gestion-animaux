<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json; charset=utf-8");



ini_set('default_charset', 'UTF-8');


if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

set_include_path("./src");
session_start();

require_once("src/model/AnimalStorageMySQL.php");
require_once("src/view/JSONView.php");
require_once("src/control/Controller.php");
require_once("src/Router.php");
require_once('/users/22011193/private/mysql_config.php');

try {
    // Connexion à la base de données
    $pdo = new PDO(
        "mysql:host=" . MYSQL_HOST . ";dbname=" . MYSQL_DB . ";charset=utf8",
        MYSQL_USER,
        MYSQL_PASSWORD
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Instanciation du stockage d'animaux
    $animalStorage = new AnimalStorageMySQL($pdo);

    // Vérification des paramètres de l'URL
    if (isset($_GET['collection']) && $_GET['collection'] === 'animaux') {
        $view = new JSONView();

        // Si un ID est spécifié, retourne les détails de l'animal
        if (isset($_GET['id'])) {
            $animal = $animalStorage->read($_GET['id']);
            if ($animal) {
                $view->renderJSON([
                    "nom" => $animal->getName(),
                    "espece" => $animal->getSpecies(),
                    "age" => $animal->getAge()
                ]);
            } else {
                http_response_code(404); // Animal non trouvé
                $view->renderJSON(["error" => "Animal not found"]);
            }
        } else {
            // Sinon, retourne la liste complète des animaux
            $animals = $animalStorage->readAll();
            $result = [];
            foreach ($animals as $id => $animal) {
                $result[] = ["id" => $id, "nom" => $animal->getName()];
            }
            $view->renderJSON($result);
        }
    } else {
        // Si collection n'est pas spécifiée
        http_response_code(400); // Mauvaise requête
        echo json_encode(["error" => "Collection non spécifiée."]);
    }

} catch (Exception $e) {
    // En cas d'erreur
    http_response_code(500); // Erreur serveur
    echo json_encode(["error" => $e->getMessage()]);
}
?>
