<?php
namespace App\Models;

class Instructor
{
    public $id;
    public $name;
    public $email;
    public $specialization;
    public $bio;
    public $photo;
    public $created_at;
    public $updated_at;

    public $courses;

    public function getCourseCount()
    {
        return $this->courses ? count($this->courses) : 0;
    }
}