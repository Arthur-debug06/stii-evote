# HOSTING DEPLOYMENT GUIDE

## Overview
This guide will help you deploy the STII E-Vote System to any hosting environment using the provided configuration files.

## Files Created

### 1. `hosting-config.php`
This is the **ONLY** file you need to edit when deploying to hosting. It contains all environment-specific settings.

### 2. `apply-hosting-config.php`
This script automatically applies your hosting configuration to create the proper `.env` file and set up directories.

## 🚀 Quick Deployment Steps (UPDATED - SIMPLIFIED!)

### Step 1: Upload Files
Upload all your project files to your hosting server (usually to `public_html` or `www` folder).

### Step 2: Update Configuration File
Edit **`hosting-config.php`** with your hosting details:

```php
// Your domain URL
'APP_URL' => 'https://yourdomain.com',

// Database settings from your hosting provider
'DB_HOST' => 'localhost',                    // Usually localhost
'DB_DATABASE' => 'your_database_name',       // Your database name
'DB_USERNAME' => 'your_db_username',         // Your database username  
'DB_PASSWORD' => 'your_db_password',         // Your database password

// Email settings for system notifications (optional - already configured)
'MAIL_USERNAME' => 'your_email@domain.com',
'MAIL_PASSWORD' => 'your_email_password',
'MAIL_FROM_ADDRESS' => 'noreply@yourdomain.com',
```

### Step 3: Deploy Everything Automatically
Run the **NEW** complete deployment script:

```bash
php deploy-to-hosting.php
```

**That's it!** This single command will:
- ✅ Apply all configurations automatically
- ✅ Generate secure APP_KEY
- ✅ Set up directories and permissions
- ✅ Create security configurations
- ✅ Test database connection
- ✅ Optimize for production
- ✅ Create deployment info

### Optional: Import Database (if needed)
If your database is empty, import your SQL file through your hosting control panel, or run:
```bash
php artisan migrate
```

## Troubleshooting Tool

Check your configuration anytime:
```bash
php check-hosting-config.php
```

## Common Hosting Provider Settings

### cPanel/Shared Hosting
- Database Host: `localhost`
- PHP Version: 8.1 or higher
- Create database and user through cPanel
- Upload files to `public_html`

### DigitalOcean/VPS
- Install PHP 8.1+, MySQL, Nginx/Apache
- Configure firewall
- Set up SSL certificate
- Use process manager for background tasks

### AWS/Cloud Hosting
- Use RDS for database
- Configure security groups
- Consider S3 for file storage
- Set up Load Balancer if needed

## Security Checklist

Before going live:

- [ ] Set `APP_ENV` to `production`
- [ ] Set `APP_DEBUG` to `false`
- [ ] Generate new `APP_KEY`
- [ ] Use strong database passwords
- [ ] Enable SSL certificate
- [ ] Set proper file permissions
- [ ] Remove any test/development files
- [ ] Backup your database

## Troubleshooting

### Common Issues

**500 Internal Server Error**
- Check file permissions
- Ensure storage directories are writable
- Check error logs

**Database Connection Error**
- Verify database credentials in `hosting-config.php`
- Ensure database server is running
- Check if database exists

**Email Not Sending**
- Verify email credentials
- Check if hosting provider blocks SMTP
- Try different mail drivers

**Session Issues**
- Ensure storage/framework/sessions is writable
- Check session configuration
- Clear browser cookies

### Getting Help

1. Check your hosting provider's error logs
2. Enable debug mode temporarily by setting `APP_DEBUG=true`
3. Check Laravel logs in `storage/logs/`
4. Contact your hosting provider for server-specific issues

## Important Notes

- **Always backup your database** before making changes
- **Test thoroughly** in a staging environment first
- **Keep your hosting-config.php secure** - it contains sensitive information
- **Never commit passwords** to version control
- **Update regularly** for security patches

## Support

For system-specific issues, contact the development team.
For hosting-related issues, contact your hosting provider's support.

---

*This deployment guide was generated for the STII E-Vote System*