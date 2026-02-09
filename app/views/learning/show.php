<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container-fluid">
    <div class="flex h-screen pt-16">
        <!-- Sidebar Navigation -->
        <div class="w-80 bg-white border-r border-gray-200 overflow-y-auto">
            <div class="p-4 border-b border-gray-200">
                <a href="<?php echo base_url('course.php?id=' . $course['id']); ?>" class="flex items-center text-purple-600 hover:text-purple-800 mb-2">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Course
                </a>
                <h2 class="text-lg font-bold text-gray-900"><?php echo e($course['title']); ?></h2>
                
                <!-- Progress -->
                <div class="mt-3">
                    <div class="flex justify-between text-sm text-gray-600 mb-1">
                        <span>Progress</span>
                        <span id="progressText"><?php echo round($enrollment['progress']); ?>%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div id="progressBar" class="bg-gradient-primary h-2 rounded-full transition-all" style="width: <?php echo $enrollment['progress']; ?>%"></div>
                    </div>
                </div>
            </div>

            <!-- Module List -->
            <div class="p-4">
                <?php if (empty($course['modules'])): ?>
                    <p class="text-gray-600 text-sm">No modules available</p>
                <?php else: ?>
                    <?php foreach ($course['modules'] as $moduleIndex => $module): ?>
                        <div class="mb-4">
                            <h3 class="font-semibold text-gray-900 mb-2 flex items-center">
                                <span class="flex items-center justify-center w-6 h-6 bg-purple-100 text-purple-600 rounded-full mr-2 text-xs">
                                    <?php echo $moduleIndex + 1; ?>
                                </span>
                                <?php echo e($module['title']); ?>
                            </h3>
                            
                            <?php if (empty($module['resources'])): ?>
                                <p class="text-gray-500 text-sm ml-8">No resources</p>
                            <?php else: ?>
                                <ul class="space-y-1">
                                    <?php foreach ($module['resources'] as $resource): ?>
                                        <?php 
                                        $isCompleted = in_array($resource['id'], $completedResourceIds);
                                        $isCurrent = $currentResource && $currentResource['id'] == $resource['id'];
                                        ?>
                                        <li>
                                            <a href="<?php echo base_url('learning.php?id=' . $course['id'] . '&resource=' . $resource['id']); ?>" 
                                               class="flex items-center p-2 rounded <?php echo $isCurrent ? 'bg-purple-100 text-purple-700' : 'text-gray-700 hover:bg-gray-100'; ?>">
                                                <!-- Type Icon -->
                                                <?php if ($resource['type'] === 'video'): ?>
                                                    <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M8 5v14l11-7z"/>
                                                    </svg>
                                                <?php else: ?>
                                                    <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6z"/>
                                                    </svg>
                                                <?php endif; ?>
                                                
                                                <span class="text-sm flex-1 truncate"><?php echo e($resource['title']); ?></span>
                                                
                                                <!-- Completed Checkmark -->
                                                <?php if ($isCompleted): ?>
                                                    <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                                                    </svg>
                                                <?php endif; ?>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="flex-1 bg-gray-50 overflow-y-auto">
            <?php if ($currentResource): ?>
                <div class="max-w-6xl mx-auto p-6">
                    <!-- Resource Header -->
                    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1">
                                <h1 class="text-2xl font-bold text-gray-900 mb-2"><?php echo e($currentResource['title']); ?></h1>
                                <div class="flex items-center text-gray-600">
                                    <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm font-semibold">
                                        <?php echo e(ucfirst($currentResource['type'])); ?>
                                    </span>
                                </div>
                            </div>
                            
                            <button id="markCompleteBtn" 
                                    data-resource-id="<?php echo $currentResource['id']; ?>"
                                    data-course-id="<?php echo $course['id']; ?>"
                                    class="px-6 py-2 <?php echo in_array($currentResource['id'], $completedResourceIds) ? 'bg-green-500' : 'bg-purple-600'; ?> text-white rounded-lg hover:opacity-90 transition">
                                <?php echo in_array($currentResource['id'], $completedResourceIds) ? '✓ Completed' : 'Mark as Complete'; ?>
                            </button>
                        </div>
                    </div>

                    <!-- Resource Content -->
                    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                        <?php if ($currentResource['type'] === 'video'): ?>
                            <!-- Video Player -->
                            <div class="bg-black aspect-video">
                                <video id="videoPlayer" class="w-full h-full" controls>
                                    <source src="<?php echo e($currentResource['file_path']); ?>" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                            </div>
                        <?php elseif ($currentResource['type'] === 'document' || $currentResource['type'] === 'pdf'): ?>
                            <!-- PDF Viewer -->
                            <div class="p-6">
                                <div class="mb-4">
                                    <a href="<?php echo e($currentResource['file_path']); ?>" target="_blank" 
                                       class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M19 9h-4V3H9v6H5l7 7 7-7zM5 18v2h14v-2H5z"/>
                                        </svg>
                                        Download PDF
                                    </a>
                                </div>
                                <embed src="<?php echo e($currentResource['file_path']); ?>" 
                                       type="application/pdf" 
                                       class="w-full" 
                                       style="height: 800px;">
                            </div>
                        <?php else: ?>
                            <div class="p-6">
                                <p class="text-gray-600">Resource type not supported for preview.</p>
                                <a href="<?php echo e($currentResource['file_path']); ?>" target="_blank" 
                                   class="inline-flex items-center mt-4 px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M19 9h-4V3H9v6H5l7 7 7-7zM5 18v2h14v-2H5z"/>
                                    </svg>
                                    Download Resource
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Navigation -->
                    <div class="flex justify-between items-center">
                        <?php if ($prevResource): ?>
                            <a href="<?php echo base_url('learning.php?id=' . $course['id'] . '&resource=' . $prevResource['id']); ?>" 
                               class="flex items-center px-6 py-3 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                </svg>
                                Previous
                            </a>
                        <?php else: ?>
                            <div></div>
                        <?php endif; ?>

                        <?php if ($nextResource): ?>
                            <a href="<?php echo base_url('learning.php?id=' . $course['id'] . '&resource=' . $nextResource['id']); ?>" 
                               class="flex items-center px-6 py-3 bg-gradient-primary text-white rounded-lg hover:opacity-90 transition">
                                Next
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        <?php else: ?>
                            <div></div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php else: ?>
                <!-- No Resource Selected -->
                <div class="flex items-center justify-center h-full">
                    <div class="text-center">
                        <svg class="w-24 h-24 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                        <h3 class="text-xl font-semibold text-gray-700 mb-2">Welcome to the Course!</h3>
                        <p class="text-gray-600 mb-6">Select a resource from the sidebar to begin learning</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
