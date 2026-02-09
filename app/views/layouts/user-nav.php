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
                <a href="<?php echo base_url('public/index.php'); ?>" class="text-gray-700 hover:text-purple-600 font-medium transition">Dashboard</a>
                <a href="<?php echo base_url('public/courses/index.php'); ?>" class="text-gray-700 hover:text-purple-600 font-medium transition">Courses</a>
                <a href="<?php echo base_url('public/user/enrollments.php'); ?>" class="text-gray-700 hover:text-purple-600 font-medium transition">My Learning</a>
                <?php if (Session::isAdmin()): ?>
                <a href="<?php echo base_url('public/admin/dashboard.php'); ?>" class="text-gray-700 hover:text-purple-600 font-medium transition">Admin</a>
                <?php endif; ?>
            </div>
            
            <!-- User Menu -->
            <div class="flex items-center space-x-4">
                <!-- Notifications -->
                <button class="text-gray-600 hover:text-purple-600 relative">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    <span class="absolute top-0 right-0 block h-2 w-2 rounded-full bg-red-500"></span>
                </button>
                
                <!-- User Dropdown -->
                <div class="relative" x-data="{ open: false }" @click.away="open = false">
                    <button @click="open = !open" class="flex items-center space-x-3 text-gray-700 hover:text-purple-600">
                        <div class="w-8 h-8 bg-gradient-primary rounded-full flex items-center justify-center text-white font-semibold">
                            <?php echo strtoupper(substr(Session::get('user_name'), 0, 1)); ?>
                        </div>
                        <span class="hidden md:block font-medium"><?php echo e(Session::get('user_name')); ?></span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    
                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 z-50"
                         style="display: none;">
                        <div class="px-4 py-2 border-b border-gray-200">
                            <p class="text-sm font-medium text-gray-900"><?php echo e(Session::get('user_name')); ?></p>
                            <p class="text-xs text-gray-500"><?php echo e(Session::get('user_email')); ?></p>
                        </div>
                        <a href="<?php echo base_url('public/user/profile.php'); ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-purple-50">
                            Profile
                        </a>
                        <a href="<?php echo base_url('public/user/settings.php'); ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-purple-50">
                            Settings
                        </a>
                        <div class="border-t border-gray-200 mt-2"></div>
                        <form method="POST" action="<?php echo base_url('public/auth/logout.php'); ?>">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
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
            <a href="<?php echo base_url('public/index.php'); ?>" class="block text-gray-700 hover:text-purple-600 font-medium">Dashboard</a>
            <a href="<?php echo base_url('public/courses/index.php'); ?>" class="block text-gray-700 hover:text-purple-600 font-medium">Courses</a>
            <a href="<?php echo base_url('public/user/enrollments.php'); ?>" class="block text-gray-700 hover:text-purple-600 font-medium">My Learning</a>
            <?php if (Session::isAdmin()): ?>
            <a href="<?php echo base_url('public/admin/dashboard.php'); ?>" class="block text-gray-700 hover:text-purple-600 font-medium">Admin</a>
            <?php endif; ?>
            <div class="pt-3 border-t border-gray-200">
                <a href="<?php echo base_url('public/user/profile.php'); ?>" class="block text-gray-700 hover:text-purple-600 py-2">Profile</a>
                <a href="<?php echo base_url('public/user/settings.php'); ?>" class="block text-gray-700 hover:text-purple-600 py-2">Settings</a>
                <form method="POST" action="<?php echo base_url('public/auth/logout.php'); ?>" class="mt-2">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="block w-full text-left text-red-600 hover:text-red-800 py-2">Logout</button>
                </form>
            </div>
        </div>
    </div>
</nav>

<!-- Alpine.js for dropdown -->
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

<script>
function toggleMobileMenu() {
    const menu = document.getElementById('mobileMenu');
    menu.classList.toggle('hidden');
}
</script>
