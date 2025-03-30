<?php
class Animal
{
    private $name;
    private $species;
    private $age;
    private $image;

    public function __construct($name, $species, $age, $image = null)
    {
        $this->name = $name;
        $this->species = $species;
        $this->age = $age;
        $this->image = $image;
    }

    
    public function getName()
    {
        return $this->name;
    }

    
    public function getSpecies()
    {
        return $this->species;
    }

    
    public function getAge()
    {
        return $this->age;
    }

    public function getImage()
    {
        return $this->image;
    }
}

?>