// Mark as Complete functionality
const markCompleteBtn = document.getElementById('markCompleteBtn');

if (markCompleteBtn) {
    markCompleteBtn.addEventListener('click', async function() {
        const resourceId = this.getAttribute('data-resource-id');
        const courseId = this.getAttribute('data-course-id');
        const button = this;
        
        // Disable button during request
        button.disabled = true;
        button.style.opacity = '0.6';
        
        try {
            const response = await fetch('<?php echo base_url('mark-complete.php'); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    resource_id: parseInt(resourceId),
                    course_id: parseInt(courseId)
                })
            });
            
            const data = await response.json();
            
            if (data.success) {
                // Update button
                button.textContent = '✓ Completed';
                button.classList.remove('bg-purple-600');
                button.classList.add('bg-green-500');
                
                // Update progress bar
                const progressBar = document.getElementById('progressBar');
                const progressText = document.getElementById('progressText');
                if (progressBar && progressText) {
                    progressBar.style.width = data.progress + '%';
                    progressText.textContent = Math.round(data.progress) + '%';
                }
                
                // Show success message
                showNotification('Resource marked as completed!', 'success');
                
                // Reload page to update sidebar checkmarks
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                showNotification(data.message || 'Failed to update progress', 'error');
                button.disabled = false;
                button.style.opacity = '1';
            }
        } catch (error) {
            console.error('Error:', error);
            showNotification('An error occurred. Please try again.', 'error');
            button.disabled = false;
            button.style.opacity = '1';
        }
    });
}

// Show notification
function showNotification(message, type) {
    const notification = document.createElement('div');
    notification.className = `fixed top-20 right-4 z-50 px-6 py-4 rounded-lg shadow-lg max-w-md success-message ${
        type === 'success' ? 'bg-green-50 border border-green-200 text-green-800' : 'bg-red-50 border border-red-200 text-red-800'
    }`;
    notification.innerHTML = `
        <div class="flex items-center">
            <span class="text-2xl mr-3">${type === 'success' ? '✓' : '✕'}</span>
            <p class="font-medium">${message}</p>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.opacity = '0';
        setTimeout(() => notification.remove(), 500);
    }, 3000);
}

// Auto-mark video as complete when watched
const videoPlayer = document.getElementById('videoPlayer');
if (videoPlayer) {
    videoPlayer.addEventListener('ended', function() {
        if (markCompleteBtn && !markCompleteBtn.textContent.includes('Completed')) {
            markCompleteBtn.click();
        }
    });
}
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
