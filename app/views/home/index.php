<?php 
$pageTitle = 'Welcome to ' . SITE_NAME;
require_once __DIR__ . '/../layouts/header.php'; 
?>

<!-- Hero Section -->
<section class="relative bg-gradient-primary py-20 lg:py-32 overflow-hidden">
    <!-- Decorative elements -->
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-0 left-0 w-96 h-96 bg-white rounded-full filter blur-3xl"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-white rounded-full filter blur-3xl"></div>
    </div>
    
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <!-- Left Content -->
            <div class="text-white">
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold leading-tight mb-6">
                    Empower Your Career with
                    <span class="block text-yellow-300">Continuous Learning</span>
                </h1>
                <p class="text-lg md:text-xl text-purple-100 mb-8">
                    Access world-class courses, earn certifications, and unlock your full potential. 
                    Join thousands of professionals advancing their skills.
                </p>
                <div class="flex flex-col sm:flex-row gap-4">
                    <?php if (Session::isLoggedIn()): ?>
                        <a href="<?php echo base_url('public/index.php'); ?>" 
                           class="btn-primary text-center px-8 py-4 rounded-lg font-semibold text-white shadow-lg">
                            Go to Dashboard
                        </a>
                        <a href="<?php echo base_url('public/courses/index.php'); ?>" 
                           class="bg-white text-purple-700 text-center px-8 py-4 rounded-lg font-semibold hover:bg-gray-100 transition shadow-lg">
                            Browse Courses
                        </a>
                    <?php else: ?>
                        <a href="<?php echo base_url('public/auth/register.php'); ?>" 
                           class="btn-primary text-center px-8 py-4 rounded-lg font-semibold text-white shadow-lg">
                            Get Started
                        </a>
                        <a href="<?php echo base_url('public/courses/index.php'); ?>" 
                           class="bg-white text-purple-700 text-center px-8 py-4 rounded-lg font-semibold hover:bg-gray-100 transition shadow-lg">
                            Browse Courses
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Right Content - Illustration -->
            <div class="hidden lg:block">
                <div class="relative">
                    <div class="absolute inset-0 bg-white opacity-10 rounded-3xl transform rotate-6"></div>
                    <div class="relative bg-white bg-opacity-20 backdrop-filter backdrop-blur-lg rounded-3xl p-8">
                        <div class="space-y-4">
                            <div class="flex items-center space-x-4 bg-white bg-opacity-30 rounded-lg p-4">
                                <div class="w-12 h-12 bg-yellow-400 rounded-full flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-white font-semibold">Expert Instructors</p>
                                    <p class="text-purple-100 text-sm">Learn from industry leaders</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-4 bg-white bg-opacity-30 rounded-lg p-4">
                                <div class="w-12 h-12 bg-green-400 rounded-full flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-white font-semibold">Certifications</p>
                                    <p class="text-purple-100 text-sm">Validate your achievements</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-4 bg-white bg-opacity-30 rounded-lg p-4">
                                <div class="w-12 h-12 bg-blue-400 rounded-full flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-white font-semibold">Track Progress</p>
                                    <p class="text-purple-100 text-sm">Monitor your learning journey</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php if (isset($db_error) && $db_error): ?>
<!-- Database Notice -->
<section class="py-8 bg-yellow-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-yellow-100 border-l-4 border-yellow-500 p-4 rounded-lg">
            <div class="flex items-center">
                <svg class="w-6 h-6 text-yellow-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <div>
                    <p class="text-yellow-800 font-semibold">Database Configuration Required</p>
                    <p class="text-yellow-700 text-sm mt-1">The application is running with limited functionality. Please configure the database to access full features. Check README.md for setup instructions.</p>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Statistics Section -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
            <div class="text-center">
                <div class="text-4xl md:text-5xl font-bold text-purple-600 mb-2">
                    <?php echo number_format($stats['courses_offered']); ?>
                </div>
                <p class="text-gray-600 font-medium">Courses Offered</p>
            </div>
            <div class="text-center">
                <div class="text-4xl md:text-5xl font-bold text-purple-600 mb-2">
                    <?php echo number_format($stats['students_enrolled']); ?>
                </div>
                <p class="text-gray-600 font-medium">Students Enrolled</p>
            </div>
            <div class="text-center">
                <div class="text-4xl md:text-5xl font-bold text-purple-600 mb-2">
                    <?php echo $stats['completion_rate']; ?>%
                </div>
                <p class="text-gray-600 font-medium">Completion Rate</p>
            </div>
            <div class="text-center">
                <div class="text-4xl md:text-5xl font-bold text-purple-600 mb-2">
                    <?php echo number_format($stats['certificates_issued']); ?>
                </div>
                <p class="text-gray-600 font-medium">Certificates Issued</p>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                Why Choose <?php echo SITE_NAME; ?>?
            </h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Everything you need to accelerate your professional development
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Feature 1 -->
            <div class="card bg-white rounded-xl p-8 shadow-custom">
                <div class="w-16 h-16 bg-purple-100 rounded-lg flex items-center justify-center mb-6">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Comprehensive Course Catalog</h3>
                <p class="text-gray-600">
                    Access a wide range of courses covering various topics and skill levels, from beginner to advanced.
                </p>
            </div>
            
            <!-- Feature 2 -->
            <div class="card bg-white rounded-xl p-8 shadow-custom">
                <div class="w-16 h-16 bg-green-100 rounded-lg flex items-center justify-center mb-6">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Earn Certifications</h3>
                <p class="text-gray-600">
                    Complete courses and earn recognized certifications to showcase your expertise and skills.
                </p>
            </div>
            
            <!-- Feature 3 -->
            <div class="card bg-white rounded-xl p-8 shadow-custom">
                <div class="w-16 h-16 bg-blue-100 rounded-lg flex items-center justify-center mb-6">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Track Your Progress</h3>
                <p class="text-gray-600">
                    Monitor your learning journey with detailed progress tracking and personalized insights.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Popular Courses Section -->
