<?php
/**
 * Admin Edit Course View
 */
?>

<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Edit Course</h1>
    <p class="text-gray-600 mt-1">Update course details, modules, and resources</p>
</div>

<!-- Course Details -->
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <h2 class="text-lg font-semibold text-gray-800 mb-4">Course Details</h2>
    <form method="POST" action="/admin/courses/update/<?php echo $course['id']; ?>" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?php echo Session::generateToken(); ?>">
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Course Title *</label>
                <input type="text" name="title" value="<?php echo htmlspecialchars($course['title']); ?>" required 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
            </div>
            
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Description *</label>
                <textarea name="description" rows="4" required 
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"><?php echo htmlspecialchars($course['description']); ?></textarea>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Category *</label>
                <input type="text" name="category" value="<?php echo htmlspecialchars($course['category']); ?>" required 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Difficulty Level *</label>
                <select name="difficulty_level" required 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                    <option value="beginner" <?php echo $course['difficulty_level'] === 'beginner' ? 'selected' : ''; ?>>Beginner</option>
                    <option value="intermediate" <?php echo $course['difficulty_level'] === 'intermediate' ? 'selected' : ''; ?>>Intermediate</option>
                    <option value="advanced" <?php echo $course['difficulty_level'] === 'advanced' ? 'selected' : ''; ?>>Advanced</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Instructor Name</label>
                <input type="text" name="instructor_name" value="<?php echo htmlspecialchars($course['instructor_name'] ?? ''); ?>" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Duration (hours)</label>
                <input type="number" name="duration" value="<?php echo $course['duration'] ?? ''; ?>" min="0" step="0.5" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
            </div>
            
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Course Thumbnail</label>
                <?php if ($course['thumbnail']): ?>
                    <img src="/uploads/<?php echo htmlspecialchars($course['thumbnail']); ?>" alt="Current thumbnail" class="w-32 h-32 object-cover rounded-lg mb-2">
                <?php endif; ?>
                <input type="file" name="thumbnail" accept="image/*" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
            </div>
            
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Prerequisites</label>
                <textarea name="prerequisites" rows="2" 
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"><?php echo htmlspecialchars($course['prerequisites'] ?? ''); ?></textarea>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                <select name="status" required 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                    <option value="draft" <?php echo $course['status'] === 'draft' ? 'selected' : ''; ?>>Draft</option>
                    <option value="published" <?php echo $course['status'] === 'published' ? 'selected' : ''; ?>>Published</option>
                </select>
            </div>
        </div>
        
        <div class="mt-6 flex justify-end gap-4">
            <a href="/admin/courses" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                Back to Courses
            </a>
            <button type="submit" class="btn-primary text-white px-6 py-2 rounded-lg">
                Update Course
            </button>
        </div>
    </form>
</div>

<!-- Modules and Resources -->
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-lg font-semibold text-gray-800">Course Content (Modules & Resources)</h2>
        <button onclick="showAddModuleForm()" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 text-sm">
            + Add Module
        </button>
    </div>
    
    <?php if (empty($course['modules'])): ?>
        <p class="text-gray-500 text-center py-8">No modules added yet. Click "Add Module" to get started.</p>
    <?php else: ?>
        <div class="space-y-4">
            <?php foreach ($course['modules'] as $module): ?>
                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="flex justify-between items-start mb-3">
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-800"><?php echo htmlspecialchars($module['title']); ?></h3>
                            <p class="text-sm text-gray-600 mt-1"><?php echo htmlspecialchars($module['description'] ?? ''); ?></p>
                        </div>
                        <div class="flex gap-2">
                            <button onclick="showAddResourceForm(<?php echo $module['id']; ?>)" 
                                    class="text-green-600 hover:text-green-800 text-sm">
                                + Resource
                            </button>
                            <button onclick="deleteModule(<?php echo $module['id']; ?>)" 
                                    class="text-red-600 hover:text-red-800 text-sm">
                                Delete
                            </button>
                        </div>
                    </div>
                    
                    <?php if (!empty($module['resources'])): ?>
                        <div class="ml-4 space-y-2">
                            <?php foreach ($module['resources'] as $resource): ?>
                                <div class="flex items-center justify-between bg-gray-50 p-3 rounded">
                                    <div class="flex items-center">
                                        <?php
                                        $icon = match($resource['type']) {
                                            'video' => '<svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>',
                                            'document' => '<svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>',
                                            default => '<svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>'
                                        };
                                        echo $icon;
                                        ?>
                                        <span class="ml-3 text-sm"><?php echo htmlspecialchars($resource['title']); ?></span>
                                        <span class="ml-2 text-xs text-gray-500 capitalize">(<?php echo $resource['type']; ?>)</span>
                                    </div>
                                    <button onclick="deleteResource(<?php echo $resource['id']; ?>)" 
                                            class="text-red-500 hover:text-red-700 text-sm">
                                        Delete
                                    </button>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<!-- Add Module Modal -->
<div id="addModuleModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <h3 class="text-lg font-semibold mb-4">Add New Module</h3>
        <form method="POST" action="/admin/modules/store">
            <input type="hidden" name="csrf_token" value="<?php echo Session::generateToken(); ?>">
            <input type="hidden" name="course_id" value="<?php echo $course['id']; ?>">
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Module Title *</label>
                <input type="text" name="title" required 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea name="description" rows="3" 
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"></textarea>
            </div>
            
            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeAddModuleForm()" 
                        class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
                    Add Module
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Add Resource Modal -->
<div id="addResourceModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <h3 class="text-lg font-semibold mb-4">Add New Resource</h3>
        <form method="POST" action="/admin/resources/store" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?php echo Session::generateToken(); ?>">
            <input type="hidden" name="module_id" id="resource_module_id">
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Resource Title *</label>
                <input type="text" name="title" required 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Type *</label>
                <select name="type" required 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                    <option value="">Select type</option>
                    <option value="video">Video</option>
                    <option value="document">Document</option>
                    <option value="link">Link</option>
                </select>
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Upload File</label>
                <input type="file" name="file" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                <p class="text-xs text-gray-500 mt-1">For videos and documents</p>
            </div>
            
            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeAddResourceForm()" 
                        class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
                    Add Resource
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Forms -->
<form id="deleteModuleForm" method="POST" style="display: none;">
    <input type="hidden" name="csrf_token" value="<?php echo Session::generateToken(); ?>">
</form>

<form id="deleteResourceForm" method="POST" style="display: none;">
    <input type="hidden" name="csrf_token" value="<?php echo Session::generateToken(); ?>">
</form>

<script>
function showAddModuleForm() {
    document.getElementById('addModuleModal').classList.remove('hidden');
}

function closeAddModuleForm() {
    document.getElementById('addModuleModal').classList.add('hidden');
}

function showAddResourceForm(moduleId) {
    document.getElementById('resource_module_id').value = moduleId;
    document.getElementById('addResourceModal').classList.remove('hidden');
}

function closeAddResourceForm() {
    document.getElementById('addResourceModal').classList.add('hidden');
}

function deleteModule(moduleId) {
    if (confirm('Are you sure you want to delete this module? All resources in this module will also be deleted.')) {
        const form = document.getElementById('deleteModuleForm');
        form.action = '/admin/modules/delete/' + moduleId;
        form.submit();
    }
}

function deleteResource(resourceId) {
    if (confirm('Are you sure you want to delete this resource?')) {
        const form = document.getElementById('deleteResourceForm');
        form.action = '/admin/resources/delete/' + resourceId;
        form.submit();
    }
}
</script>

        </main>
    </div>
</div>
