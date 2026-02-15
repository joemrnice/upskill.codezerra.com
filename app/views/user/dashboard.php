<?php 
$pageTitle = 'Dashboard - ' . SITE_NAME;
require_once __DIR__ . '/../layouts/header.php'; 
?>

<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Welcome Section -->
        <div class="mb-8">
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-2">
                Welcome back, <?php echo e(Session::get('user_name')); ?>! 👋
            </h1>
            <p class="text-gray-600">
                Continue your learning journey and track your progress
            </p>
        </div>
        
        <?php if (isset($db_error) && $db_error): ?>
        <!-- Database Error Notice -->
        <div class="mb-8">
            <div class="bg-red-100 border-l-4 border-red-500 p-4 rounded-lg">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-red-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div>
                        <p class="text-red-800 font-semibold">Database Connection Error</p>
                        <p class="text-red-700 text-sm mt-1"><?php echo e($error_message ?? 'Unable to load dashboard data. Please contact the administrator.'); ?></p>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-xl p-6 shadow-custom">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-medium mb-1">Courses Enrolled</p>
                        <p class="text-3xl font-bold text-purple-600"><?php echo $stats['enrolled']; ?></p>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl p-6 shadow-custom">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-medium mb-1">Courses Completed</p>
                        <p class="text-3xl font-bold text-green-600"><?php echo $stats['completed']; ?></p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl p-6 shadow-custom">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-medium mb-1">Certificates Earned</p>
                        <p class="text-3xl font-bold text-yellow-600"><?php echo $stats['certificates']; ?></p>
                    </div>
                    <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-8">
                
                <!-- Enrolled Courses Section -->
                <div class="bg-white rounded-xl shadow-custom p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-bold text-gray-900">My Courses</h2>
                        <a href="<?php echo base_url('public/courses/index.php'); ?>" 
                           class="text-purple-600 hover:text-purple-700 font-semibold text-sm">
                            Browse All →
                        </a>
                    </div>
                    
                    <?php if (empty($enrolledCourses)): ?>
                    <div class="text-center py-12">
                        <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">No enrollments yet</h3>
                        <p class="text-gray-600 mb-6">Start your learning journey by enrolling in a course</p>
                        <a href="<?php echo base_url('public/courses/index.php'); ?>" 
                           class="inline-block btn-primary px-6 py-3 rounded-lg font-semibold text-white">
                            Explore Courses
                        </a>
                    </div>
                    <?php else: ?>
                    <div class="space-y-4">
                        <?php foreach ($enrolledCourses as $course): ?>
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex-1">
                                    <h3 class="text-lg font-bold text-gray-900 mb-1">
                                        <?php echo e($course['title']); ?>
                                    </h3>
                                    <p class="text-sm text-gray-600 mb-2">
                                        <?php echo e($course['instructor_name']); ?>
                                    </p>
                                    <span class="inline-block px-2 py-1 bg-<?php echo $course['status'] === 'completed' ? 'green' : 'blue'; ?>-100 text-<?php echo $course['status'] === 'completed' ? 'green' : 'blue'; ?>-600 text-xs font-semibold rounded">
                                        <?php echo ucfirst(str_replace('_', ' ', $course['status'])); ?>
                                    </span>
                                </div>
                            </div>
                            
                            <!-- Progress Bar -->
                            <div class="mb-3">
                                <div class="flex items-center justify-between text-sm text-gray-600 mb-1">
                                    <span>Progress</span>
                                    <span class="font-semibold"><?php echo number_format($course['progress'], 0); ?>%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-gradient-primary h-2 rounded-full transition-all duration-500" 
                                         style="width: <?php echo $course['progress']; ?>%"></div>
                                </div>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-gray-500">
                                    Enrolled <?php echo date('M d, Y', strtotime($course['enrollment_date'])); ?>
                                </span>
                                <?php if ($course['status'] === 'completed'): ?>
                                <a href="<?php echo base_url('public/user/certificate.php?course_id=' . $course['course_id']); ?>" 
                                   class="text-sm font-semibold text-green-600 hover:text-green-700">
                                    View Certificate →
                                </a>
                                <?php else: ?>
                                <a href="<?php echo base_url('public/courses/view.php?id=' . $course['course_id']); ?>" 
                                   class="text-sm font-semibold text-purple-600 hover:text-purple-700">
                                    Continue Learning →
                                </a>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
                
                <!-- Available Courses Section -->
                <?php if (!empty($availableCourses)): ?>
                <div class="bg-white rounded-xl shadow-custom p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-bold text-gray-900">Recommended Courses</h2>
                        <a href="<?php echo base_url('public/courses/index.php'); ?>" 
                           class="text-purple-600 hover:text-purple-700 font-semibold text-sm">
                            View All →
                        </a>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <?php foreach ($availableCourses as $course): ?>
                        <div class="card border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition">
                            <div class="h-32 bg-gradient-primary flex items-center justify-center">
                                <svg class="w-12 h-12 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                            </div>
                            <div class="p-4">
                                <div class="flex items-center mb-2">
                                    <span class="px-2 py-1 bg-purple-100 text-purple-600 text-xs font-semibold rounded">
                                        <?php echo e($course['category']); ?>
                                    </span>
                                </div>
                                <h3 class="font-bold text-gray-900 mb-2 line-clamp-2">
                                    <?php echo e($course['title']); ?>
                                </h3>
                                <div class="flex items-center justify-between">
                                    <span class="text-xs text-gray-500">
                                        <?php echo $course['enrollment_count']; ?> enrolled
                                    </span>
                                    <a href="<?php echo base_url('public/courses/view.php?id=' . $course['id']); ?>" 
                                       class="text-sm font-semibold text-purple-600 hover:text-purple-700">
                                        Enroll →
                                    </a>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
                
            </div>
            
            <!-- Sidebar -->
            <div class="space-y-6">
                
                <!-- Recent Activity -->
                <div class="bg-white rounded-xl shadow-custom p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Recent Activity</h2>
                    
                    <?php if (empty($recentActivity)): ?>
                    <p class="text-gray-500 text-sm text-center py-8">No recent activity</p>
                    <?php else: ?>
                    <div class="space-y-4">
                        <?php foreach (array_slice($recentActivity, 0, 5) as $activity): ?>
                        <div class="flex items-start space-x-3 pb-3 border-b border-gray-100 last:border-0">
                            <?php if ($activity['type'] === 'enrollment'): ?>
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                                    <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm text-gray-900">
                                    Enrolled in <span class="font-semibold"><?php echo e($activity['course_title']); ?></span>
                                </p>
                                <p class="text-xs text-gray-500 mt-1">
                                    <?php echo date('M d, Y', strtotime($activity['date'])); ?>
                                </p>
                            </div>
                            <?php elseif ($activity['type'] === 'completion'): ?>
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm text-gray-900">
                                    Completed <span class="font-semibold"><?php echo e($activity['course_title']); ?></span>
                                </p>
                                <p class="text-xs text-gray-500 mt-1">
                                    <?php echo date('M d, Y', strtotime($activity['date'])); ?>
                                </p>
                            </div>
                            <?php elseif ($activity['type'] === 'assessment'): ?>
                            <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                                    <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm9.707 5.707a1 1 0 00-1.414-1.414L9 12.586l-1.293-1.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm text-gray-900">
                                    Assessment in <span class="font-semibold"><?php echo e($activity['course_title']); ?></span>
                                </p>
                                <p class="text-xs text-gray-500 mt-1">
                                    Score: <?php echo number_format($activity['score']); ?>% • <?php echo date('M d, Y', strtotime($activity['date'])); ?>
                                </p>
                            </div>
                            <?php endif; ?>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
                
                <!-- Certificates -->
                <?php if (!empty($certificates)): ?>
                <div class="bg-white rounded-xl shadow-custom p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Certificates</h2>
                    
                    <div class="space-y-3">
                        <?php foreach (array_slice($certificates, 0, 3) as $cert): ?>
                        <div class="border border-gray-200 rounded-lg p-3 hover:shadow-md transition">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-gray-900 truncate">
                                        <?php echo e($cert['course_title']); ?>
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        <?php echo date('M d, Y', strtotime($cert['issued_date'])); ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <?php if (count($certificates) > 3): ?>
                    <a href="<?php echo base_url('public/user/certificates.php'); ?>" 
                       class="block text-center text-purple-600 hover:text-purple-700 font-semibold text-sm mt-4">
                        View All Certificates →
                    </a>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
                
                <!-- Quick Links -->
                <div class="bg-gradient-primary rounded-xl p-6 text-white">
                    <h3 class="text-lg font-bold mb-4">Quick Links</h3>
                    <div class="space-y-2">
                        <a href="<?php echo base_url('public/courses/index.php'); ?>" 
                           class="block py-2 px-3 bg-white bg-opacity-20 rounded-lg hover:bg-opacity-30 transition">
                            Browse All Courses
                        </a>
                        <a href="<?php echo base_url('public/user/enrollments.php'); ?>" 
                           class="block py-2 px-3 bg-white bg-opacity-20 rounded-lg hover:bg-opacity-30 transition">
                            My Enrollments
                        </a>
                        <a href="<?php echo base_url('public/user/certificates.php'); ?>" 
                           class="block py-2 px-3 bg-white bg-opacity-20 rounded-lg hover:bg-opacity-30 transition">
                            My Certificates
                        </a>
                    </div>
                </div>
                
            </div>
        </div>
        
    </div>
</div>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
