# Landing Page and User Dashboard Documentation

## Overview

This document describes the landing page and user dashboard implementation for the Upskill Training Platform.

## Components

### 1. HomeController (`app/controllers/HomeController.php`)

**Purpose**: Handles the landing page display for non-authenticated visitors.

**Key Features**:
- Retrieves real-time statistics from the database:
  - Total published courses
  - Total users enrolled
  - Completion rate percentage
  - Total certificates issued
- Fetches popular courses (top 6 by enrollment count)
- Passes data to the landing page view

**Methods**:
- `index()`: Main method that gathers statistics and displays the landing page

### 2. DashboardController (`app/controllers/DashboardController.php`)

**Purpose**: Handles the authenticated user dashboard.

**Security**:
- Requires authentication (checks `Session::isLoggedIn()`)
- Redirects to login page if user is not authenticated

**Key Features**:
- Displays user's enrolled courses with progress tracking
- Shows available courses for enrollment (excludes already enrolled courses)
- Provides recent activity feed (enrollments, completions, assessments)
- Lists earned certificates
- Calculates quick statistics (enrolled, completed, certificates)

**Methods**:
- `index()`: Main dashboard method
- `getAvailableCourses($userId)`: Retrieves courses not enrolled by user
- `getRecentActivity($userId)`: Fetches user's recent learning activities
- `getUserCertificates($userId)`: Gets user's earned certificates

### 3. Landing Page View (`app/views/home/index.php`)

**Sections**:

1. **Hero Section**
   - Compelling headline and value proposition
   - CTA buttons (Get Started / Browse Courses)
   - Decorative background elements
   - Feature highlights with icons

2. **Statistics Section**
   - Real-time database statistics
   - Courses offered, students enrolled, completion rate, certificates issued
   - Responsive grid layout

3. **Features Showcase**
   - Three main features:
     - Comprehensive Course Catalog
     - Earn Certifications
     - Track Your Progress
   - Card-based design with hover effects

4. **Popular Courses**
   - Displays top 6 courses by enrollment
   - Shows course category, difficulty level, and enrollment count
   - Links to course detail pages
   - "View All Courses" CTA button

5. **Testimonials**
   - Three sample testimonials
   - Star ratings
   - User avatars with initials

6. **Call to Action**
   - Final conversion section
   - Different CTAs for logged-in vs guest users

**Design Features**:
- Fully responsive (mobile, tablet, desktop)
- Tailwind CSS for styling
- Gradient backgrounds (purple to violet theme)
- Smooth animations and transitions
- Card hover effects
- Professional typography (Inter font)

### 4. Dashboard View (`app/views/user/dashboard.php`)

**Sections**:

1. **Welcome Section**
   - Personalized greeting with user's name
   - Subtitle encouragement message

2. **Quick Stats Cards**
   - Courses Enrolled (purple theme)
   - Courses Completed (green theme)
   - Certificates Earned (yellow theme)
   - Icon-based visual indicators

3. **My Courses Section**
   - Lists all enrolled courses
   - Progress bars showing completion percentage
   - Course status badges (In Progress / Completed)
   - "Continue Learning" or "View Certificate" buttons
   - Empty state with "Get Started" message for new users

4. **Recommended Courses**
   - Grid of available courses (not enrolled)
   - Shows top 6 by popularity
   - Card-based layout with course thumbnails
   - Quick enroll links

5. **Recent Activity Feed** (Sidebar)
   - Shows last 10 activities
   - Activity types:
     - Enrollments (blue icon)
     - Completions (green icon)
     - Assessments (purple icon with score)
   - Timestamp for each activity

6. **Certificates Section** (Sidebar)
   - Lists earned certificates
   - Shows course title and issue date
   - Links to view/download certificates
   - "View All" link if more than 3 certificates

7. **Quick Links** (Sidebar)
   - Browse All Courses
   - My Enrollments
   - My Certificates
   - Gradient background for visual emphasis

**Design Features**:
- Two-column layout (main content + sidebar)
- Responsive grid adapts to mobile
- Progress bars with gradient fills
- Status badges with color coding
- Activity icons with rounded backgrounds
- Card hover effects
- Professional spacing and typography

## Public Access Points

### Entry Points

1. **`public/index.php`**
   - Main entry point
   - Routes to landing page (guests) or dashboard (logged-in users)
   - Uses Session check to determine which view to show

2. **`public/home.php`**
   - Direct access to landing page
   - Useful for marketing campaigns

3. **`public/user/dashboard.php`**
   - Direct access to user dashboard
   - Requires authentication

## Integration with Existing System

### Models Used
- **Course**: Fetches course data, statistics, and popular courses
- **User**: User statistics
- **Enrollment**: User enrollments, progress tracking, and statistics

### Helpers Used
- **Session**: Authentication checks, user data access
- **Database**: Direct SQL queries for complex data retrieval

### Existing Layouts
- **header.php**: Navigation bar, flash messages
- **footer.php**: Site footer with links
- **user-nav.php**: Authenticated user navigation
- **guest-nav.php**: Guest user navigation

## URL Structure

```
/                           -> Landing page (guests) / Dashboard (logged-in)
/home.php                   -> Landing page
/user/dashboard.php         -> User dashboard (requires auth)
/courses/index.php          -> Course catalog
/courses/view.php?id=X      -> Course detail page
/auth/login.php             -> Login page
/auth/register.php          -> Registration page
```

## Responsive Design Breakpoints

- **Mobile**: < 768px (single column)
- **Tablet**: 768px - 1024px (2 columns where appropriate)
- **Desktop**: > 1024px (full layout with sidebars)

## Color Scheme

- **Primary**: Purple to Violet gradient (#667eea to #764ba2)
- **Success**: Green (#10b981)
- **Warning**: Yellow (#f59e0b)
- **Info**: Blue (#3b82f6)
- **Text**: Gray scale (#111827 to #6b7280)

## Animations

- **Card Hover**: Translate up + shadow increase
- **Button Hover**: Translate up + shadow
- **Flash Messages**: Slide in from right
- **Progress Bars**: Smooth width transition
- **Page Load**: Fade in animation

## Future Enhancements

1. **Landing Page**
   - Add video testimonials
   - Include course category filters
   - Add search functionality
   - Implement A/B testing for CTAs

2. **Dashboard**
   - Add learning goals/targets
   - Include achievement badges
   - Show personalized course recommendations based on history
   - Add calendar view for deadlines
   - Include social features (leaderboard, friends)

## Security Considerations

- Authentication required for dashboard
- CSRF protection on forms
- SQL injection prevention via parameterized queries
- XSS prevention via output escaping (`e()` helper)
- Session regeneration on authentication
