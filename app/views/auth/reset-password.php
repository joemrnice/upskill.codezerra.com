<?php 
require_once __DIR__ . '/../../bootstrap.php';
$pageTitle = 'Reset Password - ' . SITE_NAME;
$token = $token ?? '';
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
                Reset Your Password
            </h2>
            <p class="mt-2 text-sm text-gray-600">
                Enter your new password below
            </p>
        </div>

        <!-- Reset Password Form -->
        <div class="bg-white shadow-custom rounded-2xl p-8">
            <form method="POST" action="<?php echo base_url('public/auth/reset-password-process.php'); ?>" id="resetPasswordForm" class="space-y-6">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="token" value="<?php echo e($token); ?>">
                
                <!-- Password Field -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        New Password
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            class="input-focus block w-full pl-10 pr-10 py-3 border border-gray-300 rounded-lg focus:outline-none transition"
                            placeholder="••••••••"
                            required
                            autofocus
                        >
                        <button 
                            type="button" 
                            onclick="togglePassword('password', 'eyeIcon1')"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center"
                        >
                            <svg id="eyeIcon1" class="h-5 w-5 text-gray-400 hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </div>
                    <span id="passwordError" class="text-red-500 text-xs mt-1 hidden">Password must be at least 8 characters</span>
                    
                    <!-- Password Strength Indicator -->
                    <div id="passwordStrength" class="mt-2">
                        <div class="flex space-x-1">
                            <div class="h-1 flex-1 rounded bg-gray-200" id="strength1"></div>
                            <div class="h-1 flex-1 rounded bg-gray-200" id="strength2"></div>
                            <div class="h-1 flex-1 rounded bg-gray-200" id="strength3"></div>
                            <div class="h-1 flex-1 rounded bg-gray-200" id="strength4"></div>
                        </div>
                        <p id="strengthText" class="text-xs mt-1"></p>
                    </div>
                </div>

                <!-- Confirm Password Field -->
                <div>
                    <label for="confirm_password" class="block text-sm font-medium text-gray-700 mb-2">
                        Confirm New Password
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <input 
                            type="password" 
                            id="confirm_password" 
                            name="confirm_password" 
                            class="input-focus block w-full pl-10 pr-10 py-3 border border-gray-300 rounded-lg focus:outline-none transition"
                            placeholder="••••••••"
                            required
                        >
                        <button 
                            type="button" 
                            onclick="togglePassword('confirm_password', 'eyeIcon2')"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center"
                        >
                            <svg id="eyeIcon2" class="h-5 w-5 text-gray-400 hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </div>
                    <span id="confirmPasswordError" class="text-red-500 text-xs mt-1 hidden">Passwords do not match</span>
                </div>

                <!-- Password Requirements -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-xs font-medium text-gray-700 mb-2">Password must contain:</p>
                    <ul class="text-xs text-gray-600 space-y-1">
                        <li class="flex items-center">
                            <svg class="w-3 h-3 mr-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            At least 8 characters
                        </li>
                        <li class="flex items-center">
                            <svg class="w-3 h-3 mr-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            Mix of uppercase and lowercase letters
                        </li>
                        <li class="flex items-center">
                            <svg class="w-3 h-3 mr-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            At least one number
                        </li>
                    </ul>
                </div>

                <!-- Submit Button -->
                <div>
                    <button 
                        type="submit" 
                        class="w-full btn-primary text-white py-3 px-4 rounded-lg font-semibold focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500"
                    >
                        Reset Password
                    </button>
                </div>

                <!-- Back to Login -->
                <div class="text-center">
                    <a href="<?php echo base_url('public/auth/login.php'); ?>" class="inline-flex items-center text-purple-600 hover:text-purple-500 font-medium transition text-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Back to Login
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Password strength indicator
document.getElementById('password').addEventListener('input', function() {
    const password = this.value;
    const strength = calculatePasswordStrength(password);
    updateStrengthIndicator(strength);
});

function calculatePasswordStrength(password) {
    let strength = 0;
    if (password.length >= 8) strength++;
    if (password.match(/[a-z]+/)) strength++;
    if (password.match(/[A-Z]+/)) strength++;
    if (password.match(/[0-9]+/)) strength++;
    if (password.match(/[$@#&!]+/)) strength++;
    return Math.min(strength, 4);
}

function updateStrengthIndicator(strength) {
    const colors = ['bg-gray-200', 'bg-red-500', 'bg-orange-500', 'bg-yellow-500', 'bg-green-500'];
    const texts = ['', 'Weak', 'Fair', 'Good', 'Strong'];
    
    for (let i = 1; i <= 4; i++) {
        const bar = document.getElementById('strength' + i);
        bar.className = 'h-1 flex-1 rounded ' + (i <= strength ? colors[strength] : 'bg-gray-200');
    }
    
    document.getElementById('strengthText').textContent = texts[strength];
}

// Toggle password visibility
function togglePassword(fieldId, iconId) {
    const field = document.getElementById(fieldId);
    const icon = document.getElementById(iconId);
    
    if (field.type === 'password') {
        field.type = 'text';
        icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>';
    } else {
        field.type = 'password';
        icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>';
    }
}

// Form validation
document.getElementById('resetPasswordForm').addEventListener('submit', function(e) {
    let isValid = true;
    
    // Password validation
    const password = document.getElementById('password');
    const passwordError = document.getElementById('passwordError');
    if (password.value.length < 8) {
        passwordError.classList.remove('hidden');
        password.classList.add('error-input');
        isValid = false;
    } else {
        passwordError.classList.add('hidden');
        password.classList.remove('error-input');
    }
    
    // Confirm password validation
    const confirmPassword = document.getElementById('confirm_password');
    const confirmPasswordError = document.getElementById('confirmPasswordError');
    if (confirmPassword.value !== password.value) {
        confirmPasswordError.classList.remove('hidden');
        confirmPassword.classList.add('error-input');
        isValid = false;
    } else {
        confirmPasswordError.classList.add('hidden');
        confirmPassword.classList.remove('error-input');
    }
    
    if (!isValid) {
        e.preventDefault();
    }
});

// Clear errors on input
document.getElementById('password').addEventListener('input', function() {
    document.getElementById('passwordError').classList.add('hidden');
    this.classList.remove('error-input');
});

document.getElementById('confirm_password').addEventListener('input', function() {
    document.getElementById('confirmPasswordError').classList.add('hidden');
    this.classList.remove('error-input');
});
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
