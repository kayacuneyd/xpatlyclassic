# Xpatly Quick Start Guide

## Issues Fixed ✅

### Issue 1: Missing Helper Functions
**Problem:** "Call to undefined function Controllers\__()"
**Solution:** Created [core/helpers.php](core/helpers.php) with all global helper functions and loaded it in [public/index.php](public/index.php:14)

### Issue 2: Route Parameter Type Mismatch
**Problem:** "Argument #1 ($id) must be of type int, string given"
**Solution:**
- Fixed route ordering in [public/index.php](public/index.php:51-52) - specific routes (`/listings/create`) now come before wildcard routes (`/listings/{id}`)
- Updated all controller methods to accept `string $id` and cast to `int` internally in:
  - [controllers/ListingController.php](controllers/ListingController.php)
  - [controllers/AdminController.php](controllers/AdminController.php)

## Start the Platform

### 1. Install Dependencies (First Time Only)

```bash
# Install PHP dependencies
composer install

# Install Node dependencies
npm install

# Build Tailwind CSS
npm run build
```

### 2. Start Development Server

```bash
cd public
php -S localhost:8000
```

### 3. Access the Platform

Open your browser and visit: **http://localhost:8000**

## Default Login

- **URL:** http://localhost:8000/login
- **Email:** admin@xpatly.com
- **Password:** Admin123456!

## Available Helper Functions

The platform now includes these global helper functions (defined in [core/helpers.php](core/helpers.php)):

### Translation Functions
- `__(string $key, array $replace = [])` - Translate a key
- `trans(string $key, array $replace = [])` - Translate a key (alias)
- `trans_choice(string $key, int $count, array $replace = [])` - Translate with pluralization

### Security Functions
- `csrf_token()` - Get CSRF token
- `csrf_field()` - Generate CSRF hidden input field
- `e(mixed $value)` - Escape HTML entities

### URL & Asset Functions
- `url(string $path = '')` - Generate URL with locale prefix
- `route(string $path = '')` - Generate route URL (alias)
- `asset(string $path)` - Generate asset URL
- `redirect(string $url)` - Redirect to a URL

### Utility Functions
- `old(string $key, mixed $default = '')` - Get old input value
- `config(string $key, mixed $default = null)` - Get configuration value
- `dd(...$vars)` - Dump and die (debugging)

## What Was Fixed

1. **Created** [core/helpers.php](core/helpers.php) with all global helper functions
2. **Updated** [public/index.php](public/index.php:14) to load helpers early in the bootstrap process
3. **Cleaned** [core/Translation.php](core/Translation.php) to remove duplicate helper definitions

## Platform Status

✅ **Working:**
- Homepage loads successfully
- All helper functions available globally
- Translation system initialized
- Router with multi-language support active
- Database connected and migrated
- Authentication system ready

✅ **Files Created:** 55+ files
✅ **Database Tables:** 8 tables with default admin user
✅ **Core Classes:** 9 framework classes
✅ **Controllers:** 7 controllers
✅ **Models:** 9 models
✅ **Views:** 15+ view files
✅ **Email Templates:** 5 templates
✅ **Cron Jobs:** 3 automated scripts

## Next Steps

1. **Build Tailwind CSS:** Run `npm run build` to compile styles
2. **Configure Email:** Add SMTP credentials to [.env](.env:12-18)
3. **Test Features:** Create a listing, test search, try admin panel
4. **Customize:** Edit [tailwind.config.js](tailwind.config.js) and rebuild CSS

## Development Workflow

```bash
# Watch and rebuild CSS on changes
npm run watch

# Start PHP server (in another terminal)
cd public && php -S localhost:8000
```

## Troubleshooting

If you see "CSS not loading":
```bash
npm install
npm run build
```

If you see database errors:
```bash
php migrations/run_all.php
```

## Documentation

- Full setup guide: [SETUP.md](SETUP.md)
- Complete plan: [plan.json](plan.json)
- Translation keys: [languages/en.json](languages/en.json)

---

**Platform is ready!** Start coding at http://localhost:8000 🚀
