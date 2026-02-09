# Assessment/Quiz System Implementation

## Overview
This document describes the implementation of the assessment/quiz system for the Upskill Training Platform.

## Files Created

### Controllers
- **app/controllers/AssessmentController.php**
  - `show()` - Displays assessment/quiz taking interface
  - `submit()` - Processes assessment submission (POST with JSON response)
  - `result()` - Shows assessment results with detailed review

### Views
- **app/views/assessments/show.php** - Assessment taking interface
- **app/views/assessments/result.php** - Results page with detailed review

### Public Routes
- **public/assessment.php** - Routes to AssessmentController::show()
- **public/assessment-submit.php** - Routes to AssessmentController::submit()
- **public/assessment-result.php** - Routes to AssessmentController::result()

## Features Implemented

### 1. Assessment Display (show.php)
- **Timer Display**: Countdown timer showing remaining time
- **Question Navigation**: Sidebar with clickable question numbers
- **Progress Tracking**: Visual progress bar and answered question count
- **Question Types**:
  - Multiple Choice: Radio buttons with options
  - True/False: Two radio button options
  - Short Answer: Text area for free-form responses
- **Auto-save**: Automatically saves answers to localStorage
- **Auto-submit**: Automatically submits when timer expires
- **Responsive Design**: Mobile-friendly using Tailwind CSS

### 2. Assessment Submission (submit())
- **CSRF Protection**: Validates CSRF token on submission
- **Authentication Check**: Ensures user is logged in
- **Enrollment Validation**: Verifies user is enrolled in the course
- **Retake Limit**: Enforces maximum of 3 attempts
- **Score Calculation**: Uses Assessment model's submitUserAssessment()
- **JSON Response**: Returns success/error with redirect URL

### 3. Results Display (result.php)
- **Pass/Fail Status**: Clear visual indication of result
- **Score Display**: Shows percentage and points earned
- **Progress Bar**: Visual representation of score vs passing score
- **Detailed Review**: Question-by-question breakdown showing:
  - User's answer
  - Correct answer
  - Points earned
  - Visual indicators (green for correct, red for incorrect)
- **Retake Button**: Shown if user failed and has attempts remaining
- **Navigation**: Links back to course and learning page

## Security Features

### 1. Authentication
- All controller methods check if user is logged in
- Redirects to login page if not authenticated

### 2. Authorization
- Verifies user is enrolled in the course
- Validates ownership of assessment results
- Prevents unauthorized access

### 3. CSRF Protection
- Token generated and validated on form submission
- Uses Session::verifyToken() for validation

### 4. Input Validation
- Assessment ID validation
- User answer sanitization
- Prevents SQL injection through prepared statements

### 5. Rate Limiting
- Maximum 3 attempts per assessment
- Checked before allowing assessment start

## JavaScript Functionality

### Timer Management
```javascript
- Starts countdown timer on page load
- Saves time to localStorage every second
- Auto-submits when time expires
- Visual warning when 5 minutes remaining
```

### Auto-save
```javascript
- Saves answers to localStorage on input change
- Loads saved answers on page load
- Clears localStorage after successful submission
- Debounced for text inputs (1 second delay)
```

### Progress Tracking
```javascript
- Tracks answered questions in a Set
- Updates progress bar in real-time
- Updates question navigator buttons
- Color-codes answered questions (green)
```

### Question Navigation
```javascript
- Clickable question numbers
- Smooth scrolling to questions
- Visual indicators for answered/unanswered
```

## Database Tables Used

### assessments
- Stores assessment metadata
- Fields: id, course_id, title, description, duration, passing_score, total_points

### questions
- Stores individual questions
- Fields: id, assessment_id, question_text, question_type, options, correct_answer, points, order_number

### user_assessments
- Stores user assessment attempts
- Fields: id, user_id, assessment_id, score, total_points, percentage, status, submitted_at, graded_at

### user_answers
- Stores individual user answers
- Fields: id, user_assessment_id, question_id, answer, is_correct, points_earned

## Usage Flow

### Taking an Assessment
1. User navigates to `/public/assessment.php?id={assessment_id}`
2. System checks authentication and enrollment
3. System checks retake limit (max 3 attempts)
4. Assessment loads with questions and timer
5. User answers questions (auto-saved to localStorage)
6. User submits or timer expires (auto-submit)
7. System processes submission and calculates score
8. User redirected to results page

### Viewing Results
1. User navigates to `/public/assessment-result.php?id={user_assessment_id}`
2. System checks authentication and ownership
3. Results displayed with:
   - Overall score and pass/fail status
   - Detailed question review
   - Correct answers shown
   - Retake button (if applicable)

## Responsive Design

### Mobile (< 768px)
- Single column layout
- Stacked action buttons
- Simplified question navigator
- Touch-friendly input sizes

### Tablet (768px - 1024px)
- Two-column layout for some sections
- Larger buttons and inputs
- Side-by-side progress indicators

### Desktop (> 1024px)
- Full multi-column layout
- Sticky question navigator sidebar
- Expanded progress indicators
- Optimal spacing and typography

## Configuration

### Assessment Settings (config/config.php)
```php
'default_passing_score' => 70,
'allow_retake' => true,
'max_retake_attempts' => 3,
```

## Error Handling

### User-facing Errors
- Invalid assessment ID
- Not enrolled in course
- Maximum attempts reached
- Session expired
- Submission failures

### System Errors
- Database connection failures
- Transaction rollbacks
- Exception handling with try-catch blocks

## Future Enhancements

### Potential Improvements
- Question randomization
- Time extensions for accessibility
- Partial credit for short answers
- Question explanations
- Assessment analytics
- Export results to PDF
- Email notifications
- Timed questions (individual timers)
- Question pools
- Weighted questions

## Testing Checklist

- [x] PHP syntax validation
- [x] Controller methods exist
- [x] CSRF protection implemented
- [x] Authentication checks present
- [x] Enrollment validation present
- [x] Retake limit validation present
- [x] Timer functionality present
- [x] Auto-save functionality present
- [x] Progress tracking present
- [x] Question navigation present
- [x] Detailed results with correct answers
- [x] All question types supported
- [x] Responsive design implemented
- [x] Code review completed
- [x] Security scan (CodeQL) passed

## API Endpoints

### GET /public/assessment.php?id={assessment_id}
**Description**: Display assessment taking interface

**Parameters**:
- `id` (required): Assessment ID

**Response**: HTML page with assessment

### POST /public/assessment-submit.php
**Description**: Submit assessment answers

**Parameters** (POST):
- `csrf_token` (required): CSRF token
- `assessment_id` (required): Assessment ID
- `answers[{question_id}]` (required): User answers

**Response**: JSON
```json
{
  "success": true,
  "message": "Assessment submitted successfully",
  "redirect": "/public/assessment-result.php?id=123"
}
```

### GET /public/assessment-result.php?id={user_assessment_id}
**Description**: Display assessment results

**Parameters**:
- `id` (required): User Assessment ID

**Response**: HTML page with results

## Maintenance Notes

### Regular Maintenance
- Monitor localStorage usage
- Review and optimize database queries
- Update Tailwind CSS classes as needed
- Test timer accuracy across browsers
- Validate auto-save reliability

### Known Limitations
- localStorage has 5-10MB limit (sufficient for assessments)
- Timer relies on JavaScript (disabled JS = no timer)
- Short answer grading is case-insensitive exact match only
- No partial credit system currently

## Support

For issues or questions, contact the development team or refer to:
- Main README.md
- IMPLEMENTATION_SUMMARY.md
- Database schema documentation
