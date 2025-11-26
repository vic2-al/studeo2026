<?php
namespace App\Services;

use App\Repositories\CourseRepository;
use App\Models\Course;

class CourseService
{
    private $courseRepository;

    public function __construct(CourseRepository $courseRepository)
    {
        $this->courseRepository = $courseRepository;
    }

    public function getAllCourses()
    {
        return $this->courseRepository->findAll();
    }

    public function getCourse($id)
    {
        return $this->courseRepository->find($id);
    }

    public function createCourse($data)
    {
        $course = new Course();
        $course->name = $data['name'];
        $course->description = $data['description'];
        $course->duration = $data['duration'];
        $course->price = $data['price'];
        $course->instructor_id = $data['instructor_id'] ?? null;
        $course->category_id = $data['category_id'] ?? null;

        return $this->courseRepository->save($course);
    }

    public function updateCourse($id, $data)
    {
        $course = $this->courseRepository->find($id);
        if (!$course) {
            return false;
        }

        $course->name = $data['name'];
        $course->description = $data['description'];
        $course->duration = $data['duration'];
        $course->price = $data['price'];
        $course->instructor_id = $data['instructor_id'] ?? null;
        $course->category_id = $data['category_id'] ?? null;

        return $this->courseRepository->save($course);
    }

    public function deleteCourse($id)
    {
        return $this->courseRepository->delete($id);
    }

    public function getCoursesByCategory($categoryId)
    {
        return $this->courseRepository->getByCategory($categoryId);
    }
}