<?php
/**
 * Çeviri İşlemleri Yönetim Dosyası
 * 
 * Bu dosya dil değişimlerine göre içeriklerin veritabanından çekilmesi ve
 * gerekli çevirilerin yapılması işlemlerini yönetir.
 * PHP 7.3 uyumludur.
 * 
 * Note: Functions are now conditional to avoid conflicts with LanguageController
 * 
 * @author Orient Yacht Charter
 * @version 1.1
 */

if (!function_exists('translateContent')) {
    /**
     * Seçilen dile göre metni çevirir
     * 
     * @param string $original Orijinal metin (Türkçe)
     * @param string $table Çevirilerin bulunduğu tablo adı
     * @param string $field Çevirilecek alan adı
     * @param int $contentID İçerik ID'si
     * @return string Çevrilmiş metin
     */
    function translateContent($original, $table, $field, $contentID) {
        global $VT;
        
        // Varsayılan olarak orijinal metni döndür
        if (!isset($_SESSION["dil"]) || $_SESSION["dil"] == "tr") {
            return $original;
        }
        
        // Seçilen dile göre çeviriyi veritabanından çek
        $translation = $VT->VeriGetir(
            $table, 
            "WHERE content_id=? AND lang=? AND field=?", 
            array($contentID, $_SESSION["dil"], $field), 
            "ORDER BY ID ASC", 
            1
        );
        
        // Çeviri varsa ve içeriği boş değilse çeviriyi döndür
        if ($translation != false && !empty($translation[0]["translation"])) {
            return $translation[0]["translation"];
        }
        
        // Çeviri yoksa orijinal metni döndür
        return $original;
    }
}

if (!function_exists('translateArray')) {
    /**
     * İçerikleri dil seçimine göre çevirir
     * 
     * @param array $content İçerik dizisi
     * @param string $table İçeriğin bulunduğu tablo
     * @param array $fields Çevrilecek alanlar
     * @return array Çevrilmiş içerik dizisi
     */
    function translateArray($content, $table, $fields) {
        // Eğer içerik yoksa NULL döndür
        if (!$content) {
            return null;
        }
        
        // İçerik bir dizi olarak gelmediyse, diziyi döndür
        if (!is_array($content)) {
            return $content;
        }
        
        // İçerik bir sonuç kümesi ise (çoklu satır)
        if (isset($content[0]) && is_array($content[0])) {
            foreach ($content as $key => $item) {
                foreach ($fields as $field) {
                    if (isset($item[$field]) && isset($item["ID"])) {
                        $content[$key][$field] = translateContent($item[$field], $table . "_translations", $field, $item["ID"]);
                    }
                }
            }
        } else {
            // Tek bir satırsa
            foreach ($fields as $field) {
                if (isset($content[$field]) && isset($content["ID"])) {
                    $content[$field] = translateContent($content[$field], $table . "_translations", $field, $content["ID"]);
                }
            }
        }
        
        return $content;
    }
}

if (!function_exists('translateMenu')) {
    /**
     * Menü elemanlarını dil seçimine göre çevirir
     * 
     * @return array Çevrilmiş menü
     */
    function translateMenu() {
        global $VT;
        
        // Varsayılan olarak menü öğelerini veritabanından çek
        $menu = $VT->VeriGetir("menu", "WHERE durum=?", array(1), "ORDER BY sira ASC");
        
        // Çevrilecek alanlar
        $fields = ['baslik', 'aciklama', 'url'];
        
        // Çeviri işlemini gerçekleştir
        return translateArray($menu, "menu", $fields);
    }
}

if (!function_exists('translateText')) {
    /**
     * Sabit metinleri dil seçimine göre çevirir
     * 
     * @param string $key Metin anahtarı
     * @return string Çevrilmiş metin
     */
    function translateText($key, $default = '') {
        global $VT, $lang;
        
        // Site_texts tablosundan ilgili metni çek
        $ceviri = $VT->VeriGetir("site_texts", "WHERE text_key=? AND lang=?", array($key, $lang), "ORDER BY ID ASC", 1);
        
        if($ceviri != false) {
            return $ceviri[0]["text_value"];
        }
        
        // Çeviri bulunamadıysa ve varsayılan değer verildiyse onu kullan
        if(!empty($default)) {
            return $default;
        }
        
        // Hiçbir çeviri bulunamadıysa ve varsayılan değer de verilmediyse anahtarı döndür
        return $key;
    }
}

