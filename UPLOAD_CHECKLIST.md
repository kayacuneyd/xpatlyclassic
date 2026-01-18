# Quick SFTP Upload Checklist

## Before You Start

**SFTP Connection Details:**
- Host: `xpatly.eu` (or use IP from Hostinger)
- Username: `u173493092`
- Port: `22` (SFTP) or `21` (FTP)
- Password: (your Hostinger password)

## Upload Order (CRITICAL!)

### Step 1: BACKUP FIRST! ⚠️

Navigate to: `/home/u173493092/domains/xpatly.eu/public_html/storage/database/`

**Download these files to your computer:**
- `database.sqlite` → Save as `database.sqlite.backup-2026-01-03`

### Step 2: Upload Database (Priority 1)

**Local file:**
```
/Users/thomasmuentzer/Desktop/xpatlyclassic/storage/database/database.sqlite
```

**Upload to:**
```
/home/u173493092/domains/xpatly.eu/public_html/storage/database/database.sqlite
```

**What this fixes:**
- ✅ Password hash corrected (60-char bcrypt)
- ✅ Resend API key configured
- ✅ Email enabled

### Step 3: Upload PHP Files (Priority 2)

| Local File | Upload To |
|------------|-----------|
| `core/Auth.php` | `/home/u173493092/domains/xpatly.eu/public_html/core/Auth.php` |
| `core/Session.php` | `/home/u173493092/domains/xpatly.eu/public_html/core/Session.php` |
| `core/Email.php` | `/home/u173493092/domains/xpatly.eu/public_html/core/Email.php` |
| `views/layouts/header.php` | `/home/u173493092/domains/xpatly.eu/public_html/views/layouts/header.php` |

**What this fixes:**
- ✅ CSRF token synchronization
- ✅ Session cookie path (site-wide)
- ✅ Email system type hints
- ✅ Enhanced auth logging

## Verification (Test Immediately!)

### Test 1: Login
```
URL: https://xpatly.eu/en/login
Email: kayacuneyd@gmail.com
Password: SecurePassword2026!
```

**Expected result:** Login successful, redirect to dashboard

### Test 2: Check Logs (If You Have SSH Access)
```bash
tail -f /home/u173493092/domains/xpatly.eu/public_html/auth-local.log
```

**Look for:**
```
hash_len=60 | bcrypt=1 | hash_prefix=$2y$12$
Login success: user_id=2
```

### Test 3: Email (Optional)
- Login to admin panel: https://xpatly.eu/admin/settings
- Verify Resend API key is configured
- Send test message to a listing
- Email should arrive (from `onboarding@resend.dev`)

## If Something Goes Wrong

### Rollback Database:
1. Login to SFTP
2. Navigate to `/home/u173493092/domains/xpatly.eu/public_html/storage/database/`
3. Upload your backup: `database.sqlite.backup-2026-01-03`
4. Rename it to: `database.sqlite`

### Check File Permissions:
- PHP files: `644`
- SQLite database: `666` or `644`

### Clear Browser Cache:
- Chrome/Firefox: Cmd+Shift+R (Mac) or Ctrl+Shift+R (Windows)
- Clear all cookies for xpatly.eu

## After Successful Login

### Clean Up (Delete These Files from Production):
```
/home/u173493092/domains/xpatly.eu/public_html/fix_password_hash.php
/home/u173493092/domains/xpatly.eu/public_html/test_resend_email.php
/home/u173493092/domains/xpatly.eu/public_html/test_login_flow.php
```

**Why?** Security - these scripts expose sensitive functionality.

## Common SFTP Clients

**Mac:**
- Cyberduck (Free): https://cyberduck.io/
- FileZilla (Free): https://filezilla-project.org/
- Transmit (Paid): https://panic.com/transmit/

**Windows:**
- FileZilla (Free): https://filezilla-project.org/
- WinSCP (Free): https://winscp.net/

**Browser-Based:**
- Hostinger File Manager (Login to Hostinger panel → File Manager)

## Need Help?

If login still fails after upload:
1. Check auth-local.log for error messages
2. Verify file paths match exactly
3. Check file permissions (644 for PHP, 666 for SQLite)
4. Clear browser cache and cookies
5. Try different browser (incognito mode)

## What Gets Fixed:

✅ Login works with `SecurePassword2026!`
✅ Password reset emails work (Resend configured)
✅ CSRF errors eliminated
✅ Session persists across all pages
✅ Email notifications for messages

## Estimated Time: 15 minutes

Good luck! Test login immediately after upload to verify everything works.
