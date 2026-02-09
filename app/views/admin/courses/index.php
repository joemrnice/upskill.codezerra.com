<?php
/**
 * Admin Courses Index View
 */
?>

<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Manage Courses</h1>
        <p class="text-gray-600 mt-1">Create, edit, and manage all courses</p>
    </div>
    <a href="/admin/courses/create" class="btn-primary text-white px-6 py-3 rounded-lg font-semibold">
        + Create Course
    </a>
</div>

<!-- Search -->
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <form method="GET" action="/admin/courses" class="flex gap-4">
        <div class="flex-1">
            <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" 
                   placeholder="Search courses by title, category, or instructor..." 
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
        </div>
        <button type="submit" class="bg-purple-600 text-white px-6 py-2 rounded-lg hover:bg-purple-700">
            Search
        </button>
        <?php if ($search): ?>
            <a href="/admin/courses" class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-300">
                Clear
            </a>
        <?php endif; ?>
    </form>
</div>

<!-- Courses Table -->
<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Course</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Difficulty</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Enrollments</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            <?php if (empty($courses)): ?>
                <tr>
                    <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                        No courses found. <a href="/admin/courses/create" class="text-purple-600 hover:underline">Create your first course</a>
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($courses as $course): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <?php if ($course['thumbnail']): ?>
                                    <img src="/uploads/<?php echo htmlspecialchars($course['thumbnail']); ?>" 
                                         alt="<?php echo htmlspecialchars($course['title']); ?>" 
                                         class="w-16 h-16 rounded-lg object-cover mr-4">
                                <?php else: ?>
                                    <div class="w-16 h-16 bg-gray-200 rounded-lg mr-4 flex items-center justify-center">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                <?php endif; ?>
                                <div>
                                    <p class="font-medium text-gray-900"><?php echo htmlspecialchars($course['title']); ?></p>
                                    <p class="text-sm text-gray-500">by <?php echo htmlspecialchars($course['instructor_name'] ?? 'N/A'); ?></p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900"><?php echo htmlspecialchars($course['category']); ?></td>
                        <td class="px-6 py-4">
                            <span class="text-sm capitalize"><?php echo htmlspecialchars($course['difficulty_level']); ?></span>
                        </td>
                        <td class="px-6 py-4">
                            <?php if ($course['status'] === 'published'): ?>
                                <span class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">Published</span>
                            <?php else: ?>
                                <span class="px-2 py-1 text-xs font-semibold text-gray-800 bg-gray-100 rounded-full">Draft</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900"><?php echo $course['enrollment_count']; ?></td>
                        <td class="px-6 py-4 text-sm text-gray-500"><?php echo date('M d, Y', strtotime($course['created_at'])); ?></td>
                        <td class="px-6 py-4 text-right text-sm font-medium">
                            <a href="/admin/courses/edit/<?php echo $course['id']; ?>" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                            <button onclick="confirmDelete(<?php echo $course['id']; ?>, '<?php echo htmlspecialchars(addslashes($course['title'])); ?>')" 
                                    class="text-red-600 hover:text-red-900">Delete</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Pagination -->
<?php if ($totalPages > 1): ?>
    <div class="mt-6 flex justify-center">
        <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="?page=<?php echo $i; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>" 
                   class="relative inline-flex items-center px-4 py-2 border <?php echo $i === $page ? 'bg-purple-600 text-white border-purple-600' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50'; ?>">
                    <?php echo $i; ?>
                </a>
            <?php endfor; ?>
        </nav>
    </div>
<?php endif; ?>

<!-- Delete Form (Hidden) -->
<form id="deleteForm" method="POST" style="display: none;">
    <input type="hidden" name="csrf_token" value="<?php echo Session::generateToken(); ?>">
</form>

<script>
function confirmDelete(courseId, courseTitle) {
    if (confirm('Are you sure you want to delete "' + courseTitle + '"? This action cannot be undone.')) {
        const form = document.getElementById('deleteForm');
        form.action = '/admin/courses/delete/' + courseId;
        form.submit();
    }
}
</script>

        </main>
    </div>
</div>
