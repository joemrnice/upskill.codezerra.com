    </main>
    
    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- About -->
                <div>
                    <h3 class="text-gray-900 font-semibold text-lg mb-4"><?php echo SITE_NAME; ?></h3>
                    <p class="text-gray-600 text-sm">Empowering employees through continuous learning and skill development.</p>
                </div>
                
                <!-- Quick Links -->
                <div>
                    <h3 class="text-gray-900 font-semibold text-lg mb-4">Quick Links</h3>
                    <ul class="space-y-2">
                        <li><a href="<?php echo base_url('public/courses/index.php'); ?>" class="text-gray-600 hover:text-purple-600 text-sm">Courses</a></li>
                        <li><a href="<?php echo base_url('public/index.php'); ?>" class="text-gray-600 hover:text-purple-600 text-sm">Dashboard</a></li>
                        <?php if (Session::isAdmin()): ?>
                        <li><a href="<?php echo base_url('public/admin/dashboard.php'); ?>" class="text-gray-600 hover:text-purple-600 text-sm">Admin Panel</a></li>
                        <?php endif; ?>
                    </ul>
                </div>
                
                <!-- Support -->
                <div>
                    <h3 class="text-gray-900 font-semibold text-lg mb-4">Support</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-600 hover:text-purple-600 text-sm">Help Center</a></li>
                        <li><a href="#" class="text-gray-600 hover:text-purple-600 text-sm">Documentation</a></li>
                        <li><a href="#" class="text-gray-600 hover:text-purple-600 text-sm">Contact Us</a></li>
                    </ul>
                </div>
                
                <!-- Legal -->
                <div>
                    <h3 class="text-gray-900 font-semibold text-lg mb-4">Legal</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-600 hover:text-purple-600 text-sm">Privacy Policy</a></li>
                        <li><a href="#" class="text-gray-600 hover:text-purple-600 text-sm">Terms of Service</a></li>
                        <li><a href="#" class="text-gray-600 hover:text-purple-600 text-sm">Cookie Policy</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-200 mt-8 pt-8 text-center">
                <p class="text-gray-600 text-sm">
                    &copy; <?php echo date('Y'); ?> <?php echo SITE_NAME; ?>. All rights reserved.
                </p>
            </div>
        </div>
    </footer>
    
    <!-- Scripts -->
    <script>
        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(function() {
                    alert.remove();
                }, 500);
            });
        }, 5000);
    </script>
</body>
</html>
