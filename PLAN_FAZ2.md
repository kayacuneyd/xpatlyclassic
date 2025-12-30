Xpatly/Makler Proje Notları ve Revizyon Analizi

Var Olan Websitesi (xpatly.eu) Üzerinden Tasarım İncelemesi
	•	Hero alanı – Xpatly websitesindeki ana sayfa, tam genişlikte bir kahraman (hero) alanı kullanıyor. Arka plan görseli ile opak olmayan bir gölgelendirme kombinasyonu sayesinde başlıklar öne çıkıyor. Ana başlık olarak “Rent unique properties around the world” metni kullanılmış ve altında tek satırlık bir açıklama yer alıyor ￼. Tasarımda kart köşeleri yuvarlatılmış ve site genelinde yuvarlatılmış butonlar ve konteynerler tercih edilmiş.
	•	Yazı tipi – Sayfa kaynağında Google Font’larından Lexend ve Inter yazı tiplerinin yüklendiği görülüyor ￼. Xpatly‑style uyumlu bir arayüz için bu fontlar kullanılmalı.
	•	Menü yapısı – Logo sol tarafta, orta kolonda ana menü (Home, Properties, Blog, Contact) ve sağ kolonda “Sign In/Sign Up” düğmeleri bulunuyor. Menü butonları yuvarlatılmış ve sayfa genişliğinin ortasına hizalanmış.

Bu tasarım dili; tam sayfa hero resmi, yuvarlatılmış köşeler ve sade tipografi ile öne çıkıyor. Yeni sitede tasarım uyumu için bu unsurlar korunmalı ve güncel platforma uyarlanmalı.

Makler.digitaltamam.com Sitesinin Durumu

Ana Sayfa
	•	Renk ve tipografi – Yeni site, gradient mavi arka plan üzerine beyaz metin ve yuvarlak arama kutusu içeriyor. Ancak mevcut Xpatly tasarımı ile tutarlılık açısından arka plan yerine bir görsel ve açık tonlar kullanılmalı.
	•	Arama bölümü – Arama çubuğunun altında “View All Listings” ve “How It Works” gibi iki buton yer alıyor. Müşterinin talebi bu butonların kaldırılması ve aramanın daha sade hale getirilmesi yönünde.
	•	Navigasyon menüsü – Üç kolonluk yapı (Logo – Menü – Dil seçici + Sign In + Register) kullanılıyor. Sağ kolonda “Sign In” ve “Register” olmak üzere iki ayrı düğme bulunduğundan ortadaki menü grubu sola kayıyor. Bu hizalama problemi, “Sign In” ve “Register” fonksiyonlarını tek bir düğme altında toplayarak ortadaki menü grubunu tam merkezde hizalayacak şekilde çözülebilir.

Listings (İlanlar) Sayfası

Filtre panelinin sol sütununda bölge, kategori ve anlaşma türü seçimleri yer alıyor. Ayrıca fiyat aralığı ve oda sayısı için min/maks giriş alanları mevcut ve en altta “Expat‑Friendly Only” adlı bir onay kutusu var. Müşteri aşağıdaki değişiklikleri talep ediyor:
	•	“Deal Type” filtresi en üst sıraya taşınmalı.
	•	Fiyat aralığı ve oda sayısı giriş alanları yerine çift yönlü kaydırma çubuğu (range slider) kullanılmalı.
	•	“Expat‑Friendly Only” onay kutusu kaldırılmalı.
	•	“Rooms” ve “Price range” alanlarındaki artırma/azaltma butonları kaldırılmalı.
	•	Filtrelerin altına eklenen listeleme kartlarının altındaki butonlar (ilave butonlar) kaldırılmalı.

Filtre menüsünün mevcut durumunu aşağıdaki ekran görüntüsü özetlemektedir.

