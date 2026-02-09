<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="min-h-screen bg-gray-50 py-8">
    <div class="container mx-auto px-4">
        <!-- Result Header -->
        <div class="bg-white rounded-lg shadow-md p-8 mb-6">
            <div class="text-center mb-6">
                <?php if ($userAssessment['status'] === 'passed'): ?>
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-green-100 rounded-full mb-4">
                        <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <h1 class="text-3xl font-bold text-green-600 mb-2">Congratulations! You Passed!</h1>
                <?php else: ?>
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-red-100 rounded-full mb-4">
                        <svg class="w-12 h-12 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </div>
                    <h1 class="text-3xl font-bold text-red-600 mb-2">Assessment Not Passed</h1>
                <?php endif; ?>
                
                <p class="text-xl text-gray-600 mb-6"><?php echo e($userAssessment['assessment_title']); ?></p>
                
                <!-- Score Display -->
                <div class="flex items-center justify-center space-x-8 mb-6">
                    <div class="text-center">
                        <div class="text-5xl font-bold <?php echo $userAssessment['status'] === 'passed' ? 'text-green-600' : 'text-red-600'; ?>">
                            <?php echo number_format($userAssessment['percentage'], 1); ?>%
                        </div>
                        <div class="text-sm text-gray-600 mt-2">Your Score</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-gray-900">
                            <?php echo $userAssessment['score']; ?> / <?php echo $userAssessment['total_points']; ?>
                        </div>
                        <div class="text-sm text-gray-600 mt-2">Points Earned</div>
                    </div>
                </div>
                
                <!-- Progress Bar -->
                <div class="max-w-md mx-auto">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm text-gray-600">Passing Score</span>
                        <span class="text-sm font-semibold text-gray-900"><?php echo $userAssessment['passing_score']; ?>%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
                        <div class="<?php echo $userAssessment['status'] === 'passed' ? 'bg-green-600' : 'bg-red-600'; ?> h-3 rounded-full transition-all duration-500" 
                             style="width: <?php echo min($userAssessment['percentage'], 100); ?>%"></div>
                    </div>
                </div>
            </div>
            
            <!-- Submission Details -->
            <div class="border-t pt-6 grid grid-cols-1 md:grid-cols-3 gap-6 text-center">
                <div>
                    <div class="text-gray-600 text-sm mb-1">Course</div>
                    <div class="font-semibold text-gray-900"><?php echo e($userAssessment['course_title']); ?></div>
                </div>
                <div>
                    <div class="text-gray-600 text-sm mb-1">Submitted</div>
                    <div class="font-semibold text-gray-900"><?php echo date('M d, Y g:i A', strtotime($userAssessment['submitted_at'])); ?></div>
                </div>
                <div>
                    <div class="text-gray-600 text-sm mb-1">Graded</div>
                    <div class="font-semibold text-gray-900">
                        <?php echo $userAssessment['graded_at'] ? date('M d, Y g:i A', strtotime($userAssessment['graded_at'])) : 'Pending'; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 mb-6">
            <a href="<?php echo base_url('public/learning.php?course_id=' . $userAssessment['course_id']); ?>" 
               class="flex-1 bg-purple-600 hover:bg-purple-700 text-white font-semibold py-3 px-6 rounded-lg text-center transition-colors">
                Back to Course
            </a>
            
            <?php if ($canRetake && $userAssessment['status'] === 'failed'): ?>
                <a href="<?php echo base_url('public/assessment.php?id=' . $userAssessment['assessment_id']); ?>" 
                   class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg text-center transition-colors">
                    Retake Assessment
                </a>
            <?php elseif (!$canRetake && $userAssessment['status'] === 'failed'): ?>
                <div class="flex-1 bg-gray-400 text-white font-semibold py-3 px-6 rounded-lg text-center cursor-not-allowed">
                    Maximum Attempts Reached
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Detailed Results -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Question Review</h2>
            
            <div class="space-y-6">
                <?php foreach ($userAssessment['answers'] as $index => $answer): ?>
                    <div class="border-l-4 <?php echo $answer['is_correct'] ? 'border-green-500 bg-green-50' : 'border-red-500 bg-red-50'; ?> p-6 rounded-lg">
                        <!-- Question Header -->
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1">
                                <div class="flex items-center mb-2">
                                    <span class="inline-flex items-center justify-center w-8 h-8 <?php echo $answer['is_correct'] ? 'bg-green-500' : 'bg-red-500'; ?> text-white rounded-full font-semibold mr-3">
                                        <?php echo $index + 1; ?>
                                    </span>
                                    <span class="text-sm font-semibold <?php echo $answer['is_correct'] ? 'text-green-700' : 'text-red-700'; ?>">
                                        <?php echo $answer['is_correct'] ? 'Correct' : 'Incorrect'; ?>
                                    </span>
                                    <span class="ml-4 text-sm text-gray-600">
                                        <?php echo $answer['points_earned']; ?> / <?php echo $answer['points']; ?> points
                                    </span>
                                </div>
                                <p class="text-lg text-gray-900 font-medium ml-11"><?php echo e($answer['question_text']); ?></p>
                            </div>
                        </div>
                        
                        <!-- Answer Details -->
                        <div class="ml-11 space-y-3">
                            <?php if ($answer['question_type'] === 'multiple_choice'): ?>
                                <?php 
                                $options = json_decode($answer['options'], true);
                                $userAnswer = $answer['answer'];
                                $correctAnswer = $answer['correct_answer'];
                                ?>
                                
                                <?php foreach ($options as $key => $value): ?>
                                    <div class="flex items-center p-3 rounded-lg border-2 
                                        <?php if ($key === $correctAnswer): ?>
                                            border-green-500 bg-green-100
                                        <?php elseif ($key === $userAnswer && $key !== $correctAnswer): ?>
                                            border-red-500 bg-red-100
                                        <?php else: ?>
                                            border-gray-200 bg-white
                                        <?php endif; ?>">
                                        
                                        <?php if ($key === $correctAnswer): ?>
                                            <svg class="w-5 h-5 text-green-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                        <?php elseif ($key === $userAnswer && $key !== $correctAnswer): ?>
                                            <svg class="w-5 h-5 text-red-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                            </svg>
                                        <?php else: ?>
                                            <span class="w-5 h-5 mr-2"></span>
                                        <?php endif; ?>
                                        
                                        <span class="<?php echo $key === $correctAnswer ? 'text-green-900 font-semibold' : ($key === $userAnswer ? 'text-red-900' : 'text-gray-700'); ?>">
                                            <?php echo e($value); ?>
                                            <?php if ($key === $correctAnswer): ?>
                                                <span class="ml-2 text-xs text-green-600">(Correct Answer)</span>
                                            <?php endif; ?>
                                        </span>
                                    </div>
                                <?php endforeach; ?>
                                
                            <?php elseif ($answer['question_type'] === 'true_false'): ?>
                                <?php 
                                $userAnswer = $answer['answer'];
                                $correctAnswer = $answer['correct_answer'];
                                ?>
                                
                                <?php foreach (['True', 'False'] as $option): ?>
                                    <div class="flex items-center p-3 rounded-lg border-2 
                                        <?php if ($option === $correctAnswer): ?>
                                            border-green-500 bg-green-100
                                        <?php elseif ($option === $userAnswer && $option !== $correctAnswer): ?>
                                            border-red-500 bg-red-100
                                        <?php else: ?>
                                            border-gray-200 bg-white
                                        <?php endif; ?>">
                                        
                                        <?php if ($option === $correctAnswer): ?>
                                            <svg class="w-5 h-5 text-green-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                        <?php elseif ($option === $userAnswer && $option !== $correctAnswer): ?>
                                            <svg class="w-5 h-5 text-red-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                            </svg>
                                        <?php else: ?>
                                            <span class="w-5 h-5 mr-2"></span>
                                        <?php endif; ?>
                                        
                                        <span class="<?php echo $option === $correctAnswer ? 'text-green-900 font-semibold' : ($option === $userAnswer ? 'text-red-900' : 'text-gray-700'); ?>">
                                            <?php echo $option; ?>
                                            <?php if ($option === $correctAnswer): ?>
                                                <span class="ml-2 text-xs text-green-600">(Correct Answer)</span>
                                            <?php endif; ?>
                                        </span>
                                    </div>
                                <?php endforeach; ?>
                                
                            <?php elseif ($answer['question_type'] === 'short_answer'): ?>
                                <div class="space-y-3">
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">Your Answer:</label>
                                        <div class="p-3 bg-white border-2 <?php echo $answer['is_correct'] ? 'border-green-500' : 'border-red-500'; ?> rounded-lg">
                                            <p class="text-gray-900"><?php echo e($answer['answer']); ?></p>
                                        </div>
                                    </div>
                                    <?php if (!$answer['is_correct']): ?>
                                        <div>
                                            <label class="block text-sm font-semibold text-green-700 mb-2">Correct Answer:</label>
                                            <div class="p-3 bg-green-100 border-2 border-green-500 rounded-lg">
                                                <p class="text-green-900 font-medium"><?php echo e($answer['correct_answer']); ?></p>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        
        <!-- Bottom Actions -->
        <div class="mt-6 flex flex-col sm:flex-row gap-4">
            <a href="<?php echo base_url('public/learning.php?course_id=' . $userAssessment['course_id']); ?>" 
               class="flex-1 bg-gray-600 hover:bg-gray-700 text-white font-semibold py-3 px-6 rounded-lg text-center transition-colors">
                Continue Learning
            </a>
            
            <?php if ($canRetake && $userAssessment['status'] === 'failed'): ?>
                <a href="<?php echo base_url('public/assessment.php?id=' . $userAssessment['assessment_id']); ?>" 
                   class="flex-1 bg-purple-600 hover:bg-purple-700 text-white font-semibold py-3 px-6 rounded-lg text-center transition-colors">
                    Try Again
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
