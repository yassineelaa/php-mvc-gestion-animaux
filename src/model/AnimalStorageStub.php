<?php

class AnimalStorageStub implements AnimalStorage {
    private $animalsTab;

    public function __construct() {
        $this->animalsTab = array(
            'medor' => new Animal('Médor', 'chien', 5),
            'felix' => new Animal('Félix', 'chat', 3),
            'denver' => new Animal('Denver', 'dinosaure', 100),
        );
    }

    public function read($id)
    {
        return $this->animalsTab[$id] ?? null;
    }

    public function readAll() {
        return $this->animalsTab;
    }

    public function create(Animal $a) {
        throw new Exception("Non implémenté dans AnimalStorageStub");
    }

    public function delete($id) {
        throw new Exception("Non implémenté dans AnimalStorageStub");
    }

    public function update($id, Animal $a) {
        throw new Exception("Non implémenté dans AnimalStorageStub");
    }
}