Diğer Tasarım Eksikleri
	•	Menü hizalama – Sağ kolondaki iki ayrı oturum düğmesi nedeniyle orta menü sola kayıyor. “Sign In” ve “Register” işlevleri birleştirildiğinde menü ortalanabilir.
	•	Footer düzeni – Alt kısımda kolonların eşit genişlikte olmaması ve içerik miktarının farklılığı nedeniyle düzensizlik hissi oluşuyor. Her kolonda benzer sayıda bağlantı ve başlık kullanılarak düzen sağlanabilir.
	•	Emojiler – Site içinde kullanılan emojiler profesyonel tasarım anlayışını zedeliyor. Tüm emojiler kaldırılmalı.

İlan Filtreleme: Estonya Sitelerinden İlham

KV.EE – Gelişmiş Arama

Kv.ee platformu, konut arama için basit ve gelişmiş arama düzeyleri sunar. Üst bölümde gayrimenkul türü, ilçe ve şehir seçimleri, “Rooms” (oda sayısı) ve “Price” (fiyat) aralıkları gibi temel alanlar bulunur. “Advanced Search” düğmesine basınca ek filtreler açılır; bunlar arasında alan (m²), kat (floor), balkon, park yeri, sauna, yapım yılı, enerji sınıfı gibi kriterler ve yapı malzemesi ile durum (condition) listeleri yer alır. Filtreler çek‑bırak alanlar olarak tasarlanmış ve kullanıcı ihtiyaçlarına göre kademeli olarak açılır. Yeni sitede bu model örnek alınarak “Gelişmiş Filtre” bölümü tasarlanabilir.

City24.ee – Extras (Lisaväärtused)

City24 portalındaki arama çubuğunda “+ Muud…” menüsünden ek özellikler açılır. “Lift, rõdu (balkon), kelder (depo), saun, vann, garaaž, mööbel” gibi özellikler onay kutuları şeklinde listelenir. Müşteri, bu tür bir “Extras” listesi talep ediyor; bu yüzden sitedeki filtre paneline konum, fiyat, oda gibi temel kriterlerin yanı sıra yukarıdaki örneğe benzer ek özellikler eklenmeli (asansör, balkon, depo/sauna, garaj, mobilya dâhil vb.).

Giriş/Kayıt ve Doğrulama
	•	Giriş sayfası – Giriş formu merkezde yer alıyor ve sade bir tasarıma sahip, ancak hatalı girişlerde uyarı mesajları ekranın sağ üst köşesinde belirdiğinden butonlarla karışabiliyor. Müşterinin isteği doğrultusunda uyarı veya başarı mesajları form kartının üzerinde modal veya toast bildirimi olarak gösterilmeli ve sayfadaki diğer öğeleri engellememeli.
	•	Kullanıcı doğrulama – Şu an doğrulama sistemi çalışmıyor; doğrulanmamış kullanıcılar ilan ekleyebiliyor ve mesaj gönderebiliyor. İlan ekleme ve mesajlaşma işlemleri için zorunlu e‑posta doğrulaması (ve ilerleyen aşamada telefon doğrulaması) kullanılmalı. Doğrulanmamış kullanıcı giriş yaptığında, işlem yapmaya çalıştığında “Hesabınızı doğrulamadan ilan ekleyemez veya mesajlaşamazsınız” şeklinde bir uyarı gösterilmeli. Sunucunun hata vermesi yerine kullanıcı dostu bir uyarı tercih edilmeli.
	•	SMS yerine doğrulama alternatifi – SMS doğrulaması maliyetli olduğundan, e‑posta doğrulama kodu veya TOTP tabanlı (Google Authenticator) ikili doğrulama uygulanabilir. Ayrıca Estonya’da ID‑kaart veya Smart‑ID gibi dijital kimlik çözümleri yaygın olarak kullanıldığından, uzun vadede Smart‑ID entegrasyonu planlanabilir.

