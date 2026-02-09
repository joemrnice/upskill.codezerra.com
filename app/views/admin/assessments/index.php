<?php
/**
 * Admin Assessments Index View
 */
?>

<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Manage Assessments</h1>
        <p class="text-gray-600 mt-1">Create and manage course assessments</p>
    </div>
    <a href="/admin/assessments/create" class="btn-primary text-white px-6 py-3 rounded-lg font-semibold">
        + Create Assessment
    </a>
</div>

<!-- Course Filter -->
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <form method="GET" action="/admin/assessments" class="flex gap-4">
        <div class="flex-1">
            <label class="block text-sm font-medium text-gray-700 mb-2">Select Course</label>
            <select name="course_id" onchange="this.form.submit()" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                <option value="">-- Select a course to view assessments --</option>
                <?php foreach ($courses as $course): ?>
                    <option value="<?php echo $course['id']; ?>" <?php echo $courseId === (string)$course['id'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($course['title']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </form>
</div>

<?php if ($courseId && !empty($assessments)): ?>
    <!-- Assessments List -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($assessments as $assessment): ?>
            <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                <h3 class="text-lg font-semibold text-gray-800 mb-2"><?php echo htmlspecialchars($assessment['title']); ?></h3>
                <p class="text-sm text-gray-600 mb-4"><?php echo htmlspecialchars($assessment['description'] ?? 'No description'); ?></p>
                
                <div class="space-y-2 mb-4">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Duration:</span>
                        <span class="font-medium"><?php echo $assessment['duration']; ?> minutes</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Passing Score:</span>
                        <span class="font-medium"><?php echo $assessment['passing_score']; ?>%</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Total Points:</span>
                        <span class="font-medium"><?php echo $assessment['total_points']; ?></span>
                    </div>
                </div>
                
                <div class="flex gap-2">
                    <a href="/admin/assessments/edit/<?php echo $assessment['id']; ?>" 
                       class="flex-1 text-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 text-sm">
                        Edit & Manage Questions
                    </a>
                    <button onclick="confirmDelete(<?php echo $assessment['id']; ?>, '<?php echo htmlspecialchars(addslashes($assessment['title'])); ?>')" 
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 text-sm">
                        Delete
                    </button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php elseif ($courseId): ?>
    <div class="bg-white rounded-lg shadow-md p-8 text-center">
        <p class="text-gray-500 mb-4">No assessments found for this course.</p>
        <a href="/admin/assessments/create?course_id=<?php echo $courseId; ?>" 
           class="inline-block btn-primary text-white px-6 py-2 rounded-lg">
            Create First Assessment
        </a>
    </div>
<?php endif; ?>

<!-- Delete Form -->
<form id="deleteForm" method="POST" style="display: none;">
    <input type="hidden" name="csrf_token" value="<?php echo Session::generateToken(); ?>">
</form>

<script>
function confirmDelete(assessmentId, assessmentTitle) {
    if (confirm('Are you sure you want to delete "' + assessmentTitle + '"? All questions will also be deleted.')) {
        const form = document.getElementById('deleteForm');
        form.action = '/admin/assessments/delete/' + assessmentId;
        form.submit();
    }
}
</script>

        </main>
    </div>
</div>
