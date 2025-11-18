# 🚀 InfinityFree Upload Guide for STII E-Vote System

## 📁 CRITICAL: Upload Directory Structure

### ❌ WRONG - Don't upload the entire project folder
```
htdocs/
└── stii-evote/  ← Don't do this!
    ├── public/
    ├── app/
    └── ...
```

### ✅ CORRECT - Upload contents properly
```
htdocs/
├── index.php          ← From public/ folder
├── .htaccess          ← From public/ folder  
├── css/               ← From public/css/
├── js/                ← From public/js/
├── build/             ← From public/build/
├── storage/           ← From public/storage/
├── uploads/           ← From public/uploads/
├── app/               ← From root app/
├── bootstrap/         ← From root bootstrap/
├── config/            ← From root config/
├── database/          ← From root database/
├── resources/         ← From root resources/
├── routes/            ← From root routes/
├── storage/           ← From root storage/
├── vendor/            ← From root vendor/
├── .env               ← Your environment file
├── artisan            ← Laravel artisan command
├── composer.json      ← Composer configuration
└── hosting-config.php ← Your hosting config
```

## 📋 Step-by-Step Upload Process

### 1️⃣ Upload Public Files First
Upload these files to your **htdocs** root directory:
- `public/index.php` → `htdocs/index.php`
- `public/.htaccess` → `htdocs/.htaccess`
- `public/css/` → `htdocs/css/`
- `public/js/` → `htdocs/js/`
- `public/build/` → `htdocs/build/`
- `public/storage/` → `htdocs/storage/`
- `public/uploads/` → `htdocs/uploads/`

### 2️⃣ Upload Laravel Application Files
Upload these folders to your **htdocs** root directory:
- `app/` → `htdocs/app/`
- `bootstrap/` → `htdocs/bootstrap/`
- `config/` → `htdocs/config/`
- `database/` → `htdocs/database/`
- `resources/` → `htdocs/resources/`
- `routes/` → `htdocs/routes/`
- `storage/` → `htdocs/storage/`
- `vendor/` → `htdocs/vendor/`

### 3️⃣ Upload Configuration Files
Upload these files to your **htdocs** root directory:
- `.env`
- `artisan`
- `composer.json`
- `hosting-config.php`

## 🔧 After Upload: Run Setup

1. Visit: `https://your-site.infinityfreeapp.com/hosting-config.php`
2. Follow the setup instructions
3. Delete temporary files when prompted

## 🌐 Access Your Site

Your voting system will be available at:
`https://your-site.infinityfreeapp.com`

## 🚨 Common Issues

### 404 Error
- Check that `index.php` is in the **htdocs** root (not in a subfolder)
- Ensure `.htaccess` is uploaded correctly
- Verify file permissions

### Database Connection Error
- Run `hosting-config.php` to test connection
- Check database credentials in `.env`
- Ensure you're using InfinityFree database server

### Blank Page
- Check `storage/logs/` for error messages
- Ensure `APP_KEY` is set in `.env`
- Verify all folders have correct permissions

## 🎯 Quick Test

After upload, test these URLs:
1. `https://your-site.infinityfreeapp.com` - Should show login page
2. `https://your-site.infinityfreeapp.com/hosting-config.php` - Database test
3. `https://your-site.infinityfreeapp.com/deployment-complete-check.php` - Full check

Your system scored **100%** locally, so it should work perfectly once uploaded correctly! 🚀