Blog ve Çok Dilli İçerik
	•	Üç dilli blog – Siteye tam özellikli bir blog bölümü eklenmeli. WordPress benzeri bir CMS yapısı kurularak her yazı için Estonca, İngilizce ve Rusça içerikler girilebilmeli; SEO için başlık, meta açıklama ve slug her dilde ayrı yapılandırılmalı.
	•	SEO yönetimi – Blog yazıları için meta başlık, açıklama ve etiket alanları yönetim paneline eklenmeli. Yapılandırılmış veri (schema.org/Article) kullanarak arama motorları tarafından daha iyi okunması sağlanmalı.

Düzenleme ve Geliştirme İçin Öneriler

Aşağıdaki maddeler, yapay zekâya yöneltilecek komutlarda net bir şekilde belirtilmeli:
	1.	Tasarım uyumu
	•	Hero bölümünü xpatly.eu’daki gibi tam genişlikte görsel arka plan ve yuvarlatılmış köşeler ile tasarla. Yazı tipi olarak Lexend (başlıklar) ve Inter (gövde metni) kullan ￼.
	•	Arka plan görselleri optimize edilerek performans kaybı önlenmeli (lazy loading ve modern görüntü formatı – WebP kullanımı).
	2.	Navigasyon ve footer
	•	Navigasyon menüsünü üç kolonlu yapıya sadık kalarak orta kolonun tam ortada görünmesi için sağ taraftaki “Sign In” ve “Register” seçeneklerini tek bir kullanıcı menüsü altında birleştir.
	•	Footer kolonlarını eşit genişlikte olacak şekilde düzenle; her kolonda benzer sayıda bağlantı ve başlık kullan.
	3.	Arama ve filtreleme
	•	Listings sayfasındaki “Deal Type” filtresini ilk sıraya taşı.
	•	Fiyat ve oda sayısı alanlarına çift yönlü range slider ekleyerek min/maks girişlerini kaldır.
	•	“Expat‑Friendly Only” onay kutusunu kaldır.
	•	Arama çubuğunun altında yer alan gereksiz butonları kaldır ve CTA’ları (Call‑to‑Action) hero alanının içinde konumlandır.
	•	Gelişmiş filtre olarak alan (m²), kat, yapım yılı, enerji sınıfı, durum vb. ek seçenekler ekle; kv.ee sitesindeki gibi kademeli açılan filtre paneli modelini kullan.
	•	City24.ee’deki ekstra özellikler listesini örnek alarak “Elevator, Balcony, Sauna, Garage, Furnished…” gibi opsiyonlar ekle.
	4.	Giriş, kayıt ve doğrulama
	•	Giriş ve kayıt sayfalarında formun üzerinde görünecek toast/modal mesaj alanı tasarla; böylece hata ve başarı mesajları butonlarla karışmasın.
	•	E‑posta doğrulaması zorunlu hale getir; doğrulanmamış kullanıcılar ilan ekleyemez veya mesaj atamaz. İşlem denendiğinde kullanıcıyı bilgilendiren bir uyarı penceresi göster.
	•	SMS doğrulaması yerine e‑posta doğrulama kodu veya Authenticator tabanlı iki aşamalı doğrulama (TOTP) desteği ekle. Uzun vadede Estonya’nın Smart‑ID sistemine entegrasyon düşünülebilir.
	5.	Blog modülü
	•	Yönetim panelinde blog bölümü oluştur; yazılar için Estonca, İngilizce ve Rusça içerik girilebilecek alanlar oluştur.
	•	Her dil için slug, meta başlık ve meta açıklama tanımlanabilsin. SEO için schema.org/Article işaretlemesi ekle.
	6.	Performans ve erişilebilirlik
	•	TailwindCSS kullanımı sırasında gereksiz CSS kodları “purge” edilerek boyut küçültülmeli.
	•	Görseller için alt etiketleri eklenmeli; buton ve form kontrollerinin erişilebilirlik etiketleri (aria-label) tamamlanmalı.
	7.	Veritabanı geçişi
	•	Şu an SQLite veritabanı kullanılıyor; gelecekte MySQL’e geçiş planlanıyor. Laravel Migration kullanılarak şema tanımları oluşturulmalı; veri taşınırken php artisan migrate ve php artisan db:seed komutları ile sorunsuz geçiş sağlanmalı.

