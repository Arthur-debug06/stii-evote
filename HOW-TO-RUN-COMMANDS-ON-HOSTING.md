# 🌐 How to Run Commands on Hosting Servers

## Method 1: SSH Access (Recommended)

### If your hosting provider supports SSH:

```bash
# Connect to your server via SSH
ssh username@yourdomain.com

# Navigate to your project directory
cd public_html/  # or your project folder

# Run your deployment command
php make-hosting-ready.php
```

**Hosting providers with SSH:**
- DigitalOcean
- Linode  
- AWS EC2
- Google Cloud
- Most VPS providers
- Some shared hosting (check with provider)

---

## Method 2: Hosting Control Panel Terminal

### cPanel Terminal:
1. Login to cPanel
2. Go to **"Terminal"** or **"SSH Access"**
3. Click **"Open Terminal"**
4. Navigate: `cd public_html`
5. Run: `php make-hosting-ready.php`

### Other Control Panels:
- **Plesk**: Advanced > Terminal
- **DirectAdmin**: System Info & Files > Terminal
- **Custom panels**: Look for "Terminal", "SSH", or "Command Line"

---

## Method 3: File Manager Script Execution

### If no terminal access:

1. **Upload all files** to your hosting
2. **Update `hosting-config.php`** via File Manager editor
3. **Create a web-accessible script** to run deployment

Create this file as `run-deployment.php` in your public folder:

```php
<?php
// SECURITY: Remove this file after deployment!
// Only run this once during deployment

if (!isset($_GET['confirm']) || $_GET['confirm'] !== 'yes-deploy-now') {
    die('Access denied. Add ?confirm=yes-deploy-now to URL');
}

echo "<h2>🚀 Running Deployment...</h2>";
echo "<pre>";

// Change to project root
chdir(__DIR__ . '/..');

// Run the deployment
include 'make-hosting-ready.php';

echo "</pre>";
echo "<h3>✅ Deployment Complete!</h3>";
echo "<p><strong>IMPORTANT:</strong> Delete this file now for security!</p>";
?>
```

Then visit: `https://yourdomain.com/run-deployment.php?confirm=yes-deploy-now`

**⚠️ IMPORTANT: Delete this file immediately after use!**

---

## Method 4: Hosting-Specific Solutions

### **InfinityFree / Free Hosting:**
```bash
# Usually no SSH - use File Manager method above
# Or contact support for command execution
```

### **Shared Hosting (cPanel):**
```bash
# Use cPanel Terminal or create cron job:
# In cPanel > Cron Jobs, create one-time job:
php /home/username/public_html/make-hosting-ready.php
```

### **Cloudflare Pages / Netlify:**
```bash
# Add to build commands in dashboard:
php make-hosting-ready.php
```

### **Heroku:**
```bash
# Use Heroku CLI:
heroku run php make-hosting-ready.php --app your-app-name
```

---

## Method 5: Alternative Deployment (No Commands Needed)

### If you can't run ANY commands:

1. **Update `hosting-config.php`** with your hosting details
2. **Run locally**: `php make-hosting-ready.php` 
3. **Upload the generated `.env` file** and configured files
4. **Manually set file permissions** via File Manager (755 for folders)

---

## Most Common Hosting Scenarios

### 💡 **Scenario 1: cPanel Shared Hosting**
```bash
# Best approach:
1. Login to cPanel
2. Open Terminal (if available)
3. cd public_html
4. php make-hosting-ready.php

# Alternative: Use File Manager script method
```

### 💡 **Scenario 2: VPS/Cloud Hosting**  
```bash
# SSH access usually available:
ssh root@your-server-ip
cd /var/www/html  # or your web directory
php make-hosting-ready.php
```

### 💡 **Scenario 3: Free Hosting (Limited)**
```bash
# Usually no SSH - use web script method:
1. Upload run-deployment.php to public folder
2. Visit the URL with confirmation parameter
3. Delete the script after use
```

---

## Troubleshooting

### "Command not found" or "PHP not found":
```bash
# Try full path to PHP:
/usr/bin/php make-hosting-ready.php
# or
/usr/local/bin/php make-hosting-ready.php
```

### "Permission denied":
```bash
# Make file executable first:
chmod +x make-hosting-ready.php
php make-hosting-ready.php
```

### No terminal access at all:
- Use the File Manager web script method above
- Or run locally and upload the generated files
- Contact hosting support for assistance

---

## 🎯 Quick Decision Guide

**Choose your method:**

- ✅ **Have SSH?** → Use Method 1 (SSH)
- ✅ **Have cPanel Terminal?** → Use Method 2 (Control Panel)
- ❌ **No terminal access?** → Use Method 3 (Web Script)
- 🆘 **Nothing works?** → Use Method 5 (Manual Upload)

---

## Security Notes

⚠️ **Always remove temporary scripts** after deployment
🔒 **Never leave deployment scripts** accessible on production
🛡️ **Use SSH when possible** for better security

The goal is to run `php make-hosting-ready.php` in your project directory - whatever method gets you there safely!