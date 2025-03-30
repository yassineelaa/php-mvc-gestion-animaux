<?php

class View
{
    public $title;
    public $content;
    public $menu;
    private $router;
    private $feedback;

    public function __construct(Router $router, $feedback)
    {
        $this->router = $router;
        $this->feedback = $feedback;
        $this->title = '';
        $this->content = '';
        $this->menu = [
            $this->router->getHomePageURL() => 'Accueil',
            $this->router->getAnimalListURL() => 'Liste des animaux',
            $this->router->getAnimalCreationURL() => 'Créer un animal',
        ];
    }

    public function render()
    {
        echo "<!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>{$this->title}</title>
            <link rel='stylesheet' href='css/style.css'> 
        </head>
        <body>
            <nav><ul>";
        foreach ($this->menu as $url => $text) {
            echo "<li><a href='" . $url . "'>" . $text . "</a></li>";
        }
        echo "</ul></nav>";
        
        if ($this->feedback) {
            echo "<div style='color: green; font-weight: bold; margin-bottom: 20px;'>{$this->feedback}</div>";
        }
        echo "<h1>{$this->title}</h1>
            <div>{$this->content}</div>
        </body>
        </html>";
    }

    public function prepareAnimalPage(Animal $animal)
    {
        $this->title = "Page sur " . htmlspecialchars($animal->getName());
        $this->content = '<p>' . htmlspecialchars($animal->getName()) . ' est un animal de l\'espèce ' .
                        htmlspecialchars($animal->getSpecies()) . ' et a ' . htmlspecialchars($animal->getAge()) . ' ans.</p>';

        if ($animal->getImage()) {
            $this->content .= '<p><img src="' . htmlspecialchars($animal->getImage()) . '" alt="Image de ' . htmlspecialchars($animal->getName()) . '" style="max-width: 300px; height: auto;"></p>';
        }


    }



    public function prepareUnknownAnimalPage()
    {
        $this->title = "Erreur";
        $this->content = "Animal inconnu.";
    }

    public function prepareListPage(array $animals)
    {
        $this->title = "Liste des animaux";
        $this->content = "<ul>";
        foreach ($animals as $id => $animal) {
            $url = $this->router->getAnimalURL($id); 
            $deleteUrl = $this->router->getAnimalDeleteURL($id);

             $this->content .= "<li>
                <a href='{$url}'>" . htmlspecialchars($animal->getName()) . "</a> 
                - <a href='{$deleteUrl}'>Supprimer</a>
            </li>";
        }
        $this->content .= "</ul>";
    }

    public function prepareDebugPage($variable)
    {
        $this->title = 'Debug';
        $this->content = '<pre>' . htmlspecialchars(var_export($variable, true)) . '</pre>';
    }


    public function prepareAnimalCreationPage(AnimalBuilder $builder)
    {
        $data = $builder->getData();
        $error = $builder->getError();

        $name = htmlspecialchars($data[AnimalBuilder::NAME_REF] ?? '');
        $species = htmlspecialchars($data[AnimalBuilder::SPECIES_REF] ?? '');
        $age = htmlspecialchars($data[AnimalBuilder::AGE_REF] ?? '');

        $this->title = "Créer un animal";
        $this->content = '
            <form action="' . $this->router->getAnimalSaveURL() . '" method="POST" enctype="multipart/form-data">
                ' . ($error ? '<div style="color: red; font-weight: bold;">' . htmlspecialchars($error) . '</div>' : '') . '
                <label for="name">Nom :</label>
                <input type="text" id="name" name="' . AnimalBuilder::NAME_REF . '" value="' . $name . '" required>
                <br>
                <label for="species">Espèce :</label>
                <input type="text" id="species" name="' . AnimalBuilder::SPECIES_REF . '" value="' . $species . '" required>
                <br>
                <label for="age">Âge :</label>
                <input type="number" id="age" name="' . AnimalBuilder::AGE_REF . '" value="' . $age . '" required>
                <br>
                <label for="image">Image :</label>
                <input type="file" id="image" name="image" accept="image/*">
                <br>
                <button type="submit">Créer</button>
            </form>';
    }


    public function displayAnimalCreationSuccess($id)
    {
        $url = $this->router->getAnimalURL($id);
        $this->router->POSTredirect($url, "Animal créé avec succès !");
    }

    public function redirectToList($feedback)
    {
        $this->router->POSTredirect($this->router->getAnimalListURL(), $feedback);
    }


}
?>