<?php
/**
 * Admin Edit Assessment View
 */
?>

<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Edit Assessment</h1>
    <p class="text-gray-600 mt-1">Update assessment details and manage questions</p>
</div>

<!-- Assessment Details -->
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <h2 class="text-lg font-semibold text-gray-800 mb-4">Assessment Details</h2>
    <form method="POST" action="/admin/assessments/update/<?php echo $assessment['id']; ?>">
        <input type="hidden" name="csrf_token" value="<?php echo Session::generateToken(); ?>">
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Title -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Assessment Title *</label>
                <input type="text" name="title" value="<?php echo htmlspecialchars($assessment['title']); ?>" required 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
            </div>
            
            <!-- Description -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea name="description" rows="3" 
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"><?php echo htmlspecialchars($assessment['description'] ?? ''); ?></textarea>
            </div>
            
            <!-- Duration -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Duration (minutes) *</label>
                <input type="number" name="duration" value="<?php echo $assessment['duration']; ?>" required min="1" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
            </div>
            
            <!-- Passing Score -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Passing Score (%) *</label>
                <input type="number" name="passing_score" value="<?php echo $assessment['passing_score']; ?>" required min="0" max="100" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
            </div>
            
            <!-- Total Points (Read-only) -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Total Points (Calculated)</label>
                <input type="text" value="<?php echo $assessment['total_points']; ?>" disabled 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100">
                <p class="text-xs text-gray-500 mt-1">Automatically calculated from questions</p>
            </div>
        </div>
        
        <div class="mt-6 flex justify-end gap-4">
            <a href="/admin/assessments" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                Back to Assessments
            </a>
            <button type="submit" class="btn-primary text-white px-6 py-2 rounded-lg">
                Update Assessment
            </button>
        </div>
    </form>
</div>

