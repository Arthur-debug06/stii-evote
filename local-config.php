<?php

/**
 * LOCAL DEVELOPMENT CONFIGURATION
 * ===============================
 * 
 * This configuration is for local development (XAMPP, WAMP, etc.)
 * Use this when developing locally, then switch to hosting-config.php for deployment
 * 
 * Copy this to hosting-config.php and update with your hosting details when ready to deploy
 */

return [

    /*
    |--------------------------------------------------------------------------
    | LOCAL DEVELOPMENT SETTINGS
    |--------------------------------------------------------------------------
    */
    
    'APP_ENV' => 'local',              // 'local' for development, 'production' for hosting
    'APP_DEBUG' => true,               // true for development, false for hosting
    'APP_URL' => 'http://localhost/stii-evote/public', // Your local development URL
    'APP_NAME' => 'STII E-Vote System',
    'APP_TIMEZONE' => 'Asia/Manila',

    /*
    |--------------------------------------------------------------------------
    | LOCAL DATABASE CONFIGURATION (XAMPP/WAMP)
    |--------------------------------------------------------------------------
    */
    
    'DB_CONNECTION' => 'mysql',
    'DB_HOST' => '127.0.0.1',          // localhost for XAMPP
    'DB_PORT' => '3306',
    'DB_DATABASE' => 'stii_evote',     // Your local database name
    'DB_USERNAME' => 'root',           // Default XAMPP username
    'DB_PASSWORD' => '',               // Default XAMPP password (empty)

    /*
    |--------------------------------------------------------------------------
    | EMAIL CONFIGURATION (Same as hosting)
    |--------------------------------------------------------------------------
    */
    
    'MAIL_MAILER' => 'smtp',
    'MAIL_HOST' => 'smtp.gmail.com',
    'MAIL_PORT' => '587',
    'MAIL_USERNAME' => 'rthrcapistrano@gmail.com',
    'MAIL_PASSWORD' => 'kdfg lelx egxd sjwk',
    'MAIL_ENCRYPTION' => 'tls',
    'MAIL_FROM_ADDRESS' => 'rthrcapistrano@gmail.com',
    'MAIL_FROM_NAME' => 'STII E-Vote System',

    /*
    |--------------------------------------------------------------------------
    | LOCAL DEVELOPMENT SETTINGS
    |--------------------------------------------------------------------------
    */
    
    'SESSION_DRIVER' => 'database',
    'SESSION_LIFETIME' => 120,
    'SESSION_ENCRYPT' => false,        // false for local development
    'SESSION_SECURE_COOKIES' => false, // false for HTTP in development
    
    'CACHE_STORE' => 'database',
    'QUEUE_CONNECTION' => 'database',
    'FILESYSTEM_DISK' => 'local',
    
    'LOG_CHANNEL' => 'stack',
    'LOG_LEVEL' => 'debug',            // 'debug' for development
    
    /*
    |--------------------------------------------------------------------------
    | AWS CONFIGURATION (Optional)
    |--------------------------------------------------------------------------
    */
    
    'AWS_ACCESS_KEY_ID' => '',
    'AWS_SECRET_ACCESS_KEY' => '',
    'AWS_DEFAULT_REGION' => 'us-east-1',
    'AWS_BUCKET' => '',
    
    /*
    |--------------------------------------------------------------------------
    | VOTING SYSTEM SETTINGS
    |--------------------------------------------------------------------------
    */
    
    'ALLOW_STUDENT_REGISTRATION' => true,
    'ALLOW_CANDIDATE_REGISTRATION' => true,
    'VOTING_SESSION_TIMEOUT' => 30,
    'MAX_CANDIDATES_PER_POSITION' => 10,
    'SHOW_RESULTS_IMMEDIATELY' => true, // true for testing
    
    /*
    |--------------------------------------------------------------------------
    | DEVELOPMENT SETTINGS
    |--------------------------------------------------------------------------
    */
    
    'APP_KEY' => '', // Will be generated automatically
    
    // Performance settings for local development
    'PHP_MEMORY_LIMIT' => '256M',
    'PHP_MAX_EXECUTION_TIME' => '300',
    'MAX_FILE_UPLOADS' => '20',
    'UPLOAD_MAX_FILESIZE' => '10M',
    'POST_MAX_SIZE' => '50M',

];

/*
|--------------------------------------------------------------------------
| DEVELOPMENT NOTES
|--------------------------------------------------------------------------
|
| TO DEPLOY TO HOSTING:
| 1. Copy this file to hosting-config.php
| 2. Update the values above with your hosting provider's details:
|    - Change APP_ENV to 'production'
|    - Change APP_DEBUG to false
|    - Update APP_URL to your domain
|    - Update all DB_* settings with hosting database details
|    - Set SESSION_SECURE_COOKIES to true for HTTPS
| 3. Run: php make-hosting-ready.php
|
| FOR LOCAL DEVELOPMENT:
| 1. Make sure XAMPP/WAMP is running
| 2. Create database 'stii_evote' in phpMyAdmin
| 3. Run: php apply-hosting-config.php (using this config)
| 4. Import your database SQL file
| 5. Access via: http://localhost/stii-evote/public
|
*/