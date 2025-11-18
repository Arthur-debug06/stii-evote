# 🚀 One-File Hosting Deployment

## Quick Start (3 Simple Steps)

### Step 1: Update Configuration
Edit **`hosting-config.php`** with your hosting details:
```php
'APP_URL' => 'https://yourdomain.com',
'DB_HOST' => 'your-db-host',
'DB_DATABASE' => 'your-database-name',  
'DB_USERNAME' => 'your-db-username',
'DB_PASSWORD' => 'your-db-password',
```

### Step 2: Deploy
Run the deployment script:
```bash
php deploy-to-hosting.php
```

### Step 3: Done! 
Your STII E-Vote system is ready at your domain.

---

## What This Does

✅ **Automatically configures** your Laravel app for production  
✅ **Generates secure** APP_KEY and environment settings  
✅ **Sets up proper** file permissions and security  
✅ **Tests database** connection  
✅ **Creates** required directories and symlinks  
✅ **Optimizes** for hosting performance  

## Files You Need to Update

**Only 1 file:** `hosting-config.php`

That's it! Everything else is handled automatically.

## Troubleshooting

**Check your config first:**
```bash
php check-hosting-config.php
```

**Common issues:**
- Database connection fails → Check credentials in `hosting-config.php`
- Permission errors → Set folders to 755, files to 644
- Email not working → Use App Passwords for Gmail

## Support

1. Check hosting provider's error logs
2. Verify PHP 8.2+ is enabled  
3. Run the config checker for detailed diagnostics

---

*This deployment system was designed to make hosting Laravel applications as simple as updating one file.*