# Authentication System Documentation

## Overview
A complete, secure authentication system for the training platform with login, registration, password reset, and session management.

## Features

### ✅ User Authentication
- **Login** - Email and password authentication with "remember me" functionality
- **Registration** - New user signup with validation
- **Logout** - Secure session termination with CSRF protection
- **Password Reset** - Forgot password flow with token-based reset

### 🔒 Security Features
- CSRF token validation on all forms
- Password hashing with `password_hash()` (bcrypt)
- Session ID regeneration on login
- Secure session configuration (httponly, secure cookies)
- Token-based password reset (1-hour expiration)
- HTTPS cookie security check
- POST-only logout for CSRF protection

### 🎨 User Experience
- Professional Tailwind CSS design
- Responsive mobile-first layout
- Real-time client-side validation
- Password strength indicator
- Flash messages for feedback
- Toggle password visibility
- Loading states and animations

## File Structure

```
app/
├── controllers/
│   └── AuthController.php           # Authentication logic
├── views/
│   ├── auth/
│   │   ├── login.php                # Login form
│   │   ├── register.php             # Registration form
│   │   ├── forgot-password.php      # Request password reset
│   │   └── reset-password.php       # Reset password with token
│   └── layouts/
│       ├── header.php               # HTML head and navigation loader
│       ├── footer.php               # Footer with links
│       ├── guest-nav.php            # Navigation for guests
│       └── user-nav.php             # Navigation for authenticated users
public/
└── auth/
    ├── login.php                    # Login page route
    ├── login-process.php            # Login form handler
    ├── register.php                 # Registration page route
    ├── register-process.php         # Registration form handler
    ├── logout.php                   # Logout handler
    ├── forgot-password.php          # Forgot password page route
    ├── forgot-password-process.php  # Forgot password form handler
    ├── reset-password.php           # Reset password page route
    └── reset-password-process.php   # Reset password form handler
```

## Controller Methods

### AuthController

#### `showLogin()`
Displays the login page. Redirects authenticated users to dashboard.

#### `login()`
Processes login form submission:
- Validates CSRF token
- Validates email and password
- Verifies credentials using User model
- Checks user account status
- Regenerates session ID
- Sets session variables (user_id, user_name, user_email, user_role)
- Handles "remember me" functionality
- Redirects based on user role (admin/user)

#### `showRegister()`
Displays the registration page. Redirects authenticated users to dashboard.

#### `register()`
Processes registration form submission:
- Validates CSRF token
- Validates all input fields (name, email, employee_id, password, confirm_password)
- Checks for unique email and employee_id
- Creates new user with hashed password
- Shows flash message and redirects to login

#### `logout()`
Logs out the current user:
- Validates CSRF token (POST only)
- Sets flash message before session destruction
- Clears remember me cookie
- Destroys session
- Redirects to login page

#### `showForgotPassword()`
Displays the forgot password page.

#### `forgotPassword()`
Processes forgot password request:
- Validates CSRF token
- Validates email address
- Generates secure reset token
- Sets token expiration (1 hour)
- Logs reset link (simulates email in development)
- Always shows success message (security best practice)

#### `showResetPassword()`
Displays reset password page:
- Validates token from URL
- Checks token exists and hasn't expired
- Redirects to forgot password if invalid

#### `resetPassword()`
Processes password reset:
- Validates CSRF token
- Validates new password and confirmation
- Verifies reset token
- Updates password
- Clears reset token
- Redirects to login with success message

## Session Variables

After successful login, the following session variables are set:

```php
$_SESSION['user_id']      // User's database ID
$_SESSION['user_name']    // User's full name
$_SESSION['user_email']   // User's email address
$_SESSION['user_role']    // User's role (admin/user)
```

## Validation Rules

### Registration
- **Name**: Required, 3-100 characters
- **Email**: Required, valid email format, unique
- **Employee ID**: Required, unique
- **Password**: Required, minimum 8 characters
- **Confirm Password**: Required, must match password

### Login
- **Email**: Required, valid email format
- **Password**: Required

### Password Reset
- **Password**: Required, minimum 8 characters
- **Confirm Password**: Required, must match password

## Usage Examples

### Check if User is Logged In
```php
if (Session::isLoggedIn()) {
    // User is authenticated
}
```

### Check if User is Admin
```php
if (Session::isAdmin()) {
    // User has admin role
}
```

### Get Current User Data
```php
$user = Session::getUser();
// Returns: ['id', 'name', 'email', 'role']
```

### Set Flash Message
```php
Session::setFlash('success', 'Operation completed successfully!');
Session::setFlash('error', 'An error occurred.');
```

### Protect Routes
```php
// At the top of protected pages
if (!Session::isLoggedIn()) {
    redirect(base_url('public/auth/login.php'));
}
```

