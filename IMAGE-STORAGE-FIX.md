# Image Storage Fix for Railway

## Problem
Railway's filesystem is ephemeral - uploaded files are lost on each deployment. Your images stored in `storage/app/public` don't persist across deployments.

## Solution Implemented
✅ Changed image storage from files to **base64 data URIs** stored directly in the database
✅ Updated migration to support `longtext` for base64 image data
✅ Updated views to handle both base64 and file paths (backward compatible)
✅ Added `php artisan storage:link` to deployment process

## What You Need to Do

### 1. Re-upload Your Images on Railway

Once Railway finishes deploying, you need to **re-upload your images** through the system settings interface:

1. Log into your Railway app: `https://stiievote-production.up.railway.app`
2. Go to **System Settings**
3. Find these image settings and re-upload them:
   - `login_top_logo` (Module ID: 6)
   - `login_center_logo` (Module ID: 3)
   - `sidebar_logo` (if exists)

### 2. How It Works Now

When you upload an image:
- **Before**: Saved to `storage/app/public/system_settings/filename.png` (lost on redeploy)
- **Now**: Converted to base64 and stored in database (persists forever)

Example of stored value:
```
data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAA...
```

### 3. For Local Development

Your local setup still works! The code now supports **both formats**:
- Base64 data URIs (for Railway)
- File paths (for local development)

### 4. Image Size Considerations

⚠️ **Important**: Base64 encoding increases file size by ~33%. Recommended limits:
- Logos: < 100KB original size
- Illustrations: < 500KB original size

For larger images, consider using:
- AWS S3
- Cloudinary
- ImgBB
- Or other CDN services

## Migration Notes

The migration `2025_11_18_161133_modify_system_settings_value_to_longtext.php` will run automatically on Railway to support base64 storage.

## Testing

After re-uploading images on Railway:
1. Images should appear correctly on login page
2. Images should appear in sidebar
3. Images persist after redeployment
4. No mixed content errors (HTTPS enforced)

## Next Steps (Optional)

If you want to migrate to external storage in the future:
1. Add AWS S3 credentials to Railway env vars
2. Update `config/filesystems.php` to use S3
3. Change `FILESYSTEM_DISK=s3` in environment
