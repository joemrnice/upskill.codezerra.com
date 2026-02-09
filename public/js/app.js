/**
 * Main JavaScript File
 * Upskill Training Platform
 */

// Toast notification system
function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.innerHTML = `
        <div class="flex items-center justify-between">
            <span>${message}</span>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-gray-500 hover:text-gray-700">
                ×
            </button>
        </div>
    `;
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.remove();
    }, 5000);
}

// Form validation
function validateForm(formId) {
    const form = document.getElementById(formId);
    if (!form) return false;
    
    const inputs = form.querySelectorAll('[required]');
    let isValid = true;
    
    inputs.forEach(input => {
        if (!input.value.trim()) {
            input.classList.add('border-red-500');
            isValid = false;
        } else {
            input.classList.remove('border-red-500');
        }
    });
    
    return isValid;
}

// Password strength checker
function checkPasswordStrength(password) {
    let strength = 0;
    
    if (password.length >= 8) strength++;
    if (password.match(/[a-z]/)) strength++;
    if (password.match(/[A-Z]/)) strength++;
    if (password.match(/[0-9]/)) strength++;
    if (password.match(/[^a-zA-Z0-9]/)) strength++;
    
    return strength;
}

// Real-time password validation
document.addEventListener('DOMContentLoaded', function() {
    const passwordInput = document.getElementById('password');
    const strengthIndicator = document.getElementById('password-strength');
    
    if (passwordInput && strengthIndicator) {
        passwordInput.addEventListener('input', function() {
            const strength = checkPasswordStrength(this.value);
            const labels = ['Very Weak', 'Weak', 'Fair', 'Good', 'Strong'];
            const colors = ['red', 'orange', 'yellow', 'blue', 'green'];
            
            strengthIndicator.textContent = labels[strength - 1] || '';
            strengthIndicator.className = `text-${colors[strength - 1]}-600 text-sm mt-1`;
        });
    }
});

// Confirm dialog
function confirmAction(message) {
    return confirm(message);
}

// Delete confirmation
function confirmDelete(formId, message = 'Are you sure you want to delete this item?') {
    if (confirm(message)) {
        document.getElementById(formId).submit();
    }
}

// Search functionality
function searchItems(inputId, targetClass) {
    const input = document.getElementById(inputId);
    const filter = input.value.toLowerCase();
    const items = document.getElementsByClassName(targetClass);
    
    Array.from(items).forEach(item => {
        const text = item.textContent.toLowerCase();
        if (text.includes(filter)) {
            item.style.display = '';
        } else {
            item.style.display = 'none';
        }
    });
}

// Toggle view (grid/list)
function toggleView(viewType) {
    const container = document.getElementById('courses-container');
    if (!container) return;
    
    if (viewType === 'grid') {
        container.className = 'grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6';
    } else {
        container.className = 'space-y-4';
    }
}

// Modal functions
function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
}

// Close modal on outside click
document.addEventListener('click', function(event) {
    if (event.target.classList.contains('modal-overlay')) {
        event.target.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
});

// File upload preview
function previewImage(input) {
    const preview = document.getElementById('image-preview');
    if (!preview || !input.files || !input.files[0]) return;
    
    const reader = new FileReader();
    reader.onload = function(e) {
        preview.src = e.target.result;
        preview.classList.remove('hidden');
    };
    reader.readAsDataURL(input.files[0]);
}

// Quiz timer
let timerInterval;

function startTimer(duration, displayId) {
    let timer = duration;
    const display = document.getElementById(displayId);
    
    timerInterval = setInterval(function() {
        const minutes = parseInt(timer / 60, 10);
        const seconds = parseInt(timer % 60, 10);
        
        const displayMinutes = minutes < 10 ? "0" + minutes : minutes;
        const displaySeconds = seconds < 10 ? "0" + seconds : seconds;
        
        if (display) {
            display.textContent = displayMinutes + ":" + displaySeconds;
        }
        
        if (--timer < 0) {
            clearInterval(timerInterval);
            // Auto-submit form
            const form = document.getElementById('assessment-form');
            if (form) {
                showToast('Time is up! Submitting your answers...', 'warning');
                setTimeout(() => form.submit(), 2000);
            }
        }
    }, 1000);
}

function stopTimer() {
    if (timerInterval) {
        clearInterval(timerInterval);
    }
}

// Progress tracking
function markResourceComplete(resourceId) {
    fetch('/resource/complete', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            resource_id: resourceId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('Progress saved!', 'success');
            // Update progress bar if exists
            const progressBar = document.getElementById('progress-bar');
            if (progressBar && data.progress) {
                progressBar.style.width = data.progress + '%';
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

// Auto-save for assessments
let autoSaveTimeout;

function autoSaveAnswer(questionId, answer) {
    clearTimeout(autoSaveTimeout);
    
    autoSaveTimeout = setTimeout(() => {
        // Save to localStorage as backup
        const assessmentId = document.getElementById('assessment_id')?.value;
        if (assessmentId) {
            const key = `assessment_${assessmentId}_question_${questionId}`;
            localStorage.setItem(key, answer);
        }
    }, 1000);
}

// Load saved answers
function loadSavedAnswers() {
    const assessmentId = document.getElementById('assessment_id')?.value;
    if (!assessmentId) return;
    
    const questions = document.querySelectorAll('[data-question-id]');
    questions.forEach(question => {
        const questionId = question.dataset.questionId;
        const key = `assessment_${assessmentId}_question_${questionId}`;
        const savedAnswer = localStorage.getItem(key);
        
        if (savedAnswer) {
            const input = question.querySelector('input[type="radio"], input[type="text"], textarea');
            if (input) {
                if (input.type === 'radio') {
                    const radio = question.querySelector(`input[value="${savedAnswer}"]`);
                    if (radio) radio.checked = true;
                } else {
                    input.value = savedAnswer;
                }
            }
        }
    });
}

// Clear saved answers after submission
function clearSavedAnswers() {
    const assessmentId = document.getElementById('assessment_id')?.value;
    if (!assessmentId) return;
    
    const questions = document.querySelectorAll('[data-question-id]');
    questions.forEach(question => {
        const questionId = question.dataset.questionId;
        const key = `assessment_${assessmentId}_question_${questionId}`;
        localStorage.removeItem(key);
    });
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    // Load saved answers if on assessment page
    if (document.getElementById('assessment_id')) {
        loadSavedAnswers();
    }
    
    // Add smooth scrolling
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
});
