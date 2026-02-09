<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container mx-auto px-4 py-8">
    <!-- Back Button -->
    <a href="<?php echo base_url('courses.php'); ?>" class="inline-flex items-center text-purple-600 hover:text-purple-800 mb-6">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Back to Courses
    </a>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2">
            <!-- Course Header -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                <div class="relative h-64 bg-gradient-primary">
                    <?php if ($course['thumbnail']): ?>
                        <img src="<?php echo e($course['thumbnail']); ?>" alt="<?php echo e($course['title']); ?>" class="w-full h-full object-cover">
                    <?php endif; ?>
                </div>
                
                <div class="p-6">
                    <div class="flex items-center space-x-3 mb-4">
                        <span class="px-4 py-1 bg-purple-100 text-purple-700 rounded-full text-sm font-semibold">
                            <?php echo e(ucfirst($course['category'])); ?>
                        </span>
                        <span class="px-4 py-1 bg-gray-100 text-gray-700 rounded-full text-sm font-semibold">
                            <?php echo e(ucfirst($course['difficulty_level'])); ?>
                        </span>
                        <?php if ($isEnrolled): ?>
                            <span class="px-4 py-1 bg-green-100 text-green-700 rounded-full text-sm font-semibold">
                                Enrolled
                            </span>
                        <?php endif; ?>
                    </div>
                    
                    <h1 class="text-3xl font-bold text-gray-900 mb-3"><?php echo e($course['title']); ?></h1>
                    
                    <div class="flex items-center text-gray-600 mb-4">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                        </svg>
                        <span class="font-medium"><?php echo e($course['instructor_name']); ?></span>
                    </div>
                </div>
            </div>

            <!-- Course Description -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">About This Course</h2>
                <p class="text-gray-700 leading-relaxed"><?php echo nl2br(e($course['description'])); ?></p>
                
                <?php if ($course['prerequisites']): ?>
                    <div class="mt-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Prerequisites</h3>
                        <p class="text-gray-700"><?php echo nl2br(e($course['prerequisites'])); ?></p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Course Modules -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Course Content</h2>
                
                <?php if (empty($course['modules'])): ?>
                    <p class="text-gray-600">No modules available yet.</p>
                <?php else: ?>
                    <div class="space-y-4">
                        <?php foreach ($course['modules'] as $index => $module): ?>
                            <div class="border border-gray-200 rounded-lg overflow-hidden">
                                <button class="w-full flex items-center justify-between p-4 bg-gray-50 hover:bg-gray-100 transition module-toggle" data-module="<?php echo $module['id']; ?>">
                                    <div class="flex items-center">
                                        <span class="flex items-center justify-center w-8 h-8 bg-purple-600 text-white rounded-full mr-3 text-sm font-semibold">
                                            <?php echo $index + 1; ?>
                                        </span>
                                        <span class="font-semibold text-gray-900"><?php echo e($module['title']); ?></span>
                                    </div>
                                    <svg class="w-5 h-5 text-gray-500 transform transition-transform module-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                                
                                <div class="module-content hidden p-4 bg-white">
                                    <?php if ($module['description']): ?>
                                        <p class="text-gray-600 mb-4"><?php echo e($module['description']); ?></p>
                                    <?php endif; ?>
                                    
                                    <?php if (empty($module['resources'])): ?>
                                        <p class="text-gray-500 text-sm">No resources available</p>
                                    <?php else: ?>
                                        <ul class="space-y-2">
                                            <?php foreach ($module['resources'] as $resource): ?>
                                                <li class="flex items-center text-gray-700">
                                                    <?php if ($resource['type'] === 'video'): ?>
                                                        <svg class="w-5 h-5 mr-2 text-blue-500" fill="currentColor" viewBox="0 0 24 24">
                                                            <path d="M8 5v14l11-7z"/>
                                                        </svg>
                                                    <?php else: ?>
                                                        <svg class="w-5 h-5 mr-2 text-red-500" fill="currentColor" viewBox="0 0 24 24">
                                                            <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6z"/>
                                                        </svg>
                                                    <?php endif; ?>
                                                    <span><?php echo e($resource['title']); ?></span>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <!-- Enrollment Card -->
            <div class="bg-white rounded-lg shadow-md p-6 sticky top-4">
                <h3 class="text-xl font-bold text-gray-900 mb-4">Course Details</h3>
                
                <div class="space-y-4 mb-6">
                    <?php if ($course['duration']): ?>
                        <div class="flex items-center text-gray-700">
                            <svg class="w-5 h-5 mr-3 text-purple-600" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z"/>
                            </svg>
                            <div>
                                <div class="text-sm text-gray-500">Duration</div>
                                <div class="font-semibold"><?php echo e($course['duration']); ?></div>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <div class="flex items-center text-gray-700">
                        <svg class="w-5 h-5 mr-3 text-purple-600" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                        </svg>
                        <div>
                            <div class="text-sm text-gray-500">Students</div>
                            <div class="font-semibold"><?php echo count($course['modules'] ?? []); ?> modules</div>
                        </div>
                    </div>
                    
                    <div class="flex items-center text-gray-700">
                        <svg class="w-5 h-5 mr-3 text-purple-600" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                        </svg>
                        <div>
                            <div class="text-sm text-gray-500">Level</div>
                            <div class="font-semibold"><?php echo e(ucfirst($course['difficulty_level'])); ?></div>
                        </div>
                    </div>
                </div>

                <?php if ($isEnrolled): ?>
                    <div class="mb-4">
                        <div class="text-sm text-gray-600 mb-2">Your Progress</div>
                        <div class="w-full bg-gray-200 rounded-full h-3">
                            <div class="bg-gradient-primary h-3 rounded-full transition-all" style="width: <?php echo $enrollment['progress']; ?>%"></div>
                        </div>
                        <div class="text-sm text-gray-600 mt-1"><?php echo round($enrollment['progress']); ?>% complete</div>
                    </div>
                    
                    <a href="<?php echo base_url('learning.php?id=' . $course['id']); ?>" class="block w-full text-center px-6 py-3 bg-gradient-primary text-white rounded-lg font-semibold hover:opacity-90 transition">
                        Continue Learning
                    </a>
                <?php elseif (Session::isLoggedIn()): ?>
                    <form action="<?php echo base_url('enroll-process.php'); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="course_id" value="<?php echo $course['id']; ?>">
                        <button type="submit" class="w-full px-6 py-3 bg-gradient-primary text-white rounded-lg font-semibold hover:opacity-90 transition">
                            Enroll Now - Free
                        </button>
                    </form>
                <?php else: ?>
                    <a href="<?php echo base_url('auth/login.php'); ?>" class="block w-full text-center px-6 py-3 bg-gradient-primary text-white rounded-lg font-semibold hover:opacity-90 transition">
                        Login to Enroll
                    </a>
                <?php endif; ?>
                
                <p class="text-center text-sm text-gray-500 mt-4">Full lifetime access</p>
            </div>
        </div>
    </div>
</div>

<script>
// Module toggle functionality
document.querySelectorAll('.module-toggle').forEach(button => {
    button.addEventListener('click', function() {
        const content = this.nextElementSibling;
        const icon = this.querySelector('.module-icon');
        
        content.classList.toggle('hidden');
        icon.classList.toggle('rotate-180');
    });
});
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
