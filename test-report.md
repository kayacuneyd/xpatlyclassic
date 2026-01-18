# QA Smoke Report (2026-01-01)

## Environment

- Server: `php -S 127.0.0.1:8000 -t public`
- DB: `storage/database/database.sqlite` (15 listings; 14 active, 1 pending)
- Assets: `public/uploads/listings/{1..15}/main.jpg` present and primary in DB

## Checks performed

- Home `/` (GET) → 200 OK, page renders.
- Listings `/listings` → 200 OK; page shows "14 properties found" (pending listing excluded). Cards reference `/uploads/listings/{id}/main.jpg`.
- Map view `/listings?view=map` → 200 OK; HTML/JS delivered (client-side zoom not verified here).
- Listing detail `/listings/1` → 200 OK; gallery initialized with `main.jpg`.
- Image availability `/uploads/listings/1/main.jpg` and `/uploads/listings/15/main.jpg` → 200 OK.
- Admin `/admin/listings` (unauthenticated) → 302 to `/login` (expected access control).

## Notes / Risks

- HEAD requests return 404 because routes are GET-only (e.g., `curl -I /`), but normal GETs work.
- Map zoom behavior not observable via curl; needs browser validation to confirm city-level zoom heuristic.
- No authenticated flows (login, create/edit listing, messages) were exercised; recommend a separate pass with test credentials.

## Additional auth/media findings (owner.rent@xpatly.com)

- Seeded user passwords do not match documented `Test123456!` (password_verify fails). Had to manually reset hashes in DB to log in.
- After resetting hashes, login succeeds and dashboard renders (200).
- Messaging flow (POST /listings/{id}/message) requires `sender_email` even for authenticated users and CSRF token parsing is brittle; initial attempts returned 403 and no message was persisted. Needs browser verification.
- HEAD requests return 404 (GET works); minor but may confuse uptime monitors.

-PWA YAPILANDIRMASI - SAYFA YÜKLENİRKEN BİR ANLIK BEYAZ EKRAN GELİYOR. PERFORMANS AÇISINDAN BİR DE BANNERIN NE GİBİ ETKİSİ VAR ONA DA BAKMAK LAZIM
-FORGET PASS ERROR VERİYOR
-PHP HATALI ZİYARETÇİYE GÖSTERİLMEMELİ
-DİL ÇEVİRİLERİNDE BAZI EKSİKLİKLER GÖZLEMLENMİŞ
-SPAM KORUMASI YOK, EN AZINDAN BAŞLANGIÇ İÇİN HONEYPOT ŞEKLİNDE BİR ÇÖZÜM UYGULANABİLİR Mİ?

## Lighthouse follow-up (2026-01-01)
- ✅ Added explicit width/height to header logo to prevent CLS (`views/layouts/header.php`).
- ✅ Improved form accessibility on home quick search: aria-labels on selects/ranges/numbers, heading to h2, dots enlarged with aria-labels and 24px target, price text darker for contrast (`views/home/index.php`).
- ✅ Increased CTA contrast: hero primary button now blue (secondary-700); header Sign In button uses secondary-800; footer developer link uses darker color + underline for distinguishable link (`views/layouts/header.php`, `views/home/index.php`, `views/layouts/footer.php`).
- ✅ Added aria-labels/ids to listing filters and price/rooms/area inputs on search page (`views/search/index.php`); carousel prev/next buttons now have aria-labels; Rent CTA text/icons switched to dark for contrast (`views/home/index.php`).
- ✅ Search/listings: added id/for on condition/energy selects, floor/year inputs; pagination links enlarged to 48px and labelled; listing card images get width/height; detail gallery images/thumbnails get width/height and larger, labelled arrows; prices use darker secondary color; toast close button labelled.
- ✅ Blog index: “Read more” links now have descriptive aria-label + sr-only title text for SEO/a11y.
- ✅ Listings/search: added sort label/id, hamburger menu now has aria-label; gallery arrows enlarged to meet touch target guidance.
- ✅ Blog images now include explicit width/height on listing and detail pages to avoid unsized-image CLS.
- ⚠️ Remaining to verify/fix: contrast on yellow cards/buttons elsewhere (rent/host tiles, text-white on yellow), range/number labels on search listings page, carousel arrow buttons need aria-labels, link-in-text-block contrast across site. Re-run Lighthouse/axe to confirm `button-name`, `color-contrast`, `select-name/label`, `target-size`, `unsized-images` pass.

## Security notes
- Error display now auto-disabled in production via `APP_ENV` check; still logs all errors (`public/index.php`).
- Added baseline security headers: X-Frame-Options, X-Content-Type-Options, Referrer-Policy strict-origin-when-cross-origin, and a restrictive Permissions-Policy (`public/index.php`).
- CSRF/auth flows were not modified in this round; login, forgot-password, spam/Honeypot still flagged as follow-up items.
- No new external dependencies added; changes were limited to headers and markup/a11y/contrast and do not widen attack surface.

## Email / forgot-password updates
- `.env` cleaned for SMTP values (Hostinger) without inline comments.
- `config/mail.php` now supports both SMTP_* and MAIL_* env keys, plus reply-to.
- Added a global `send_mail()` helper using PHPMailer with config values.
- Forgot-password now sends a reset email with token link (neutral messaging preserved); reset link uses APP_URL + locale-aware URL. Reply-To can route to support address.
- Session cookies now set Secure only when HTTPS detected (prevents cookie drop on http during testing); login form uses locale-aware URLs for POST/links.
- Login screen “Forgot password” link moved outside the form to avoid any browser validation blocking navigation.
- Session storage now uses `storage/sessions` when writable (creates it if missing) to improve session persistence on shared hosting.
- Session storage now falls back to a temp directory (`/tmp/xpatly_sessions`) when storage isn’t writable.
- SQLite path resolution now supports absolute paths to avoid broken DB connections on shared hosting.
- Added auth debug logging to `storage/logs/auth.log` for login attempts, session save path, and login success/failure.
- Forgot-password now logs submit, validation failures, and mail send success/failure to `storage/logs/auth.log`.
- Reset-password now logs submit and success, clears `intended_url`, and redirects using locale-aware login URL.
- Auth redirects (login/register/forgot/reset/logout/verify) now use locale-aware URLs; login/register forms use locale-aware action/links.
- Auth middleware now stores `intended_url` before redirecting to login.
- Verification emails fall back to SMTP (`send_mail`) when Resend is not configured.
## Auth Flow - Local Test Results (Mock Email)

Date: 2026-01-02
Scope: Signup / Login / Forgot Password / Reset Password (mock email)

Tests:
- signup_user_created: OK
- signup_hash_bcrypt: OK
- login_wrong_email_toast: OK
- login_wrong_password_toast: OK
- forgot_token_created: OK
- reset_token_cleared: OK
- reset_hash_bcrypt: OK
- login_success_redirected: OK

Notes:
- Password policy disallows sequential patterns (e.g., 123/abc).
- Tests run locally with MAIL_MOCK=1 (no outbound email).

## Lighthouse + Playwright (2026-01-04)

- Lighthouse (https://xpatly.eu): Performance 67, Accessibility 91, Best Practices 96, SEO 100.
- Key Lighthouse metrics: FCP 3.8s, LCP 5.2s, TBT 0ms, CLS 0.108, Speed Index 4.7s, TTI 5.2s.
- Playwright smoke (public pages): /, /listings, /login load with non-empty titles.
- Report artifact: `lighthouse-report.json`.
