<?php
namespace App\Models;

class Enrollment
{
    public $id;
    public $student_id;
    public $course_id;
    public $enrolled_at;
    public $status;
    public $progress;
    public $created_at;
    public $updated_at;

    public $student;
    public $course;

    public function getStatusBadge()
    {
        $statuses = [
            'active' => 'success',
            'completed' => 'primary',
            'cancelled' => 'danger',
            'paused' => 'warning'
        ];

        return $statuses[$this->status] ?? 'secondary';
    }
}