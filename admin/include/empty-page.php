<?php
// Bu sayfa admin panelde boş sayfaların sorun tespiti için
echo "<div style='background-color:#cce5ff; color:#004085; padding:20px; margin:20px; border-radius:5px;'>";
echo "<h3>Test Sayfası</h3>";
echo "<p>Bu sayfa, admin panelde sayfa gösteriminin doğru çalışıp çalışmadığını test etmek için oluşturulmuştur.</p>";

echo "<h4>Değişken Kontrolü:</h4>";
echo "<ul>";
echo "<li>SABIT tanımlı mı: " . (defined("SABIT") ? "EVET" : "HAYIR") . "</li>";
echo "<li>VT değişkeni kullanılabilir mi: " . (isset($VT) ? "EVET" : "HAYIR") . "</li>";
echo "<li>SITE değişkeni: " . (defined("SITE") ? SITE : "Tanımlı değil") . "</li>";
echo "</ul>";

echo "<p>Eğer bu sayfa düzgün görüntülenebiliyorsa, sorun yachts.php dosyasında olabilir.</p>";
echo "</div>";
?> 