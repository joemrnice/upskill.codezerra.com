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
        # Adjust the socket path based on your PHP version
        # Examples: php-fpm.sock, php7.4-fpm.sock, php8.1-fpm.sock, php8.2-fpm.sock
        fastcgi_pass unix:/var/run/php/php-fpm.sock;
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

## 🌐 DreamHost Deployment Instructions

### Option 1: Change Web Directory (Recommended)
1. Log into DreamHost Panel
2. Go to **Domains** → **Manage Domains**
3. Click **Edit** next to `upskill.codezerra.com`
4. Under "Web directory", change from `/home/$USER/upskill.codezerra.com` to `/home/$USER/upskill.codezerra.com/public`
   (Replace `$USER` with your actual DreamHost username)
5. Click **Change settings**
6. Wait 5-10 minutes for DNS propagation

### Option 2: Use Root .htaccess (Current Setup)
If you cannot change the web directory, the root `.htaccess` will automatically redirect all requests to the `public` folder.

**Verify your setup:**
```bash
# SSH into DreamHost (replace $USER with your username)
cd ~/upskill.codezerra.com

# Check if files are present
ls -la public/

# Check if .htaccess exists
cat .htaccess
cat public/.htaccess
```

### Database Setup on DreamHost
1. Create MySQL database in DreamHost Panel
2. Note down: database name, username, password, hostname
3. Copy `.env.example` to `.env`:
```bash
cd ~/upskill.codezerra.com
cp .env.example .env
chmod 600 .env  # Secure the file
```

4. Edit `.env` file with your database credentials:
```bash
nano .env  # or use your preferred editor
```

Update the values:
```env
DB_HOST="mysql.yourhostname.com"
DB_NAME="your_database_name"
DB_USER="your_username"
DB_PASS="your_password"
SITE_URL="https://upskill.codezerra.com"
```

5. Import the database schema:
```bash
mysql -h YOUR_MYSQL_HOSTNAME -u YOUR_USERNAME -p YOUR_DATABASE_NAME < database/schema.sql
```

**Security Note:** Never store credentials in `.htaccess` files. Always use `.env` files with proper permissions (chmod 600) and ensure `.env` is in your `.gitignore` file.

### Troubleshooting DreamHost Issues

**Issue: Still seeing "almost here!" page**
- Clear browser cache
- Wait 5-10 minutes for DreamHost cache to clear
- Check that files are uploaded to the correct directory
- Verify .htaccess files are present

**Issue: 500 Internal Server Error**
- Check DreamHost error logs: `~/logs/upskill.codezerra.com/https/error.log`
- Verify PHP version is 7.4+ in DreamHost Panel
- Check file permissions: `chmod 755` on directories, `chmod 644` on files

**Issue: Database Connection Error**
- Verify database credentials in config
- Check that database hostname is correct (not localhost)
- Ensure MySQL user has access to the database

## Installation

### Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher / MariaDB 10.2 or higher
- Apache 2.4+ with mod_rewrite enabled OR Nginx
- Composer (optional, for dependency management)

### Quick Start

The application will work without a database initially, but with limited functionality. You'll see a warning message on the landing page indicating that database configuration is required for full features.

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

3. **Create and configure database:**
   
   Create a new MySQL database:
   ```bash
   mysql -u root -p
   CREATE DATABASE upskill_training CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   CREATE USER 'upskill_user'@'localhost' IDENTIFIED BY 'your_secure_password';
   GRANT ALL PRIVILEGES ON upskill_training.* TO 'upskill_user'@'localhost';
   FLUSH PRIVILEGES;
   EXIT;
   ```

4. **Import database schema:**
   ```bash
   mysql -u your_user -p your_database < database/schema.sql
   ```
   
   This will create all necessary tables and insert sample data including:
   - Admin user (email: admin@codezerra.com, password: admin123)
   - Sample courses for testing

5. **Set file permissions:**
   ```bash
   chmod -R 755 public/
   chmod -R 775 public/uploads/
   chown -R www-data:www-data public/uploads/
   ```

6. **Configure Apache:**
   - Enable mod_rewrite: `sudo a2enmod rewrite`
   - Point document root to `/path/to/upskill.codezerra.com/public`
   - Restart Apache: `sudo systemctl restart apache2`

### Default Credentials

After importing the database schema, you can login with:

- **Admin Account:**
  - Email: admin@codezerra.com
  - Password: admin123
  
- **Test User Account:**
  - Email: user@example.com
  - Password: password123

**Important:** Change these default passwords immediately after first login!

### Verifying Installation

1. **Access the application:** Navigate to your configured URL (e.g., https://upskill.codezerra.com)
2. **Landing page should load** without errors
3. **If database is not configured:** You'll see a yellow warning banner but the page will still load
4. **If database is configured:** The landing page will show real statistics and course listings
5. **Test login:** Try logging in with the admin credentials

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

### Database Connection Issues

**Symptom:** Yellow warning banner appears on landing page saying "Database Configuration Required"

**Solutions:**
1. **Verify database credentials** in `.env` or `config/database.php`:
   - Check DB_HOST (use `localhost` or actual MySQL hostname)
   - Verify DB_NAME matches your database name
   - Confirm DB_USER and DB_PASS are correct

2. **Test database connection:**
   ```bash
   mysql -h YOUR_HOST -u YOUR_USER -p YOUR_DATABASE
   ```

3. **Check if database exists:**
   ```bash
   mysql -u root -p
   SHOW DATABASES;
   ```

4. **Verify user permissions:**
   ```sql
   SHOW GRANTS FOR 'your_user'@'localhost';
   ```

5. **For DreamHost:** Hostname is NOT `localhost`, check DreamHost panel for correct MySQL hostname

**Note:** The application will still work without a database, but only displays the landing page with zero statistics.

### Internal Server Error (500)

**Previous Issue (Now Fixed):** Controllers were trying to connect to database in constructors, causing fatal errors when database wasn't configured. This has been fixed - the application now gracefully handles missing database connections.

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