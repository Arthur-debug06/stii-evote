<?php

/**
 * HOSTING CONFIGURATION FILE - INFINITYFREE READY
 * ===============================================
 * 
 * This is configured for InfinityFree hosting with detailed troubleshooting
 * Update the database credentials with your exact InfinityFree details
 * 
 * INFINITYFREE SETUP INSTRUCTIONS:
 * 1. Login to InfinityFree Control Panel
 * 2. Go to "MySQL Databases"
 * 3. Create database (if not exists)
 * 4. Copy EXACT details from control panel to this file
 * 5. Run: php make-hosting-ready.php
 * 
 * Last Updated: October 24, 2025
 */

return [

    /*
    |--------------------------------------------------------------------------
    | PRODUCTION HOSTING SETTINGS
    |--------------------------------------------------------------------------
    */
    
    'APP_ENV' => 'production',         // MUST be 'production' for hosting
    'APP_DEBUG' => false,              // MUST be false for security
    'APP_URL' => 'https://stii-evote.infinityfreeapp.com', // UPDATE: Your domain
    'APP_NAME' => 'STII E-Vote System',
    'APP_TIMEZONE' => 'Asia/Manila',

    /*
    |--------------------------------------------------------------------------
    | INFINITYFREE DATABASE CONFIGURATION
    |--------------------------------------------------------------------------
    | 
    | IMPORTANT: Get these EXACT values from your InfinityFree control panel:
    | 1. Login to InfinityFree
    | 2. Go to "MySQL Databases" 
    | 3. Copy the exact values shown there
    |
    | COMMON INFINITYFREE PATTERNS:
    | • Database Host: sql300.infinityfree.com (or sql200, sql201, sql301)
    | • Database Name: if0_XXXXXXX_yourdbname
    | • Username: if0_XXXXXXX (matches first part of database name)
    | • Password: What you set when creating the database user
    */
    
    'DB_CONNECTION' => 'mysql',
    'DB_HOST' => 'sql300.infinityfree.com',        // CHECK: Exact hostname from control panel
    'DB_PORT' => '3306',                           // Usually 3306 for InfinityFree
    'DB_DATABASE' => 'if0_40083083_stii_evote',    // CHECK: Exact database name
    'DB_USERNAME' => 'if0_40083083',               // CHECK: Exact username
    'DB_PASSWORD' => 'u8XY8gkXSx',                 // CHECK: Exact password

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
    | SESSION & SECURITY CONFIGURATION  
    |--------------------------------------------------------------------------
    */
    
    'SESSION_DRIVER' => 'database',
    'SESSION_LIFETIME' => 120,
    'SESSION_ENCRYPT' => true,         // Enable for production
    'SESSION_SECURE_COOKIES' => true,  // Enable for HTTPS
    
    /*
    |--------------------------------------------------------------------------
    | CACHE & PERFORMANCE SETTINGS
    |--------------------------------------------------------------------------
    */
    
    'CACHE_STORE' => 'database',
    'QUEUE_CONNECTION' => 'database',
    'LOG_CHANNEL' => 'stack',
    'LOG_LEVEL' => 'error',            // 'error' for production
    
    /*
    |--------------------------------------------------------------------------
    | FILE STORAGE CONFIGURATION
    |--------------------------------------------------------------------------
    */
    
    'FILESYSTEM_DISK' => 'local',
    
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
    | VOTING SYSTEM SPECIFIC SETTINGS
    |--------------------------------------------------------------------------
    */
    
    'ALLOW_STUDENT_REGISTRATION' => true,
    'ALLOW_CANDIDATE_REGISTRATION' => true,
    'VOTING_SESSION_TIMEOUT' => 30,
    'MAX_CANDIDATES_PER_POSITION' => 10,
    'SHOW_RESULTS_IMMEDIATELY' => false,    // false for production
    
    /*
    |--------------------------------------------------------------------------
    | SECURITY KEYS & TOKENS
    |--------------------------------------------------------------------------
    */
    
    'APP_KEY' => '',  // Will be generated automatically
    
    /*
    |--------------------------------------------------------------------------
    | HOSTING PERFORMANCE SETTINGS
    |--------------------------------------------------------------------------
    */
    
    'PHP_MEMORY_LIMIT' => '256M',
    'PHP_MAX_EXECUTION_TIME' => '300',
    'MAX_FILE_UPLOADS' => '20',
    'UPLOAD_MAX_FILESIZE' => '10M',
    'POST_MAX_SIZE' => '50M',

];

/*
|--------------------------------------------------------------------------
| INFINITYFREE TROUBLESHOOTING GUIDE
|--------------------------------------------------------------------------
|
| DATABASE CONNECTION ISSUES:
| 
| Error: "getaddrinfo failed" or "Name or service not known"
| ✅ Solution: Double-check DB_HOST in your InfinityFree control panel
| 
| Error: "Access denied"  
| ✅ Solution: Verify DB_USERNAME and DB_PASSWORD are exactly as shown in control panel
| 
| Error: "Unknown database"
| ✅ Solution: Make sure database exists and DB_DATABASE name is exact
|
| VERIFICATION STEPS:
| 1. Login to InfinityFree control panel
| 2. Go to "MySQL Databases"
| 3. Try accessing phpMyAdmin with your credentials
| 4. If phpMyAdmin works, your credentials are correct
| 5. If phpMyAdmin fails, reset your database password
|
| TESTING COMMANDS:
| • php smart-config-check.php        (Smart environment detection)
| • php infinityfree-db-check.php     (Detailed InfinityFree diagnostics)
| • php make-hosting-ready.php        (Full deployment)
|
| SUPPORT:
| If all credentials are verified and phpMyAdmin works but Laravel still fails:
| 1. The database server might be temporarily down
| 2. Contact InfinityFree support
| 3. Try again later
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