### Admin-Only Pages
```php
if (!Session::isAdmin()) {
    Session::setFlash('error', 'Access denied.');
    redirect(base_url('public/index.php'));
}
```

## Frontend Features

### Client-Side Validation
All forms include JavaScript validation for immediate feedback:
- Email format validation
- Password strength checking
- Password confirmation matching
- Required field validation
- Real-time error display

### Password Strength Indicator
Visual feedback on password strength:
- Weak (red)
- Fair (orange)
- Good (yellow)
- Strong (green)

Checks for:
- Minimum 8 characters
- Lowercase letters
- Uppercase letters
- Numbers
- Special characters

### Responsive Design
- Mobile-first approach
- Hamburger menu for mobile
- Collapsible navigation
- Touch-friendly form inputs

## Security Best Practices Implemented

1. **CSRF Protection**: All forms include and validate CSRF tokens
2. **Password Hashing**: Passwords hashed with bcrypt (PASSWORD_BCRYPT)
3. **Session Security**: HttpOnly, Secure, and SameSite cookie flags
4. **Session Regeneration**: New session ID on login to prevent fixation
5. **Input Sanitization**: All user input sanitized with `Validator::sanitize()`
6. **SQL Injection Prevention**: Prepared statements in User model
7. **XSS Prevention**: Output escaped with `e()` function
8. **Secure Password Reset**: Time-limited tokens, secure random generation
9. **Rate Limiting**: (Recommended for production - not implemented)
10. **Account Status Check**: Suspended users cannot log in

## Email Integration (Development Mode)

Currently, password reset emails are logged to the error log:

```php
error_log("Password reset link for {$email}: {$resetLink}");
```

### Production Implementation
To send actual emails in production, replace the email simulation in `forgotPassword()`:

```php
// Instead of error_log, use your email service:
$mailer = new EmailService();
$mailer->send($email, 'Password Reset', $resetLink);
```

## Customization

### Styling
All styles use Tailwind CSS. Custom colors are defined in `header.php`:
- Primary gradient: `#667eea` to `#764ba2`
- Secondary gradient: `#f093fb` to `#f5576c`

### Modify these in the `<style>` section of `header.php` to change the color scheme.

### Logo
Update the logo in navigation files:
```php
<div class="w-10 h-10 bg-gradient-primary rounded-lg">
    <span class="text-white font-bold text-xl">U</span>
</div>
```

### Site Name
Configured in `config/config.php`:
```php
'site_name' => 'Your Site Name'
```

## Testing Checklist

- [ ] User can register with valid data
- [ ] Registration fails with invalid/duplicate data
- [ ] User can login with correct credentials
- [ ] Login fails with incorrect credentials
- [ ] Session variables are set correctly on login
- [ ] User is redirected based on role (admin/user)
- [ ] Remember me functionality works
- [ ] User can logout successfully
- [ ] Flash messages display correctly
- [ ] Password reset token is generated
- [ ] Password can be reset with valid token
- [ ] Expired tokens are rejected
- [ ] All forms have CSRF protection
- [ ] Client-side validation works
- [ ] Mobile responsive design works
- [ ] Navigation changes based on auth status

## Browser Support

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)
- Mobile browsers (iOS Safari, Chrome Mobile)

## Dependencies

- **PHP**: 7.4+
- **Database**: MySQL/MariaDB
- **Frontend**: Tailwind CSS 3.x (CDN)
- **JavaScript**: Alpine.js 3.x (for dropdowns)

## Future Enhancements

- [ ] Two-factor authentication (2FA)
- [ ] OAuth/Social login integration
- [ ] Email verification on registration
- [ ] Rate limiting for login attempts
- [ ] Account lockout after failed attempts
- [ ] Password history (prevent reuse)
- [ ] Session timeout with warning
- [ ] Activity logging
- [ ] Remember me with database tokens
- [ ] Progressive password requirements

## Troubleshooting

### Flash Messages Don't Display
- Ensure `Session::start()` is called before setting flash messages
- Check that the header includes flash message display code

### CSRF Token Validation Fails
- Verify session is started before form loads
- Check that form includes `csrf_field()`
- Ensure cookies are enabled in browser

### Password Reset Link Doesn't Work
- Check that token hasn't expired (1 hour limit)
- Verify database `reset_token` and `reset_token_expires` fields exist
- Check error logs for token generation issues

### Logout Doesn't Work
- Ensure logout form uses POST method
- Verify CSRF token is included in logout form
- Check that Session::destroy() is called

## Support

For issues or questions:
1. Check this documentation
2. Review error logs
3. Verify database schema matches requirements
4. Check session and cookie settings in php.ini
