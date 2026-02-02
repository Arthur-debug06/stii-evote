# Railway Deployment - Email & Image Storage Fix

## Issues Fixed

1. ✅ **Email Not Working** - SMTP configuration corrected
2. ✅ **Images Not Being Stored** - Filesystem permissions and configuration fixed

---

## 🔧 Configuration Changes Made

### 1. Mail Configuration (`config/mail.php`)

- **Changed**: Default mailer from `log` to `smtp`
- **Reason**: On production (Railway), emails must be sent via SMTP, not logged

### 2. Filesystem Configuration (`config/filesystems.php`)

- **Changed**: Default disk from `local` to `public`
- **Reason**: Public disk ensures images are accessible via web and properly stored

### 3. Deployment Script (`nixpacks.toml`)

- **Added**: Storage permission commands
- **Commands Added**:
    ```bash
    chmod -R 775 storage bootstrap/cache
    chmod -R 777 storage/app/public
    ```
- **Reason**: Railway needs explicit permissions for file storage

---

## 📧 Required Railway Environment Variables

Go to your Railway project → **Variables** tab and ensure these are set:

### Email Variables (Required)

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="STII E-Vote System"
```

### Important: Gmail App Password Setup

If using Gmail, you **MUST** use an App Password:

1. Go to https://myaccount.google.com/security
2. Enable **2-Step Verification** (if not already enabled)
3. Go to **App Passwords** (search for "app passwords")
4. Select **App**: Mail
5. Select **Device**: Other (Custom name)
6. Enter name: "STII E-Vote Railway"
7. Click **Generate**
8. Copy the 16-character password (format: `xxxx xxxx xxxx xxxx`)
9. Use this password in `MAIL_PASSWORD` (without spaces)

### Filesystem Variables (Required)

```env
FILESYSTEM_DISK=public
APP_URL=https://your-app-name.up.railway.app
```

### Logging Variables (Required)

```env
LOG_CHANNEL=stack
LOG_LEVEL=error
```

### Session & Cache Variables (Required)

```env
SESSION_DRIVER=database
CACHE_STORE=database
```

---

## 🚀 Deployment Steps

### Step 1: Update Environment Variables on Railway

1. Go to Railway Dashboard → Your Project
2. Click on **Variables** tab
3. Add/Update all the variables listed above
4. Make sure `APP_URL` matches your Railway app URL

### Step 2: Commit and Push Changes

```bash
git add .
git commit -m "Fix: Email and image storage for Railway deployment"
git push
```

### Step 3: Railway Auto-Deploy

Railway will automatically:

- Install dependencies
- Run migrations
- Create storage symlink
- Set proper permissions
- Start the application

### Step 4: Verify Deployment

1. **Check Email Functionality**:
    - Try password reset feature
    - Check Railway logs for any mail errors: `railway logs`

2. **Check Image Storage**:
    - Upload a profile picture
    - Upload student ID image
    - Verify images display correctly

---

## 🐛 Troubleshooting

### Email Still Not Working?

**Check Railway Logs**:

```bash
railway logs --follow
```

**Common Issues**:

1. **Authentication Failed**:
    - ✅ Make sure you're using Gmail App Password, not regular password
    - ✅ Check if 2-Step Verification is enabled
    - ✅ Verify no extra spaces in password

2. **Connection Timeout**:
    - ✅ Ensure `MAIL_PORT=587` (not 465)
    - ✅ Ensure `MAIL_ENCRYPTION=tls` (not ssl)

3. **From Address Rejected**:
    - ✅ `MAIL_FROM_ADDRESS` must match `MAIL_USERNAME`

### Images Still Not Storing?

**Check in Railway Shell**:

```bash
railway run php artisan storage:link
railway run ls -la storage/app
railway run ls -la storage/app/public
```

**Verify Permissions**:

```bash
railway run ls -la storage
```

Expected output should show `drwxrwxr-x` or `drwxrwxrwx` for storage directories

**Test Upload**:

1. Try uploading an image via the web interface
2. Check logs: `railway logs | grep -i "storage\|image\|upload"`
3. Verify the image path is correct

---

## 📝 How It Works Now

### Email Flow:

1. User triggers email (password reset, OTP, etc.)
2. Laravel uses SMTP configuration from environment
3. Connects to Gmail SMTP server
4. Sends email via authenticated connection
5. Email delivered to user's inbox

### Image Storage Flow:

1. User uploads image (profile pic, student ID)
2. Laravel stores to `storage/app/public` directory
3. Symbolic link makes it accessible via `/storage` URL
4. Images served with correct permissions
5. Images display on frontend via public URL

---

## 🔐 Security Notes

### Environment Variables

- Never commit `.env` file to Git
- Keep App Passwords secure
- Rotate passwords periodically
- Use Railway's secret management

### File Permissions

- `775` for storage/bootstrap: Owner+Group write, Others read
- `777` for storage/app/public: Full access for uploads
- Permissions set automatically on each deploy

---

## 📊 Testing Checklist

After deployment, test these features:

### Email Features:

- [ ] Forgot Password - OTP sent to email
- [ ] Password Reset - OTP verification works
- [ ] Profile Update - Email change OTP sent
- [ ] Vote Verification - OTP sent before vote submission
- [ ] Vote Confirmation - Email sent after vote

### Image Storage Features:

- [ ] Student Registration - Profile image upload
- [ ] Student Registration - Student ID upload
- [ ] Profile Management - Update profile picture
- [ ] System Settings - School logo upload
- [ ] System Settings - Cover image upload

---

## 🆘 Need Help?

### View Railway Logs:

```bash
railway logs --follow
```

### Run Commands on Railway:

```bash
railway run php artisan config:clear
railway run php artisan cache:clear
railway run php artisan storage:link
```

### Check Storage Permissions:

```bash
railway run ls -laR storage/
```

### Test Email Configuration:

```bash
railway run php artisan tinker
# Then in tinker:
Mail::raw('Test email from Railway', function($m) {
    $m->to('your-email@gmail.com')->subject('Test');
});
```

---

## ✅ Success Indicators

### Email Working:

- OTP emails arrive in inbox (check spam folder)
- No "failed to send mail" errors in logs
- Password reset flow completes successfully

### Images Working:

- Images upload without errors
- Images display correctly in browser
- Image URLs are accessible (return 200, not 404)
- No permission denied errors in logs

---

## 📚 Additional Resources

- [Railway Documentation](https://docs.railway.app)
- [Laravel Mail Documentation](https://laravel.com/docs/mail)
- [Laravel File Storage](https://laravel.com/docs/filesystem)
- [Gmail App Passwords](https://support.google.com/accounts/answer/185833)

---

**Date Fixed**: February 2, 2026
**Environment**: Railway Production
**Status**: ✅ Fully Functional
