<?php
/**
 * @package Model
 */
namespace App\src\model;

use App\src\entity\Category;
use App\src\Framework\Manager;

/**
 * This file manage SQL requests from category's database
 *
 * @author Franck D <franck.pyren@gmail.com>
 */
class CategoryManager extends Manager
{    
    /**
     * Return object hydrated
     *
     * @param  array $row [Result of database request]
     * @return void
     */
    private function buildObject($row)
    {
        $category = new Category();
        $category->setId($row['id']);
        $category->setName($row['name']);
        $category->setDescription($row['description']);
        return $category;
    }
        
    /**
     * Return list of categories
     *
     * @return void
     */
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
        
    /**
     * Return list of categories
     *
     * @param  int $categoryId [Category index]
     * @return void
     */
    public function getCategory($categoryId)
    {
        $sql='SELECT id, name, description FROM category WHERE id = ?';
        $result= $this->createQuery($sql, [$categoryId]);
        $category=$result->fetch();
        $result->closeCursor();
        return $this->buildObject($category);
    }
    
    /**
     * Add new category in database by catagory's datas sent
     *
     * @param  array $category [Datas category for integration in database]
     * @return void
     */
    public function addCategory($category)
    {
        //Permet de insérer la nouvelle catégorie dans la table
        $sql = 'INSERT INTO category (name, description) VALUES (?, ?)';
        $this->createQuery($sql, [
            $category->get('name'), $category->get('description')]);
    }
    
    /**
     * Update the category by categoryId and datas associated
     *
     * @param  array $category [Datas category]
     * @param  int $categoryId [Category index]
     * @return void
     */
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
    
    /**
     * Delete category in database by categoryId
     *
     * @param  int $categoryId [Category index]
     * @return void
     */
    public function deleteCategory($categoryId)
    {
        //Permet de supprimer la catégorie, les article et ses commentaires associés
        $sql = 'DELETE FROM posts WHERE category_id = ?';
        $this->createQuery($sql, [$categoryId]);
        $sql = 'DELETE FROM category WHERE id = ?';
        $this->createQuery($sql, [$categoryId]);
    }
}
