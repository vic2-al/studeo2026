<?php
namespace App\Models;

class Course
{
    public $id;
    public $name;
    public $description;
    public $duration;
    public $price;
    public $instructor_id;
    public $category_id;
    public $created_at;
    public $updated_at;

    public $instructor;
    public $category;

    public function getInstructorName()
    {
        return $this->instructor ? $this->instructor->name : 'N/A';
    }

    public function getCategoryName()
    {
        return $this->category ? $this->category->name : 'N/A';
    }
}