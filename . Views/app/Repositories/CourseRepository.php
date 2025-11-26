<?php
namespace App\Repositories;

use PDO;
use App\Models\Course;

class CourseRepository
{
    private $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function findAll()
    {
        $stmt = $this->db->query("
            SELECT c.*, i.name as instructor_name, cat.name as category_name 
            FROM courses c 
            LEFT JOIN instructors i ON c.instructor_id = i.id 
            LEFT JOIN categories cat ON c.category_id = cat.id 
            ORDER BY c.name
        ");

        $courses = $stmt->fetchAll(PDO::FETCH_CLASS, Course::class);

        // Popular relações
        foreach ($courses as $course) {
            $course->instructor = (object) ['name' => $course->instructor_name];
            $course->category = (object) ['name' => $course->category_name];
        }

        return $courses;
    }

    public function find($id)
    {
        $stmt = $this->db->prepare("
            SELECT c.*, i.name as instructor_name, cat.name as category_name 
            FROM courses c 
            LEFT JOIN instructors i ON c.instructor_id = i.id 
            LEFT JOIN categories cat ON c.category_id = cat.id 
            WHERE c.id = ?
        ");
        $stmt->execute([$id]);

        $course = $stmt->fetchObject(Course::class);
        if ($course) {
            $course->instructor = (object) ['name' => $course->instructor_name];
            $course->category = (object) ['name' => $course->category_name];
        }

        return $course;
    }

    public function save(Course $course)
    {
        $data = [
            $course->name,
            $course->description,
            $course->duration,
            $course->price,
            $course->instructor_id,
            $course->category_id
        ];

        if ($course->id) {
            $stmt = $this->db->prepare("
                UPDATE courses 
                SET name = ?, description = ?, duration = ?, price = ?, instructor_id = ?, category_id = ?, updated_at = NOW() 
                WHERE id = ?
            ");
            $data[] = $course->id;
        } else {
            $stmt = $this->db->prepare("
                INSERT INTO courses (name, description, duration, price, instructor_id, category_id, created_at) 
                VALUES (?, ?, ?, ?, ?, ?, NOW())
            ");
        }

        return $stmt->execute($data);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM courses WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function getByCategory($categoryId)
    {
        $stmt = $this->db->prepare("SELECT * FROM courses WHERE category_id = ? ORDER BY name");
        $stmt->execute([$categoryId]);
        return $stmt->fetchAll(PDO::FETCH_CLASS, Course::class);
    }
}