# Xpatly Setup Guide

## ✅ Installation Complete!

The Xpatly real estate platform has been successfully installed. Here's what was created:

### Project Structure
```
xpatlyclassic/
├── config/          # Configuration files
├── core/            # Core framework (Router, Database, Auth, etc.)
├── controllers/     # MVC Controllers
├── models/          # Database Models
├── views/           # HTML Templates
├── public/          # Public web root
├── languages/       # Translation files (en.json)
├── migrations/      # Database migrations
├── storage/         # Database, logs, cache
└── vendor/          # Composer dependencies
```

### What's Been Installed

✅ **Core Framework:**
- Custom MVC Router with multi-language support
- Database layer (SQLite with MySQL migration path)
- Authentication system
- Form Validator with anti-discrimination filter
- Translation system
- Session & Flash messages
- File uploader with image processing
- Simple cache system

✅ **Database:**
- 8 tables created (users, listings, listing_images, messages, saved_searches, favorites, admin_logs, reports)
- Indexes created for performance
- Default admin user created

✅ **Features:**
- User authentication (register, login, password reset)
- Listing management (create, edit, delete)
- Expat-friendly badge system
- Discriminatory content blocker
- Image upload (1-40 images per listing)
- Multi-language support (EN, ET, RU ready)
- Admin panel (listing/user management)
- Search & filtering
- Leaflet.js maps integration ready

## 🚀 How to Run

### Option 1: PHP Built-in Server (Development)

```bash
cd /Users/thomasmuentzer/Desktop/xpatlyclassic/public
php -S localhost:8000
```

Then visit: **http://localhost:8000**

### Option 2: MAMP/XAMPP

1. Point document root to: `/Users/thomasmuentzer/Desktop/xpatlyclassic/public`
2. Start Apache
3. Visit: **http://localhost**

## 🔐 Default Admin Credentials

- **Email:** admin@xpatly.com
- **Password:** Admin123456!

## 📝 Next Steps

### 1. Install Tailwind CSS (Required for styling)

```bash
npm install
npm run build
```

### 2. Configure Services (Optional)

Edit `.env` file to add:
- SMTP email settings (for password reset, notifications)
- Twilio credentials (for phone verification)
- Google OAuth (for social login)

### 3. Test the Platform

1. Visit homepage: http://localhost:8000
2. Login as admin: http://localhost:8000/login
3. Create a test listing
4. Browse listings
5. Test search & filters

## 🗂️ Important Files

- **Entry Point:** `public/index.php`
- **Routes:** Defined in `public/index.php`
- **Database:** `storage/database/database.sqlite`
- **Config:** `.env` file
- **Translations:** `languages/en.json`

## 🎨 Frontend Customization

1. Edit Tailwind config: `tailwind.config.js`
2. Modify input CSS: `public/assets/css/input.css`
3. Rebuild CSS: `npm run build`

## 🗺️ Maps Integration

Leaflet.js is configured and ready. Maps will be loaded on pages with `$useMap = true`.

Config file: `config/map.php`

## 📊 Database Management

### Re-run migrations:
```bash
php migrations/run_all.php
```

### Migrate to MySQL:
1. Update DB_CONNECTION in `.env`
2. Add MySQL credentials
3. Run migration script

## 🐛 Troubleshooting

### Issue: "Page not found"
- Check that you're in the `public/` directory
- Verify `.htaccess` is present

### Issue: "Database error"
- Check file permissions on `storage/` folder
- Verify SQLite extension is enabled

### Issue: "CSS not loading"
- Run `npm install && npm run build`
- Check `/public/assets/css/app.css` exists

## 📖 Documentation

- Full plan: `plan.json`
- Database schema: See `migrations/` folder
- Translation keys: `languages/en.json`

## 🎯 Core Features Status

✅ Complete & Working:
- User authentication
- Database & models
- MVC architecture
- Admin panel structure
- Listing system structure
- Multi-language support
- Discriminatory content filter

⏳ Needs Configuration:
- Email sending (add SMTP credentials)
- Phone verification (add Twilio credentials)
- Map display (views not created yet)
- Frontend styling (Tailwind needs compilation)

## 🔄 Development Workflow

1. **Make changes** to code
2. **Test** in browser
3. **Check logs** in `storage/logs/`
4. **Rebuild CSS** if needed: `npm run build`

## 🚀 Production Deployment

See `plan.json` section "deployment_instructions" for complete Hostinger deployment guide.

## 📞 Support

Developer: Cüneyt Kaya - kayacuneyt.com

---

**Platform is ready for development and testing!** 🎉

Start by running: `php -S localhost:8000` from the public/ directory.
