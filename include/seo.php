<?php
/**
 * SEO Optimization for Orient Yacht Charter
 * Generates appropriate meta tags, Open Graph data, Twitter Card, and JSON-LD structured data
 * Compatible with PHP 7.3
 */

// Default meta tags if no specific page is loaded
$metaTitle = $sitebaslik;
$metaDescription = $siteaciklama;
$metaKeywords = $siteaahtar;
$canonicalURL = $siteurl;
$metaImage = $siteurl . "assets/img/logo.png";

// Current page determination
$currentPage = isset($_GET["sayfa"]) ? $_GET["sayfa"] : "home";

// Yacht detail page SEO 
if($currentPage == "yacht-detay" && isset($_GET["seflink"])) {
    $seflink = $_GET["seflink"];
    $yat = $VT->VeriGetir("yatlar", "WHERE seflink=? AND durum=?", array($seflink, 1), "ORDER BY ID ASC", 1);
    
    if($yat != false) {
        // Get yacht details
        $yatID = $yat[0]["ID"];
        $yatBaslik = $yat[0]["baslik"];
        $yatAciklama = strip_tags($yat[0]["aciklama"]);
        $metaTitle = $yatBaslik . " - " . $sitebaslik;
        $metaDescription = mb_substr($yatAciklama, 0, 160, 'UTF-8') . "..."; 
        $metaKeywords = $yatBaslik . ", " . $yat[0]["marka"] . ", " . $yat[0]["kategori"] . ", " . $siteaahtar;
        $canonicalURL = $siteurl . "yat/" . $seflink;
        
        // Get yacht image for meta tags
        $resim = $VT->VeriGetir("resimler", "WHERE tablo=? AND KID=?", array("yatlar", $yatID), "ORDER BY ID ASC", 1);
        if($resim != false) {
            $metaImage = $siteurl . "images/" . $resim[0]["resim"];
        }
    }
}

// Corporate pages SEO
elseif($currentPage == "kurumsal" && isset($_GET["seflink"])) {
    $seflink = $_GET["seflink"];
    $sayfa = $VT->VeriGetir("kurumsal", "WHERE seflink=? AND durum=?", array($seflink, 1), "ORDER BY ID ASC", 1);
    
    if($sayfa != false) {
        $sayfaBaslik = $sayfa[0]["baslik"];
        $sayfaAciklama = strip_tags($sayfa[0]["aciklama"]);
        $metaTitle = $sayfaBaslik . " - " . $sitebaslik;
        $metaDescription = mb_substr($sayfaAciklama, 0, 160, 'UTF-8') . "...";
        $metaKeywords = $sayfaBaslik . ", " . $siteaahtar;
        $canonicalURL = $siteurl . "kurumsal/" . $seflink;
    }
}

// Yacht listing page SEO
elseif($currentPage == "yachts") {
    $metaTitle = "Tüm Yatlarımız - " . $sitebaslik;
    $metaDescription = "Orient Yacht Charter'da lüks ve konforlu yat kiralama seçeneklerini keşfedin. Farklı boyutlarda, özelliklerde ve fiyatlarda tekneler.";
    $canonicalURL = $siteurl . "yatlar";
}

// Contact page SEO
elseif($currentPage == "iletisim") {
    $metaTitle = "İletişim - " . $sitebaslik;
    $metaDescription = "Orient Yacht Charter ile iletişime geçin. Adres: " . $siteadres . ", Telefon: " . $sitetelefon;
    $canonicalURL = $siteurl . "iletisim";
}

// Generate Open Graph, Twitter Card, and meta tags
?>

<!-- Basic Meta Tags -->
<title><?php echo $metaTitle; ?></title>
<meta name="description" content="<?php echo $metaDescription; ?>">
<meta name="keywords" content="<?php echo $metaKeywords; ?>">
<link rel="canonical" href="<?php echo $canonicalURL; ?>">

<!-- Open Graph / Facebook -->
<meta property="og:type" content="website">
<meta property="og:url" content="<?php echo $canonicalURL; ?>">
<meta property="og:title" content="<?php echo $metaTitle; ?>">
<meta property="og:description" content="<?php echo $metaDescription; ?>">
<meta property="og:image" content="<?php echo $metaImage; ?>">
<meta property="og:site_name" content="<?php echo $sitebaslik; ?>">