<!-- Questions Management -->
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-lg font-semibold text-gray-800">Questions (<?php echo count($assessment['questions']); ?>)</h2>
        <button onclick="showAddQuestionForm()" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 text-sm">
            + Add Question
        </button>
    </div>
    
    <?php if (empty($assessment['questions'])): ?>
        <p class="text-gray-500 text-center py-8">No questions added yet. Click "Add Question" to get started.</p>
    <?php else: ?>
        <div class="space-y-4">
            <?php foreach ($assessment['questions'] as $index => $question): ?>
                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="flex justify-between items-start mb-3">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="bg-purple-100 text-purple-800 text-xs font-semibold px-2 py-1 rounded">
                                    Q<?php echo $index + 1; ?>
                                </span>
                                <span class="bg-gray-100 text-gray-800 text-xs px-2 py-1 rounded capitalize">
                                    <?php echo str_replace('_', ' ', $question['question_type']); ?>
                                </span>
                                <span class="text-xs text-gray-600">
                                    <?php echo $question['points']; ?> points
                                </span>
                            </div>
                            <p class="text-gray-800 font-medium"><?php echo htmlspecialchars($question['question_text']); ?></p>
                            
                            <?php if ($question['question_type'] === 'multiple_choice' && $question['options']): ?>
                                <div class="mt-2 ml-4 space-y-1">
                                    <?php 
                                    $options = json_decode($question['options'], true);
                                    if (is_array($options)) {
                                        foreach ($options as $option): 
                                    ?>
                                        <div class="flex items-center text-sm">
                                            <span class="<?php echo $option === $question['correct_answer'] ? 'text-green-600 font-semibold' : 'text-gray-600'; ?>">
                                                <?php echo $option === $question['correct_answer'] ? '✓' : '○'; ?> 
                                                <?php echo htmlspecialchars($option); ?>
                                            </span>
                                        </div>
                                    <?php 
                                        endforeach;
                                    }
                                    ?>
                                </div>
                            <?php else: ?>
                                <p class="mt-2 text-sm text-green-600">
                                    <strong>Answer:</strong> <?php echo htmlspecialchars($question['correct_answer']); ?>
                                </p>
                            <?php endif; ?>
                        </div>
                        <button onclick="deleteQuestion(<?php echo $question['id']; ?>)" 
                                class="text-red-500 hover:text-red-700 text-sm ml-4">
                            Delete
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<!-- Add Question Modal -->
<div id="addQuestionModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-10 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white mb-10">
        <h3 class="text-lg font-semibold mb-4">Add New Question</h3>
        <form method="POST" action="/admin/questions/store">
            <input type="hidden" name="csrf_token" value="<?php echo Session::generateToken(); ?>">
            <input type="hidden" name="assessment_id" value="<?php echo $assessment['id']; ?>">
            
            <!-- Question Type -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Question Type *</label>
                <select name="question_type" id="question_type" required onchange="toggleQuestionTypeFields()" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                    <option value="">Select type</option>
                    <option value="multiple_choice">Multiple Choice</option>
                    <option value="true_false">True/False</option>
                    <option value="short_answer">Short Answer</option>
                </select>
            </div>
            
            <!-- Question Text -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Question *</label>
                <textarea name="question_text" rows="3" required 
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"></textarea>
            </div>
            
            <!-- Options (for multiple choice) -->
            <div id="optionsField" class="mb-4 hidden">
                <label class="block text-sm font-medium text-gray-700 mb-2">Options</label>
                <div id="optionsList" class="space-y-2">
                    <input type="text" name="options[]" placeholder="Option 1" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                    <input type="text" name="options[]" placeholder="Option 2" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                    <input type="text" name="options[]" placeholder="Option 3" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                    <input type="text" name="options[]" placeholder="Option 4" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>
                <button type="button" onclick="addOption()" class="mt-2 text-purple-600 text-sm hover:text-purple-800">
                    + Add Another Option
                </button>
            </div>
            
            <!-- Correct Answer -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Correct Answer *</label>
                <input type="text" name="correct_answer" id="correct_answer" required 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                <p class="text-xs text-gray-500 mt-1" id="answerHint">Enter the exact correct answer</p>
            </div>
            
            <!-- Points -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Points *</label>
                <input type="number" name="points" required min="1" value="1" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
            </div>
            
            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeAddQuestionForm()" 
                        class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
                    Add Question
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Question Form -->
<form id="deleteQuestionForm" method="POST" style="display: none;">
    <input type="hidden" name="csrf_token" value="<?php echo Session::generateToken(); ?>">
    <input type="hidden" name="assessment_id" value="<?php echo $assessment['id']; ?>">
</form>

<script>
function showAddQuestionForm() {
    document.getElementById('addQuestionModal').classList.remove('hidden');
}

function closeAddQuestionForm() {
    document.getElementById('addQuestionModal').classList.add('hidden');
}

function toggleQuestionTypeFields() {
    const questionType = document.getElementById('question_type').value;
    const optionsField = document.getElementById('optionsField');
    const answerField = document.getElementById('correct_answer');
    const answerHint = document.getElementById('answerHint');
    
    if (questionType === 'multiple_choice') {
        optionsField.classList.remove('hidden');
        answerHint.textContent = 'Enter the exact text of the correct option';
    } else if (questionType === 'true_false') {
        optionsField.classList.add('hidden');
        answerHint.textContent = 'Enter "True" or "False"';
        answerField.value = 'True';
    } else {
        optionsField.classList.add('hidden');
        answerHint.textContent = 'Enter the exact correct answer';
    }
}

function addOption() {
    const optionsList = document.getElementById('optionsList');
    const optionCount = optionsList.children.length + 1;
    const input = document.createElement('input');
    input.type = 'text';
    input.name = 'options[]';
    input.placeholder = 'Option ' + optionCount;
    input.className = 'w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500';
    optionsList.appendChild(input);
}

function deleteQuestion(questionId) {
    if (confirm('Are you sure you want to delete this question?')) {
        const form = document.getElementById('deleteQuestionForm');
        form.action = '/admin/questions/delete/' + questionId;
        form.submit();
    }
}
</script>

        </main>
    </div>
</div>
