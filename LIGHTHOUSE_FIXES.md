# Lighthouse Performance Optimization Plan

## Executive Summary
**Current Scores:**
- Performance: 27/100 ⚠️
- SEO: 62/100 ⚠️
- Accessibility: 57/100 ⚠️
- Best Practices: 65/100 ⚠️
- PWA: 0/100 ❌

**Core Web Vitals:**
- LCP (Largest Contentful Paint): 5.5s ❌ (Target: <2.5s)
- CLS (Cumulative Layout Shift): 0.118 ⚠️ (Target: <0.1)
- INP: null

---

## Critical Issues (High Priority)

### 1. ⚠️ Largest Contentful Paint - 4.5s
**Impact:** VERY HIGH
**Effort:** High
**Current:** 4.5 seconds | **Target:** <2.5 seconds

**Problem:**
- Largest image/content takes 4.5 seconds to paint
- Affects all pages globally

**Solution:**
```html
<!-- BEFORE -->
<img src="/uploads/listings/1/image.jpg" alt="Listing">

<!-- AFTER -->
<!-- 1. Add loading priority for LCP images -->
<img src="/uploads/listings/1/image.jpg"
     alt="Listing"
     fetchpriority="high"
     loading="eager">

<!-- 2. Preload LCP image in <head> -->
<link rel="preload"
      as="image"
      href="/uploads/listings/1/image.jpg"
      fetchpriority="high">

<!-- 3. Use responsive images -->
<img srcset="/uploads/listings/1/image-400w.jpg 400w,
             /uploads/listings/1/image-800w.jpg 800w,
             /uploads/listings/1/image-1200w.jpg 1200w"
     sizes="(max-width: 640px) 400px,
            (max-width: 1024px) 800px,
            1200px"
     src="/uploads/listings/1/image-800w.jpg"
     alt="Listing"
     fetchpriority="high">
```

**Implementation Steps:**
1. Update `views/layouts/header.php` - add preload for hero/featured images
2. Update image rendering in listing cards
3. Generate multiple image sizes during upload in `core/Uploader.php`
4. Add `fetchpriority="high"` to LCP images

---

### 2. ⚠️ Eliminate Render-Blocking Resources - 2,330ms savings
**Impact:** VERY HIGH
**Effort:** High
**Current:** Blocking 2.3 seconds

**Problem:**
- Tailwind CDN blocks rendering: `https://cdn.tailwindcss.com/3.4.17?plugins=forms@0.5.10,typography@0.5.16`
- Google Fonts blocking: Lexend & Inter
- Leaflet CSS blocking on all pages

**Solution:**

**A. Replace Tailwind CDN with Build Version**
```bash
# Install Tailwind locally
npm install -D tailwindcss @tailwindcss/forms @tailwindcss/typography
npx tailwindcss init

# Create tailwind.config.js
cat > tailwind.config.js << 'EOF'
module.exports = {
  content: ["./views/**/*.php", "./public/**/*.html"],
  theme: { extend: {} },
  plugins: [
    require('@tailwindcss/forms'),
    require('@tailwindcss/typography'),
  ],
}
EOF

# Build CSS
npx tailwindcss -i ./input.css -o ./public/assets/css/tailwind.min.css --minify

# Add to build script in package.json
{
  "scripts": {
    "build:css": "tailwindcss -i ./input.css -o ./public/assets/css/tailwind.min.css --minify",
    "watch:css": "tailwindcss -i ./input.css -o ./public/assets/css/tailwind.css --watch"
  }
}
```

**B. Optimize Google Fonts**
```html
<!-- BEFORE in header.php -->
<link href="https://fonts.googleapis.com/css2?family=Lexend:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<!-- AFTER -->
<!-- 1. Preconnect -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

<!-- 2. Load async with display=swap -->
<link href="https://fonts.googleapis.com/css2?family=Lexend:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap"
      rel="stylesheet"
      media="print"
      onload="this.media='all'">
<noscript>
  <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</noscript>
```

