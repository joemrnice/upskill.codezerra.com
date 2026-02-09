<?php 
$pageTitle = 'My Certificates - ' . SITE_NAME;
require_once __DIR__ . '/../layouts/header.php'; 
require_once __DIR__ . '/../layouts/user-nav.php';
?>

<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-2">
                        My Certificates
                    </h1>
                    <p class="text-gray-600">
                        View and download your earned certificates
                    </p>
                </div>
                <a href="<?php echo base_url('public/profile/index.php'); ?>" 
                    class="text-purple-600 hover:text-purple-700 font-medium inline-flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Profile
                </a>
            </div>
        </div>

        <?php if (empty($certificates)): ?>
            <!-- Empty State -->
            <div class="bg-white rounded-xl shadow-custom p-12 text-center">
                <div class="max-w-md mx-auto">
                    <div class="w-24 h-24 bg-purple-100 rounded-full mx-auto mb-6 flex items-center justify-center">
                        <svg class="w-12 h-12 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">No Certificates Yet</h2>
                    <p class="text-gray-600 mb-6">
                        You haven't earned any certificates yet. Complete courses and pass assessments to earn certificates.
                    </p>
                    <a href="<?php echo base_url('public/courses/index.php'); ?>" 
                        class="btn-primary text-white px-6 py-3 rounded-lg font-medium inline-flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                        Browse Courses
                    </a>
                </div>
            </div>
        <?php else: ?>
            <!-- Certificates Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <?php foreach ($certificates as $certificate): ?>
                    <div class="bg-white rounded-xl shadow-custom overflow-hidden hover:shadow-xl transition-all duration-300">
                        <!-- Certificate Header with Badge -->
                        <div class="bg-gradient-primary p-6 relative overflow-hidden">
                            <div class="absolute top-0 right-0 w-32 h-32 transform translate-x-8 -translate-y-8">
                                <div class="w-full h-full rounded-full bg-white opacity-10"></div>
                            </div>
                            <div class="absolute bottom-0 left-0 w-24 h-24 transform -translate-x-6 translate-y-6">
                                <div class="w-full h-full rounded-full bg-white opacity-10"></div>
                            </div>
                            
                            <div class="relative z-10">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center">
                                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                                        </svg>
                                    </div>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-white bg-opacity-20 text-white">
                                        Verified
                                    </span>
                                </div>
                                <h3 class="text-xl font-bold text-white mb-2">
                                    Certificate of Completion
                                </h3>
                                <p class="text-purple-100 text-sm">
                                    <?php echo e($certificate['course_title']); ?>
                                </p>
                            </div>
                        </div>

                        <!-- Certificate Details -->
                        <div class="p-6">
                            <div class="space-y-4">
                                <!-- Certificate Number -->
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <svg class="w-5 h-5 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-xs text-gray-500 uppercase tracking-wide">Certificate Number</p>
                                        <p class="text-sm font-mono font-medium text-gray-900">
                                            <?php echo e($certificate['certificate_number']); ?>
                                        </p>
                                    </div>
                                </div>

                                <!-- Issue Date -->
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <svg class="w-5 h-5 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-xs text-gray-500 uppercase tracking-wide">Date Issued</p>
                                        <p class="text-sm font-medium text-gray-900">
                                            <?php echo date('F d, Y', strtotime($certificate['issued_date'])); ?>
                                        </p>
                                    </div>
                                </div>

                                <!-- Instructor -->
                                <?php if (!empty($certificate['instructor_name'])): ?>
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <svg class="w-5 h-5 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-xs text-gray-500 uppercase tracking-wide">Instructor</p>
                                        <p class="text-sm font-medium text-gray-900">
                                            <?php echo e($certificate['instructor_name']); ?>
                                        </p>
                                    </div>
                                </div>
                                <?php endif; ?>

                                <!-- Duration -->
                                <?php if (!empty($certificate['duration'])): ?>
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <svg class="w-5 h-5 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-xs text-gray-500 uppercase tracking-wide">Course Duration</p>
                                        <p class="text-sm font-medium text-gray-900">
                                            <?php echo e($certificate['duration']); ?>
                                        </p>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>

                            <!-- Actions -->
                            <div class="mt-6 pt-6 border-t border-gray-200">
                                <div class="flex items-center justify-between gap-3">
                                    <?php if (!empty($certificate['file_path'])): ?>
                                        <a href="<?php echo base_url($certificate['file_path']); ?>" 
                                            target="_blank"
                                            class="flex-1 inline-flex items-center justify-center px-4 py-2 border border-purple-600 text-purple-600 rounded-lg hover:bg-purple-50 transition font-medium text-sm">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            View
                                        </a>
                                        <a href="<?php echo base_url($certificate['file_path']); ?>" 
                                            download
                                            class="flex-1 btn-primary text-white px-4 py-2 rounded-lg font-medium inline-flex items-center justify-center text-sm">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                            </svg>
                                            Download
                                        </a>
                                    <?php else: ?>
                                        <div class="w-full text-center py-2">
                                            <span class="text-sm text-gray-500">Certificate file not available</span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Summary Stats -->
            <div class="mt-8 bg-white rounded-xl shadow-custom p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-purple-600 mb-2">
                            <?php echo count($certificates); ?>
                        </div>
                        <p class="text-gray-600 text-sm">Total Certificates Earned</p>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-green-600 mb-2">
                            <?php 
                            $uniqueCourses = array_unique(array_column($certificates, 'course_id'));
                            echo count($uniqueCourses); 
                            ?>
                        </div>
                        <p class="text-gray-600 text-sm">Courses Completed</p>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-blue-600 mb-2">
                            <?php 
                            $firstCert = end($certificates);
                            $monthsSince = $firstCert ? floor((time() - strtotime($firstCert['issued_date'])) / (30 * 24 * 60 * 60)) : 0;
                            echo $monthsSince; 
                            ?>
                        </div>
                        <p class="text-gray-600 text-sm">Months of Learning</p>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
