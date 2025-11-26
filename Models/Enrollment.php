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

    // Relações
    public $student;
    public $course;

    public function getStudentName()
    {
        return $this->student ? $this->student->name : 'N/A';
    }

    public function getCourseName()
    {
        return $this->course ? $this->course->name : 'N/A';
    }

    public function getStatusBadge()
    {
        $statuses = [
            'active' => 'success',
            'completed' => 'primary',
            'cancelled' => 'danger',
            'paused' => 'warning'
        ];

        $badge = $statuses[$this->status] ?? 'secondary';
        return '<span class="badge bg-' . $badge . '">' . ucfirst($this->status) . '</span>';
    }
}