<?php
namespace App\src\model;

use App\src\entity\Category;
use App\src\manager\Manager;

class CategoryManager extends Manager
{
    private function buildObject($row)
    {
        $category = new Category();
        $category->setId($row['id']);
        $category->setName($row['name']);
        $category->setDescription($row['description']);
        return $category;
    }

    public function getCategories()
    {
        $sql='SELECT id, name, description FROM category ORDER BY id DESC';
        $result= $this->createQuery($sql);
        foreach ($result as $row) {
            $categoryId=$row['id'];
            $category[$categoryId]=$this->buildObject($row);
        }
        $result->closeCursor();
        return $category;
    }

    public function getCategory($categoryId)
    {
        $sql='SELECT id, name, description FROM category WHERE id = ?';
        $result= $this->createQuery($sql, [$categoryId]);
        $category=$result->fetch();
        $result->closeCursor();
        return $this->buildObject($category);
    }

    public function addCategory($category)
    {
        //Permet de insérer la nouvelle catégorie dans la table
        var_dump($category);
        $sql = 'INSERT INTO category (name, description) VALUES (?, ?)';
        $this->createQuery($sql, [
            $category->get('name'), $category->get('description')]);
    }

    public function editCategory($category, $categoryId)
    {
        //Permet de mettre à jour la catégorie
        $sql = 'UPDATE category SET name=:name, description=:description WHERE id=:categoryId';
        $this->createQuery($sql, [
            'name' => $category->get('name'),
            'description' => $category->get('description'),
            'categoryId' =>$categoryId
        ]);
    }

    public function deleteCategory($categoryId)
    {
        //Permet de supprimer la catégorie, les article et ses commentaires associés( a faire)
        $sql = 'DELETE FROM post WHERE category_id = ?';
        $this->createQuery($sql, [$categoryId]);
        $sql = 'DELETE FROM category WHERE id = ?';
        $this->createQuery($sql, [$categoryId]);
    }
}
