<?php

/**
 * HOSTING CONFIGURATION FILE
 * =========================
 * 
 * This is the ONLY file you need to edit when deploying this Laravel application
 * to a hosting environment. All settings here will override the default configurations.
 * 
 * INSTRUCTIONS:
 * 1. Copy this file to your hosting server
 * 2. Update the values below with your hosting provider's details
 * 3. Make sure this file is included in your bootstrap/app.php or config files
 * 
 * Last Updated: October 21, 2025
 */

return [

    /*
    |--------------------------------------------------------------------------
    | HOSTING ENVIRONMENT SETTINGS
    |--------------------------------------------------------------------------
    */
    
    // Set to 'production' for live hosting
    'APP_ENV' => 'production',
    
    // Set to false for security in production
    'APP_DEBUG' => false,
    
    // Your hosting domain URL (CHANGE THIS)
    'APP_URL' => 'https://yourdomain.com',
    
    // Application name as it appears to users
    'APP_NAME' => 'STII E-Vote System',
    
    // Timezone for your hosting location
    'APP_TIMEZONE' => 'Asia/Manila',

    /*
    |--------------------------------------------------------------------------
    | DATABASE CONFIGURATION
    |--------------------------------------------------------------------------
    | Update these with your hosting provider's database details
    */
    
    'DB_CONNECTION' => 'mysql',
    'DB_HOST' => 'localhost',              // CHANGE THIS - your hosting DB host
    'DB_PORT' => '3306',                   // Usually 3306
    'DB_DATABASE' => 'your_database_name', // CHANGE THIS - your hosting database name
    'DB_USERNAME' => 'your_db_username',   // CHANGE THIS - your hosting DB username
    'DB_PASSWORD' => 'your_db_password',   // CHANGE THIS - your hosting DB password

    /*
    |--------------------------------------------------------------------------
    | EMAIL CONFIGURATION
    |--------------------------------------------------------------------------
    | Configure email settings for notifications and system emails
    */
    
    'MAIL_MAILER' => 'smtp',
    'MAIL_HOST' => 'smtp.gmail.com',   // Or your hosting provider's SMTP
    'MAIL_PORT' => '587',
    'MAIL_USERNAME' => 'rthrcapistrano@gmail.com',        // Working email found in system
    'MAIL_PASSWORD' => 'kdfg lelx egxd sjwk',            // App password for Gmail
    'MAIL_ENCRYPTION' => 'tls',
    'MAIL_FROM_ADDRESS' => 'rthrcapistrano@gmail.com',    // Working sender address
    'MAIL_FROM_NAME' => 'STII E-Vote System',

    /*
    |--------------------------------------------------------------------------
    | SESSION & SECURITY CONFIGURATION  
    |--------------------------------------------------------------------------
    */
    
    'SESSION_DRIVER' => 'database',
    'SESSION_LIFETIME' => 120,         // Minutes before auto-logout
    'SESSION_ENCRYPT' => true,         // Enable for production security
    'SESSION_SECURE_COOKIES' => true,  // Enable for HTTPS
    
    /*
    |--------------------------------------------------------------------------
    | CACHE & PERFORMANCE SETTINGS
    |--------------------------------------------------------------------------
    */
    
    'CACHE_STORE' => 'database',       // or 'redis' if available on hosting
    'QUEUE_CONNECTION' => 'database',  // or 'redis' for better performance
    
    /*
    |--------------------------------------------------------------------------
    | FILE STORAGE CONFIGURATION
    |--------------------------------------------------------------------------
    */
    
    'FILESYSTEM_DISK' => 'local',      // or 's3' for cloud storage
    
    // If using cloud storage (optional)
    'AWS_ACCESS_KEY_ID' => '',
    'AWS_SECRET_ACCESS_KEY' => '',
    'AWS_DEFAULT_REGION' => 'us-east-1',
    'AWS_BUCKET' => '',

    /*
    |--------------------------------------------------------------------------
    | LOGGING CONFIGURATION
    |--------------------------------------------------------------------------
    */
    
    'LOG_CHANNEL' => 'stack',
    'LOG_LEVEL' => 'error',            // 'error' for production, 'debug' for testing
    
    /*
    |--------------------------------------------------------------------------
    | HOSTING-SPECIFIC SETTINGS
    |--------------------------------------------------------------------------
    | These may vary based on your hosting provider
    */
    
    // PHP memory and execution limits
    'PHP_MEMORY_LIMIT' => '256M',
    'PHP_MAX_EXECUTION_TIME' => '300',
    
    // File upload limits
    'MAX_FILE_UPLOADS' => '20',
    'UPLOAD_MAX_FILESIZE' => '10M',
    'POST_MAX_SIZE' => '50M',
    
    /*
    |--------------------------------------------------------------------------
    | VOTING SYSTEM SPECIFIC SETTINGS
    |--------------------------------------------------------------------------
    */
    
    // Enable/disable registration during hosting
    'ALLOW_STUDENT_REGISTRATION' => true,
    'ALLOW_CANDIDATE_REGISTRATION' => true,
    
    // Voting session timeout (minutes)
    'VOTING_SESSION_TIMEOUT' => 30,
    
    // Maximum candidates per position
    'MAX_CANDIDATES_PER_POSITION' => 10,
    
    // Election result visibility
    'SHOW_RESULTS_IMMEDIATELY' => false,
    
    /*
    |--------------------------------------------------------------------------
    | SECURITY KEYS & TOKENS
    |--------------------------------------------------------------------------
    | These should be generated fresh for hosting
    */
    
    // Generate new key: php artisan key:generate
    'APP_KEY' => '',  // Will be generated automatically when applying config
    
    /*
    |--------------------------------------------------------------------------
    | HOSTING PROVIDER NOTES
    |--------------------------------------------------------------------------
    | 
    | COMMON HOSTING PROVIDERS SETTINGS:
    | 
    | cPanel/Shared Hosting:
    | - DB_HOST: usually 'localhost'
    | - Check File Manager for correct paths
    | - May need to adjust .htaccess
    | 
    | DigitalOcean/VPS:
    | - DB_HOST: 'localhost' or private IP
    | - Configure firewall for database access
    | 
    | AWS/Cloud:
    | - Use RDS for database
    | - Configure security groups
    | - Consider using S3 for file storage
    |
    */

];

/*
|--------------------------------------------------------------------------
| HOSTING CHECKLIST
|--------------------------------------------------------------------------
|
| Before going live, ensure you have:
| 
| ☐ Updated all database credentials above
| ☐ Changed APP_URL to your domain
| ☐ Set APP_DEBUG to false
| ☐ Set APP_ENV to 'production'
| ☐ Generated new APP_KEY
| ☐ Configured email settings
| ☐ Uploaded database to hosting server
| ☐ Set proper file permissions (755 for folders, 644 for files)
| ☐ Ensured storage/ and bootstrap/cache/ are writable
| ☐ Configured SSL certificate
| ☐ Tested all major functionality
|
| SECURITY REMINDERS:
| ☐ This file contains sensitive information - protect it
| ☐ Never commit passwords to version control
| ☐ Use strong passwords for all accounts
| ☐ Keep your hosting environment updated
|
*/