<!-- Twitter -->
<meta property="twitter:card" content="summary_large_image">
<meta property="twitter:url" content="<?php echo $canonicalURL; ?>">
<meta property="twitter:title" content="<?php echo $metaTitle; ?>">
<meta property="twitter:description" content="<?php echo $metaDescription; ?>">
<meta property="twitter:image" content="<?php echo $metaImage; ?>">

<?php 
// JSON-LD Structured Data for Yacht Detail Page
if($currentPage == "yacht-detay" && isset($yat) && $yat != false): 
    $jsonLdYacht = array(
        "@context" => "https://schema.org/",
        "@type" => "Product",
        "name" => $yatBaslik,
        "description" => $metaDescription,
        "image" => $metaImage,
        "brand" => array(
            "@type" => "Brand",
            "name" => $yat[0]["marka"]
        ),
        "offers" => array(
            "@type" => "Offer",
            "priceCurrency" => "USD",
            "price" => $yat[0]["fiyat"],
            "priceValidUntil" => date('Y-m-d', strtotime('+1 year')),
            "availability" => "https://schema.org/InStock"
        )
    );
?>
<script type="application/ld+json">
<?php echo json_encode($jsonLdYacht); ?>
</script>
<?php endif; ?>

<?php 
// JSON-LD Structured Data for Local Business
$jsonLdBusiness = array(
    "@context" => "https://schema.org",
    "@type" => "BoatRental",
    "name" => $sitebaslik,
    "image" => $siteurl . "assets/img/logo.png",
    "url" => $siteurl,
    "telephone" => $sitetelefon,
    "address" => array(
        "@type" => "PostalAddress",
        "streetAddress" => $siteadres,
        "addressLocality" => "Bodrum",
        "addressRegion" => "Muğla",
        "addressCountry" => "TR"
    ),
    "geo" => array(
        "@type" => "GeoCoordinates",
        "latitude" => "37.0344",
        "longitude" => "27.4305"
    ),
    "openingHoursSpecification" => array(
        array(
            "@type" => "OpeningHoursSpecification",
            "dayOfWeek" => array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"),
            "opens" => "09:00",
            "closes" => "18:00"
        )
    )
);
?>
<script type="application/ld+json">
<?php echo json_encode($jsonLdBusiness); ?>
</script>

<?php
/**
 * SEO Constants and Functions
 * Provides default SEO values for the site
 */

// Default SEO constants for use throughout the site
if (!defined('SITE_TITLE')) {
    define('SITE_TITLE', 'Orient Yacht | Premium Yacht Charter Services');
}

if (!defined('SITE_DESC')) {
    define('SITE_DESC', 'Luxury yacht charter services offering exceptional sailing experiences with a fleet of premium yachts for rent.');
}

if (!defined('SITE_KEYWORDS')) {
    define('SITE_KEYWORDS', 'yacht charter, yacht rental, luxury yacht, boat rental, sailing, vacation, sea, private yacht');
}

// Function to get SEO data for a specific page
if (!function_exists('getSEOData')) {
    function getSEOData($page = 'home') {
        // Default SEO data
        $seoData = [
            'home' => [
                'title' => 'Orient Yacht | Premium Yacht Charter Services',
                'description' => 'Luxury yacht charter services offering exceptional sailing experiences with a fleet of premium yachts for rent.',
                'keywords' => 'yacht charter, yacht rental, luxury yacht, boat rental, sailing, vacation, sea, private yacht'
            ],
            'yatlar' => [
                'title' => 'Our Yacht Fleet | Orient Yacht',
                'description' => 'Explore our diverse fleet of luxury yachts available for charter. Find the perfect yacht for your next adventure.',
                'keywords' => 'yacht fleet, yacht charter, yacht rental, boats for rent, sailing yacht, motor yacht'
            ],
            'hakkimizda' => [
                'title' => 'About Us | Orient Yacht',
                'description' => 'Learn about Orient Yacht, our mission, values, and commitment to providing premium yacht charter experiences.',
                'keywords' => 'yacht company, yacht charter history, about orient yacht, yacht services'
            ],
            'iletisim' => [
                'title' => 'Contact Us | Orient Yacht',
                'description' => 'Get in touch with Orient Yacht for yacht charter inquiries, reservations, or general information.',
                'keywords' => 'yacht rental contact, yacht charter inquiry, yacht booking, contact yacht company'
            ]
        ];
        
        // Return SEO data for requested page or home if not found
        return $seoData[$page] ?? $seoData['home'];
    }
}
?> 