<?php 
$pageTitle = '404 - Page Not Found - ' . SITE_NAME;
require_once __DIR__ . '/../layouts/header.php';
?>

<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-purple-50 to-indigo-100">
    <div class="max-w-2xl w-full space-y-8">
        <!-- Error Container -->
        <div class="bg-white shadow-custom rounded-2xl p-8 md:p-12 text-center">
            <!-- Logo -->
            <div class="mx-auto h-20 w-20 bg-gradient-to-br from-purple-600 to-indigo-600 rounded-full flex items-center justify-center shadow-lg mb-8">
                <span class="text-white font-bold text-3xl">U</span>
            </div>
            
            <!-- 404 Image/Icon -->
            <div class="mb-8">
                <svg class="mx-auto h-32 w-32 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            
            <!-- Error Message -->
            <h1 class="text-6xl font-extrabold text-gray-900 mb-4">404</h1>
            <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-4">
                Page Not Found
            </h2>
            <p class="text-gray-600 mb-8 text-lg">
                Oops! The page you're looking for doesn't exist. It might have been moved or deleted.
            </p>
            
            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                <a href="<?php echo base_url('public/index.php'); ?>" 
                   class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Go Home
                </a>
                
                <button onclick="window.history.back()" 
                        class="inline-flex items-center px-6 py-3 border-2 border-purple-600 text-purple-600 font-semibold rounded-lg hover:bg-purple-50 transition duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Go Back
                </button>
            </div>
            
            <!-- Help Text -->
            <div class="mt-8 pt-8 border-t border-gray-200">
                <p class="text-sm text-gray-500">
                    Need help? 
                    <a href="<?php echo base_url('public/auth/login.php'); ?>" class="text-purple-600 hover:text-purple-700 font-medium">
                        Login
                    </a> 
                    or 
                    <a href="<?php echo base_url('public/courses.php'); ?>" class="text-purple-600 hover:text-purple-700 font-medium">
                        Browse Courses
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
