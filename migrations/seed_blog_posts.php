<?php
/**
 * Seed: Add blog posts in 3 languages
 */

require_once __DIR__ . '/../core/Database.php';

use Core\Database;

$db = new Database();
$conn = $db->getPDO();

// Get admin user ID
$stmt = $conn->query("SELECT id FROM users WHERE role = 'super_admin' LIMIT 1");
$admin = $stmt->fetch();
$authorId = $admin ? $admin['id'] : 1;

$posts = [
    [
        'slug' => 'expat-guide-tallinn-2025',
        'status' => 'published',
        'featured_image' => null,
        'author_id' => $authorId,

        // English
        'title_en' => 'Complete Guide to Living in Tallinn as an Expat in 2025',
        'content_en' => '<p>Tallinn, Estonia\'s charming capital, has become one of the most attractive destinations for expats in Northern Europe. With its blend of medieval architecture and cutting-edge technology, this Baltic gem offers a unique lifestyle.</p>

<h2>Why Choose Tallinn?</h2>
<p>Tallinn consistently ranks high in quality of life indexes. The city offers excellent digital infrastructure, a thriving startup scene, and a surprisingly affordable cost of living compared to other European capitals.</p>

<h2>Housing Market Overview</h2>
<p>The rental market in Tallinn is diverse, ranging from modern apartments in the city center to peaceful residential areas like Pirita and Nõmme. Average rent for a 2-bedroom apartment in the center is around €800-1200 per month.</p>

<h2>Best Neighborhoods for Expats</h2>
<ul>
<li><strong>Old Town (Vanalinn):</strong> Historic charm, tourist-friendly, but pricier</li>
<li><strong>Kalamaja:</strong> Trendy, artistic, with cafes and restaurants</li>
<li><strong>Kristiine:</strong> Family-friendly with good schools and parks</li>
<li><strong>Kadriorg:</strong> Elegant, peaceful, close to the park and sea</li>
</ul>

<h2>Practical Tips</h2>
<p>Learn basic Estonian phrases, get an e-Residency card for digital services, and join expat communities on Facebook. The city is very English-friendly, especially in business areas.</p>

<p>Overall, Tallinn offers an excellent balance of modern convenience, natural beauty, and cultural richness - perfect for expats seeking a high quality of life.</p>',
        'meta_title_en' => 'Living in Tallinn: Complete Expat Guide 2025',
        'meta_description_en' => 'Everything you need to know about living in Tallinn as an expat: neighborhoods, housing, cost of living, and practical tips for 2025.',

        // Estonian
        'title_et' => 'Täielik juhend Tallinnas elamiseks ekspaadina 2025. aastal',
        'content_et' => '<p>Tallinn, Eesti võluv pealinn, on muutunud üheks atraktiivsemaks sihtkohaks ekspaatidele Põhja-Euroopas. Keskaegse arhitektuuri ja tipptasemel tehnoloogia segunemisega pakub see Balti pärl ainulaadset eluviisi.</p>

<h2>Miks valida Tallinn?</h2>
<p>Tallinn on pidevalt kõrgel kohal elukvaliteedi edetabelites. Linn pakub suurepärast digitaalset infrastruktuuri, õitsevat idufirmade skeenet ja üllatavalt taskukohast elukallidust võrreldes teiste Euroopa pealinnadega.</p>

<h2>Kinnisvaraturu ülevaade</h2>
<p>Tallinna üüriturg on mitmekesine, ulatudes kaasaegsetest korterites kesklinna rahulikest elamupiirkondades nagu Pirita ja Nõmme. Keskmine üür 2-toalise korteri eest kesklinnas on umbes €800-1200 kuus.</p>

<h2>Parimad piirkonnad ekspaatidele</h2>
<ul>
<li><strong>Vanalinn:</strong> Ajalooline võlu, turismisõbralik, kuid kallim</li>
<li><strong>Kalamaja:</strong> Trendikas, kunstiline, kohvikute ja restoranidega</li>
<li><strong>Kristiine:</strong> Peresõbralik heade koolide ja parkidega</li>
<li><strong>Kadriorg:</strong> Elegantne, rahulik, pargi ja mere lähedal</li>
</ul>

<h2>Praktilised nõuanded</h2>
<p>Õpi põhilisi eesti keele fraase, hangi e-residentsuse kaart digitaalsete teenuste jaoks ja liitu ekspaatide kogukondadega Facebookis. Linn on väga inglise keele sõbralik, eriti äripiirkondades.</p>

<p>Üldiselt pakub Tallinn suurepärast tasakaalu kaasaegse mugavuse, looduskauniduse ja kultuurilise rikkuse vahel - ideaalne ekspaatidele, kes otsivad kõrget elukvaliteeti.</p>',
        'meta_title_et' => 'Elamine Tallinnas: Täielik ekspaadi juhend 2025',
        'meta_description_et' => 'Kõik, mida peate teadma Tallinnas ekspaadina elamise kohta: piirkonnad, eluasemed, elukallimus ja praktilised nõuanded 2025. aastaks.',

        // Russian
        'title_ru' => 'Полный гид по жизни в Таллине для экспатов в 2025 году',
        'content_ru' => '<p>Таллин, очаровательная столица Эстонии, стал одним из самых привлекательных направлений для экспатов в Северной Европе. Сочетая средневековую архитектуру и передовые технологии, эта балтийская жемчужина предлагает уникальный образ жизни.</p>

<h2>Почему выбрать Таллин?</h2>
<p>Таллин постоянно занимает высокие позиции в индексах качества жизни. Город предлагает отличную цифровую инфраструктуру, процветающую стартап-сцену и удивительно доступную стоимость жизни по сравнению с другими европейскими столицами.</p>

<h2>Обзор рынка жилья</h2>
<p>Рынок аренды в Таллине разнообразен, от современных квартир в центре города до тихих жилых районов, таких как Пирита и Нымме. Средняя арендная плата за 2-комнатную квартиру в центре составляет около €800-1200 в месяц.</p>

<h2>Лучшие районы для экспатов</h2>
<ul>
<li><strong>Старый город (Ваналинн):</strong> Исторический шарм, туристический, но дороже</li>
<li><strong>Каламая:</strong> Модный, художественный, с кафе и ресторанами</li>
<li><strong>Кристийне:</strong> Семейный район с хорошими школами и парками</li>
<li><strong>Кадриорг:</strong> Элегантный, спокойный, близко к парку и морю</li>
</ul>

<h2>Практические советы</h2>
<p>Выучите базовые эстонские фразы, получите карту электронного резидентства для цифровых услуг и присоединитесь к сообществам экспатов в Facebook. Город очень дружелюбен к английскому языку, особенно в деловых районах.</p>

<p>В целом, Таллин предлагает отличный баланс современного удобства, природной красоты и культурного богатства - идеально для экспатов, ищущих высокое качество жизни.</p>',
        'meta_title_ru' => 'Жизнь в Таллине: Полный гид для экспатов 2025',
        'meta_description_ru' => 'Все, что нужно знать о жизни в Таллине как экспат: районы, жилье, стоимость жизни и практические советы на 2025 год.',

        'published_at' => date('Y-m-d H:i:s', strtotime('-5 days')),
    ],

    [
        'slug' => 'estonian-rental-contracts-guide',
        'status' => 'published',
        'featured_image' => null,
        'author_id' => $authorId,

        // English
        'title_en' => 'Understanding Estonian Rental Contracts: What Expats Need to Know',
        'content_en' => '<p>Renting an apartment in Estonia can be straightforward once you understand the local rental laws and contract basics. This guide will help you navigate the rental process with confidence.</p>

<h2>Types of Rental Agreements</h2>
<p>Estonia recognizes two main types of rental agreements:</p>
<ul>
<li><strong>Fixed-term lease:</strong> Specified duration (e.g., 1 year), cannot be terminated early without agreement</li>
<li><strong>Open-ended lease:</strong> No fixed end date, either party can terminate with 3 months\' notice</li>
</ul>

<h2>Key Contract Elements</h2>
<p>A proper Estonian rental contract should include:</p>
<ul>
<li>Names and ID codes of landlord and tenant</li>
<li>Exact address and description of the property</li>
<li>Monthly rent amount and payment due date</li>
<li>Utility payment arrangements</li>
<li>Security deposit amount (typically 1-3 months\' rent)</li>
<li>Start date and lease duration</li>
<li>Termination conditions</li>
</ul>

<h2>Tenant Rights and Obligations</h2>
<p><strong>Your Rights:</strong></p>
<ul>
<li>Peaceful enjoyment of the property</li>
<li>Timely repairs and maintenance</li>
<li>Privacy (landlord must give notice before visits)</li>
<li>Return of deposit after lease ends</li>
</ul>

<p><strong>Your Obligations:</strong></p>
<ul>
<li>Pay rent on time</li>
<li>Maintain the property in good condition</li>
<li>Report major damages immediately</li>
<li>Follow house rules and regulations</li>
</ul>

<h2>Security Deposits</h2>
<p>Security deposits in Estonia are typically 1-3 months\' rent. The landlord must return it within 15 days after the lease ends, minus any deductions for damages. Always document the property\'s condition when moving in and out.</p>

<h2>Red Flags to Watch Out For</h2>
<ul>
<li>Landlord refuses to provide a written contract</li>
<li>Excessive security deposit demands (over 3 months)</li>
<li>Contract only in Estonian without translation</li>
<li>Unclear utility payment arrangements</li>
<li>No property inspection before signing</li>
</ul>

<p>When in doubt, consult with a local real estate agency or lawyer. Having a clear, fair contract protects both you and your landlord.</p>',
        'meta_title_en' => 'Estonian Rental Contracts Guide for Expats',
        'meta_description_en' => 'Complete guide to understanding rental contracts in Estonia: types of leases, tenant rights, security deposits, and red flags to avoid.',

        // Estonian
        'title_et' => 'Eesti üürilepingute mõistmine: mida ekspaadid peavad teadma',
        'content_et' => '<p>Korteri üürimine Eestis võib olla lihtne, kui mõistate kohalikke üüriseadusi ja lepingu aluseid. See juhend aitab teil üüriprotsessis enesekindlalt orienteeruda.</p>

<h2>Üürilepingute tüübid</h2>
<p>Eestis tunnustatakse kahte peamist üürilepingu tüüpi:</p>
<ul>
<li><strong>Tähtajaline üür:</strong> Määratud kestus (nt 1 aasta), ei saa ennetähtaegselt lõpetada ilma kokkuleppeta</li>
<li><strong>Tähtajatu üür:</strong> Fikseeritud lõppkuupäev puudub, kumbki pool võib lõpetada 3-kuulise etteteatamisega</li>
</ul>

<h2>Peamised lepinguelemendid</h2>
<p>Korralik Eesti üürileping peaks sisaldama:</p>
<ul>
<li>Üürileandja ja üürniku nimed ja isikukoodid</li>
<li>Kinnistu täpne aadress ja kirjeldus</li>
<li>Kuine üüri summa ja maksekuupäev</li>
<li>Kommunaalkulude maksmise korraldus</li>
<li>Tagatisraha summa (tavaliselt 1-3 kuu üür)</li>
<li>Alguskuupäev ja üüriperiood</li>
<li>Lõpetamise tingimused</li>
</ul>

<h2>Üürniku õigused ja kohustused</h2>
<p><strong>Teie õigused:</strong></p>
<ul>
<li>Kinnistu rahulik kasutamine</li>
<li>Õigeaegne remont ja hooldus</li>
<li>Privaatsus (üürileandja peab külastustest ette teatama)</li>
<li>Tagatisraha tagastamine üüri lõppedes</li>
</ul>

<p><strong>Teie kohustused:</strong></p>
<ul>
<li>Üüri õigeaegne maksmine</li>
<li>Kinnistu heas seisukorras hoidmine</li>
<li>Suurematest kahjustustest kohene teavitamine</li>
<li>Kodukorra ja eeskirjade järgimine</li>
</ul>

<h2>Tagatisrahad</h2>
<p>Tagatisrahad Eestis on tavaliselt 1-3 kuu üür. Üürileandja peab selle tagastama 15 päeva jooksul pärast üüri lõppu, miinus kahjustuste eest mahaarvamised. Dokumenteerige kinnistu seisukord alati sisse- ja väljakolimise ajal.</p>

<h2>Hoiatavad märgid, mida jälgida</h2>
<ul>
<li>Üürileandja keeldub kirjaliku lepingu andmisest</li>
<li>Liiga suur tagatisraha nõue (üle 3 kuu)</li>
<li>Leping ainult eesti keeles ilma tõlketa</li>
<li>Ebaselged kommunaalkulude maksmise kokkulepped</li>
<li>Kinnistu ülevaatus puudub enne allkirjastamist</li>
</ul>

<p>Kahtluse korral konsulteerige kohaliku kinnisvarabürooga või juristiga. Selge ja õiglane leping kaitseb nii teid kui ka teie üürileandja.</p>',
        'meta_title_et' => 'Eesti üürilepingute juhend ekspaatidele',
        'meta_description_et' => 'Täielik juhend Eesti üürilepingute mõistmiseks: lepingutüübid, üürniku õigused, tagatisrahad ja vältimiseks hoiatavad märgid.',

        // Russian
        'title_ru' => 'Понимание эстонских договоров аренды: что нужно знать экспатам',
        'content_ru' => '<p>Аренда квартиры в Эстонии может быть простой, если вы понимаете местные законы об аренде и основы договоров. Это руководство поможет вам уверенно ориентироваться в процессе аренды.</p>

<h2>Типы договоров аренды</h2>
<p>В Эстонии признаются два основных типа договоров аренды:</p>
<ul>
<li><strong>Срочная аренда:</strong> Определенная продолжительность (например, 1 год), не может быть досрочно расторгнута без соглашения</li>
<li><strong>Бессрочная аренда:</strong> Без фиксированной даты окончания, любая сторона может расторгнуть с уведомлением за 3 месяца</li>
</ul>

<h2>Ключевые элементы договора</h2>
<p>Правильный эстонский договор аренды должен включать:</p>
<ul>
<li>Имена и личные коды арендодателя и арендатора</li>
<li>Точный адрес и описание недвижимости</li>
<li>Сумма ежемесячной аренды и срок оплаты</li>
<li>Порядок оплаты коммунальных услуг</li>
<li>Сумма залога (обычно 1-3 месячной аренды)</li>
<li>Дата начала и продолжительность аренды</li>
<li>Условия расторжения</li>
</ul>

<h2>Права и обязанности арендатора</h2>
<p><strong>Ваши права:</strong></p>
<ul>
<li>Спокойное пользование недвижимостью</li>
<li>Своевременный ремонт и обслуживание</li>
<li>Конфиденциальность (арендодатель должен уведомлять о визитах)</li>
<li>Возврат залога после окончания аренды</li>
</ul>

<p><strong>Ваши обязанности:</strong></p>
<ul>
<li>Своевременная оплата аренды</li>
<li>Поддержание недвижимости в хорошем состоянии</li>
<li>Немедленное сообщение о серьезных повреждениях</li>
<li>Соблюдение правил и регламентов</li>
</ul>

<h2>Залоговые депозиты</h2>
<p>Залоговые депозиты в Эстонии обычно составляют 1-3 месячной аренды. Арендодатель должен вернуть его в течение 15 дней после окончания аренды, за вычетом возможных вычетов за повреждения. Всегда документируйте состояние недвижимости при въезде и выезде.</p>

<h2>Тревожные сигналы</h2>
<ul>
<li>Арендодатель отказывается предоставить письменный договор</li>
<li>Чрезмерные требования залога (более 3 месяцев)</li>
<li>Договор только на эстонском языке без перевода</li>
<li>Неясные договоренности по оплате коммунальных услуг</li>
<li>Отсутствие осмотра недвижимости перед подписанием</li>
</ul>

<p>В случае сомнений проконсультируйтесь с местным агентством недвижимости или юристом. Четкий, справедливый договор защищает и вас, и вашего арендодателя.</p>',
        'meta_title_ru' => 'Гид по договорам аренды в Эстонии для экспатов',
        'meta_description_ru' => 'Полное руководство по пониманию договоров аренды в Эстонии: типы аренды, права арендатора, залоги и тревожные сигналы.',

        'published_at' => date('Y-m-d H:i:s', strtotime('-10 days')),
    ],

    [
        'slug' => 'best-free-activities-tallinn',
        'status' => 'published',
        'featured_image' => null,
        'author_id' => $authorId,

        // English
        'title_en' => '10 Amazing Free Activities in Tallinn for New Expats',
        'content_en' => '<p>Living in Tallinn doesn\'t have to be expensive. Here are 10 fantastic free activities to help you explore the city and meet new people without breaking the bank.</p>

<h2>1. Explore the Old Town</h2>
<p>Tallinn\'s medieval Old Town is a UNESCO World Heritage site and completely free to explore. Wander through cobblestone streets, admire Gothic architecture, and discover hidden courtyards.</p>

<h2>2. Kadriorg Park</h2>
<p>This beautiful baroque park is perfect for jogging, picnicking, or simply relaxing. Don\'t miss the Japanese Garden and Swan Pond. Free entry all year round.</p>

<h2>3. Telliskivi Creative City</h2>
<p>Every Saturday, the Telliskivi Flea Market offers vintage finds, local crafts, and street food. Even if you don\'t buy anything, the atmosphere is worth experiencing.</p>

<h2>4. Seaside Promenade</h2>
<p>Walk or bike along the 3km coastal promenade from Pirita to Kadriorg. Stunning sea views, fresh air, and plenty of benches to rest.</p>

<h2>5. Free Museum Days</h2>
<p>Many museums offer free entry on specific days. Kumu Art Museum is free on the first Friday of each month, and the Estonian History Museum offers free entry on the last Friday.</p>

<h2>6. Tallinn Botanic Garden</h2>
<p>While the greenhouse costs €5, the outdoor gardens are free. Beautiful in every season, especially during spring bloom and autumn colors.</p>

<h2>7. Street Art Tours</h2>
<p>Kalamaja and Telliskivi are filled with amazing street art. Create your own self-guided tour and discover murals by local and international artists.</p>

<h2>8. Song Festival Grounds</h2>
<p>Visit this historic site where Estonia\'s Singing Revolution took place. The grounds are free to visit and offer great views of the city.</p>

<h2>9. Harjumägi Park</h2>
<p>This small hill in the city center offers panoramic views of Tallinn. In winter, it\'s a popular sledding spot. Free and accessible 24/7.</p>

<h2>10. Free Events and Festivals</h2>
<p>Check out Tallinn\'s event calendar for free concerts, outdoor cinema, and cultural festivals throughout the year. Summer is especially busy with free events.</p>

<p>Remember to join local Facebook groups like "Expats in Estonia" to discover more free activities and events happening in the city!</p>',
        'meta_title_en' => '10 Free Activities in Tallinn for Expats',
        'meta_description_en' => 'Discover the best free things to do in Tallinn: parks, museums, street art, and events that won\'t cost you a cent.',

        // Estonian
        'title_et' => '10 hämmastavat tasuta tegevust Tallinnas uutele ekspaatidele',
        'content_et' => '<p>Tallinnas elamine ei pea olema kallis. Siin on 10 fantastilist tasuta tegevust, mis aitavad teil linna avastada ja uusi inimesi kohata ilma rahakotti tühjendamata.</p>

<h2>1. Avasta Vanalinna</h2>
<p>Tallinna keskaegne Vanalinn on UNESCO maailmapärandi objekt ja täiesti tasuta avastamiseks. Jaluta munakivitänavatel, imetle gooti arhitektuuri ja ava peidetud siseõuesid.</p>

<h2>2. Kadrioru park</h2>
<p>See kaunis barokkpark on ideaalne jooksmiseks, pikniku pidamiseks või lihtsalt puhkamiseks. Ära jäta vahele Jaapani aeda ja Luigetiiki. Tasuta sissepääs aastaringselt.</p>

<h2>3. Telliskivi loomelinnak</h2>
<p>Igal laupäeval pakub Telliskivi kirbut vintage-leide, kohalikku käsitööd ja tänavatoidud. Isegi kui sa midagi ei osta, on atmosfäär kogemist väärt.</p>

<h2>4. Rannapromenaad</h2>
<p>Jaluta või sõida jalgrattaga mööda 3km pikkust rannapromenaadi Piritalt Kadriorgu. Vapustavad merevaated, värske õhk ja palju pinke puhkamiseks.</p>

<h2>5. Muuseumide tasuta päevad</h2>
<p>Paljud muuseumid pakuvad tasuta sissepääsu kindlatel päevadel. Kumu kunstimuuseum on tasuta iga kuu esimesel reedel ja Eesti Ajaloomuuseum pakub tasuta sissepääsu viimasel reedel.</p>

<h2>6. Tallinna botaanikaaed</h2>
<p>Kuigi kasvuhoone maksab €5, on väliaiad tasuta. Ilus igal aastaajal, eriti kevadise õitsemise ja sügiseste värvide ajal.</p>

<h2>7. Tänavataide tutvumisringkäigud</h2>
<p>Kalamaja ja Telliskivi on täis hämmastavaet tänavatöödekujundusi. Loo oma isejuhitud ekskursioon ja ava kohalike ja rahvusvaheliste kunstnike muraaleid.</p>

<h2>8. Lauluväljak</h2>
<p>Külasta seda ajalolist kohta, kus toimus Eesti laulev revolutsioon. Alale pääsemine on tasuta ja pakub suurepäraseid vaateid linnale.</p>

<h2>9. Harjumäe park</h2>
<p>See väike küngas kesklinna pakub panoraamvaateid Tallinnale. Talvel on see populaarne kelgumägi. Tasuta ja ligipääsetav 24/7.</p>

<h2>10. Tasuta üritused ja festivalid</h2>
<p>Vaata Tallinna ürituste kalendrit tasuta kontsertide, väliteatrite ja kultuurifestivalide kohta aastaringselt. Suvi on eriti tihedalt täis tasuta üritustega.</p>

<p>Ära unusta liituda kohalike Facebook-gruppidega nagu "Expats in Estonia", et avastada rohkem tasuta tegevusi ja üritusi, mis linnas toimuvad!</p>',
        'meta_title_et' => '10 tasuta tegevust Tallinnas ekspaatidele',
        'meta_description_et' => 'Avasta parimad tasuta asjad, mida Tallinnas teha: pargid, muuseumid, tänavataide ja üritused, mis sind senti ei maksa.',

        // Russian
        'title_ru' => '10 потрясающих бесплатных занятий в Таллине для новых экспатов',
        'content_ru' => '<p>Жизнь в Таллине не обязательно должна быть дорогой. Вот 10 фантастических бесплатных занятий, которые помогут вам исследовать город и познакомиться с новыми людьми, не опустошая кошелек.</p>

<h2>1. Исследуйте Старый город</h2>
<p>Средневековый Старый город Таллина - объект Всемирного наследия ЮНЕСКО, и его можно исследовать совершенно бесплатно. Прогуляйтесь по мощеным улицам, полюбуйтесь готической архитектурой и откройте скрытые дворики.</p>

<h2>2. Парк Кадриорг</h2>
<p>Этот красивый барочный парк идеален для пробежек, пикников или просто отдыха. Не пропустите Японский сад и Лебединый пруд. Бесплатный вход круглый год.</p>

<h2>3. Творческий город Теллискиви</h2>
<p>Каждую субботу блошиный рынок Теллискиви предлагает винтажные находки, местные ремесла и уличную еду. Даже если вы ничего не купите, атмосфера стоит того.</p>

<h2>4. Набережная</h2>
<p>Прогуляйтесь или покатайтесь на велосипеде вдоль 3-километровой прибрежной набережной от Пириты до Кадриорга. Потрясающие виды на море, свежий воздух и много скамеек для отдыха.</p>

<h2>5. Бесплатные дни в музеях</h2>
<p>Многие музеи предлагают бесплатный вход в определенные дни. Художественный музей Куму бесплатен в первую пятницу каждого месяца, а Эстонский исторический музей предлагает бесплатный вход в последнюю пятницу.</p>

<h2>6. Таллинский ботанический сад</h2>
<p>Хотя теплица стоит €5, открытые сады бесплатны. Красиво в любое время года, особенно во время весеннего цветения и осенних красок.</p>

<h2>7. Туры по стрит-арту</h2>
<p>Каламая и Теллискиви заполнены потрясающим стрит-артом. Создайте свой собственный самостоятельный тур и откройте для себя фрески местных и международных художников.</p>

<h2>8. Певческое поле</h2>
<p>Посетите это историческое место, где произошла Поющая революция Эстонии. Вход на территорию бесплатный, открываются прекрасные виды на город.</p>

<h2>9. Парк Харьюмяэ</h2>
<p>Этот небольшой холм в центре города предлагает панорамные виды на Таллин. Зимой это популярное место для катания на санках. Бесплатно и доступно 24/7.</p>

<h2>10. Бесплатные мероприятия и фестивали</h2>
<p>Проверьте календарь мероприятий Таллина для бесплатных концертов, кинопоказов под открытым небом и культурных фестивалей в течение года. Лето особенно насыщено бесплатными событиями.</p>

<p>Не забудьте присоединиться к местным группам в Facebook, таким как "Expats in Estonia", чтобы узнать больше о бесплатных мероприятиях в городе!</p>',
        'meta_title_ru' => '10 бесплатных занятий в Таллине для экспатов',
        'meta_description_ru' => 'Откройте лучшие бесплатные развлечения в Таллине: парки, музеи, стрит-арт и мероприятия, которые не стоят ни цента.',

        'published_at' => date('Y-m-d H:i:s', strtotime('-15 days')),
    ],

    [
        'slug' => 'winter-survival-guide-tallinn',
        'status' => 'published',
        'featured_image' => null,
        'author_id' => $authorId,

        // English
        'title_en' => 'Winter Survival Guide for Expats in Tallinn',
        'content_en' => '<p>Estonian winters can be challenging if you\'re not prepared. This comprehensive guide will help you survive and even enjoy the cold, dark months in Tallinn.</p>

<h2>What to Expect</h2>
<p>Tallinn winters typically last from November to March, with temperatures ranging from -5°C to -20°C. Expect short daylight hours (as little as 6 hours in December) and occasional snowstorms.</p>

<h2>Essential Winter Gear</h2>
<ul>
<li><strong>Proper winter coat:</strong> Invest in a good down jacket or parka. Estonians recommend Canada Goose or local brand Helly Hansen.</li>
<li><strong>Winter boots:</strong> Waterproof, insulated, with good grip. Ice cleats (liukutõkestid) are essential for icy days.</li>
<li><strong>Layering clothes:</strong> Thermal underwear, wool socks, scarves, gloves, and hats are non-negotiable.</li>
<li><strong>Face protection:</strong> On extremely cold days (-15°C or below), protect your face with a scarf or balaclava.</li>
</ul>

<h2>Staying Warm Indoors</h2>
<p>Most Estonian apartments have excellent central heating, but older buildings might need extra warmth:</p>
<ul>
<li>Use thick curtains to insulate windows</li>
<li>Invest in a good humidifier (heating dries the air)</li>
<li>Layer rugs over cold floors</li>
<li>Check window seals for drafts</li>
</ul>

<h2>Transportation in Winter</h2>
<p><strong>Public Transport:</strong> Very reliable even in heavy snow. Buses and trams run on schedule, streets are cleared quickly.</p>
<p><strong>Driving:</strong> Mandatory winter tires from December 1 to March 1. Consider getting studded tires for extra safety. Allow extra time for warming up your car and driving slowly.</p>
<p><strong>Walking:</strong> Main streets are salted and cleared, but sidewalks can be icy. Walk like a penguin (short steps, center of gravity forward) to avoid slipping!</p>

<h2>Beating the Winter Blues</h2>
<p>Long, dark winters can affect mood. Here\'s how to stay positive:</p>
<ul>
<li><strong>Light therapy:</strong> Use a SAD lamp for 20-30 minutes each morning</li>
<li><strong>Vitamin D supplements:</strong> Essential when sunlight is limited</li>
<li><strong>Stay active:</strong> Join a gym, try ice skating, or cross-country skiing</li>
<li><strong>Socialize:</strong> Don\'t hibernate! Join expat groups and winter activities</li>
<li><strong>Embrace hygge:</strong> Make your home cozy with candles, warm blankets, and hot drinks</li>
</ul>

<h2>Winter Activities to Enjoy</h2>
<ul>
<li>Ice skating at Tallinn Ice Hall or outdoor rinks</li>
<li>Cross-country skiing in Aegna or Pirita</li>
<li>Visit Christmas markets (November-January)</li>
<li>Sauna culture - a perfect winter tradition!</li>
<li>Winter swimming (if you\'re brave!)</li>
</ul>

<h2>Essential Winter Apps</h2>
<ul>
<li><strong>Ilmateenistus:</strong> Estonian weather service app</li>
<li><strong>Elron:</strong> Train schedules</li>
<li><strong>Bolt:</strong> Convenient for cold days when you don\'t want to walk</li>
</ul>

<p>Remember, Estonians have been dealing with harsh winters for centuries. Follow their lead, invest in proper gear, and you\'ll not just survive but thrive in Tallinn\'s winter wonderland!</p>',
        'meta_title_en' => 'Winter Survival Guide for Tallinn Expats',
        'meta_description_en' => 'Complete guide to surviving Estonian winters: essential gear, staying warm, transportation tips, and activities to enjoy the cold season.',

        // Estonian
        'title_et' => 'Talvine ellujäämisjuhend ekspaatidele Tallinnas',
        'content_et' => '<p>Eesti talved võivad olla keerulised, kui sa pole ette valmistatud. See põhjalik juhend aitab sul külmi, pimedaid kuid Tallinnas mitte ainult üle elada, vaid isegi nautida.</p>

<h2>Mida oodata</h2>
<p>Tallinna talved kestavad tavaliselt novembrist märtsini, temperatuuridega -5°C kuni -20°C vahel. Oota lühikesi päevavalgustunde (detsembris kõigest 6 tundi) ja aeg-ajalt lumemüristormi.</p>

<h2>Hädavajalik talvevarustus</h2>
<ul>
<li><strong>Korralik talvejope:</strong> Invecteeri hea sulgjope või parka. Eestlased soovitavad Canada Goose või kohalikku brändi Helly Hansen.</li>
<li><strong>Talvesaapad:</strong> Veekindlad, soojustatud, hea haardega. Jääkramplid (liukutõkestid) on hädavajalikud jäistel päevadel.</li>
<li><strong>Kihtriided:</strong> Termopesud, villased sokid, sallid, kindad ja mütsid pole läbirahaikmatud.</li>
<li><strong>Näokaitse:</strong> Eriti külmadel päevadel (-15°C või alla) kaitse oma nägu salli või balaklava abil.</li>
</ul>

<h2>Siseruumides soojana püsimine</h2>
<p>Enamikus Eesti korterides on suurepärane keskküte, kuid vanemad hooned võivad vajada lisasoojust:</p>
<ul>
<li>Kasuta paksuid kardinaid akende isoleerimiseks</li>
<li>Invecteeri hea õhuniisutaja (küte kuivatab õhku)</li>
<li>Aseta vaibad külmadele põrandale</li>
<li>Kontrolli akende tihendeid läbipuhumiste suhtes</li>
</ul>

<h2>Transport talvel</h2>
<p><strong>Ühistransport:</strong> Väga usaldusväärne isegi tugeva lume korral. Bussid ja trammid sõidavad ajakava järgi, tänavad puhahstatakse kiiresti.</p>
<p><strong>Sõitmine:</strong> Kohustuslikud talverehvid 1. detsembrist kuni 1. märtsini. Kaaluge naastrehvide hankimist täiendava ohutuse tagamiseks. Jätke lisaaega auto soojendamiseks ja aeglaseks sõitmiseks.</p>
<p><strong>Jalutamine:</strong> Peasunavad on soolatud ja puhastatud, kuid kõnniteed võivad olla libeda. Kõnni nagu pingviin (lühikesed sammud, raskuskese ees), et vältida libisemist!</p>

<h2>Talvedepresviooni võitmine</h2>
<p>Pikk, pime talv võib mõjutada meeleolu. Siin on, kuidas püsida positiivne:</p>
<ul>
<li><strong>Valgusravt:</strong> Kasuta SAD-lampi 20-30 minutit igal hommikul</li>
<li><strong>D-vitamiin lisandid:</strong> Hädavajalik, kui päikesevalgust on vähe</li>
<li><strong>Püsi aktiivne:</strong> Liitu spordisaaliga, proovi uisutamist või murdmaasuusatamist</li>
<li><strong>Sotesialiseeri:</strong> Ära talveune! Liitu ekspaatide gruppidega ja talveüritustega</li>
<li><strong>Omaksu hygget:</strong> Tee oma kodu hubauseks küünalde, soojade tekikide ja kuumade jookidega</li>
</ul>

<h2>Talveaktiviteedid, mida nautida</h2>
<ul>
<li>Uisutamine Tallinna jäähallis või välipääsul</li>
<li>Murdmaasuusatamine Aegnas või Piritas</li>
<li>Külasta jõultuotesid (november-jaanuar)</li>
<li>Saunakultuur - täiuslik talvetradisüün!</li>
<li>Talvine ujumine (kui oled julge!)</li>
</ul>

<h2>Hädavajalikud talve äpid</h2>
<ul>
<li><strong>Ilmateenistus:</strong> Eesti ilmateenistuse äpp</li>
<li><strong>Elron:</strong> Rongigraafikud</li>
<li><strong>Bolt:</strong> Mugav külmadel päevadel, kui ei taha jalutada</li>
</ul>

<p>Pea meeles, et eestlased on karmide talvedega toime tulnud juba sajandeid. Järgi nende eeskuju, invecteeri korralikku varustust ja sa mitte ainult ei ela üle, vaid õitsed Tallinna talve imedemaal!</p>',
        'meta_title_et' => 'Talvine ellujäämisjuhend Tallinna ekspaatidele',
        'meta_description_et' => 'Täielik juhend Eesti talvede üleelamiseks: hädavajalik varustus, soojana püsimine, transpordi nõuanded ja tegevused külmal aastaajal.',

        // Russian
        'title_ru' => 'Руководство по выживанию зимой для экспатов в Таллине',
        'content_ru' => '<p>Эстонские зимы могут быть сложными, если вы не готовы. Это подробное руководство поможет вам не только выжить, но и насладиться холодными, темными месяцами в Таллине.</p>

<h2>Чего ожидать</h2>
<p>Таллинские зимы обычно длятся с ноября по март, с температурами от -5°C до -20°C. Ожидайте короткие световые дни (всего 6 часов в декабре) и периодические снежные бури.</p>

<h2>Необходимое зимнее снаряжение</h2>
<ul>
<li><strong>Правильная зимняя куртка:</strong> Инвестируйте в хороший пуховик или парку. Эстонцы рекомендуют Canada Goose или местный бренд Helly Hansen.</li>
<li><strong>Зимние ботинки:</strong> Водонепроницаемые, утепленные, с хорошим сцеплением. Ледяные шипы (liukutõkestid) необходимы в гололед.</li>
<li><strong>Многослойная одежда:</strong> Термобелье, шерстяные носки, шарфы, перчатки и шапки обязательны.</li>
<li><strong>Защита лица:</strong> В очень холодные дни (-15°C и ниже) защищайте лицо шарфом или балаклавой.</li>
</ul>

<h2>Сохранение тепла в помещении</h2>
<p>В большинстве эстонских квартир отличное центральное отопление, но старые здания могут нуждаться в дополнительном тепле:</p>
<ul>
<li>Используйте плотные шторы для утепления окон</li>
<li>Приобретите хороший увлажнитель (отопление сушит воздух)</li>
<li>Положите ковры на холодные полы</li>
<li>Проверьте уплотнители окон на сквозняки</li>
</ul>

<h2>Транспорт зимой</h2>
<p><strong>Общественный транспорт:</strong> Очень надежен даже в сильный снегопад. Автобусы и трамваи ходят по расписанию, улицы быстро очищаются.</p>
<p><strong>Вождение:</strong> Обязательные зимние шины с 1 декабря по 1 марта. Рассмотрите шипованные шины для дополнительной безопасности. Оставьте дополнительное время на прогрев автомобиля и медленную езду.</p>
<p><strong>Ходьба:</strong> Основные улицы посыпаны солью и очищены, но тротуары могут быть скользкими. Ходите как пингвин (короткие шаги, центр тяжести вперед), чтобы не поскользнуться!</p>

<h2>Борьба с зимней хандрой</h2>
<p>Длинные, темные зимы могут повлиять на настроение. Вот как оставаться позитивным:</p>
<ul>
<li><strong>Светотерапия:</strong> Используйте лампу SAD по 20-30 минут каждое утро</li>
<li><strong>Добавки витамина D:</strong> Необходимы при ограниченном солнечном свете</li>
<li><strong>Оставайтесь активными:</strong> Запишитесь в спортзал, попробуйте катание на коньках или беговые лыжи</li>
<li><strong>Общайтесь:</strong> Не впадайте в спячку! Присоединяйтесь к группам экспатов и зимним мероприятиям</li>
<li><strong>Примите хюгге:</strong> Сделайте свой дом уютным со свечами, теплыми одеялами и горячими напитками</li>
</ul>

<h2>Зимние развлечения</h2>
<ul>
<li>Катание на коньках в Таллинском ледовом зале или на открытых катках</li>
<li>Беговые лыжи на Эгна или Пирита</li>
<li>Посетите рождественские ярмарки (ноябрь-январь)</li>
<li>Культура сауны - идеальная зимняя традиция!</li>
<li>Зимнее плавание (если вы смелы!)</li>
</ul>

<h2>Необходимые зимние приложения</h2>
<ul>
<li><strong>Ilmateenistus:</strong> Приложение эстонской метеослужбы</li>
<li><strong>Elron:</strong> Расписание поездов</li>
<li><strong>Bolt:</strong> Удобно в холодные дни, когда не хочется идти пешком</li>
</ul>

<p>Помните, эстонцы справляются с суровыми зимами веками. Следуйте их примеру, инвестируйте в правильное снаряжение, и вы не просто выживете, но и будете процветать в зимней сказке Таллина!</p>',
        'meta_title_ru' => 'Руководство по выживанию зимой для экспатов Таллина',
        'meta_description_ru' => 'Полное руководство по выживанию эстонских зим: необходимое снаряжение, сохранение тепла, советы по транспорту и занятия в холодный сезон.',

        'published_at' => date('Y-m-d H:i:s', strtotime('-20 days')),
    ],
];

// Insert posts
foreach ($posts as $post) {
    try {
        $stmt = $conn->prepare("
            INSERT INTO blog_posts (
                slug, status, featured_image, author_id,
                title_en, content_en, meta_title_en, meta_description_en,
                title_et, content_et, meta_title_et, meta_description_et,
                title_ru, content_ru, meta_title_ru, meta_description_ru,
                published_at, created_at, updated_at
            ) VALUES (
                :slug, :status, :featured_image, :author_id,
                :title_en, :content_en, :meta_title_en, :meta_description_en,
                :title_et, :content_et, :meta_title_et, :meta_description_et,
                :title_ru, :content_ru, :meta_title_ru, :meta_description_ru,
                :published_at, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP
            )
        ");

        $stmt->execute($post);
        echo "✓ Created: {$post['title_en']}\n";
    } catch (Exception $e) {
        echo "✗ Error creating {$post['slug']}: " . $e->getMessage() . "\n";
    }
}

echo "\n✓ Blog posts seeded successfully!\n";
