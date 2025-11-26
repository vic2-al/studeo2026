<?php
namespace App\Repositories;

use PDO;
use App\Models\Category;

class CategoryRepository
{
    private $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function findAll()
    {
        $stmt = $this->db->query("
            SELECT c.*, COUNT(co.id) as course_count 
            FROM categories c 
            LEFT JOIN courses co ON c.id = co.category_id 
            GROUP BY c.id 
            ORDER BY c.name
        ");

        $categories = $stmt->fetchAll(PDO::FETCH_CLASS, Category::class);

        foreach ($categories as $category) {
            $category->courses = $category->course_count;
        }

        return $categories;
    }

    public function find($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM categories WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetchObject(Category::class);
    }

    public function save(Category $category)
    {
        if ($category->id) {
            $stmt = $this->db->prepare("UPDATE categories SET name = ?, description = ?, color = ?, updated_at = NOW() WHERE id = ?");
            return $stmt->execute([$category->name, $category->description, $category->color, $category->id]);
        } else {
            $stmt = $this->db->prepare("INSERT INTO categories (name, description, color, created_at) VALUES (?, ?, ?, NOW())");
            return $stmt->execute([$category->name, $category->description, $category->color]);
        }
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM categories WHERE id = ?");
        return $stmt->execute([$id]);
    }
}