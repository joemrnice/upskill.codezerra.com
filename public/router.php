<?php
/**
 * Router
 * Handle URL routing
 */

require_once __DIR__ . '/../app/bootstrap.php';

// Get request URI and method
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$requestMethod = $_SERVER['REQUEST_METHOD'];

// Remove /public from URI if present
$requestUri = str_replace('/public', '', $requestUri);

// Define routes
$routes = [
    'GET' => [
        '/' => 'HomeController@index',
        '/login' => 'AuthController@showLogin',
        '/register' => 'AuthController@showRegister',
        '/logout' => 'AuthController@logout',
        '/forgot-password' => 'AuthController@showForgotPassword',
        '/reset-password' => 'AuthController@showResetPassword',
        
        // User routes
        '/dashboard' => 'DashboardController@index',
        '/courses' => 'CourseController@index',
        '/course' => 'CourseController@show',
        '/enroll' => 'CourseController@enroll',
        '/learning' => 'LearningController@show',
        '/assessment' => 'AssessmentController@show',
        '/assessment/result' => 'AssessmentController@result',
        '/profile' => 'ProfileController@index',
        '/certificates' => 'ProfileController@certificates',
        
        // Admin routes
        '/admin' => 'AdminController@dashboard',
        '/admin/courses' => 'AdminCourseController@index',
        '/admin/courses/create' => 'AdminCourseController@create',
        '/admin/courses/edit' => 'AdminCourseController@edit',
        '/admin/courses/delete' => 'AdminCourseController@delete',
        '/admin/users' => 'AdminUserController@index',
        '/admin/users/edit' => 'AdminUserController@edit',
        '/admin/users/delete' => 'AdminUserController@delete',
        '/admin/enrollments' => 'AdminEnrollmentController@index',
        '/admin/assessments' => 'AdminAssessmentController@index',
        '/admin/assessments/create' => 'AdminAssessmentController@create',
        '/admin/assessments/edit' => 'AdminAssessmentController@edit',
    ],
    'POST' => [
        '/login' => 'AuthController@login',
        '/register' => 'AuthController@register',
        '/forgot-password' => 'AuthController@forgotPassword',
        '/reset-password' => 'AuthController@resetPassword',
        
        // User actions
        '/enroll' => 'CourseController@processEnroll',
        '/assessment/submit' => 'AssessmentController@submit',
        '/resource/complete' => 'LearningController@markComplete',
        '/profile/update' => 'ProfileController@update',
        '/profile/password' => 'ProfileController@updatePassword',
        
        // Admin actions
        '/admin/courses/store' => 'AdminCourseController@store',
        '/admin/courses/update' => 'AdminCourseController@update',
        '/admin/modules/store' => 'AdminModuleController@store',
        '/admin/modules/delete' => 'AdminModuleController@delete',
        '/admin/resources/store' => 'AdminResourceController@store',
        '/admin/resources/delete' => 'AdminResourceController@delete',
        '/admin/users/store' => 'AdminUserController@store',
        '/admin/users/update' => 'AdminUserController@update',
        '/admin/assessments/store' => 'AdminAssessmentController@store',
        '/admin/assessments/update' => 'AdminAssessmentController@update',
        '/admin/questions/store' => 'AdminQuestionController@store',
        '/admin/questions/delete' => 'AdminQuestionController@delete',
    ]
];

// Find matching route
$matched = false;

if (isset($routes[$requestMethod])) {
    foreach ($routes[$requestMethod] as $route => $handler) {
        // Check for exact match
        if ($requestUri === $route) {
            $matched = true;
            list($controller, $method) = explode('@', $handler);
            
            // Load controller and call method
            $controllerFile = __DIR__ . '/../app/controllers/' . $controller . '.php';
            if (file_exists($controllerFile)) {
                require_once $controllerFile;
                $controllerInstance = new $controller();
                $controllerInstance->$method();
            } else {
                die("Controller not found: " . $controller);
            }
            break;
        }
        
        // Check for routes with query parameters
        $routeParts = explode('?', $route);
        if ($routeParts[0] === explode('?', $requestUri)[0]) {
            $matched = true;
            list($controller, $method) = explode('@', $handler);
            
            $controllerFile = __DIR__ . '/../app/controllers/' . $controller . '.php';
            if (file_exists($controllerFile)) {
                require_once $controllerFile;
                $controllerInstance = new $controller();
                $controllerInstance->$method();
            }
            break;
        }
    }
}

// Handle 404
if (!$matched) {
    http_response_code(404);
    echo "404 - Page Not Found";
}