**C. Load Leaflet Conditionally**
```php
// views/layouts/header.php
<?php if (isset($useMap) && $useMap): ?>
    <!-- Only load Leaflet on pages that need it -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" media="print" onload="this.media='all'">
    <noscript><link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"></noscript>
<?php endif; ?>

<!-- At bottom before </body> -->
<?php if (isset($useMap) && $useMap): ?>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" defer></script>
<?php endif; ?>
```

---

### 3. ⚠️ Reduce Unused JavaScript - 63 KiB savings
**Impact:** HIGH
**Effort:** Medium
**Current:** 145 KiB total, 40 KiB wasted

**Problem:**
- Tailwind CDN JIT includes unused utilities
- Alpine.js loaded on all pages
- Leaflet loaded on all pages (even those without maps)

**Solution:**
1. **Replace Tailwind CDN** (see solution #2)
2. **Load Alpine.js conditionally:**
```php
// views/layouts/footer.php
<?php if (isset($useAlpine) && $useAlpine): ?>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<?php endif; ?>
```

3. **Bundle and minify JavaScript:**
```bash
# Install esbuild for bundling
npm install -D esbuild

# Create build script
cat > build.js << 'EOF'
require('esbuild').build({
  entryPoints: ['public/assets/js/app.js'],
  bundle: true,
  minify: true,
  outfile: 'public/assets/js/app.min.js',
})
EOF

# Add to package.json
{
  "scripts": {
    "build:js": "node build.js"
  }
}
```

---

### 4. ⚠️ Cumulative Layout Shift - 0.108
**Impact:** HIGH
**Effort:** High
**Current:** 0.108 | **Target:** <0.1

**Problem:**
- Images loading without dimensions cause layout shifts
- Dynamic content insertion
- Web fonts causing FOUT (Flash of Unstyled Text)

**Solution:**

**A. Add Image Dimensions**
```php
// BEFORE
<img src="<?= $image ?>" alt="Listing">

// AFTER
<img src="<?= $image ?>"
     alt="Listing"
     width="800"
     height="600"
     class="w-full h-auto">

// Or use aspect ratio
<div class="aspect-[4/3] relative">
    <img src="<?= $image ?>"
         alt="Listing"
         class="absolute inset-0 w-full h-full object-cover">
</div>
```

**B. Reserve Space for Dynamic Content**
```css
/* Add skeleton loaders */
.skeleton {
    background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
    background-size: 200% 100%;
    animation: loading 1.5s infinite;
}

@keyframes loading {
    0% { background-position: 200% 0; }
    100% { background-position: -200% 0; }
}
```

**C. Optimize Font Loading**
```css
/* Add font-display: swap to @font-face */
@font-face {
    font-family: 'Lexend';
    font-display: swap; /* Prevents FOUT */
    src: url(...);
}
```

---

### 5. ⚠️ Image Optimization
**Impact:** MEDIUM-HIGH
**Effort:** Medium
**Savings:** 76 KiB + faster loading

**Problem:**
- Images not in WebP/AVIF format
- Images not optimized (7 KiB potential savings per image)
- No responsive image sizes

**Solution:**

**A. Generate WebP versions during upload:**
```php
// core/Uploader.php - add to processImage()
private function processImage(string $source, string $destination): bool
{
    // ... existing code ...

    // Save as JPEG (existing)
    imagejpeg($image, $destination, self::JPEG_QUALITY);

    // ALSO save as WebP
    $webpDest = preg_replace('/\.(jpg|jpeg|png)$/i', '.webp', $destination);
    imagewebp($image, $webpDest, 85);

    imagedestroy($image);
    return true;
}
```

**B. Use picture element for WebP:**
```html
<picture>
    <source srcset="/uploads/listings/1/image.webp" type="image/webp">
    <source srcset="/uploads/listings/1/image.jpg" type="image/jpeg">
    <img src="/uploads/listings/1/image.jpg" alt="Listing" loading="lazy">
</picture>
```

**C. Enable WebP in .htaccess (already exists!):**
```apache
# Already in public/.htaccess lines 96-101
<IfModule mod_rewrite.c>
    RewriteCond %{HTTP_ACCEPT} image/webp
    RewriteCond %{REQUEST_URI}  (?i)(.*)(\.jpe?g|\.png)$
    RewriteCond %{DOCUMENT_ROOT}%1.webp -f
    RewriteRule (?i)(.*)(\.jpe?g|\.png)$ %1.webp [L,T=image/webp,R]
</IfModule>
```

---

### 6. ⚠️ Minimize Main-Thread Work - 3.8s
**Impact:** HIGH
**Effort:** Medium
**Current:** 3.8 seconds spent on main thread

**Problem:**
- Script evaluation: 2.1 seconds
- Leaflet.js taking 1.7 seconds to parse/execute
- Third-party blocking: 1.7 seconds

**Solution:**

**A. Defer non-critical JavaScript:**
```html
<!-- Move all scripts to bottom with defer -->
<script src="/assets/js/app.js" defer></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" defer></script>
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
```

**B. Code splitting - load Leaflet only when needed:**
```javascript
// Load Leaflet dynamically
if (document.getElementById('map')) {
    import('https://unpkg.com/leaflet@1.9.4/dist/leaflet-src.esm.js')
        .then(L => {
            // Initialize map
        });
}
```

**C. Use Intersection Observer for lazy initialization:**
```javascript
// Only initialize map when it's visible
const mapContainer = document.getElementById('map');
if (mapContainer) {
    const observer = new IntersectionObserver((entries) => {
        if (entries[0].isIntersecting) {
            initMap();
            observer.disconnect();
        }
    });
    observer.observe(mapContainer);
}
```

---

## Medium Priority Issues

### 7. ⚠️ Buttons Without Accessible Names
**Impact:** MEDIUM (Accessibility)
**Effort:** Low

**Problem:**
- Language dropdown button has no aria-label
- Mobile menu toggle has no label

**Solution:**
```php
// views/layouts/header.php
<!-- Language selector -->
<button type="button"
        class="text-gray-700 hover:text-gray-900"
        aria-label="Select language"
        aria-expanded="false"
        @click="showLangMenu = !showLangMenu">
    <!-- SVG icon -->
</button>

<!-- Mobile menu toggle -->
<button type="button"
        class="md:hidden"
        aria-label="Open menu"
        aria-expanded="false"
        @click="mobileMenuOpen = !mobileMenuOpen">
    <!-- Hamburger icon -->
</button>
```

---

### 8. ⚠️ Third-Party Code Impact
**Impact:** MEDIUM
**Effort:** Medium
**Blocking Time:** 1,710ms

**Problem:**
- Unpkg CDN for Leaflet
- Tailwind CDN
- Google Fonts

**Solution:**
1. **Host Leaflet locally:**
```bash
# Download Leaflet
mkdir -p public/assets/libs/leaflet
cd public/assets/libs/leaflet
wget https://unpkg.com/leaflet@1.9.4/dist/leaflet.js
wget https://unpkg.com/leaflet@1.9.4/dist/leaflet.css
wget https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png
wget https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png
```

2. **Update references:**
```php
<link rel="stylesheet" href="/assets/libs/leaflet/leaflet.css">
<script src="/assets/libs/leaflet/leaflet.js" defer></script>
```

3. **Self-host Google Fonts (optional):**
```bash
# Use google-webfonts-helper
# Download fonts and serve from /assets/fonts/
```

---

## Low Priority / Quick Wins

### 9. ✅ Add Missing Meta Tags
**Effort:** Very Low

```php
// views/layouts/header.php - ADD:
<meta name="theme-color" content="#4F46E5">
<meta name="description" content="<?= $metaDescription ?? 'Find expat-friendly housing in Estonia' ?>">
<link rel="manifest" href="/manifest.json">
```

---

### 10. ✅ Enable Text Compression
**Effort:** Very Low

Already enabled in `.htaccess` lines 31-55 ✓

---

### 11. ✅ Add Cache Headers
**Effort:** Very Low

Already enabled in `.htaccess` lines 60-91 ✓

---

## Implementation Roadmap

### Phase 1: Quick Wins (1-2 hours)
- [ ] Add `fetchpriority="high"` to LCP images
- [ ] Add `width` and `height` to all images
- [ ] Add `defer` to all script tags
- [ ] Add `aria-label` to buttons
- [ ] Load Leaflet conditionally (only on map pages)
- [ ] Add aspect-ratio containers for images

**Expected Improvement:** Performance +15-20 points

---

### Phase 2: Build Process Setup (2-4 hours)
- [ ] Install Tailwind CSS locally
- [ ] Setup build scripts (npm)
- [ ] Generate WebP images during upload
- [ ] Bundle and minify JavaScript
- [ ] Replace Tailwind CDN with built version

**Expected Improvement:** Performance +20-25 points

---

### Phase 3: Advanced Optimizations (4-8 hours)
- [ ] Implement responsive image sizes (srcset)
- [ ] Add preload hints for critical resources
- [ ] Implement lazy loading for below-fold images
- [ ] Add skeleton loaders for dynamic content
- [ ] Host third-party libraries locally
- [ ] Implement code splitting for Leaflet

**Expected Improvement:** Performance +15-20 points

---

### Phase 4: Infrastructure (Optional)
- [ ] Setup CDN (Cloudflare)
- [ ] Enable HTTP/2 or HTTP/3
- [ ] Implement service worker caching (already exists!)
- [ ] Add resource hints (dns-prefetch, preconnect)

**Expected Improvement:** Performance +5-10 points

---

## Target Scores After Implementation

| Metric | Current | Target | Expected |
|--------|---------|--------|----------|
| **Performance** | 27 | 90+ | 85-92 |
| **SEO** | 62 | 95+ | 95+ |
| **Accessibility** | 57 | 90+ | 88-95 |
| **Best Practices** | 65 | 95+ | 92-98 |
| **LCP** | 4.5s | <2.5s | 1.8-2.2s |
| **CLS** | 0.118 | <0.1 | 0.05-0.08 |

---

## Files to Modify

### High Priority:
1. `views/layouts/header.php` - Add preload, optimize fonts
2. `views/layouts/footer.php` - Move scripts, add defer
3. `core/Uploader.php` - Generate WebP, responsive sizes
4. `views/home/index.php` - Add fetchpriority to hero image
5. `views/listings/show.php` - Image dimensions, lazy loading
6. Create `tailwind.config.js` - Build process
7. Create `package.json` - Build scripts

### Medium Priority:
8. All view files with images - Add width/height
9. All view files with buttons - Add aria-labels
10. `public/.htaccess` - Already optimized ✓

---

## Testing After Each Phase

```bash
# Run Lighthouse after each phase
npx lighthouse https://xpatly.eu --view

# Or use Chrome DevTools
# Open DevTools > Lighthouse > Analyze page load
```

---

## Notes

- **Current bottleneck:** Tailwind CDN (2.3s blocking)
- **Biggest win:** Replace CDN with build version
- **Quick wins:** Image optimization + defer scripts
- **Long-term:** Consider Next.js or static site generation for listings

---

## Resources

- [Web.dev Performance Guide](https://web.dev/performance/)
- [Tailwind Build Process](https://tailwindcss.com/docs/installation)
- [WebP Image Guide](https://developers.google.com/speed/webp)
- [Core Web Vitals](https://web.dev/vitals/)
