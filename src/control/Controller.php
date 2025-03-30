<?php

require_once("view/View.php");
require_once("model/Animal.php");
require_once("model/AnimalBuilder.php");
require_once("model/AnimalStorage.php");


class Controller
{
    private $view;
    private $storage;

    public function __construct(View $view, AnimalStorage $storage)
    {
        $this->view = $view;
        $this->storage = $storage; 
    }

    public function showInformation($id)
    {
        
        $animal = $this->storage->read($id);
        if ($animal) {
            $this->view->prepareAnimalPage($animal);
        } else {
            $this->view->prepareUnknownAnimalPage();
        }
    }

    public function showList()
    {
        $this->view->prepareListPage($this->storage->readAll());
    }



    public function createNewAnimal()
    {
        $builder = new AnimalBuilder();
        $this->view->prepareAnimalCreationPage($builder);
    }



    public function saveNewAnimal(array $data)
    {
        $builder = new AnimalBuilder($data);
    
        if (!$builder->isValid()) {
            $this->view->prepareAnimalCreationPage($builder);
            return;
        }
    
        $animal = $builder->createAnimal();
    
        // Gestion de l'upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $tmpName = $_FILES['image']['tmp_name'];
            $fileName = basename($_FILES['image']['name']);
            $fileExt = pathinfo($fileName, PATHINFO_EXTENSION);
    
            // Vérifier que le fichier est une image
            if (!in_array($fileExt, ['jpg', 'jpeg', 'png', 'gif']) || !exif_imagetype($tmpName)) {
                $this->view->prepareAnimalCreationPage($builder);
                return;
            }
    
            // Créer un nom unique pour le fichier
            $newFileName = uniqid() . '.' . $fileExt;
            $destination = __DIR__ . "/../../uploads/" . $newFileName;
    
            if (move_uploaded_file($tmpName, $destination)) {
                $animal = new Animal(
                    $animal->getName(),
                    $animal->getSpecies(),
                    $animal->getAge(),
                    "uploads/" . $newFileName
                );
            }
        }
    
        $id = $this->storage->create($animal);
        $this->view->displayAnimalCreationSuccess($id);
    }


    public function deleteAnimal($id)
    {
        if ($this->storage->delete($id)) {
            $this->view->redirectToList("L'animal a été supprimé avec succès !");
        } else {
            $this->view->redirectToList("Erreur : Impossible de supprimer l'animal.");
        }
    }

    


}

?>