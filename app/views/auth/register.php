<?php 
require_once __DIR__ . '/../../bootstrap.php';
$pageTitle = 'Register - ' . SITE_NAME;
$errors = Session::get('errors', []);
$oldInput = Session::get('old_input', []);
Session::remove('errors');
Session::remove('old_input');
require_once __DIR__ . '/../layouts/header.php';
?>

<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-purple-50 to-indigo-100">
    <div class="max-w-2xl w-full space-y-8">
        <!-- Logo and Title -->
        <div class="text-center">
            <div class="mx-auto h-16 w-16 bg-gradient-primary rounded-full flex items-center justify-center shadow-lg">
                <span class="text-white font-bold text-2xl">U</span>
            </div>
            <h2 class="mt-6 text-3xl font-extrabold text-gray-900">
                Create Your Account
            </h2>
            <p class="mt-2 text-sm text-gray-600">
                Join us and start your learning journey today
            </p>
        </div>

        <!-- Registration Form -->
        <div class="bg-white shadow-custom rounded-2xl p-8">
            <form method="POST" action="<?php echo base_url('public/auth/register-process.php'); ?>" id="registerForm" class="space-y-6">
                <?php echo csrf_field(); ?>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Name Field -->
                    <div class="md:col-span-2">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Full Name *
                        </label>
                        <input 
                            type="text" 
                            id="name" 
                            name="name" 
                            value="<?php echo e($oldInput['name'] ?? ''); ?>"
                            class="input-focus block w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none transition <?php echo isset($errors['name']) ? 'error-input' : ''; ?>"
                            placeholder="John Doe"
                            required
                            autofocus
                        >
                        <?php if (isset($errors['name'])): ?>
                            <span class="text-red-500 text-xs mt-1"><?php echo e($errors['name']); ?></span>
                        <?php endif; ?>
                        <span id="nameError" class="text-red-500 text-xs mt-1 hidden">Name must be at least 3 characters</span>
                    </div>

                    <!-- Email Field -->
                    <div class="md:col-span-2">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email Address *
                        </label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            value="<?php echo e($oldInput['email'] ?? ''); ?>"
                            class="input-focus block w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none transition <?php echo isset($errors['email']) ? 'error-input' : ''; ?>"
                            placeholder="you@company.com"
                            required
                        >
                        <?php if (isset($errors['email'])): ?>
                            <span class="text-red-500 text-xs mt-1"><?php echo e($errors['email']); ?></span>
                        <?php endif; ?>
                        <span id="emailError" class="text-red-500 text-xs mt-1 hidden">Please enter a valid email address</span>
                    </div>

                    <!-- Employee ID Field -->
                    <div class="md:col-span-2">
                        <label for="employee_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Employee ID *
                        </label>
                        <input 
                            type="text" 
                            id="employee_id" 
                            name="employee_id" 
                            value="<?php echo e($oldInput['employee_id'] ?? ''); ?>"
                            class="input-focus block w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none transition <?php echo isset($errors['employee_id']) ? 'error-input' : ''; ?>"
                            placeholder="EMP12345"
                            required
                        >
                        <?php if (isset($errors['employee_id'])): ?>
                            <span class="text-red-500 text-xs mt-1"><?php echo e($errors['employee_id']); ?></span>
                        <?php endif; ?>
                        <span id="employeeIdError" class="text-red-500 text-xs mt-1 hidden">Employee ID is required</span>
                    </div>

                    <!-- Password Field -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            Password *
                        </label>
                        <div class="relative">
                            <input 
                                type="password" 
                                id="password" 
                                name="password" 
                                class="input-focus block w-full px-4 py-3 pr-10 border border-gray-300 rounded-lg focus:outline-none transition <?php echo isset($errors['password']) ? 'error-input' : ''; ?>"
                                placeholder="••••••••"
                                required
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
                        <?php if (isset($errors['password'])): ?>
                            <span class="text-red-500 text-xs mt-1"><?php echo e($errors['password']); ?></span>
                        <?php endif; ?>
                        <span id="passwordError" class="text-red-500 text-xs mt-1 hidden">Password must be at least 8 characters</span>
                        <div id="passwordStrength" class="mt-2">
                            <div class="flex space-x-1">
                                <div class="h-1 flex-1 rounded" id="strength1"></div>
                                <div class="h-1 flex-1 rounded" id="strength2"></div>
                                <div class="h-1 flex-1 rounded" id="strength3"></div>
                                <div class="h-1 flex-1 rounded" id="strength4"></div>
                            </div>
                            <p id="strengthText" class="text-xs mt-1"></p>
                        </div>
                    </div>

                    <!-- Confirm Password Field -->
                    <div>
                        <label for="confirm_password" class="block text-sm font-medium text-gray-700 mb-2">
                            Confirm Password *
                        </label>
                        <div class="relative">
                            <input 
                                type="password" 
                                id="confirm_password" 
                                name="confirm_password" 
                                class="input-focus block w-full px-4 py-3 pr-10 border border-gray-300 rounded-lg focus:outline-none transition <?php echo isset($errors['confirm_password']) ? 'error-input' : ''; ?>"
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
                        <?php if (isset($errors['confirm_password'])): ?>
                            <span class="text-red-500 text-xs mt-1"><?php echo e($errors['confirm_password']); ?></span>
                        <?php endif; ?>
                        <span id="confirmPasswordError" class="text-red-500 text-xs mt-1 hidden">Passwords do not match</span>
                    </div>
                </div>

                <!-- Terms and Conditions -->
                <div class="flex items-start">
                    <input 
                        type="checkbox" 
                        id="terms" 
                        name="terms" 
                        class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded mt-1"
                        required
                    >
                    <label for="terms" class="ml-2 block text-sm text-gray-700">
                        I agree to the <a href="#" class="text-purple-600 hover:text-purple-500">Terms of Service</a> and <a href="#" class="text-purple-600 hover:text-purple-500">Privacy Policy</a>
                    </label>
                </div>

                <!-- Submit Button -->
                <div>
                    <button 
                        type="submit" 
                        class="w-full btn-primary text-white py-3 px-4 rounded-lg font-semibold focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500"
                    >
                        Create Account
                    </button>
                </div>

                <!-- Divider -->
                <div class="relative my-6">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-4 bg-white text-gray-500">Already have an account?</span>
                    </div>
                </div>

                <!-- Login Link -->
                <div class="text-center">
                    <a href="<?php echo base_url('public/auth/login.php'); ?>" class="text-purple-600 hover:text-purple-500 font-medium transition">
                        Sign in instead →
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
    const textColors = ['', 'text-red-600', 'text-orange-600', 'text-yellow-600', 'text-green-600'];
    
    for (let i = 1; i <= 4; i++) {
        const bar = document.getElementById('strength' + i);
        bar.className = 'h-1 flex-1 rounded ' + (i <= strength ? colors[strength] : 'bg-gray-200');
    }
    
    document.getElementById('strengthText').textContent = texts[strength];
    document.getElementById('strengthText').className = 'text-xs mt-1 ' + textColors[strength];
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
document.getElementById('registerForm').addEventListener('submit', function(e) {
    let isValid = true;
    
    // Name validation
    const name = document.getElementById('name');
    const nameError = document.getElementById('nameError');
    if (name.value.length < 3) {
        nameError.classList.remove('hidden');
        name.classList.add('error-input');
        isValid = false;
    } else {
        nameError.classList.add('hidden');
        name.classList.remove('error-input');
    }
    
    // Email validation
    const email = document.getElementById('email');
    const emailError = document.getElementById('emailError');
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email.value)) {
        emailError.classList.remove('hidden');
        email.classList.add('error-input');
        isValid = false;
    } else {
        emailError.classList.add('hidden');
        email.classList.remove('error-input');
    }
    
    // Employee ID validation
    const employeeId = document.getElementById('employee_id');
    const employeeIdError = document.getElementById('employeeIdError');
    if (employeeId.value.length === 0) {
        employeeIdError.classList.remove('hidden');
        employeeId.classList.add('error-input');
        isValid = false;
    } else {
        employeeIdError.classList.add('hidden');
        employeeId.classList.remove('error-input');
    }
    
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
['name', 'email', 'employee_id', 'password', 'confirm_password'].forEach(function(field) {
    document.getElementById(field).addEventListener('input', function() {
        const errorId = field === 'employee_id' ? 'employeeIdError' : field === 'confirm_password' ? 'confirmPasswordError' : field + 'Error';
        document.getElementById(errorId).classList.add('hidden');
        this.classList.remove('error-input');
    });
});
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