<?php if (!empty($courses)): ?>
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                Popular Courses
            </h2>
            <p class="text-xl text-gray-600">
                Start learning with our most popular courses
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($courses as $course): ?>
            <div class="card bg-white rounded-xl overflow-hidden shadow-custom">
                <div class="h-48 bg-gradient-primary flex items-center justify-center">
                    <svg class="w-20 h-20 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <div class="p-6">
                    <div class="flex items-center mb-3">
                        <span class="px-3 py-1 bg-purple-100 text-purple-600 text-xs font-semibold rounded-full">
                            <?php echo e($course['category']); ?>
                        </span>
                        <span class="ml-auto text-sm text-gray-500">
                            <?php echo e($course['difficulty_level']); ?>
                        </span>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">
                        <?php echo e($course['title']); ?>
                    </h3>
                    <p class="text-gray-600 text-sm mb-4 line-clamp-2">
                        <?php echo e(substr($course['description'], 0, 100)) . '...'; ?>
                    </p>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">
                            <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                            <?php echo $course['enrollment_count']; ?> enrolled
                        </span>
                        <a href="<?php echo base_url('public/courses/view.php?id=' . $course['id']); ?>" 
                           class="text-purple-600 hover:text-purple-700 font-semibold text-sm">
                            Learn More →
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <div class="text-center mt-12">
            <a href="<?php echo base_url('public/courses/index.php'); ?>" 
               class="inline-block btn-primary px-8 py-3 rounded-lg font-semibold text-white">
                View All Courses
            </a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Testimonials Section -->
<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                What Our Learners Say
            </h2>
            <p class="text-xl text-gray-600">
                Success stories from professionals like you
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Testimonial 1 -->
            <div class="bg-white rounded-xl p-8 shadow-custom">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-gradient-primary rounded-full flex items-center justify-center text-white font-bold text-lg">
                        S
                    </div>
                    <div class="ml-4">
                        <h4 class="font-bold text-gray-900">Sarah Johnson</h4>
                        <p class="text-sm text-gray-500">Software Engineer</p>
                    </div>
                </div>
                <div class="flex mb-4">
                    <?php for ($i = 0; $i < 5; $i++): ?>
                    <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                    <?php endfor; ?>
                </div>
                <p class="text-gray-600">
                    "The courses are well-structured and easy to follow. I've gained valuable skills that helped me advance in my career. Highly recommended!"
                </p>
            </div>
            
            <!-- Testimonial 2 -->
            <div class="bg-white rounded-xl p-8 shadow-custom">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-gradient-primary rounded-full flex items-center justify-center text-white font-bold text-lg">
                        M
                    </div>
                    <div class="ml-4">
                        <h4 class="font-bold text-gray-900">Michael Chen</h4>
                        <p class="text-sm text-gray-500">Product Manager</p>
                    </div>
                </div>
                <div class="flex mb-4">
                    <?php for ($i = 0; $i < 5; $i++): ?>
                    <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                    <?php endfor; ?>
                </div>
                <p class="text-gray-600">
                    "Great platform for continuous learning. The certifications are recognized and the progress tracking keeps me motivated."
                </p>
            </div>
            
            <!-- Testimonial 3 -->
            <div class="bg-white rounded-xl p-8 shadow-custom">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-gradient-primary rounded-full flex items-center justify-center text-white font-bold text-lg">
                        E
                    </div>
                    <div class="ml-4">
                        <h4 class="font-bold text-gray-900">Emily Rodriguez</h4>
                        <p class="text-sm text-gray-500">Data Analyst</p>
                    </div>
                </div>
                <div class="flex mb-4">
                    <?php for ($i = 0; $i < 5; $i++): ?>
                    <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                    <?php endfor; ?>
                </div>
                <p class="text-gray-600">
                    "The instructors are knowledgeable and the course content is always up-to-date. I've learned so much in a short time!"
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action Section -->
<section class="py-20 bg-gradient-primary">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl md:text-4xl font-bold text-white mb-6">
            Ready to Start Your Learning Journey?
        </h2>
        <p class="text-xl text-purple-100 mb-8 max-w-2xl mx-auto">
            Join thousands of professionals who are already transforming their careers with our platform.
        </p>
        <?php if (!Session::isLoggedIn()): ?>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="<?php echo base_url('public/auth/register.php'); ?>" 
               class="bg-white text-purple-700 px-8 py-4 rounded-lg font-semibold hover:bg-gray-100 transition shadow-lg">
                Create Account
            </a>
            <a href="<?php echo base_url('public/auth/login.php'); ?>" 
               class="border-2 border-white text-white px-8 py-4 rounded-lg font-semibold hover:bg-white hover:text-purple-700 transition shadow-lg">
                Sign In
            </a>
        </div>
        <?php else: ?>
        <a href="<?php echo base_url('public/courses/index.php'); ?>" 
           class="inline-block bg-white text-purple-700 px-8 py-4 rounded-lg font-semibold hover:bg-gray-100 transition shadow-lg">
            Explore Courses
        </a>
        <?php endif; ?>
    </div>
</section>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
