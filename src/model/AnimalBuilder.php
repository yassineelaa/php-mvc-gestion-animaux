<?php

class AnimalBuilder
{
    private $data;
    private $error;

    const NAME_REF = 'NAME';
    const SPECIES_REF = 'SPECIES';
    const AGE_REF = 'AGE';

    public function __construct(array $data = [])
    {
        $this->data = $data;
        $this->error = null;
    }

    public function getData()
    {
        return $this->data;
    }

    public function getError()
    {
        return $this->error;
    }

    

    public function isValid()
    {
        if (empty($this->data[self::NAME_REF])) {
            $this->error = "Le nom est obligatoire.";
            return false;
        }

        if (empty($this->data[self::SPECIES_REF])) {
            $this->error = "L'espèce est obligatoire.";
            return false;
        }

        if (empty($this->data[self::AGE_REF]) || !is_numeric($this->data[self::AGE_REF]) || $this->data[self::AGE_REF] <= 0) {
            $this->error = "L'âge doit être un entier positif.";
            return false;
        }

        return true;
    }

    public function createAnimal()
    {
        return new Animal(
            $this->data[self::NAME_REF],
            $this->data[self::SPECIES_REF],
            (int)$this->data[self::AGE_REF]
        );
    }
}
?>