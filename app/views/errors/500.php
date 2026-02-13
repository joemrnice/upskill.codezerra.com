<?php 
$pageTitle = '500 - Server Error - ' . SITE_NAME;
require_once __DIR__ . '/../layouts/header.php';
?>

<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-purple-50 to-indigo-100">
    <div class="max-w-2xl w-full space-y-8">
        <!-- Error Container -->
        <div class="bg-white shadow-custom rounded-2xl p-8 md:p-12 text-center">
            <!-- Logo -->
            <div class="mx-auto h-20 w-20 bg-gradient-to-br from-red-600 to-orange-600 rounded-full flex items-center justify-center shadow-lg mb-8">
                <span class="text-white font-bold text-3xl">!</span>
            </div>
            
            <!-- Error Icon -->
            <div class="mb-8">
                <svg class="mx-auto h-32 w-32 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            
            <!-- Error Message -->
            <h1 class="text-6xl font-extrabold text-gray-900 mb-4">500</h1>
            <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-4">
                Internal Server Error
            </h2>
            <p class="text-gray-600 mb-8 text-lg">
                Something went wrong on our end. Our team has been notified and we're working to fix it.
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
                
                <button onclick="location.reload()" 
                        class="inline-flex items-center px-6 py-3 border-2 border-purple-600 text-purple-600 font-semibold rounded-lg hover:bg-purple-50 transition duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Try Again
                </button>
            </div>
            
            <!-- Support Info -->
            <div class="mt-8 pt-8 border-t border-gray-200">
                <p class="text-sm text-gray-500 mb-2">
                    If this problem persists, please contact our support team.
                </p>
                <p class="text-sm text-gray-600 font-medium">
                    <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    Support: <?php echo defined('ADMIN_EMAIL') ? ADMIN_EMAIL : 'admin@codezerra.com'; ?>
                </p>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
