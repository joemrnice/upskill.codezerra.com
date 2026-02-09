<?php
/**
 * Admin Create Course View
 */
?>

<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Create New Course</h1>
    <p class="text-gray-600 mt-1">Fill in the details to create a new course</p>
</div>

<div class="bg-white rounded-lg shadow-md p-6">
    <form method="POST" action="/admin/courses/store" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?php echo Session::generateToken(); ?>">
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Title -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Course Title *</label>
                <input type="text" name="title" required 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
            </div>
            
            <!-- Description -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Description *</label>
                <textarea name="description" rows="4" required 
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"></textarea>
            </div>
            
            <!-- Category -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Category *</label>
                <input type="text" name="category" required 
                       placeholder="e.g., Programming, Design, Marketing"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
            </div>
            
            <!-- Difficulty Level -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Difficulty Level *</label>
                <select name="difficulty_level" required 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                    <option value="">Select difficulty</option>
                    <option value="beginner">Beginner</option>
                    <option value="intermediate">Intermediate</option>
                    <option value="advanced">Advanced</option>
                </select>
            </div>
            
            <!-- Instructor Name -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Instructor Name</label>
                <input type="text" name="instructor_name" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
            </div>
            
            <!-- Duration -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Duration (hours)</label>
                <input type="number" name="duration" min="0" step="0.5" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
            </div>
            
            <!-- Thumbnail -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Course Thumbnail</label>
                <input type="file" name="thumbnail" accept="image/*" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                <p class="text-xs text-gray-500 mt-1">Accepted formats: JPG, PNG, WEBP (Max 5MB)</p>
            </div>
            
            <!-- Prerequisites -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Prerequisites</label>
                <textarea name="prerequisites" rows="2" 
                          placeholder="List any prerequisites for this course..."
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"></textarea>
            </div>
            
            <!-- Status -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                <select name="status" required 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                    <option value="draft">Draft</option>
                    <option value="published">Published</option>
                </select>
            </div>
        </div>
        
        <div class="mt-6 flex justify-end gap-4">
            <a href="/admin/courses" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                Cancel
            </a>
            <button type="submit" class="btn-primary text-white px-6 py-2 rounded-lg">
                Create Course
            </button>
        </div>
    </form>
</div>

        </main>
    </div>
</div>
