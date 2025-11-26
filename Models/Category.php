<?php
namespace App\Models;

class Category
{
    public $id;
    public $name;
    public $description;
    public $color;
    public $created_at;
    public $updated_at;

    public $courses;

    public function getCourseCount()
    {
        return $this->courses ? count($this->courses) : 0;
    }
}