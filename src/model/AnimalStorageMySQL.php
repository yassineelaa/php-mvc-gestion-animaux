<?php

require_once("AnimalStorage.php");

class AnimalStorageMySQL implements AnimalStorage
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function read($id)
    {
        $query = "SELECT * FROM animals WHERE id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if ($row) {
            return new Animal($row['name'], $row['species'], $row['age'], $row['image']);
        } else {
            return null;
        }
    }


    public function readAll()
    {
        $query = "SELECT * FROM animals";
        $stmt = $this->pdo->query($query);

        $animals = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $animals[$row['id']] = new Animal($row['name'], $row['species'], $row['age'], $row['image']);
        }
        return $animals;
    }


    public function create(Animal $animal)
    {
        $query = "INSERT INTO animals (name, species, age, image) VALUES (:name, :species, :age, :image)";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([
            'name' => $animal->getName(),
            'species' => $animal->getSpecies(),
            'age' => $animal->getAge(),
            'image' => $animal->getImage() 
        ]);
        return $this->pdo->lastInsertId(); 
    }
    



    public function delete($id)
    {
        $query = "DELETE FROM animals WHERE id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['id' => $id]);

        return $stmt->rowCount() > 0;
    }

    public function update($id, Animal $animal)
    {
        throw new Exception("update() not yet implemented");
    }
}
?>