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

    public function getCourseCount()
    {
        // Será implementado no Repository
        return 0;
    }
}