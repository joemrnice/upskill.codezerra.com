<?php
/**
 * Admin Enrollments Index View
 */
?>

<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Manage Enrollments</h1>
    <p class="text-gray-600 mt-1">View and manage all course enrollments</p>
</div>

<!-- Filters -->
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <form method="GET" action="/admin/enrollments" class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
            <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                <option value="">All Statuses</option>
                <option value="in_progress" <?php echo ($filters['status'] ?? '') === 'in_progress' ? 'selected' : ''; ?>>In Progress</option>
                <option value="completed" <?php echo ($filters['status'] ?? '') === 'completed' ? 'selected' : ''; ?>>Completed</option>
            </select>
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Course</label>
            <select name="course_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                <option value="">All Courses</option>
                <?php foreach ($courses as $course): ?>
                    <option value="<?php echo $course['id']; ?>" <?php echo ($filters['course_id'] ?? '') === (string)$course['id'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($course['title']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="flex items-end">
            <button type="submit" class="bg-purple-600 text-white px-6 py-2 rounded-lg hover:bg-purple-700 w-full">
                Apply Filters
            </button>
        </div>
    </form>
</div>

<!-- Enrollments Table -->
<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Course</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Progress</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Enrolled</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            <?php if (empty($enrollments)): ?>
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                        No enrollments found.
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($enrollments as $enrollment): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div>
                                <p class="font-medium text-gray-900"><?php echo htmlspecialchars($enrollment['user_name']); ?></p>
                                <p class="text-sm text-gray-500"><?php echo htmlspecialchars($enrollment['user_email']); ?></p>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm text-gray-900"><?php echo htmlspecialchars($enrollment['course_title']); ?></p>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="w-32 bg-gray-200 rounded-full h-2 mr-2">
                                    <div class="bg-purple-600 h-2 rounded-full" style="width: <?php echo $enrollment['progress']; ?>%"></div>
                                </div>
                                <span class="text-sm text-gray-700"><?php echo number_format($enrollment['progress'], 1); ?>%</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <?php if ($enrollment['status'] === 'completed'): ?>
                                <span class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">Completed</span>
                            <?php else: ?>
                                <span class="px-2 py-1 text-xs font-semibold text-blue-800 bg-blue-100 rounded-full">In Progress</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500"><?php echo date('M d, Y', strtotime($enrollment['enrollment_date'])); ?></td>
                        <td class="px-6 py-4 text-right text-sm font-medium">
                            <button onclick="confirmDelete(<?php echo $enrollment['id']; ?>, '<?php echo htmlspecialchars(addslashes($enrollment['user_name'])); ?>', '<?php echo htmlspecialchars(addslashes($enrollment['course_title'])); ?>')" 
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
                <a href="?page=<?php echo $i; ?><?php echo ($filters['status'] ?? '') ? '&status=' . urlencode($filters['status']) : ''; ?><?php echo ($filters['course_id'] ?? '') ? '&course_id=' . urlencode($filters['course_id']) : ''; ?>" 
                   class="relative inline-flex items-center px-4 py-2 border <?php echo $i === $page ? 'bg-purple-600 text-white border-purple-600' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50'; ?>">
                    <?php echo $i; ?>
                </a>
            <?php endfor; ?>
        </nav>
    </div>
<?php endif; ?>

<!-- Delete Form -->
<form id="deleteForm" method="POST" style="display: none;">
    <input type="hidden" name="csrf_token" value="<?php echo Session::generateToken(); ?>">
</form>

<script>
function confirmDelete(enrollmentId, userName, courseTitle) {
    if (confirm('Are you sure you want to delete ' + userName + '\'s enrollment in "' + courseTitle + '"?')) {
        const form = document.getElementById('deleteForm');
        form.action = '/admin/enrollments/delete/' + enrollmentId;
        form.submit();
    }
}
</script>

        </main>
    </div>
</div>
