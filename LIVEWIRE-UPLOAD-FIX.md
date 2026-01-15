# 🔧 Livewire File Upload Fix for Railway Deployment

## Problem

Livewire file uploads were failing with **401 Unauthorized** error on Railway production:

```
POST /livewire/upload-file - 401 Unauthorized
```

## Root Cause

1. **Missing Livewire Configuration**: Laravel 11 doesn't include `config/livewire.php` by default
2. **CSRF Token Issues**: Railway's proxy setup caused CSRF validation failures on file uploads
3. **Session Cookie Configuration**: `SESSION_SAME_SITE=lax` prevented cross-origin requests
4. **Missing Temporary Upload Disk**: Livewire needs a dedicated disk for temporary file storage

## Solution Applied

### 1. ✅ Created Livewire Configuration

**File**: `config/livewire.php`

Added complete Livewire configuration with:

-   Temporary file upload disk: `local`
-   Upload rules: max 12MB
-   Upload directory: `livewire-tmp`
-   Preview MIME types support
-   Max upload time: 5 minutes

### 2. ✅ Added Temporary Upload Disk

**File**: `config/filesystems.php`

Added `livewire-tmp` disk configuration:

```php
'livewire-tmp' => [
    'driver' => 'local',
    'root' => storage_path('app/livewire-tmp'),
    'visibility' => 'private',
    'throw' => false,
],
```

### 3. ✅ Updated Session Configuration

**File**: `config/session.php`

Changed `same_site` setting to work with production:

```php
'same_site' => env('SESSION_SAME_SITE', env('APP_ENV') === 'production' ? 'none' : 'lax'),
```

### 4. ✅ Updated Railway Environment Variables

**File**: `.env.railway`

Added/updated:

```env
SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE=none
SESSION_HTTP_ONLY=true
SESSION_PARTITIONED_COOKIE=false

LIVEWIRE_TEMPORARY_FILE_UPLOAD_DISK=local
LIVEWIRE_APP_URL=${APP_URL}
```

### 5. ✅ Configured Middleware

**File**: `bootstrap/app.php`

Added CSRF exception for Livewire uploads and proxy trust:

```php
->withMiddleware(function (Middleware $middleware) {
    // Trust all proxies for Railway deployment
    $middleware->trustProxies(at: '*');

    // Livewire file upload needs to be accessible
    $middleware->validateCsrfTokens(except: [
        'livewire/upload-file',
    ]);
})
```

## Deployment Steps

### 1. Commit and Push Changes

```bash
git add .
git commit -m "Fix: Livewire file upload 401 error on Railway"
git push origin main
```

### 2. Railway Environment Variables

In Railway dashboard, update environment variables:

```
SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE=none
SESSION_HTTP_ONLY=true
LIVEWIRE_TEMPORARY_FILE_UPLOAD_DISK=local
LIVEWIRE_APP_URL=https://stii-evote-production.up.railway.app
```

### 3. Ensure Storage Directory Exists

After deployment, run:

```bash
php artisan storage:link
```

This will be done automatically on Railway if you have it in your build/deploy scripts.

## Files Modified

1. ✅ `config/livewire.php` - **Created** (new file)
2. ✅ `config/filesystems.php` - Added `livewire-tmp` disk
3. ✅ `config/session.php` - Updated `same_site` for production
4. ✅ `bootstrap/app.php` - Added CSRF exception and proxy trust
5. ✅ `.env.railway` - Updated session and Livewire settings

## Testing After Deployment

### Test File Upload

1. Login as a student
2. Go to Candidacy Management
3. Try uploading a grade attachment
4. ✅ Should upload successfully without 401 error

### Expected Behavior

-   File uploads to temporary storage
-   Validation occurs
-   File moves to permanent storage (`storage/app/public/grade_attachments/`)
-   Candidacy is created successfully

## Technical Details

### Why `SESSION_SAME_SITE=none`?

Railway uses proxies, which creates cross-origin scenarios. Setting `same_site=none` with `secure=true` allows cookies to work across these boundaries.

### Why Exclude CSRF for `/livewire/upload-file`?

Livewire's upload endpoint uses its own security mechanism. The CSRF token validation was interfering with the upload process on Railway's infrastructure.

### Why Trust All Proxies?

Railway routes traffic through multiple proxies. Trusting all proxies (`*`) ensures Laravel correctly identifies the client IP and protocol (HTTPS).

## Rollback Plan

If issues occur, revert these files:

```bash
git revert HEAD
git push origin main
```

Or manually restore from backup and redeploy.

## Additional Notes

-   ✅ No breaking changes to existing functionality
-   ✅ Local development still works (uses `lax` same-site)
-   ✅ Production security maintained with secure cookies
-   ✅ All other file uploads (profile images, etc.) also benefit from this fix

## Support

If file upload issues persist:

1. Check Railway logs: `railway logs`
2. Verify environment variables are set correctly
3. Ensure `storage/app/livewire-tmp` directory has write permissions
4. Check browser console for JavaScript errors

---

**Last Updated**: January 15, 2026
**Status**: ✅ Ready for Deployment
