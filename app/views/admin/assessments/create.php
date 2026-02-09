<?php
/**
 * Admin Create Assessment View
 */
?>

<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Create New Assessment</h1>
    <p class="text-gray-600 mt-1">Create an assessment for a course</p>
</div>

<div class="bg-white rounded-lg shadow-md p-6">
    <form method="POST" action="/admin/assessments/store">
        <input type="hidden" name="csrf_token" value="<?php echo Session::generateToken(); ?>">
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Course -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Select Course *</label>
                <select name="course_id" required 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                    <option value="">-- Select a course --</option>
                    <?php foreach ($courses as $course): ?>
                        <option value="<?php echo $course['id']; ?>" <?php echo ($courseId ?? '') == $course['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($course['title']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <!-- Title -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Assessment Title *</label>
                <input type="text" name="title" required 
                       placeholder="e.g., Final Exam, Module 1 Quiz"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
            </div>
            
            <!-- Description -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea name="description" rows="3" 
                          placeholder="Brief description of the assessment..."
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"></textarea>
            </div>
            
            <!-- Duration -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Duration (minutes) *</label>
                <input type="number" name="duration" required min="1" 
                       placeholder="e.g., 60"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
            </div>
            
            <!-- Passing Score -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Passing Score (%) *</label>
                <input type="number" name="passing_score" required min="0" max="100" 
                       placeholder="e.g., 70"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
            </div>
        </div>
        
        <div class="mt-6 flex justify-end gap-4">
            <a href="/admin/assessments" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                Cancel
            </a>
            <button type="submit" class="btn-primary text-white px-6 py-2 rounded-lg">
                Create Assessment
            </button>
        </div>
    </form>
</div>

        </main>
    </div>
</div>
