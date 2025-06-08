<?php
/**
 * Static Translations
 * 
 * This file contains static translations that are used as fallback
 * when database translations are not available.
 * Translations are now organized by language, then context, then key.
 * 
 * @return array Array of translations by language and context
 */

return [
    // English translations
    'en' => [
        // Default context
        'default' => [
            'site.title' => 'Orient Yacht Charter',
            'site.description' => 'Luxury yacht charter services',
            
            // Footer
            'footer.copyright' => 'All rights reserved',
            'footer.privacy' => 'Privacy Policy',
            'footer.terms' => 'Terms of Service'
        ],
        
        // Navigation context
        'navigation' => [
            'nav.home' => 'Home',
            'nav.yachts' => 'Yachts',
            'nav.services' => 'Services',
            'nav.destinations' => 'Destinations',
            'nav.about' => 'About Us',
            'nav.contact' => 'Contact',
        ],
        
        // Yacht related context 
        'yacht' => [
            'yacht.title' => 'Luxury Yacht',
            'yacht.description' => 'Explore the seas with our luxury yacht rental service',
            'yacht.details' => 'Yacht Details',
            'yacht.features' => 'Features',
            'yacht.specifications' => 'Specifications',
            'yacht.gallery' => 'Gallery',
            'yacht.booking' => 'Book Now',
            'yacht.inquire' => 'Request Information',
            'yacht.location_unknown' => 'Location not specified',
            'yacht.persons' => 'Persons',
            'yacht.cabins' => 'Cabins',
            'yacht.price_per_day' => 'Day',
            'yacht.model' => 'Model',
            'yacht.manufacturer' => 'Manufacturer',
            'yacht.model_year' => 'Model Year',
            'yacht.length' => 'Length',
            'yacht.capacity' => 'Capacity',
            'yacht.view_details' => 'View Details',
            'yacht.book_now' => 'Book This Yacht'
        ],
        
        // Contact form context
        'contact' => [
            'contact.title' => 'Contact Us',
            'contact.subtitle' => 'Get in touch with us',
            'contact.name' => 'Your Name',
            'contact.email' => 'Email Address',
            'contact.phone' => 'Phone Number',
            'contact.message' => 'Your Message',
            'contact.submit' => 'Send Message',
            'contact.success' => 'Your message has been sent successfully!',
            'contact.error' => 'There was an error sending your message. Please try again.',
        ],
        
        // Home page context
        'home' => [
            'home.default_banner.title' => 'Exclusive Yacht Experience',
            'home.default_banner.description' => 'Discover the pinnacle of luxury at sea',
            'home.default_banner.button' => 'Discover More',
            
            'home.about.title' => 'About Orient Yacht Charter',
            'home.about.description' => 'Discover unparalleled sailing experiences with Orient Yacht Charter. We offer a premium fleet of luxury yachts for unforgettable journeys across the crystal-clear waters of Turkey and beyond.',
            'home.about.feature1' => 'Professional crew and personalized service',
            'home.about.feature2' => 'Luxury amenities and comfort',
            'home.about.feature3' => 'Safety as our highest priority',
            'home.about.feature4' => 'Exclusive destinations and routes',
            'home.about.signature' => 'Captain Ali Yılmaz',
            
            'home.advantage1.title' => 'Experienced Captains',
            'home.advantage1.description' => 'Our professional captains have years of sailing experience in local waters.',
            'home.advantage2.title' => 'Premium Equipment',
            'home.advantage2.description' => 'Every yacht is equipped with top-quality gear for water activities.',
            'home.advantage3.title' => 'Personalized Service',
            'home.advantage3.description' => 'Customize your journey with our attentive and dedicated team.',
            
            'home.fleet.title' => 'Our Luxury Fleet',
            'home.fleet.description' => 'Discover our collection of premium yachts available for charter',
            'home.view_all_yachts' => 'View All Yachts',
            
            'home.featured_deal.title' => 'Special Offer',
            'home.featured_deal.rental' => 'Daily Rental Starting From',
            'home.featured_deal.call_now' => 'Call for availability:',
            'home.featured_deal.default_title' => 'Exclusive Yacht Experience',
            'home.featured_deal.default_subtitle' => 'Special Limited-Time Offer',
            'home.featured_deal.default_text' => 'Experience luxury at sea with our premium yacht charters. Contact us for details about our current promotions.',
            
            'home.counter1.description' => 'Happy Customers',
            'home.counter2.description' => 'Completed Tours',
            'home.counter3.description' => 'Destinations',
            'home.counter4.description' => 'Quality Services',
            
            'home.offers.title' => 'Premium Boat<br>Rental Services',
            'home.offers.description' => 'Experience premium yacht rental services with our exclusive fleet. Our dedicated team ensures your journey is comfortable and memorable.',
            'home.offers.button' => 'View More',
            
            'home.offer1.title' => 'Water Sports Boat',
            'home.offer1.description' => 'Experience exciting water sports and activities with our specialized vessels perfectly equipped for adventures.',
            'home.offer2.title' => 'Family Gathering',
            'home.offer2.description' => 'Create unforgettable memories with loved ones aboard our spacious and comfortable family-friendly yachts.',
            'home.offer3.title' => 'Corporate Events',
            'home.offer3.description' => 'Host impressive business meetings, team building activities or corporate celebrations in an exclusive yacht setting.',
            'home.offer4.title' => 'Celebrations Events',
            'home.offer4.description' => 'Make your special occasions extraordinary with our luxury yacht rentals perfect for birthdays and celebrations.',
            
            'home.video.subtitle' => 'Give us a call or drop an email, We endeavor to answer within 24 hours',
            'home.video.title' => 'We\'ve Exclusive Boats With Charter Offers',
            'home.video.highlight' => 'LET\'S PLAN YOUR NEXT TOUR!',
            'home.video.call' => 'Call Us Today:',
            'home.video.email' => 'Email:',
            'home.video.button' => 'Watch A Tour',
            
            'home.gallery.title' => 'Our Gallery',
            'home.gallery.description' => 'Enjoy beautiful moments from our luxury yacht experiences',
            'home.gallery.image' => 'Gallery Image',
            
            'home.contact.title' => 'Get In Touch With Us',
            'home.contact.subtitle' => 'Have questions or ready to book? Contact us today!',
            'home.contact.button' => 'Contact Us'
        ]
    ],
    
    // Turkish translations
    'tr' => [
        // Default context
        'default' => [
            'site.title' => 'Orient Yat Kiralama',
            'site.description' => 'Lüks yat kiralama hizmetleri',
            
            // Footer
            'footer.copyright' => 'Tüm hakları saklıdır',
            'footer.privacy' => 'Gizlilik Politikası',
            'footer.terms' => 'Kullanım Koşulları'
        ],
        
        // Navigation context
        'navigation' => [
            'nav.home' => 'Ana Sayfa',
            'nav.yachts' => 'Yatlar',
            'nav.services' => 'Hizmetler',
            'nav.destinations' => 'Destinasyonlar',
            'nav.about' => 'Hakkımızda',
            'nav.contact' => 'İletişim',
        ],
        
        // Yacht related context
        'yacht' => [
            'yacht.title' => 'Lüks Yat',
            'yacht.description' => 'Lüks yat kiralama hizmetimiz ile denizleri keşfedin',
            'yacht.details' => 'Yat Detayları',
            'yacht.features' => 'Özellikler',
            'yacht.specifications' => 'Teknik Özellikler',
            'yacht.gallery' => 'Galeri',
            'yacht.booking' => 'Rezervasyon Yap',
            'yacht.inquire' => 'Bilgi İsteyin',
            'yacht.location_unknown' => 'Konum belirtilmemiş',
            'yacht.persons' => 'Kişi',
            'yacht.cabins' => 'Kabin',
            'yacht.price_per_day' => 'Gün',
            'yacht.model' => 'Model',
            'yacht.manufacturer' => 'Üretici',
            'yacht.model_year' => 'Model Yılı',
            'yacht.length' => 'Uzunluk',
            'yacht.capacity' => 'Kapasite',
            'yacht.view_details' => 'Detayları Görüntüle',
            'yacht.book_now' => 'Rezervasyon Yap'
        ],
        
        // Contact form context
        'contact' => [
            'contact.title' => 'İletişim',
            'contact.subtitle' => 'Bizimle iletişime geçin',
            'contact.name' => 'Adınız',
            'contact.email' => 'E-posta Adresiniz',
            'contact.phone' => 'Telefon Numaranız',
            'contact.message' => 'Mesajınız',
            'contact.submit' => 'Mesaj Gönder',
            'contact.success' => 'Mesajınız başarıyla gönderildi!',
            'contact.error' => 'Mesajınız gönderilirken bir hata oluştu. Lütfen tekrar deneyin.',
        ],
        
        // Home page context
        'home' => [
            'home.default_banner.title' => 'Özel Yat Deneyimi',
            'home.default_banner.description' => 'Denizdeki lüksün zirvesini keşfedin',
            'home.default_banner.button' => 'Daha Fazlasını Keşfet',
            
            'home.about.title' => 'Orient Yat Kiralama Hakkında',
            'home.about.description' => 'Orient Yat Kiralama ile eşsiz yelken deneyimleri keşfedin. Türkiye\'nin kristal berraklığındaki sularında ve ötesinde unutulmaz yolculuklar için lüks yatlardan oluşan premium bir filo sunuyoruz.',
            'home.about.feature1' => 'Profesyonel mürettebat ve kişiselleştirilmiş hizmet',
            'home.about.feature2' => 'Lüks olanaklar ve konfor',
            'home.about.feature3' => 'En yüksek önceliğimiz güvenlik',
            'home.about.feature4' => 'Özel destinasyonlar ve rotalar',
            'home.about.signature' => 'Kaptan Ali Yılmaz',
            
            'home.advantage1.title' => 'Deneyimli Kaptanlar',
            'home.advantage1.description' => 'Profesyonel kaptanlarımız yerel sularda yıllarca yelken deneyimine sahiptir.',
            'home.advantage2.title' => 'Premium Ekipman',
            'home.advantage2.description' => 'Her yat, su aktiviteleri için en kaliteli ekipmanlarla donatılmıştır.',
            'home.advantage3.title' => 'Kişiselleştirilmiş Hizmet',
            'home.advantage3.description' => 'Dikkatli ve özenli ekibimizle yolculuğunuzu özelleştirin.',
            
            'home.fleet.title' => 'Lüks Filomuz',
            'home.fleet.description' => 'Kiralık premium yat koleksiyonumuzu keşfedin',
            'home.view_all_yachts' => 'Tüm Yatları Görüntüle',
            
            'home.featured_deal.title' => 'Özel Teklif',
            'home.featured_deal.rental' => 'Günlük Kiralama Başlangıç Fiyatı',
            'home.featured_deal.call_now' => 'Müsaitlik için arayın:',
            'home.featured_deal.default_title' => 'Özel Yat Deneyimi',
            'home.featured_deal.default_subtitle' => 'Sınırlı Süre Özel Teklif',
            'home.featured_deal.default_text' => 'Premium yat kiralama hizmetimizle denizdeki lüksü deneyimleyin. Mevcut promosyonlarımız hakkında detaylar için bize ulaşın.',
            
            'home.counter1.description' => 'Mutlu Müşteri',
            'home.counter2.description' => 'Tamamlanan Turlar',
            'home.counter3.description' => 'Destinasyonlar',
            'home.counter4.description' => 'Kaliteli Hizmetler',
            
            'home.offers.title' => 'Premium Tekne<br>Kiralama Hizmetleri',
            'home.offers.description' => 'Özel filomuz ile premium yat kiralama hizmetlerini deneyimleyin. Özel ekibimiz yolculuğunuzun rahat ve unutulmaz olmasını sağlar.',
            'home.offers.button' => 'Daha Fazlası',
            
            'home.offer1.title' => 'Su Sporları Teknesi',
            'home.offer1.description' => 'Maceralar için mükemmel donanımlı özel teknelerimizle heyecan verici su sporları ve aktivitelerini deneyimleyin.',
            'home.offer2.title' => 'Aile Buluşması',
            'home.offer2.description' => 'Ferah ve konforlu aile dostu yatlarımızda sevdiklerinizle unutulmaz anılar yaratın.',
            'home.offer3.title' => 'Kurumsal Etkinlikler',
            'home.offer3.description' => 'Özel bir yat ortamında etkileyici iş toplantıları, takım oluşturma etkinlikleri veya kurumsal kutlamalar düzenleyin.',
            'home.offer4.title' => 'Kutlama Etkinlikleri',
            'home.offer4.description' => 'Doğum günleri ve kutlamalar için mükemmel olan lüks yat kiralama hizmetimizle özel anlarınızı olağanüstü hale getirin.',
            
            'home.video.subtitle' => 'Bizi arayın veya e-posta gönderin, 24 saat içinde cevap vermeye çalışıyoruz',
            'home.video.title' => 'Özel Charter Teklifleri ile Özel Teknelerimiz Var',
            'home.video.highlight' => 'BİR SONRAKİ TURUNUZU PLANLAYIN!',
            'home.video.call' => 'Bizi Arayın:',
            'home.video.email' => 'E-posta:',
            'home.video.button' => 'Bir Tur İzleyin',
            
            'home.gallery.title' => 'Galerimiz',
            'home.gallery.description' => 'Lüks yat deneyimlerimizden güzel anları keşfedin',
            'home.gallery.image' => 'Galeri Görseli',
            
            'home.contact.title' => 'Bizimle İletişime Geçin',
            'home.contact.subtitle' => 'Sorularınız mı var veya rezervasyon yapmaya hazır mısınız? Bugün bize ulaşın!',
            'home.contact.button' => 'İletişim'
        ]
    ],
    
    // German translations
    'de' => [
        // Default context
        'default' => [
            'site.title' => 'Orient Yacht Charter',
            'site.description' => 'Luxus-Yachtcharter-Dienste',
            
            // Footer
            'footer.copyright' => 'Alle Rechte vorbehalten',
            'footer.privacy' => 'Datenschutzrichtlinie',
            'footer.terms' => 'Nutzungsbedingungen'
        ],
        
        // Navigation context
        'navigation' => [
            'nav.home' => 'Startseite',
            'nav.yachts' => 'Yachten',
            'nav.services' => 'Dienstleistungen',
            'nav.destinations' => 'Reiseziele',
            'nav.about' => 'Über Uns',
            'nav.contact' => 'Kontakt',
        ],
        
        // Yacht related context
        'yacht' => [
            'yacht.title' => 'Luxusyacht',
            'yacht.description' => 'Entdecken Sie die Meere mit unserem Luxusyacht-Charterservice',
            'yacht.details' => 'Yacht-Details',
            'yacht.features' => 'Ausstattung',
            'yacht.specifications' => 'Technische Daten',
            'yacht.gallery' => 'Galerie',
            'yacht.booking' => 'Jetzt Buchen',
            'yacht.inquire' => 'Informationen Anfordern',
            'yacht.location_unknown' => 'Standort nicht angegeben',
            'yacht.persons' => 'Personen',
            'yacht.cabins' => 'Kabinen',
            'yacht.price_per_day' => 'Tag',
            'yacht.model' => 'Modell',
            'yacht.manufacturer' => 'Hersteller',
            'yacht.model_year' => 'Baujahr',
            'yacht.length' => 'Länge',
            'yacht.capacity' => 'Kapazität',
            'yacht.view_details' => 'Details Anzeigen',
            'yacht.book_now' => 'Jetzt Buchen'
        ],
        
        // Contact form context
        'contact' => [
            'contact.title' => 'Kontakt',
            'contact.subtitle' => 'Nehmen Sie Kontakt mit uns auf',
            'contact.name' => 'Ihr Name',
            'contact.email' => 'E-Mail-Adresse',
            'contact.phone' => 'Telefonnummer',
            'contact.message' => 'Ihre Nachricht',
            'contact.submit' => 'Nachricht Senden',
            'contact.success' => 'Ihre Nachricht wurde erfolgreich gesendet!',
            'contact.error' => 'Beim Senden Ihrer Nachricht ist ein Fehler aufgetreten. Bitte versuchen Sie es erneut.',
        ]
    ],
    
    // Russian translations
    'ru' => [
        // Default context
        'default' => [
            'site.title' => 'Orient Yacht Charter',
            'site.description' => 'Услуги по аренде роскошных яхт',
            
            // Footer
            'footer.copyright' => 'Все права защищены',
            'footer.privacy' => 'Политика Конфиденциальности',
            'footer.terms' => 'Условия Использования'
        ],
        
        // Navigation context
        'navigation' => [
            'nav.home' => 'Главная',
            'nav.yachts' => 'Яхты',
            'nav.services' => 'Услуги',
            'nav.destinations' => 'Направления',
            'nav.about' => 'О Нас',
            'nav.contact' => 'Контакты',
        ],
        
        // Yacht related context
        'yacht' => [
            'yacht.title' => 'Роскошная Яхта',
            'yacht.description' => 'Исследуйте моря с помощью нашей услуги аренды роскошных яхт',
            'yacht.details' => 'Детали Яхты',
            'yacht.features' => 'Функции',
            'yacht.specifications' => 'Технические Характеристики',
            'yacht.gallery' => 'Галерея',
            'yacht.booking' => 'Забронировать Сейчас',
            'yacht.inquire' => 'Запросить Информацию',
            'yacht.location_unknown' => 'Местоположение не указано',
            'yacht.persons' => 'Человек',
            'yacht.cabins' => 'Каюты',
            'yacht.price_per_day' => 'День',
            'yacht.model' => 'Модель',
            'yacht.manufacturer' => 'Производитель',
            'yacht.model_year' => 'Год выпуска',
            'yacht.length' => 'Длина',
            'yacht.capacity' => 'Вместимость',
            'yacht.view_details' => 'Посмотреть детали',
            'yacht.book_now' => 'Забронировать'
        ],
        
        // Contact form context
        'contact' => [
            'contact.title' => 'Контакты',
            'contact.subtitle' => 'Свяжитесь с нами',
            'contact.name' => 'Ваше Имя',
            'contact.email' => 'Электронная Почта',
            'contact.phone' => 'Номер Телефона',
            'contact.message' => 'Ваше Сообщение',
            'contact.submit' => 'Отправить Сообщение',
            'contact.success' => 'Ваше сообщение успешно отправлено!',
            'contact.error' => 'При отправке вашего сообщения произошла ошибка. Пожалуйста, попробуйте еще раз.',
        ]
    ],
    
    // French translations
    'fr' => [
        // Default context
        'default' => [
            'site.title' => 'Orient Yacht Charter',
            'site.description' => 'Services de location de yachts de luxe',
            
            // Footer
            'footer.copyright' => 'Tous droits réservés',
            'footer.privacy' => 'Politique de Confidentialité',
            'footer.terms' => 'Conditions d\'Utilisation'
        ],
        
        // Navigation context
        'navigation' => [
            'nav.home' => 'Accueil',
            'nav.yachts' => 'Yachts',
            'nav.services' => 'Services',
            'nav.destinations' => 'Destinations',
            'nav.about' => 'À Propos',
            'nav.contact' => 'Contact',
        ],
        
        // Yacht related context
        'yacht' => [
            'yacht.title' => 'Yacht de Luxe',
            'yacht.description' => 'Explorez les mers avec notre service de location de yachts de luxe',
            'yacht.details' => 'Détails du Yacht',
            'yacht.features' => 'Caractéristiques',
            'yacht.specifications' => 'Spécifications Techniques',
            'yacht.gallery' => 'Galerie',
            'yacht.booking' => 'Réserver Maintenant',
            'yacht.inquire' => 'Demander des Informations',
            'yacht.location_unknown' => 'Emplacement non spécifié',
            'yacht.persons' => 'Personnes',
            'yacht.cabins' => 'Cabines',
            'yacht.price_per_day' => 'Jour',
            'yacht.model' => 'Modèle',
            'yacht.manufacturer' => 'Fabricant',
            'yacht.model_year' => 'Année du modèle',
            'yacht.length' => 'Longueur',
            'yacht.capacity' => 'Capacité',
            'yacht.view_details' => 'Voir les détails',
            'yacht.book_now' => 'Réserver'
        ],
        
        // Contact form context
        'contact' => [
            'contact.title' => 'Contact',
            'contact.subtitle' => 'Contactez-nous',
            'contact.name' => 'Votre Nom',
            'contact.email' => 'Adresse E-mail',
            'contact.phone' => 'Numéro de Téléphone',
            'contact.message' => 'Votre Message',
            'contact.submit' => 'Envoyer Message',
            'contact.success' => 'Votre message a été envoyé avec succès!',
            'contact.error' => 'Une erreur s\'est produite lors de l\'envoi de votre message. Veuillez réessayer.',
        ]
    ]
];
?> 