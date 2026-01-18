# Production Deployment Instructions

## Files to Upload via SFTP

### Priority 1: Database (Password Fix)
```
storage/database/database.sqlite
```
**Upload to:** `/home/u173493092/domains/xpatly.eu/public_html/storage/database/`

**IMPORTANT:** Backup the current database first!
```bash
# On production server, run this first:
cp storage/database/database.sqlite storage/database/database.sqlite.backup
```

### Priority 2: Core PHP Files (CSRF & Email Fixes)
```
core/Auth.php
core/Session.php
core/Email.php
views/layouts/header.php
```
**Upload to:** `/home/u173493092/domains/xpatly.eu/public_html/`
(Keep the same directory structure)

## Upload Steps (via SFTP Client)

### Using FileZilla / Cyberduck / Any SFTP Client:

1. **Connect to server:**
   - Host: `xpatly.eu` or IP from Hostinger
   - Username: `u173493092`
   - Password: (your hosting password)
   - Port: `21` (FTP) or `22` (SFTP)

2. **Backup first (CRITICAL!):**
   - Navigate to `/home/u173493092/domains/xpatly.eu/public_html/storage/database/`
   - Right-click `database.sqlite`
   - Download to your computer as backup
   - Or rename it to `database.sqlite.backup` on server

3. **Upload database:**
   - Navigate to local: `/Users/thomasmuentzer/Desktop/xpatlyclassic/storage/database/`
   - Select `database.sqlite`
   - Upload to: `/home/u173493092/domains/xpatly.eu/public_html/storage/database/`
   - Overwrite when asked

4. **Upload PHP files:**
   - Navigate to local: `/Users/thomasmuentzer/Desktop/xpatlyclassic/`
   - Upload these files (keeping directory structure):
     - `core/Auth.php` → `/home/u173493092/domains/xpatly.eu/public_html/core/`
     - `core/Session.php` → `/home/u173493092/domains/xpatly.eu/public_html/core/`
     - `core/Email.php` → `/home/u173493092/domains/xpatly.eu/public_html/core/`
     - `views/layouts/header.php` → `/home/u173493092/domains/xpatly.eu/public_html/views/layouts/`

5. **Test immediately:**
   - Go to https://xpatly.eu/en/login
   - Login with:
     - Email: `kayacuneyd@gmail.com`
     - Password: `SecurePassword2026!`
   - Should work! ✅

## What Gets Fixed:

### After Database Upload:
- ✅ Password hash corrected (60-char bcrypt)
- ✅ Can login with `SecurePassword2026!`
- ✅ Resend API key configured

### After PHP Files Upload:
- ✅ CSRF token synchronization fixed
- ✅ Session cookie path fixed (site-wide)
- ✅ Email system type hints fixed
- ✅ Enhanced auth logging

## Verification:

After upload, test these:

1. **Login test:**
   ```
   https://xpatly.eu/en/login
   Email: kayacuneyd@gmail.com
   Password: SecurePassword2026!
   ```
   Expected: Login successful, redirect to dashboard

2. **Check logs:**
   ```bash
   # If you have SSH access
   tail -f auth-local.log
   ```
   Should see:
   ```
   Login hash info: ... | hash_len=60 | bcrypt=1
   Login success: ...
   ```

3. **Test email (optional):**
   - Need to verify domain in Resend first
   - Then test forgot password feature

## Rollback (if something goes wrong):

1. **Restore database backup:**
   - Upload the backup file you downloaded in step 2
   - Rename back to `database.sqlite`

2. **Restore PHP files:**
   - Re-upload old versions from server backup

## Alternative: Browser-Based Upload

If you don't have SFTP client:

1. **Use Hostinger File Manager:**
   - Login to Hostinger panel
   - Go to File Manager
   - Navigate to `/public_html/`
   - Use Upload button for each file

2. **For Database:**
   - Download current database as backup first
   - Upload new database.sqlite
   - Make sure permissions are correct (644)

## Post-Deployment Cleanup:

After confirming everything works:

1. **Clear app cache and sessions (deploy step):**
   ```bash
   # If you have SSH access
   php cron/cleanup.php
   ```
   Or delete these folders manually in File Manager:
   - `storage/cache/*.cache`
   - `storage/sessions/*`

2. **Delete test scripts** (via SFTP or File Manager):
   ```
   fix_password_hash.php
   test_resend_email.php
   test_login_flow.php
   ```

3. **Clear browser cache:**
   - Clear cookies for xpatly.eu
   - Hard refresh (Cmd+Shift+R on Mac)

## Need Help?

If upload fails or login still doesn't work:
1. Check auth-local.log for errors
2. Verify file permissions (should be 644 for .php files, 666 for .sqlite)
3. Make sure you uploaded to correct paths
