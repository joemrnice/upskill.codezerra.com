<?php 
require_once __DIR__ . '/../../bootstrap.php';
$pageTitle = 'Forgot Password - ' . SITE_NAME;
require_once __DIR__ . '/../layouts/header.php';
?>

<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-purple-50 to-indigo-100">
    <div class="max-w-md w-full space-y-8">
        <!-- Logo and Title -->
        <div class="text-center">
            <div class="mx-auto h-16 w-16 bg-gradient-primary rounded-full flex items-center justify-center shadow-lg">
                <span class="text-white font-bold text-2xl">U</span>
            </div>
            <h2 class="mt-6 text-3xl font-extrabold text-gray-900">
                Forgot Password?
            </h2>
            <p class="mt-2 text-sm text-gray-600">
                No worries, we'll send you reset instructions
            </p>
        </div>

        <!-- Forgot Password Form -->
        <div class="bg-white shadow-custom rounded-2xl p-8">
            <form method="POST" action="<?php echo base_url('public/auth/forgot-password-process.php'); ?>" id="forgotPasswordForm" class="space-y-6">
                <?php echo csrf_field(); ?>
                
                <!-- Info Message -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-blue-700">
                                Enter your email address and we'll send you a link to reset your password.
                            </p>
                        </div>
                    </div>
                </div>
                
                <!-- Email Field -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        Email Address
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                            </svg>
                        </div>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            class="input-focus block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:outline-none transition"
                            placeholder="you@company.com"
                            required
                            autofocus
                        >
                    </div>
                    <span id="emailError" class="text-red-500 text-xs mt-1 hidden">Please enter a valid email address</span>
                </div>

                <!-- Submit Button -->
                <div>
                    <button 
                        type="submit" 
                        class="w-full btn-primary text-white py-3 px-4 rounded-lg font-semibold focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500"
                    >
                        Send Reset Link
                    </button>
                </div>

                <!-- Divider -->
                <div class="relative my-6">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-4 bg-white text-gray-500">Or</span>
                    </div>
                </div>

                <!-- Back to Login -->
                <div class="text-center">
                    <a href="<?php echo base_url('public/auth/login.php'); ?>" class="inline-flex items-center text-purple-600 hover:text-purple-500 font-medium transition">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Back to Login
                    </a>
                </div>
            </form>
        </div>

        <!-- Help Text -->
        <div class="text-center">
            <p class="text-sm text-gray-600">
                Remember your password? 
                <a href="<?php echo base_url('public/auth/login.php'); ?>" class="text-purple-600 hover:text-purple-500 font-medium">
                    Sign in
                </a>
            </p>
        </div>
    </div>
</div>

<script>
// Client-side validation
document.getElementById('forgotPasswordForm').addEventListener('submit', function(e) {
    const email = document.getElementById('email');
    const emailError = document.getElementById('emailError');
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    
    if (!emailRegex.test(email.value)) {
        emailError.classList.remove('hidden');
        email.classList.add('error-input');
        e.preventDefault();
    } else {
        emailError.classList.add('hidden');
        email.classList.remove('error-input');
    }
});

// Clear error on input
document.getElementById('email').addEventListener('input', function() {
    document.getElementById('emailError').classList.add('hidden');
    this.classList.remove('error-input');
});
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
