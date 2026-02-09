# Upskill Training Platform

Professional employee training platform by CodeZerra.

## Server Configuration

### Apache Configuration (Recommended)

#### Option 1: Point Document Root to /public (Recommended)

The application is designed to have the web server's document root point directly to the `public` directory. This is the most secure and performant option.

**Apache VirtualHost Configuration:**

```apache
<VirtualHost *:80>
    ServerName upskill.codezerra.com
    DocumentRoot /path/to/upskill.codezerra.com/public
    
    <Directory /path/to/upskill.codezerra.com/public>
        AllowOverride All
        Require all granted
        
        # Enable .htaccess for routing
        Options -Indexes +FollowSymLinks
    </Directory>
    
    # Optional: Redirect HTTP to HTTPS
    # RewriteEngine On
    # RewriteCond %{HTTPS} off
    # RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
</VirtualHost>

<VirtualHost *:443>
    ServerName upskill.codezerra.com
    DocumentRoot /path/to/upskill.codezerra.com/public
    
    <Directory /path/to/upskill.codezerra.com/public>
        AllowOverride All
        Require all granted
        Options -Indexes +FollowSymLinks
    </Directory>
    
    # SSL Configuration
    SSLEngine on
    SSLCertificateFile /path/to/certificate.crt
    SSLCertificateKeyFile /path/to/private.key
    SSLCertificateChainFile /path/to/chain.crt
</VirtualHost>
```

#### Option 2: Use Root .htaccess Redirect

If you cannot configure the document root to point to `/public`, the root `.htaccess` file will automatically redirect all requests to the `public` directory.

**Apache VirtualHost Configuration:**

```apache
<VirtualHost *:80>
    ServerName upskill.codezerra.com
    DocumentRoot /path/to/upskill.codezerra.com
    
    <Directory /path/to/upskill.codezerra.com>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

### Nginx Configuration

For Nginx servers, use the following configuration:

```nginx
server {
    listen 80;
    server_name upskill.codezerra.com;
    root /path/to/upskill.codezerra.com/public;
    index index.php;
    
    location / {
        try_files $uri $uri/ /router.php?$query_string;
    }
    
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }
    
    # Deny access to hidden files
    location ~ /\. {
        deny all;
    }
    
    # Static assets caching
    location ~* \.(jpg|jpeg|png|gif|ico|css|js|svg|woff|woff2|ttf|eot)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }
}
```

## Installation

### Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher / MariaDB 10.2 or higher
- Apache 2.4+ with mod_rewrite enabled OR Nginx
- Composer (optional, for dependency management)

### Setup Steps

1. **Clone the repository:**
   ```bash
   git clone https://github.com/joemrnice/upskill.codezerra.com.git
   cd upskill.codezerra.com
   ```

2. **Configure environment:**
   ```bash
   cp .env.example .env
   # Edit .env with your database and site configuration
   ```

3. **Import database:**
   ```bash
   mysql -u your_user -p your_database < database/schema.sql
   ```

4. **Set file permissions:**
   ```bash
   chmod -R 755 public/
   chmod -R 775 public/uploads/
   chown -R www-data:www-data public/uploads/
   ```

5. **Configure Apache:**
   - Enable mod_rewrite: `sudo a2enmod rewrite`
   - Point document root to `/path/to/upskill.codezerra.com/public`
   - Restart Apache: `sudo systemctl restart apache2`

## Development vs Production

### Development Mode

In `app/bootstrap.php`, error reporting is enabled by default:

```php
error_reporting(E_ALL);
ini_set('display_errors', 1);
```

This helps with debugging during development.

### Production Mode

For production environments:

1. **Disable error display** in `app/bootstrap.php`:
   ```php
   error_reporting(E_ALL);
   ini_set('display_errors', 0);
   ini_set('log_errors', 1);
   ini_set('error_log', '/path/to/error.log');
   ```

2. **Ensure HTTPS** is configured in your web server

3. **Set secure PHP settings** in `php.ini`:
   ```ini
   display_errors = Off
   log_errors = On
   error_log = /var/log/php/error.log
   session.cookie_secure = On
   session.cookie_httponly = On
   ```

## Troubleshooting

### Internal Server Error (500)

1. **Check Apache error logs:**
   ```bash
   tail -f /var/log/apache2/error.log
   ```

2. **Verify mod_rewrite is enabled:**
   ```bash
   sudo a2enmod rewrite
   sudo systemctl restart apache2
   ```

3. **Check .htaccess syntax:**
   - Ensure the root `.htaccess` properly redirects to `/public`
   - Ensure `public/.htaccess` has correct routing rules

4. **File permissions:**
   - Apache user (www-data) needs read access to all files
   - Write access needed for `public/uploads/` directory

### Clean URLs Not Working

1. Ensure mod_rewrite is enabled
2. Verify `AllowOverride All` is set in Apache configuration
3. Check that `.htaccess` files exist in both root and `public/` directories

### Static Assets Not Loading

1. Check file permissions on `public/css/` and `public/js/` directories
2. Verify paths in your HTML use correct base URLs
3. Check browser console for 404 errors

## Features

- User authentication and registration
- Course catalog and enrollment
- Progress tracking
- Assessment system with automatic grading
- Certificate generation
- Admin dashboard for content management
- Responsive design

## License

Proprietary - CodeZerra