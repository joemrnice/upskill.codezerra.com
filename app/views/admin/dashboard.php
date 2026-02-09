<?php
/**
 * Admin Dashboard View
 */
?>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <!-- Total Users -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">Total Users</p>
                <p class="text-3xl font-bold text-gray-800"><?php echo number_format($userStats['total_users']); ?></p>
                <p class="text-xs text-green-600 mt-1">+<?php echo $userStats['new_today']; ?> today</p>
            </div>
            <div class="bg-blue-100 rounded-full p-3">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
            </div>
        </div>
    </div>
    
    <!-- Total Courses -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">Total Courses</p>
                <p class="text-3xl font-bold text-gray-800"><?php echo number_format($courseStats['total_courses']); ?></p>
                <p class="text-xs text-gray-600 mt-1"><?php echo $courseStats['published_count']; ?> published</p>
            </div>
            <div class="bg-purple-100 rounded-full p-3">
                <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
            </div>
        </div>
    </div>
    
    <!-- Total Enrollments -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">Total Enrollments</p>
                <p class="text-3xl font-bold text-gray-800"><?php echo number_format($enrollmentStats['total_enrollments']); ?></p>
                <p class="text-xs text-green-600 mt-1">+<?php echo $enrollmentStats['new_today']; ?> today</p>
            </div>
            <div class="bg-green-100 rounded-full p-3">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                </svg>
            </div>
        </div>
    </div>
    
    <!-- Completion Rate -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">Avg. Progress</p>
                <p class="text-3xl font-bold text-gray-800"><?php echo number_format($enrollmentStats['average_progress'], 1); ?>%</p>
                <p class="text-xs text-gray-600 mt-1"><?php echo $enrollmentStats['completed_count']; ?> completed</p>
            </div>
            <div class="bg-yellow-100 rounded-full p-3">
                <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <!-- Popular Courses -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Popular Courses</h3>
        <div class="space-y-4">
            <?php if (empty($popularCourses)): ?>
                <p class="text-gray-500 text-sm">No courses available yet.</p>
            <?php else: ?>
                <?php foreach ($popularCourses as $course): ?>
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <h4 class="font-medium text-gray-800"><?php echo htmlspecialchars($course['title']); ?></h4>
                            <p class="text-sm text-gray-500"><?php echo htmlspecialchars($course['category']); ?></p>
                        </div>
                        <span class="bg-purple-100 text-purple-800 text-xs font-medium px-2.5 py-0.5 rounded">
                            <?php echo $course['enrollment_count']; ?> enrolled
                        </span>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <div class="mt-4">
            <a href="/admin/courses" class="text-purple-600 hover:text-purple-800 text-sm font-medium">View all courses →</a>
        </div>
    </div>
    
    <!-- Recent Enrollments -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Recent Enrollments</h3>
        <div class="space-y-4">
            <?php if (empty($recentEnrollments)): ?>
                <p class="text-gray-500 text-sm">No enrollments yet.</p>
            <?php else: ?>
                <?php foreach ($recentEnrollments as $enrollment): ?>
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <h4 class="font-medium text-gray-800"><?php echo htmlspecialchars($enrollment['user_name']); ?></h4>
                            <p class="text-sm text-gray-500"><?php echo htmlspecialchars($enrollment['course_title']); ?></p>
                        </div>
                        <div class="text-right">
                            <span class="text-xs text-gray-500"><?php echo date('M d, Y', strtotime($enrollment['enrollment_date'])); ?></span>
                            <div class="w-16 bg-gray-200 rounded-full h-2 mt-1">
                                <div class="bg-purple-600 h-2 rounded-full" style="width: <?php echo $enrollment['progress']; ?>%"></div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <div class="mt-4">
            <a href="/admin/enrollments" class="text-purple-600 hover:text-purple-800 text-sm font-medium">View all enrollments →</a>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="bg-white rounded-lg shadow-md p-6">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Quick Actions</h3>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <a href="/admin/courses/create" class="flex items-center p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-purple-500 hover:bg-purple-50 transition-colors">
            <svg class="w-8 h-8 text-purple-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            <span class="font-medium text-gray-700">Create New Course</span>
        </a>
        
        <a href="/admin/assessments/create" class="flex items-center p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-purple-500 hover:bg-purple-50 transition-colors">
            <svg class="w-8 h-8 text-purple-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <span class="font-medium text-gray-700">Create Assessment</span>
        </a>
        
        <a href="/admin/users" class="flex items-center p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-purple-500 hover:bg-purple-50 transition-colors">
            <svg class="w-8 h-8 text-purple-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
            </svg>
            <span class="font-medium text-gray-700">Manage Users</span>
        </a>
    </div>
</div>

        </main>
    </div>
</div>
