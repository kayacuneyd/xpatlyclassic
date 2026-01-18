# Production Upload Checklist (SFTP)

## CRITICAL: Backup First! ⚠️

Before uploading anything, download current database as backup:
- Download: `/home/u173493092/domains/xpatly.eu/public_html/storage/database/database.sqlite`
- Save as: `database.sqlite.backup-2026-01-03`

---

## Files to Upload (In Order)

### 1. Database (MOST IMPORTANT)
```
Local:  storage/database/database.sqlite
Remote: /home/u173493092/domains/xpatly.eu/public_html/storage/database/database.sqlite
```
**What this fixes:**
- ✅ Corrects password hashes (60-char bcrypt)
- ✅ Adds Resend API key
- ✅ Enables email system
- ✅ Fixes login issues
- ✅ Fixes register issues

### 2. Core PHP Files
```
Local:  core/Auth.php
Remote: /home/u173493092/domains/xpatly.eu/public_html/core/Auth.php

Local:  core/Session.php
Remote: /home/u173493092/domains/xpatly.eu/public_html/core/Session.php

Local:  core/Email.php
Remote: /home/u173493092/domains/xpatly.eu/public_html/core/Email.php
```
**What this fixes:**
- ✅ Enhanced auth logging
- ✅ CSRF token synchronization
- ✅ Session cookie path (site-wide)
- ✅ Email type hints (Resend integration)

### 3. View Files
```
Local:  views/layouts/header.php
Remote: /home/u173493092/domains/xpatly.eu/public_html/views/layouts/header.php

Local:  views/admin/edit-user.php
Remote: /home/u173493092/domains/xpatly.eu/public_html/views/admin/edit-user.php
```
**What this fixes:**
- ✅ CSRF JavaScript auto-sync
- ✅ Super admin password reset feature

### 4. Controllers
```
Local:  controllers/AdminController.php
Remote: /home/u173493092/domains/xpatly.eu/public_html/controllers/AdminController.php
```
**What this fixes:**
- ✅ Super admin password reset logic

---

## Quick SFTP Steps

### Using FileZilla / Cyberduck:

1. **Connect:**
   - Host: `xpatly.eu`
   - Username: `u173493092`
   - Password: (your Hostinger password)
   - Port: `22` (SFTP)

2. **Backup database:**
   - Navigate to: `/home/u173493092/domains/xpatly.eu/public_html/storage/database/`
   - Download `database.sqlite` to your computer

3. **Upload files:**
   - Upload each file from local → remote (keep directory structure)
   - Overwrite when asked

---

## Immediate Test After Upload

1. **Test Register:**
   ```
   https://xpatly.eu/en/register

   Full Name: Test User
   Email: test@example.com
   Phone: +1234567890
   Password: DonaldTrump1234
   Confirm: DonaldTrump1234
   ```
   Expected: Success message, redirect to login

2. **Test Login:**
   ```
   https://xpatly.eu/en/login

   Email: kayacuneyd@gmail.com
   Password: SecurePassword2026!
   ```
   Expected: Login successful, redirect to dashboard

3. **Test Super Admin Password Reset:**
   - Login as super admin
   - Go to: https://xpatly.eu/admin/users
   - Edit a user
   - Scroll to "Set New Password" section
   - Set new password
   - Verify user can login with new password

---

## What Gets Fixed After Upload

✅ **Authentication:**
- Login works with correct password
- Register creates new users properly
- Password reset emails work
- Super admin can reset user passwords

✅ **Security:**
- CSRF tokens synchronized
- Session cookies work site-wide
- All passwords use bcrypt (60-char)

✅ **Email:**
- Resend API configured
- Welcome emails sent
- Password reset emails sent
- Message notification emails sent

---

## Rollback (If Needed)

If something goes wrong:

1. **Restore database:**
   - Upload your backup: `database.sqlite.backup-2026-01-03`
   - Rename to: `database.sqlite`

2. **Contact support:**
   - Check auth-local.log for errors
   - Report issue with log details

---

## Files NOT to Upload (Security)

❌ Do NOT upload these test scripts:
- `fix_password_hash.php`
- `test_resend_email.php`
- `test_login_flow.php`
- `DEPLOYMENT_INSTRUCTIONS.md`
- `PRODUCTION_UPLOAD_LIST.md`
- `UPLOAD_CHECKLIST.md`

These are for local testing only and contain sensitive operations.

---

## Post-Upload Cleanup

After confirming everything works:

**On Production Server (via SFTP or Hostinger File Manager):**

Delete these files if they exist:
```
/home/u173493092/domains/xpatly.eu/public_html/fix_password_hash.php
/home/u173493092/domains/xpatly.eu/public_html/test_resend_email.php
/home/u173493092/domains/xpatly.eu/public_html/test_login_flow.php
```

**On Local (Keep for reference):**
- Keep all files as they are
- This is your working copy

---

## Estimated Time: 10-15 minutes

Good luck! 🚀

After upload, let me know if you encounter any issues.
