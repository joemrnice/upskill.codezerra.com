<?php 
$pageTitle = 'My Profile - ' . SITE_NAME;
require_once __DIR__ . '/../layouts/header.php'; 
require_once __DIR__ . '/../layouts/user-nav.php';
?>

<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-2">
                My Profile
            </h1>
            <p class="text-gray-600">
                Manage your account settings and view your learning history
            </p>
        </div>

        <!-- Flash Messages -->
        <?php if (Session::hasFlash('success')): ?>
            <div class="mb-6 bg-green-50 border-l-4 border-green-400 p-4 rounded">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-700"><?php echo Session::getFlash('success'); ?></p>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php if (Session::hasFlash('error')): ?>
            <div class="mb-6 bg-red-50 border-l-4 border-red-400 p-4 rounded">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700"><?php echo Session::getFlash('error'); ?></p>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-custom p-6">
                    <div class="text-center mb-6">
                        <div class="w-24 h-24 bg-gradient-primary rounded-full mx-auto mb-4 flex items-center justify-center">
                            <span class="text-3xl font-bold text-white">
                                <?php echo strtoupper(substr($user['name'], 0, 2)); ?>
                            </span>
                        </div>
                        <h2 class="text-xl font-bold text-gray-900"><?php echo e($user['name']); ?></h2>
                        <p class="text-gray-600 text-sm"><?php echo e($user['email']); ?></p>
                        <p class="text-gray-500 text-sm mt-1">
                            Employee ID: <?php echo e($user['employee_id']); ?>
                        </p>
                    </div>
                    
                    <div class="border-t pt-6">
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600 text-sm">Member Since</span>
                                <span class="text-gray-900 font-medium text-sm">
                                    <?php echo date('M Y', strtotime($user['created_at'])); ?>
                                </span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600 text-sm">Role</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                    <?php echo ucfirst(e($user['role'])); ?>
                                </span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600 text-sm">Status</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo $user['status'] === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                                    <?php echo ucfirst(e($user['status'])); ?>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="border-t pt-6 mt-6">
                        <a href="<?php echo base_url('public/profile/certificates.php'); ?>" class="w-full btn-primary text-white px-4 py-2 rounded-lg font-medium inline-flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                            </svg>
                            View Certificates
                        </a>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Personal Information Form -->
                <div class="bg-white rounded-xl shadow-custom p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-6">Personal Information</h3>
                    
                    <form action="<?php echo base_url('public/profile/update.php'); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        
                        <div class="space-y-4">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                    Full Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="name" name="name" 
                                    value="<?php echo e($user['name']); ?>"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg input-focus"
                                    required>
                            </div>
                            
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                    Email Address <span class="text-red-500">*</span>
                                </label>
                                <input type="email" id="email" name="email" 
                                    value="<?php echo e($user['email']); ?>"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg input-focus"
                                    required>
                            </div>
                            
                            <div>
                                <label for="employee_id" class="block text-sm font-medium text-gray-700 mb-2">
                                    Employee ID <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="employee_id" name="employee_id" 
                                    value="<?php echo e($user['employee_id']); ?>"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg input-focus"
                                    required>
                            </div>
                        </div>
                        
                        <div class="mt-6">
                            <button type="submit" class="btn-primary text-white px-6 py-2 rounded-lg font-medium">
                                Update Profile
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Change Password Form -->
                <div class="bg-white rounded-xl shadow-custom p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-6">Change Password</h3>
                    
                    <form action="<?php echo base_url('public/profile/update-password.php'); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        
                        <div class="space-y-4">
                            <div>
                                <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">
                                    Current Password <span class="text-red-500">*</span>
                                </label>
                                <input type="password" id="current_password" name="current_password" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg input-focus"
                                    required>
                            </div>
                            
                            <div>
                                <label for="new_password" class="block text-sm font-medium text-gray-700 mb-2">
                                    New Password <span class="text-red-500">*</span>
                                </label>
                                <input type="password" id="new_password" name="new_password" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg input-focus"
                                    required minlength="8">
                                <p class="text-xs text-gray-500 mt-1">Must be at least 8 characters</p>
                            </div>
                            
                            <div>
                                <label for="confirm_password" class="block text-sm font-medium text-gray-700 mb-2">
                                    Confirm New Password <span class="text-red-500">*</span>
                                </label>
                                <input type="password" id="confirm_password" name="confirm_password" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg input-focus"
                                    required minlength="8">
                            </div>
                        </div>
                        
                        <div class="mt-6">
                            <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-2 rounded-lg font-medium transition">
                                Change Password
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Enrollment History -->
                <div class="bg-white rounded-xl shadow-custom p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-6">Enrollment History</h3>
                    
                    <?php if (empty($enrollments)): ?>
                        <div class="text-center py-8">
                            <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                            <p class="text-gray-500">You haven't enrolled in any courses yet.</p>
                            <a href="<?php echo base_url('public/courses/index.php'); ?>" class="text-purple-600 hover:text-purple-700 font-medium mt-2 inline-block">
                                Browse Courses
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="space-y-4">
                            <?php foreach ($enrollments as $enrollment): ?>
                                <div class="border border-gray-200 rounded-lg p-4 hover:border-purple-300 transition">
                                    <div class="flex items-start justify-between mb-3">
                                        <div class="flex-1">
                                            <h4 class="font-semibold text-gray-900"><?php echo e($enrollment['title']); ?></h4>
                                            <p class="text-sm text-gray-600 mt-1">
                                                Enrolled: <?php echo date('M d, Y', strtotime($enrollment['enrollment_date'])); ?>
                                            </p>
                                        </div>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php 
                                            echo $enrollment['status'] === 'completed' ? 'bg-green-100 text-green-800' : 
                                                ($enrollment['status'] === 'in_progress' ? 'bg-blue-100 text-blue-800' : 
                                                'bg-gray-100 text-gray-800'); 
                                        ?>">
                                            <?php echo ucfirst(str_replace('_', ' ', e($enrollment['status']))); ?>
                                        </span>
                                    </div>
                                    
                                    <div class="mb-2">
                                        <div class="flex items-center justify-between text-sm mb-1">
                                            <span class="text-gray-600">Progress</span>
                                            <span class="font-medium text-gray-900"><?php echo number_format($enrollment['progress'], 0); ?>%</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div class="bg-gradient-primary h-2 rounded-full transition-all duration-300" 
                                                style="width: <?php echo $enrollment['progress']; ?>%"></div>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-center justify-between mt-3">
                                        <a href="<?php echo base_url('public/courses/view.php?id=' . $enrollment['course_id']); ?>" 
                                            class="text-purple-600 hover:text-purple-700 text-sm font-medium">
                                            View Course →
                                        </a>
                                        <?php if ($enrollment['status'] === 'completed' && $enrollment['completed_at']): ?>
                                            <span class="text-xs text-gray-500">
                                                Completed: <?php echo date('M d, Y', strtotime($enrollment['completed_at'])); ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Test Scores Summary -->
                <div class="bg-white rounded-xl shadow-custom p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-6">Recent Test Scores</h3>
                    
                    <?php if (empty($testScores)): ?>
                        <div class="text-center py-8">
                            <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <p class="text-gray-500">No test scores available yet.</p>
                        </div>
                    <?php else: ?>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assessment</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Course</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Score</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <?php foreach ($testScores as $score): ?>
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-3 text-sm text-gray-900"><?php echo e($score['assessment_title']); ?></td>
                                            <td class="px-4 py-3 text-sm text-gray-600"><?php echo e($score['course_title']); ?></td>
                                            <td class="px-4 py-3 text-sm">
                                                <span class="font-medium text-gray-900">
                                                    <?php echo number_format($score['percentage'], 1); ?>%
                                                </span>
                                                <span class="text-gray-500 text-xs">
                                                    (<?php echo $score['score']; ?>/<?php echo $score['total_points']; ?>)
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-sm">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php 
                                                    echo $score['status'] === 'passed' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; 
                                                ?>">
                                                    <?php echo ucfirst(e($score['status'])); ?>
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-600">
                                                <?php echo date('M d, Y', strtotime($score['submitted_at'])); ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
