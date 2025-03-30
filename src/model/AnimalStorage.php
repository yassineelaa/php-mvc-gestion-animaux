<?php

interface AnimalStorage
{
    /**
     * Renvoie l'instance de Animal ayant pour identifiant $id, ou null si aucun animal n'a cet identifiant.
     * @param string $id
     * @return Animal|null
     */
    public function read($id);

    /**
     * Renvoie un tableau associatif identifiant => Animal contenant tous les animaux.
     * @return array
     */
    public function readAll();

    /**
     * Ajoute un animal à la base et retourne son identifiant.
     * @param Animal $a
     * @return string L'identifiant de l'animal créé.
     */
    public function create(Animal $a);

    /**
     * Supprime l'animal correspondant à l'identifiant donné.
     * @param string $id
     * @return bool True si la suppression a été effectuée, false sinon.
     */
    public function delete($id);

    /**
     * Met à jour un animal dans la base.
     * @param string $id
     * @param Animal $a
     * @return bool True si la mise à jour a été effectuée, false sinon.
     */
    public function update($id, Animal $a);
}
?>