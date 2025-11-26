<?php
namespace App\Controllers;

use App\Services\CourseService;
use App\Services\InstructorService;
use App\Services\CategoryService;

class CourseController extends Controller
{
    private $courseService;
    private $instructorService;
    private $categoryService;

    public function __construct()
    {
        parent::__construct();
        $this->courseService = new CourseService($this->courseRepository);
        $this->instructorService = new InstructorService($this->instructorRepository);
        $this->categoryService = new CategoryService($this->categoryRepository);
    }

    public function index()
    {
        $courses = $this->courseService->getAllCourses();
        $this->render('admin/courses/index', ['courses' => $courses]);
    }

    public function create()
    {
        $instructors = $this->instructorService->getAllInstructors();
        $categories = $this->categoryService->getAllCategories();

        $this->render('admin/courses/create', [
            'instructors' => $instructors,
            'categories' => $categories
        ]);
    }

    public function store()
    {
        if ($_POST) {
            $this->courseService->createCourse($_POST);
            $this->redirect('/admin/courses');
        }
    }

    public function edit($id)
    {
        $course = $this->courseService->getCourse($id);
        $instructors = $this->instructorService->getAllInstructors();
        $categories = $this->categoryService->getAllCategories();

        $this->render('admin/courses/edit', [
            'course' => $course,
            'instructors' => $instructors,
            'categories' => $categories
        ]);
    }

    public function update($id)
    {
        if ($_POST) {
            $this->courseService->updateCourse($id, $_POST);
            $this->redirect('/admin/courses');
        }
    }

    public function delete($id)
    {
        $this->courseService->deleteCourse($id);
        $this->redirect('/admin/courses');
    }
}