<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="min-h-screen bg-gray-50 py-8">
    <div class="container mx-auto px-4">
        <!-- Assessment Header -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div class="mb-4 md:mb-0">
                    <h1 class="text-3xl font-bold text-gray-900 mb-2"><?php echo e($assessment['title']); ?></h1>
                    <p class="text-gray-600"><?php echo e($assessment['course_title']); ?></p>
                </div>
                <div class="flex items-center space-x-6">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-purple-600" id="timer">
                            <?php echo str_pad($assessment['duration'], 2, '0', STR_PAD_LEFT); ?>:00
                        </div>
                        <div class="text-sm text-gray-600">Time Remaining</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-gray-900">
                            <?php echo count($assessment['questions']); ?>
                        </div>
                        <div class="text-sm text-gray-600">Questions</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-gray-900">
                            <?php echo $assessment['total_points']; ?>
                        </div>
                        <div class="text-sm text-gray-600">Total Points</div>
                    </div>
                </div>
            </div>
            
            <!-- Instructions -->
            <div class="mt-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
                <div class="flex items-start">
                    <svg class="w-6 h-6 text-blue-600 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        <h3 class="font-semibold text-blue-900 mb-2">Instructions:</h3>
                        <ul class="text-sm text-blue-800 space-y-1">
                            <li>• You have <?php echo $assessment['duration']; ?> minutes to complete this assessment</li>
                            <li>• All questions must be answered before submission</li>
                            <li>• Your answers are automatically saved as you type</li>
                            <li>• Passing score: <?php echo $assessment['passing_score']; ?>%</li>
                            <li>• The assessment will auto-submit when time expires</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Progress Bar -->
        <div class="bg-white rounded-lg shadow-md p-4 mb-6">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-semibold text-gray-700">Progress</span>
                <span class="text-sm font-semibold text-gray-700" id="progress-text">0 / <?php echo count($assessment['questions']); ?> answered</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-3">
                <div id="progress-bar" class="bg-purple-600 h-3 rounded-full transition-all duration-300" style="width: 0%"></div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            <!-- Questions -->
            <div class="lg:col-span-3">
                <form id="assessment-form" class="space-y-6">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="assessment_id" value="<?php echo $assessment['id']; ?>">
                    
                    <?php foreach ($assessment['questions'] as $index => $question): ?>
                        <div class="bg-white rounded-lg shadow-md p-6 question-card" data-question-id="<?php echo $question['id']; ?>">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1">
                                    <div class="flex items-center mb-2">
                                        <span class="inline-flex items-center justify-center w-8 h-8 bg-purple-100 text-purple-600 rounded-full font-semibold mr-3">
                                            <?php echo $index + 1; ?>
                                        </span>
                                        <span class="text-sm font-semibold text-gray-600"><?php echo $question['points']; ?> point<?php echo $question['points'] > 1 ? 's' : ''; ?></span>
                                    </div>
                                    <p class="text-lg text-gray-900 font-medium ml-11"><?php echo e($question['question_text']); ?></p>
                                </div>
                            </div>

                            <?php if ($question['question_type'] === 'multiple_choice'): ?>
                                <?php $options = json_decode($question['options'], true); ?>
                                <div class="ml-11 space-y-3">
                                    <?php foreach ($options as $optionKey => $optionValue): ?>
                                        <label class="flex items-center p-4 border border-gray-200 rounded-lg cursor-pointer hover:bg-purple-50 hover:border-purple-300 transition-colors">
                                            <input type="radio" 
                                                   name="answers[<?php echo $question['id']; ?>]" 
                                                   value="<?php echo e($optionKey); ?>" 
                                                   class="w-4 h-4 text-purple-600 focus:ring-purple-500 question-input"
                                                   data-question-id="<?php echo $question['id']; ?>">
                                            <span class="ml-3 text-gray-700"><?php echo e($optionValue); ?></span>
                                        </label>
                                    <?php endforeach; ?>
                                </div>

                            <?php elseif ($question['question_type'] === 'true_false'): ?>
                                <div class="ml-11 space-y-3">
                                    <label class="flex items-center p-4 border border-gray-200 rounded-lg cursor-pointer hover:bg-purple-50 hover:border-purple-300 transition-colors">
                                        <input type="radio" 
                                               name="answers[<?php echo $question['id']; ?>]" 
                                               value="True" 
                                               class="w-4 h-4 text-purple-600 focus:ring-purple-500 question-input"
                                               data-question-id="<?php echo $question['id']; ?>">
                                        <span class="ml-3 text-gray-700">True</span>
                                    </label>
                                    <label class="flex items-center p-4 border border-gray-200 rounded-lg cursor-pointer hover:bg-purple-50 hover:border-purple-300 transition-colors">
                                        <input type="radio" 
                                               name="answers[<?php echo $question['id']; ?>]" 
                                               value="False" 
                                               class="w-4 h-4 text-purple-600 focus:ring-purple-500 question-input"
                                               data-question-id="<?php echo $question['id']; ?>">
                                        <span class="ml-3 text-gray-700">False</span>
                                    </label>
                                </div>

                            <?php elseif ($question['question_type'] === 'short_answer'): ?>
                                <div class="ml-11">
                                    <textarea name="answers[<?php echo $question['id']; ?>]" 
                                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent resize-none question-input"
                                              data-question-id="<?php echo $question['id']; ?>"
                                              rows="4" 
                                              placeholder="Type your answer here..."></textarea>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>

                    <div class="bg-white rounded-lg shadow-md p-6">
                        <button type="submit" 
                                id="submit-btn"
                                class="w-full md:w-auto bg-purple-600 hover:bg-purple-700 text-white font-semibold py-3 px-8 rounded-lg transition-colors disabled:bg-gray-400 disabled:cursor-not-allowed">
                            Submit Assessment
                        </button>
                    </div>
                </form>
            </div>

            <!-- Question Navigation Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-md p-6 sticky top-24">
                    <h3 class="font-semibold text-gray-900 mb-4">Question Navigator</h3>
                    <div class="grid grid-cols-5 lg:grid-cols-4 gap-2" id="question-nav">
                        <?php foreach ($assessment['questions'] as $index => $question): ?>
                            <button type="button" 
                                    class="question-nav-btn w-10 h-10 flex items-center justify-center border-2 border-gray-300 rounded-lg text-sm font-semibold hover:border-purple-400 transition-colors"
                                    data-question-id="<?php echo $question['id']; ?>"
                                    data-question-index="<?php echo $index; ?>">
                                <?php echo $index + 1; ?>
                            </button>
                        <?php endforeach; ?>
                    </div>
                    <div class="mt-6 space-y-2 text-sm">
                        <div class="flex items-center">
                            <div class="w-6 h-6 border-2 border-gray-300 rounded mr-2"></div>
                            <span class="text-gray-600">Unanswered</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-6 h-6 bg-green-500 border-2 border-green-500 rounded mr-2"></div>
                            <span class="text-gray-600">Answered</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const assessmentId = <?php echo $assessment['id']; ?>;
    const duration = <?php echo $assessment['duration']; ?> * 60; // Convert to seconds
    const totalQuestions = <?php echo count($assessment['questions']); ?>;
    const storageKey = `assessment_${assessmentId}_answers`;
    const timerKey = `assessment_${assessmentId}_timer`;
    
    let timeRemaining = duration;
    let timerInterval;
    let answeredQuestions = new Set();
    
    // Initialize timer
    const savedTime = localStorage.getItem(timerKey);
    if (savedTime) {
        timeRemaining = parseInt(savedTime);
    }
    
    // Load saved answers
    loadSavedAnswers();
    
    // Start timer
    startTimer();
    
    // Auto-save on input change
    document.querySelectorAll('.question-input').forEach(input => {
        input.addEventListener('change', function() {
            saveAnswers();
            updateQuestionStatus(this.dataset.questionId);
            updateProgress();
        });
        
        if (input.tagName === 'TEXTAREA') {
            input.addEventListener('input', debounce(function() {
                saveAnswers();
                updateQuestionStatus(this.dataset.questionId);
                updateProgress();
            }, 1000));
        }
    });
    
    // Question navigation
    document.querySelectorAll('.question-nav-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const index = this.dataset.questionIndex;
            const questionCards = document.querySelectorAll('.question-card');
            questionCards[index].scrollIntoView({ behavior: 'smooth', block: 'start' });
        });
    });
    
    // Form submission
    document.getElementById('assessment-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Check if all questions are answered
        const unansweredCount = totalQuestions - answeredQuestions.size;
        if (unansweredCount > 0) {
            if (!confirm(`You have ${unansweredCount} unanswered question(s). Are you sure you want to submit?`)) {
                return;
            }
        }
        
        submitAssessment();
    });
    
    function startTimer() {
        updateTimerDisplay();
        
        timerInterval = setInterval(() => {
            timeRemaining--;
            localStorage.setItem(timerKey, timeRemaining);
            updateTimerDisplay();
            
            if (timeRemaining <= 0) {
                clearInterval(timerInterval);
                alert('Time is up! Your assessment will be submitted automatically.');
                submitAssessment();
            }
        }, 1000);
    }
    
    function updateTimerDisplay() {
        const minutes = Math.floor(timeRemaining / 60);
        const seconds = timeRemaining % 60;
        const timerElement = document.getElementById('timer');
        timerElement.textContent = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
        
        // Change color when time is running out
        if (timeRemaining <= 300) { // 5 minutes
            timerElement.classList.remove('text-purple-600');
            timerElement.classList.add('text-red-600');
        }
    }
    
    function saveAnswers() {
        const formData = new FormData(document.getElementById('assessment-form'));
        const answers = {};
        
        for (let [key, value] of formData.entries()) {
            if (key.startsWith('answers[')) {
                const questionId = key.match(/\d+/)[0];
                answers[questionId] = value;
            }
        }
        
        localStorage.setItem(storageKey, JSON.stringify(answers));
    }
    
    function loadSavedAnswers() {
        const saved = localStorage.getItem(storageKey);
        if (!saved) return;
        
        const answers = JSON.parse(saved);
        
        for (let [questionId, value] of Object.entries(answers)) {
            const inputs = document.querySelectorAll(`[name="answers[${questionId}]"]`);
            
            inputs.forEach(input => {
                if (input.type === 'radio') {
                    if (input.value === value) {
                        input.checked = true;
                        updateQuestionStatus(questionId);
                    }
                } else if (input.tagName === 'TEXTAREA') {
                    input.value = value;
                    if (value.trim()) {
                        updateQuestionStatus(questionId);
                    }
                }
            });
        }
        
        updateProgress();
    }
    
    function updateQuestionStatus(questionId) {
        const inputs = document.querySelectorAll(`[data-question-id="${questionId}"]`);
        let isAnswered = false;
        
        inputs.forEach(input => {
            if (input.type === 'radio' && input.checked) {
                isAnswered = true;
            } else if (input.tagName === 'TEXTAREA' && input.value.trim()) {
                isAnswered = true;
            }
        });
        
        const navBtn = document.querySelector(`.question-nav-btn[data-question-id="${questionId}"]`);
        
        if (isAnswered) {
            answeredQuestions.add(questionId);
            navBtn.classList.remove('border-gray-300');
            navBtn.classList.add('bg-green-500', 'border-green-500', 'text-white');
        } else {
            answeredQuestions.delete(questionId);
            navBtn.classList.remove('bg-green-500', 'border-green-500', 'text-white');
            navBtn.classList.add('border-gray-300');
        }
    }
    
    function updateProgress() {
        const answered = answeredQuestions.size;
        const percentage = (answered / totalQuestions) * 100;
        
        document.getElementById('progress-bar').style.width = percentage + '%';
        document.getElementById('progress-text').textContent = `${answered} / ${totalQuestions} answered`;
    }
    
    function submitAssessment() {
        const submitBtn = document.getElementById('submit-btn');
        submitBtn.disabled = true;
        submitBtn.textContent = 'Submitting...';
        
        const formData = new FormData(document.getElementById('assessment-form'));
        
        fetch('<?php echo base_url('public/assessment-submit.php'); ?>', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Clear saved data
                localStorage.removeItem(storageKey);
                localStorage.removeItem(timerKey);
                clearInterval(timerInterval);
                
                // Redirect to results
                window.location.href = data.redirect;
            } else {
                alert('Error: ' + data.message);
                submitBtn.disabled = false;
                submitBtn.textContent = 'Submit Assessment';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while submitting the assessment. Please try again.');
            submitBtn.disabled = false;
            submitBtn.textContent = 'Submit Assessment';
        });
    }
    
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
    
    // Warn before leaving page
    window.addEventListener('beforeunload', function(e) {
        e.preventDefault();
        e.returnValue = '';
    });
});
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
