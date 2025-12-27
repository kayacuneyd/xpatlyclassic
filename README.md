# Xpatly - Expat-Friendly Real Estate Platform for Estonia

![Status](https://img.shields.io/badge/Status-Production%20Ready-success)
![PHP](https://img.shields.io/badge/PHP-8.2+-blue)
![License](https://img.shields.io/badge/License-Proprietary-red)

## 🌍 Multilingual Platform

**Fully Translated in 3 Languages:**
- 🇬🇧 **English** - Primary language
- 🇪🇪 **Estonian (Eesti)** - Full translation
- 🇷🇺 **Russian (Русский)** - Full translation

All UI elements, forms, messages, and content are available in all three languages with URL-based locale switching.

## 🎯 Project Overview

Xpatly is a discrimination-free real estate platform designed specifically for expats in Estonia. The platform ensures equal access to housing by filtering discriminatory content and promoting expat-friendly listings.

### Key Features

- ✅ **Multi-language Support** - URL-based locale switching (/en, /et, /ru)
- ✅ **Advanced Search & Filtering** - By region, price, area, rooms, amenities
- ✅ **Expat-Friendly Badge System** - Verified properties welcoming expats
- ✅ **Anti-Discrimination Filter** - Automatic content filtering in 3 languages
- ✅ **Listing Management** - Create, edit, delete property listings
- ✅ **Image Upload System** - 1-40 images per listing with auto-resize
- ✅ **Interactive Maps** - Leaflet.js integration (no API key required)
- ✅ **Messaging System** - Direct communication between users and owners
- ✅ **Favorites & Saved Searches** - Personalized user experience
- ✅ **Email Notifications** - Instant, daily, and weekly alerts
- ✅ **Admin Panel** - Complete management interface with activity logging

## 📊 Demo Data Included

### Test Accounts Ready to Use

| Email | Password | Role | Language | Listings |
|-------|----------|------|----------|----------|
| kayacuneyd@gmail.com | Admin123456! | Super Admin | EN | - |
| owner.rent@xpatly.com | Test123456! | Property Owner | ET | 9 rentals |
| owner.sell@xpatly.com | Test123456! | Property Owner | ET | 6 sales |
| renter@xpatly.com | Test123456! | User | EN | - |
| buyer@xpatly.com | Test123456! | User | RU | - |

### 15 Sample Listings Included

- **3 Apartments for Rent** (Tallinn - Kesklinn, Kadriorg, Mustamäe)
- **3 Houses for Rent** (Nõmme, Viimsi, Keila)
- **3 Rooms for Rent** (Kristiine, Kesklinn, Pirita)
- **3 Apartments for Sale** (Kesklinn, Kalamaja, Lasnamäe)
- **3 Houses for Sale** (Pirita, Nõmme, Saku)

All listings include realistic descriptions, GPS coordinates, real Estonian addresses, and market-accurate pricing.

## 🚀 Quick Start

### Installation

```bash
# 1. Install PHP dependencies
composer install

# 2. Install Node dependencies and build CSS
npm install
npm run build

# 3. Database is already seeded with test data
# To re-seed: php migrations/seed_data.php

# 4. Start development server
cd public
php -S localhost:8000
```

### Access the Platform

**Homepage:** http://localhost:8000

**Language URLs:**
- http://localhost:8000/en - English
- http://localhost:8000/et - Estonian
- http://localhost:8000/ru - Russian

**Admin Panel:** http://localhost:8000/admin
- Login: kayacuneyd@gmail.com / Admin123456!

## 🏗️ Technical Stack

- **Backend:** Native PHP 8.2+ (custom MVC framework, no Laravel/Symfony)
- **Frontend:** Tailwind CSS 3.4, Alpine.js 3.13
- **Database:** SQLite with WAL mode (MySQL migration ready)
- **Maps:** Leaflet.js with OpenStreetMap (free, no API key)
- **Email:** PHPMailer with SMTP support
- **Security:** CSRF protection, bcrypt (cost 12), prepared statements

## 📁 Project Structure

```
xpatlyclassic/
├── config/          # Configuration files (database, mail, map)
├── core/            # MVC Framework (9 classes)
├── controllers/     # MVC Controllers (7 files)
├── models/          # Database Models (9 files)
├── views/           # HTML Templates (15+ files)
├── languages/       # Translation files (en.json, et.json, ru.json)
├── emails/          # Email templates (5 files)
├── cron/            # Cron jobs (3 scripts)
├── migrations/      # Database migrations (8 + seeder)
├── public/          # Public web root
├── storage/         # Database, logs, cache, sessions
└── vendor/          # Composer dependencies
```

## 🔒 Security Features

- **CSRF Protection:** Tokens on all forms
- **Password Security:** Bcrypt with cost 12, min 12 chars with complexity
- **SQL Injection Prevention:** Prepared statements everywhere
- **XSS Prevention:** HTML escaping on all output
- **Content Filtering:** Anti-discrimination in EN/ET/RU
- **Session Security:** HTTP-only, secure flags in production

## 🌐 Multilingual System

### How It Works

URLs automatically detect language:
- `/en/listings` → English
- `/et/listings` → Estonian
- `/ru/listings` → Russian

Translation helper functions:
```php
__('home.title')           // Get translated string
trans('listing.create')    // Alias for __()
trans_choice('views', 5)   // Pluralization
```

All 214 translation keys are available in all three languages.

## 📧 Email System

### 5 Email Templates Included

1. **Welcome Email** - New user registration
2. **Email Verification** - Account activation
3. **Password Reset** - Forgotten password
4. **New Message** - Listing inquiry notification
5. **Listing Approved** - Admin approval notification

Configure SMTP in `.env` file.

## ⏰ Automated Tasks (Cron Jobs)

### Email Alerts
```bash
# Instant alerts (every 5 minutes)
*/5 * * * * php /path/to/cron/send_instant_alerts.php

# Daily digest (9 AM)
0 9 * * * php /path/to/cron/send_daily_alerts.php
```

### Maintenance
```bash
# Database cleanup & backup (2 AM daily)
0 2 * * * php /path/to/cron/cleanup.php
```

Cleanup tasks include:
- Remove expired tokens
- Delete old unverified accounts
- Clean cache and session files
- Automatic SQLite backup (keep last 7)

## 🗄️ Database

**8 Tables with Sample Data:**
- users (5 accounts)
- listings (15 active)
- listing_images (ready for uploads)
- messages
- saved_searches
- favorites
- admin_logs
- reports

All tables have proper indexes and foreign key relationships.

### Migration to MySQL

```bash
# Update .env
DB_CONNECTION=mysql
DB_HOST=localhost
DB_NAME=xpatly_db

# Run migrations
php migrations/run_all.php
```

## 🎨 Frontend

### Tailwind CSS

```bash
npm run build   # Production build
npm run watch   # Development watch mode
```

Customization: Edit `tailwind.config.js` and rebuild.

### Alpine.js Features

- Image gallery with lightbox
- Multi-step form wizard
- Mobile navigation menu
- Filter panels
- Interactive components

### Leaflet.js Maps

Free mapping solution:
- No API key required
- OpenStreetMap tiles
- Location markers
- Interactive pin placement

## 👨‍💼 Admin Panel

### Features

- **Dashboard:** Statistics and overview
- **Listings:** Approve/reject/edit listings
- **Users:** Manage user accounts (super_admin only)
- **Reports:** Review content reports
- **Activity Logs:** Complete audit trail

### Access Levels

- **super_admin:** Full system access
- **moderator:** Listing & report management
- **owner:** Own listings management
- **user:** Browse and search only

## 🚀 Production Deployment

### Hostinger Deployment Steps

1. **Upload files to public_html**
2. **Configure .env for production**
   ```bash
   APP_ENV=production
   APP_DEBUG=false
   ```
3. **Run migrations**
   ```bash
   php migrations/run_all.php
   php migrations/seed_data.php
   ```
4. **Set permissions**
   ```bash
   chmod -R 755 storage/ public/uploads/
   ```
5. **Configure cron jobs** in cPanel

See **DEPLOYMENT_READY.md** for complete instructions.

## 📖 Documentation

- **SETUP.md** - Complete installation guide
- **QUICKSTART.md** - Quick start + fixes
- **DEPLOYMENT_READY.md** - Production deployment
- **plan.json** - Original project specification

## 🐛 Troubleshooting

### CSS not loading
```bash
npm install && npm run build
```

### Database errors
```bash
chmod 755 storage/database/
php migrations/run_all.php
```

### Routes not working
- Check `.htaccess` in public/
- Verify mod_rewrite enabled
- Restart web server

## 📊 Current Status

✅ **Fully Functional** - All features implemented and tested
✅ **Production Ready** - Security hardened, optimized
✅ **Multilingual** - 3 languages fully translated (214 keys each)
✅ **Demo Data** - 15 listings + 5 users ready to use
✅ **Documented** - Complete setup and deployment guides

## 🔮 Future Enhancements

- Payment integration (Stripe/Montonio)
- Real-time chat (WebSocket)
- Mobile app (React Native)
- Google OAuth integration
- Advanced analytics dashboard
- Property comparison
- Virtual tours (360°)

## 📞 Support

**Developer:** Cüneyt Kaya
**Website:** kayacuneyt.com
**Email:** kayacuneyd@gmail.com

## 📄 License

Proprietary - All rights reserved

---

**Platform is 100% ready for deployment!** 🎉

Built with ❤️ for the expat community in Estonia.

**Next Steps:**
1. Run `npm run build` to compile CSS
2. Start server: `cd public && php -S localhost:8000`
3. Visit http://localhost:8000
4. Login with kayacuneyd@gmail.com
5. Explore all features!
