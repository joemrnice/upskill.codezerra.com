<nav class="bg-white shadow-sm border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Logo -->
            <div class="flex items-center">
                <a href="<?php echo base_url('public/index.php'); ?>" class="flex items-center">
                    <div class="w-10 h-10 bg-gradient-primary rounded-lg flex items-center justify-center">
                        <span class="text-white font-bold text-xl">U</span>
                    </div>
                    <span class="ml-3 text-xl font-bold text-gray-900"><?php echo SITE_NAME; ?></span>
                </a>
            </div>
            
            <!-- Navigation Links -->
            <div class="hidden md:flex items-center space-x-8">
                <a href="<?php echo base_url('public/index.php'); ?>" class="text-gray-700 hover:text-purple-600 font-medium transition">Home</a>
                <a href="<?php echo base_url('public/courses/index.php'); ?>" class="text-gray-700 hover:text-purple-600 font-medium transition">Courses</a>
                <a href="#" class="text-gray-700 hover:text-purple-600 font-medium transition">About</a>
            </div>
            
            <!-- Auth Links -->
            <div class="flex items-center space-x-4">
                <a href="<?php echo base_url('public/auth/login.php'); ?>" class="text-gray-700 hover:text-purple-600 font-medium transition">
                    Login
                </a>
                <a href="<?php echo base_url('public/auth/register.php'); ?>" class="btn-primary text-white px-6 py-2 rounded-lg font-medium">
                    Register
                </a>
            </div>
            
            <!-- Mobile menu button -->
            <div class="md:hidden">
                <button type="button" class="text-gray-700 hover:text-purple-600" onclick="toggleMobileMenu()">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>
    
    <!-- Mobile menu -->
    <div id="mobileMenu" class="hidden md:hidden border-t border-gray-200">
        <div class="px-4 py-4 space-y-3">
            <a href="<?php echo base_url('public/index.php'); ?>" class="block text-gray-700 hover:text-purple-600 font-medium">Home</a>
            <a href="<?php echo base_url('public/courses/index.php'); ?>" class="block text-gray-700 hover:text-purple-600 font-medium">Courses</a>
            <a href="#" class="block text-gray-700 hover:text-purple-600 font-medium">About</a>
            <div class="pt-3 border-t border-gray-200 space-y-2">
                <a href="<?php echo base_url('public/auth/login.php'); ?>" class="block text-center text-gray-700 hover:text-purple-600 font-medium py-2">Login</a>
                <a href="<?php echo base_url('public/auth/register.php'); ?>" class="block text-center btn-primary text-white px-6 py-2 rounded-lg font-medium">Register</a>
            </div>
        </div>
    </div>
</nav>

<script>
function toggleMobileMenu() {
    const menu = document.getElementById('mobileMenu');
    menu.classList.toggle('hidden');
}
</script>