if (!function_exists('translateDate')) {
    /**
     * Dile göre tarih formatını döndürür
     * 
     * @param string $date MySQL tarih formatı (YYYY-MM-DD)
     * @param bool $withTime Saat bilgisi eklensin mi?
     * @return string Formatlanmış tarih
     */
    function translateDate($date, $withTime = false) {
        if (empty($date)) {
            return "";
        }
        
        $format = 'd.m.Y';
        if ($withTime) {
            $format .= ' H:i';
        }
        
        // Seçilen dile göre tarih formatını değiştir
        if (isset($_SESSION["dil"])) {
            switch ($_SESSION["dil"]) {
                case "en":
                    $format = 'Y-m-d';
                    if ($withTime) {
                        $format .= ' H:i';
                    }
                    break;
                case "de":
                    $format = 'd.m.Y';
                    if ($withTime) {
                        $format .= ' H:i';
                    }
                    break;
                case "ru":
                    $format = 'd.m.Y';
                    if ($withTime) {
                        $format .= ' H:i';
                    }
                    break;
            }
        }
        
        // Tarihi formatla
        $dateObj = new DateTime($date);
        return $dateObj->format($format);
    }
}

if (!function_exists('translateCurrency')) {
    /**
     * Dile göre para birimi formatı döndürür
     * 
     * @param float $amount Tutar
     * @return string Formatlanmış tutar
     */
    function translateCurrency($amount, $currency = '') {
        global $lang;
        
        // Varsayılan para birimi
        if(empty($currency)) {
            $currency = "TRY";
        }
        
        // Dil tercihine göre para birimi formatını belirle
        switch($lang) {
            case 'en':
                $symbol = ($currency == "TRY") ? "₺" : "$";
                return $symbol . ' ' . number_format($amount, 0, '.', ',');
            case 'de':
                $symbol = ($currency == "TRY") ? "₺" : "€";
                return $symbol . ' ' . number_format($amount, 0, ',', '.');
            case 'ru':
                $symbol = ($currency == "TRY") ? "₺" : "₽";
                return $symbol . ' ' . number_format($amount, 0, ',', ' ');
            default: // Türkçe
                return number_format($amount, 0, ',', '.') . ' ₺';
        }
    }
}

if (!function_exists('createTranslationTables')) {
    /**
     * Çoklu dil desteği için veritabanı şeması oluşturma örneği
     * 
     * NOT: Bu fonksiyon sadece örnek amaçlıdır ve kullanılmadan önce
     * veritabanı yapısına uygun olarak düzenlenmelidir.
     */
    function createTranslationTables() {
        global $VT;
        
        // Örnek - Her bir içerik tablosu için çeviri tablosu oluşturma
        $tables = ["yachts", "services", "pages", "blog"];
        
        foreach ($tables as $table) {
            $sql = "CREATE TABLE IF NOT EXISTS " . $table . "_translations (
                ID int(11) NOT NULL AUTO_INCREMENT,
                content_id int(11) NOT NULL,
                lang varchar(2) NOT NULL,
                field varchar(50) NOT NULL,
                translation text NOT NULL,
                PRIMARY KEY (ID),
                KEY content_id (content_id),
                KEY lang (lang),
                KEY field (field)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;";
            
            $VT->SorguCalistir($sql, array());
        }
        
        // Sabit metinler için tablo oluşturma
        $sql = "CREATE TABLE IF NOT EXISTS site_texts (
            ID int(11) NOT NULL AUTO_INCREMENT,
            text_key varchar(100) NOT NULL,
            tr_content text NOT NULL,
            en_content text NOT NULL,
            de_content text NOT NULL,
            ru_content text NOT NULL,
            PRIMARY KEY (ID),
            UNIQUE KEY text_key (text_key)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;";
        
        $VT->SorguCalistir($sql, array());
    }
}

// Dil ayarını kontrol et ve gerekirse varsayılan dili ayarla
if (!isset($_SESSION["dil"])) {
    $_SESSION["dil"] = "tr";
}
?> 