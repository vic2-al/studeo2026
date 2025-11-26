// Courses Routes
$app->get('/admin/courses', [CourseController::class, 'index']);
$app->get('/admin/courses/create', [CourseController::class, 'create']);
$app->post('/admin/courses/store', [CourseController::class, 'store']);
$app->get('/admin/courses/edit/{id}', [CourseController::class, 'edit']);
$app->post('/admin/courses/update/{id}', [CourseController::class, 'update']);
$app->get('/admin/courses/delete/{id}', [CourseController::class, 'delete']);

// Categories Routes
$app->get('/admin/categories', [CategoryController::class, 'index']);
$app->get('/admin/categories/create', [CategoryController::class, 'create']);
$app->post('/admin/categories/store', [CategoryController::class, 'store']);
$app->get('/admin/categories/edit/{id}', [CategoryController::class, 'edit']);
$app->post('/admin/categories/update/{id}', [CategoryController::class, 'update']);
$app->get('/admin/categories/delete/{id}', [CategoryController::class, 'delete']);

// Instructors Routes
$app->get('/admin/instructors', [InstructorController::class, 'index']);
$app->get('/admin/instructors/create', [InstructorController::class, 'create']);
$app->post('/admin/instructors/store', [InstructorController::class, 'store']);
$app->get('/admin/instructors/edit/{id}', [InstructorController::class, 'edit']);
$app->post('/admin/instructors/update/{id}', [InstructorController::class, 'update']);
$app->get('/admin/instructors/delete/{id}', [InstructorController::class, 'delete']);

// Enrollments Routes
$app->get('/admin/enrollments', [EnrollmentController::class, 'index']);
$app->get('/admin/enrollments/create', [EnrollmentController::class, 'create']);
$app->post('/admin/enrollments/store', [EnrollmentController::class, 'store']);
$app->get('/admin/enrollments/edit/{id}', [EnrollmentController::class, 'edit']);
$app->post('/admin/enrollments/update/{id}', [EnrollmentController::class, 'update']);
$app->get('/admin/enrollments/delete/{id}', [EnrollmentController::class, 'delete']);