Sonuç

Yukarıdaki analiz, mevcut xpatly.eu sitesindeki güçlü tasarım öğelerini koruyarak makler.digitaltamam.com sitesini modern, erişilebilir ve kullanıcı dostu hale getirmek için yapılması gereken revizyonları özetlemektedir. Hero bölümünden navigasyon yapısına, arama filtrelerinden kullanıcı doğrulamasına kadar her bir detay için net gereksinimler listelenmiştir. Yapay zekâ destekli geliştirmede bu maddeler komut olarak verilerek proje yönlendirilmelidir.

---

# ✅ FAZ 2 UYGULAMA RAPORU

**Tarih:** 29 Aralık 2024  
**Durum:** TAMAMLANDI (TOTP 2FA ve AWS SDK hariç - beklemede)

---

## 1. Tasarım ve UI/UX İyileştirmeleri ✅

### TailwindCSS CDN Entegrasyonu
- **Dosya:** `views/layouts/header.php`
- npm yerine CDN kullanımına geçildi
- Özel renk paleti tanımlandı:
  - Primary: `#f9a825` (Amber)
  - Secondary: `#1976d2` (Blue)
  - White: `#FFFFFF`

### Google Fonts Eklendi
- **Lexend** → Başlıklar için (`font-heading` class)
- **Inter** → Gövde metinleri için (`font-body` class)

### Hero Bölümü Güncellendi
- **Dosya:** `views/home/index.php`
- Amber-to-blue gradient arka plan
- Tek CTA butonu ("View All" - gereksiz "How It Works" kaldırıldı)
- Emoji yerine SVG star ikonu

### Emoji Temizliği
- Tüm emojiler SVG ikonlarla değiştirildi
- **Dosya:** `public/assets/css/custom.css` - SVG bayrak ikonları eklendi
- Dil seçici: 🇬🇧🇪🇪🇷🇺 → CSS-based SVG flags

---

## 2. Navigasyon ve Footer ✅

### Sign In/Register Birleştirildi
- **Dosya:** `views/layouts/header.php`
- İki ayrı buton yerine tek dropdown menü
- Menü artık tam ortada hizalanıyor

