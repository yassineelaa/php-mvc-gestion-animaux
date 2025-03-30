<?php

require_once("view/View.php");
require_once("view/JSONView.php");
require_once("control/Controller.php");

class Router
{
    public function main($storage, $viewType = "HTMLView")
    {
        if ($viewType === "JSONView") {
            $view = new JSONView();

            if (isset($_GET['collection']) && $_GET['collection'] === 'animaux') {
                if (isset($_GET['id'])) {
                    $animal = $storage->read($_GET['id']);
                    if ($animal) {
                        $view->renderJSON([
                            "nom" => $animal->getName(),
                            "espece" => $animal->getSpecies(),
                            "age" => $animal->getAge()
                        ]);
                    } else {
                        $view->renderJSON(["error" => "Animal not found"]);
                        http_response_code(404);
                    }
                } else {
                    $animals = $storage->readAll();
                    $result = [];
                    foreach ($animals as $id => $animal) {
                        $result[] = ["id" => $id, "nom" => $animal->getName()];
                    }
                    $view->renderJSON($result);
                }
                return;
            }
        }


        $feedback = $_SESSION['feedback'] ?? null;
        unset($_SESSION['feedback']);

        $view = new View($this, $feedback);
        $controller = new Controller($view, $storage); 
        
        if (isset($_GET['action'])) {
            switch ($_GET['action']) {
                case 'nouveau':
                    $controller->createNewAnimal();
                    break;

                case 'sauverNouveau':
                    $controller->saveNewAnimal($_POST);
                    break;

                case 'liste':
                    $controller->showList();
                    break;
                    
                case 'delete':
                        $controller->deleteAnimal($_GET['id']);
            
                    break;
                default:
                    $view->title = "Erreur";
                    $view->content = "<p>Action inconnue : " . htmlspecialchars($_GET['action']) . "</p>";
                    break;
            }
        } elseif (isset($_GET['id'])) {
            $controller->showInformation($_GET['id']);
        } else {
            $view->title = "Accueil";
            $view->content = "<p>Bienvenue sur la page d'accueil !</p>";
        }
    

        
        $view->render();
    }

    public function getAnimalURL($id)
    {
        return "site.php?id=" . urlencode($id);
    }

    public function getHomePageURL()
    {
        return "site.php";
    }

    public function getAnimalListURL()
    {
        return "site.php?action=liste";
    }

    public function getAnimalCreationURL()
    {
        return "site.php?action=nouveau";
    }

    public function getAnimalSaveURL()
    {
        return "site.php?action=sauverNouveau";
    }

    public function getAnimalDeleteURL($id)
    {
        return "site.php?action=delete&id=" . urlencode($id);
    }

    public function POSTredirect($url, $feedback)
    {
        $_SESSION['feedback'] = $feedback;
        header("Location: $url", true, 303);
        exit();
    }

}

?>
