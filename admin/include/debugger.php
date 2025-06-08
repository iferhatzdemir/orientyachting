<?php
// Bu sayfa hiçbir koşula bağlı olmadan çalışacak basit bir debugger
echo "<div style='background-color:#ffeeba; color:#856404; padding:20px; margin:20px; border-radius:5px;'>";
echo "<h3>Debugger Sayfası</h3>";

// PHP sürümü ve yüklü eklentiler
echo "<h4>PHP Bilgileri:</h4>";
echo "<p>PHP Sürümü: " . phpversion() . "</p>";
echo "<p>Display Errors: " . ini_get('display_errors') . "</p>";
echo "<p>Error Reporting: " . ini_get('error_reporting') . "</p>";

// Değişken bilgileri 
echo "<h4>Tanımlı Sabitler:</h4>";
echo "<ul>";
$definedConstants = get_defined_constants(true);
$userConstants = isset($definedConstants['user']) ? $definedConstants['user'] : array();
foreach ($userConstants as $name => $value) {
    echo "<li>$name = " . (is_string($value) ? $value : var_export($value, true)) . "</li>";
}
echo "</ul>";

// Yachts tablosunu kontrol et
echo "<h4>Veritabanı Kontrol:</h4>";
echo "<p>Aşağıdaki SQL sorgusunu manuel olarak veritabanında çalıştırarak tabloların varlığını kontrol edin:</p>";
echo "<pre>SHOW TABLES LIKE 'yachts';</pre>";

// Çözüm önerileri
echo "<h4>Olası Çözümler:</h4>";
echo "<ol>";
echo "<li>admin/index.php dosyasına <code>define(\"SABIT\", true);</code> satırını ekleyin</li>";
echo "<li>Debug için yachts.php içindeki yorum satırını aktifleştirin (// echo \"&lt;div style='background...)</li>";
echo "<li>yacht_tables.sql dosyasını veritabanına import ettiğinizden emin olun</li>";
echo "<li>Boş sayfayı görüntülerken tarayıcıda F12 tuşuna basıp Console sekmesinde hata mesajı olup olmadığını kontrol edin</li>";
echo "</ol>";

echo "</div>";
?> 