### Footer Düzenlendi
- **Dosya:** `views/layouts/footer.php`
- 4 eşit genişlikte kolon (`sm:grid-cols-2 lg:grid-cols-4`)
- Her kolonda eşit sayıda link (4'er adet)
- Çeviri fonksiyonları eklendi (`__('footer.description')` vb.)

### SVG Bayrak İkonları
- Emoji bayraklar kaldırıldı
- CSS class ile SVG bayraklar: `.flag-icon-gb`, `.flag-icon-ee`, `.flag-icon-ru`

---

## 3. Arama ve Filtreleme ✅

### Dosya: `views/search/index.php`

### Filtre Sıralaması
- "Deal Type" filtresi en üst sıraya taşındı

### Range Slider'lar
- Fiyat aralığı için çift yönlü slider (Alpine.js)
- Oda sayısı için çift yönlü slider
- Alan (m²) için çift yönlü slider
- Min/max input alanları kaldırıldı

### Kaldırılan Öğeler
- "Expat-Friendly Only" onay kutusu kaldırıldı

### Gelişmiş Filtreler (Collapsible)
- Condition (Durum) dropdown
- Floor (Kat) min/max
- Year Built (Yapım Yılı) min/max
- Energy Class (Enerji Sınıfı) dropdown

### Extras Bölümü
Checkbox'lar eklendi:
- ✅ Elevator (Asansör)
- ✅ Balcony (Balkon)
- ✅ Sauna
- ✅ Garage (Garaj)
- ✅ Furnished (Mobilyalı)
- ✅ Storage (Depo)
- ✅ Bathtub (Küvet)
- ✅ Pets Allowed (Evcil Hayvan)

---

## 4. Giriş/Kayıt ve Doğrulama ✅

### Toast Bildirim Sistemi
- **Dosya:** `views/layouts/header.php`
- Köşe bildirimi yerine centered modal style
- Her mesaj tipi için SVG ikon (success/error/warning/info)
- Animasyonlu giriş/çıkış (Alpine.js transition)
- Kapatma (X) butonu eklendi

### Email Doğrulama Uyarı Bandı
- **Dosya:** `views/layouts/header.php`
- Doğrulanmamış kullanıcılara amber uyarı bandı
- "Resend verification email" linki

### User Model Güncellemesi
- **Dosya:** `models/User.php`
- `isEmailVerified()` metodu eklendi
- `isFullyVerified()` metodu güncellendi

### Doğrulama Kontrolü (Mevcut)
- `ListingController::create()` - Doğrulanmamış kullanıcılar ilan ekleyemez

### ⏸️ TOTP 2FA - BEKLEMEDE
Kullanıcı kararı bekleniyor

---

## 5. Blog Modülü ✅

### Veritabanı
- **Migration:** `migrations/012_create_blog_posts.php`
- Tablo: `blog_posts`
- Çok dilli alanlar: title_en/et/ru, content_en/et/ru, meta_title_en/et/ru, meta_description_en/et/ru

### Model
- **Dosya:** `models/BlogPost.php`
- `getPublished()`, `findBySlug()`, `create()`, `update()`, `delete()`
- `applyLocale()` - Aktif dile göre içerik seçimi
- `generateSlug()` - Otomatik URL slug üretimi

### Controller
- **Dosya:** `controllers/BlogController.php`
- Public: `index()`, `show()`
- Admin: `adminIndex()`, `create()`, `store()`, `edit()`, `update()`, `delete()`

### Public Views
- **`views/blog/index.php`** - Blog listesi, grid layout, pagination
- **`views/blog/show.php`** - Tekil yazı, Schema.org/Article markup, social sharing

### Admin Views
- **`views/admin/blog/index.php`** - Blog yönetim tablosu
- **`views/admin/blog/edit.php`** - Çok dilli düzenleyici (tabbed interface: EN/ET/RU)

### SEO
- Schema.org/Article JSON-LD markup
- Meta title ve description alanları
- Her dil için ayrı slug desteği

### Routes
- **Dosya:** `public/index.php`
- `/blog` → Blog listesi
- `/blog/{slug}` → Tekil yazı
- `/admin/blog` → Admin listesi
- `/admin/blog/create`, `/admin/blog/{id}/edit`, vb.

---

## 6. Cloudflare R2 / Local Storage Hibrit Sistem ✅

### StorageManager Sınıfı
- **Dosya:** `core/StorageManager.php`
- `getDriver()` - R2 veya local belirleme
- `isR2Available()` - R2 yapılandırılmış mı?
- `upload()` - Hibrit yükleme (R2 → local fallback)
- `delete()` - Hibrit silme
- `getUrl()` - Dosya URL'i alma

### R2 Ayarları
- **Migration:** `migrations/011_add_r2_settings.php`
- Site ayarlarına eklenen alanlar:
  - `r2_access_key_id`
  - `r2_secret_access_key`
  - `r2_bucket_name`
  - `r2_endpoint`
  - `r2_public_url`

### Admin Panel
- **Dosya:** `views/admin/settings.php`
- Super-admin için Cloudflare R2 Storage bölümü
- Aktif storage göstergesi (R2 veya Local)

### Uploader Güncellemesi
- **Dosya:** `core/Uploader.php`
- `uploadWithStorage()` - Tek dosya hibrit yükleme
- `uploadMultipleWithStorage()` - Çoklu dosya hibrit yükleme

### ListingController Güncellemesi
- **Dosya:** `controllers/ListingController.php`
- `uploadMultipleWithStorage()` kullanımına geçildi

### ⏸️ AWS SDK - BEKLEMEDE
Kullanıcı kararı bekleniyor. SDK olmadan local storage kullanılır.

---

## 7. Performans ve Erişilebilirlik ✅

### Lazy Loading
- `loading="lazy"` eklendi:
  - `views/home/index.php` - Featured listings
  - `views/search/index.php` - Listing images
  - `views/blog/index.php` - Blog images
  - `views/blog/show.php` - Featured image

### .htaccess Optimizasyonları
- **Dosya:** `public/.htaccess`
- **Gzip sıkıştırma:** HTML, CSS, JS, JSON, XML, fonts, SVG
- **Browser caching:** Images (1 yıl), CSS/JS (1 ay)
- **WebP desteği:** Otomatik WebP sunumu
- **Güvenlik header'ları:** X-Content-Type-Options, X-XSS-Protection, X-Frame-Options, Referrer-Policy
- **Hassas dosya koruması:** .env, .sql, .sqlite, .log, .md

### Aria-label'lar
- Toast kapatma butonları için aria-label eklendi
- Email verification banner dismiss butonu için aria-label eklendi

---

## 8. Veritabanı MySQL Geçişi ❌

**DURUM:** ERTELENDİ  
Müşteri onayı beklendiğinden bu fazda yapılmayacak. Şu an SQLite kullanılmaya devam ediyor.

---

## Migration Durumu

| Migration | Durum |
|-----------|-------|
| `011_add_r2_settings.php` | ✅ Çalıştırıldı |
| `012_create_blog_posts.php` | ✅ Çalıştırıldı |

---

## Bekleyen Kararlar

| Öğe | Durum | Açıklama |
|-----|-------|----------|
| TOTP 2FA | ⏸️ BEKLEMEDE | Detaylı açıklama sonrası kullanıcı kararı |
| AWS SDK | ⏸️ BEKLEMEDE | R2 kullanım kararına bağlı |
| MySQL Geçişi | ❌ ERTELENDİ | Müşteri onayı bekliyor |

---

## 🚨 Test Bulguları (29 Aralık 2025)

- Mesaj gönderme kırık – `/listings/{id}/message` POST çağrısı 500 veriyor: `table messages has no column named updated_at`. `Model::create()` otomatik `updated_at` yazdığı için tablo şemasıyla uyuşmuyor; ilan sahipleri gelen mesaj alamıyor.
- İlan raporla kırık – `/listings/{id}/report` POST çağrısı aynı nedenle 500 veriyor (`reports` tablosunda `updated_at` yok). Rapor formu hiç çalışmıyor.
- Login yanıtında PHP 8.4 uyarısı – `Core\Validator::firstError()` için “Implicitly marking parameter $field as nullable is deprecated” uyarısı HTML çıktısına ekleniyor. Başlık/redirect öncesi çıktı oluştuğu için üretimde hata sayfası riski var.
- Seed görselleri eksik – `listing_images` tablosu boş (0 kayıt), kartlar ve detay sayfalarında galeri “No images available” görünüyor; README’deki “15 sample listings” beklentisiyle uyumsuz.
- Çeviri eksiği – `/en/messages` sayfasında `messages.inbox_description` anahtarı ham halde gösteriliyor; ilgili locale girdisi yok.

---

## Kapsamlı Analiz Çerçevesi (Uygulama)

### İş Hedefleri
- Dönüşüm: ziyaretçiden kayıtlı kullanıcıya, kayıtlıdan mesaj/favori/ilan eylemine geçiş oranı.
- İçerik hacmi: yayımlı ilan + blog yazısı sayısı (EN/ET/RU) ve görsel tamlığı.
- Destek yükü: kırık form/hata sayfası → destek talebi; uyarıların düzgün iletilmesi.

### Rol Bazlı Akış Durumu
- Ziyaretçi: `/en`, `/en/listings`, `/en/blog` açılıyor; blog boş; ilan kartlarında görsel yok.
- Üye: login/Çıkış çalışıyor (uyarı sızıyor); favori ekleme çalışıyor; mesaj ve rapor gönderme 500 ile kırık.
- İlan sahibi: ilan oluşturma formu açılıyor; yayınlama akışı ve medya yükleme henüz test edilmedi (dosya upload + storage sürücüsü senaryosu çalışılmalı).
- Admin: blog CRUD, ayarlar, R2/local seçimleri henüz çalıştırılmadı (eylem planına alınmalı).

### Veri ve Şema Tutarlılığı
- `messages` ve `reports` tablolarında `updated_at` kolonu yok, `Model::create()` ekliyor → 500 hataları.
- Seed medya yok (`listing_images` 0 kayıt); demo deneyimi eksik.
- Migration/seed senaryosu: R2 ayarları ve blog tabloları hazır; ilan ve medya seed’i eksik.

### Çok Dil ve İçerik
- Eksik anahtar: `messages.inbox_description` (EN gösterimde ham).
- Blog içerikleri ve meta/slug alanları doldurulmamış; SEO testi yapılmadı.
- Dil switcher çalışıyor; sayfa bazlı slug/meta doğrulaması yapılmalı (özellikle blog, about/contact).

### Güvenlik ve Doğrulama
- PHP 8.4 deprecation uyarısı login yanıtına sızıyor (Validator nullability). Prod’da header kırılım riski.
- E-posta doğrulama zorunluluğu davranışı test edilmedi; doğrulanmamış kullanıcı için blok ve mesaj akışı doğrulanmalı.
- CSRF token’ları formlarda mevcut; rol bazlı erişim (admin/panel) henüz denenmedi.

### Performans ve Erişilebilirlik
- Lazy-load ve cache header’ları mevcut; Tailwind purge çıktısı doğrulanmadı (CDN kullanımı). Build boyutu ve kritik CSS incelenmeli.
- Aria-label ve kontrast kontrolleri kısmi; klavye navigasyonu/form hata mesajları UX’i gözden geçirilmeli.
- WebP dönüşüm/servis varsayılanları gözden geçirilmeli (gerçek görsellerle).

### İçerik ve Medya
- İlan görselleri yok; kart ve galeri deneyimi zayıf. Demo için örnek görseller eklenmeli ve alt etiketler doğrulanmalı.
- YouTube video, amenity rozetleri, harita çalışıyor; ancak medya olmadığından değer düşük.

### Analitik ve Geribildirim
- Arama, favori, mesaj, ilan yayınlama gibi temel olaylar için izleme planı yok; ölçüm noktaları tanımlanmalı.
- Toast/uyarı sistemi var; hata ve başarı mesajlarının tutarlılığı (özellikle 500’lerde) gözden geçirilmeli.

### Hataya Dayanıklılık
- R2 → local storage fallback kodu mevcut, fakat hata senaryosu test edilmedi.
- Harici kütüphane/CDN (Tailwind, Alpine, noUiSlider, Leaflet) düşerse yedek plan yok; kritik CSS/JS için self-host seçeneği değerlendirilmelidir.

### Raporlama Formatı (kullan)
- Başlık • Etki (rol + iş etkisi) • Tekrar üretim • Beklenen/Mevcut • Önerilen çözüm (dosya/komponent) • Öncelik.

### Regresyon Checklist (kritikler)
- Login/Çıkış (warning sızmaması), Kayıt, Favori ekle/çıkar, Mesaj gönder, İlan raporla, İlan oluştur (upload + kaydet), Blog CRUD (admin/public), Ayarlar (R2/local), Dil switcher, Harita ve arama/filtre, Toast/uyarı gösterimi.

### Test Verisi ve Ortam
- Hesaplar: README’deki admin/owner/user hesapları + CSRF token’lı formlar.
- Seed: ilan + görsel + blog için tutarlı seed komutu hazırlanmalı; medya örnekleri (WebP/JPG) eklenmeli.
- Ortam: Local SQLite + opsiyonel R2; staging için MySQL ve gerçek SMTP/Twilio/Smart-ID entegrasyonu planlanmalı.
