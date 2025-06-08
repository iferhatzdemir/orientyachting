<?php
@session_start();
@ob_start();
define("DATA","data/");
define("SAYFA","include/");
define("SINIF","class/");
include_once(DATA."baglanti.php");

echo "<h1>Hizmetler Modülü Kurulum Sayfası</h1>";

// Yönetici kontrolü
if(empty($_SESSION["ID"]) || empty($_SESSION["adsoyad"]) || empty($_SESSION["mail"])) {
    echo "<p style='color:red'>Bu sayfaya erişim yetkiniz yok!</p>";
    exit;
}

// hizmetler tablosunu oluştur (eğer yoksa)
try {
    // Tablo var mı kontrol et
    $tableCheck = $db->query("SHOW TABLES LIKE 'hizmetler'");
    $tableExists = $tableCheck->rowCount() > 0;
    
    if (!$tableExists) {
        // Tablo yoksa oluştur
        $sql = "CREATE TABLE hizmetler (
            ID int(11) NOT NULL AUTO_INCREMENT,
            baslik varchar(255) NOT NULL,
            seflink varchar(255) NOT NULL,
            kategori varchar(50) NOT NULL,
            ozet text DEFAULT NULL,
            aciklama text DEFAULT NULL,
            resim varchar(255) DEFAULT NULL,
            icon varchar(50) DEFAULT NULL,
            anahtar varchar(255) DEFAULT NULL,
            description varchar(255) DEFAULT NULL,
            sira int(11) NOT NULL DEFAULT 0,
            durum tinyint(1) NOT NULL DEFAULT 1,
            tarih date DEFAULT NULL,
            PRIMARY KEY (ID)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
        
        $db->exec($sql);
        echo "<p style='color:green'>hizmetler tablosu başarıyla oluşturuldu.</p>";
    } else {
        echo "<p>hizmetler tablosu zaten mevcut.</p>";
    }
    
    // Tablo boş mu kontrol et
    $count = $db->query("SELECT COUNT(*) as total FROM hizmetler")->fetch(PDO::FETCH_ASSOC);
    
    if ($count['total'] == 0) {
        // Örnek hizmetler ekle
        $tarih = date("Y-m-d");
        
        // Yat Yönetimi hizmeti
        $baslik1 = "Yat Yönetimi";
        $seflink1 = "yat-yonetimi";
        $kategori1 = "hizmetler";
        $ozet1 = "Profesyonel yat yönetimi hizmetlerimizle yatınızın her zaman mükemmel durumda kalmasını sağlıyoruz.";
        $aciklama1 = "<p>Orient Yacht Charter olarak, yat sahiplerine kapsamlı yat yönetimi hizmetleri sunuyoruz. Teknenizin bakımından, mürettebat yönetimine, sigorta süreçlerinden, yasal düzenlemelere kadar tüm ihtiyaçlarınızı karşılıyoruz.</p>
        <h3>Yat Yönetimi Hizmetlerimiz</h3>
        <ul>
        <li>Teknik bakım ve onarım takibi</li>
        <li>Mürettebat seçimi ve yönetimi</li>
        <li>Marina rezervasyonları</li>
        <li>Sigorta ve belgelendirme süreçleri</li>
        <li>Düzenli denetim ve raporlama</li>
        <li>Bütçe planlama ve finansal yönetim</li>
        </ul>
        <p>Yatınızla ilgili tüm operasyonel süreçleri profesyonel ekibimizle yönetiyoruz, böylece siz sadece denizin ve yatınızın keyfini çıkarabilirsiniz.</p>";
        $resim1 = "yacht-management.jpg";
        $icon1 = "fa-ship";
        $anahtar1 = "yat yönetimi, tekne yönetimi, tekne bakım hizmetleri, yat bakım, mürettebat yönetimi";
        $desc1 = "Orient Yacht Charter ile profesyonel yat yönetimi hizmetleri. Tekne bakım, mürettebat yönetimi ve tam kapsamlı operasyonel hizmetler.";
        
        // Kiralama ve Alım/Satım hizmeti
        $baslik2 = "Kiralama ve Alım/Satım";
        $seflink2 = "kiralama-alim-satim";
        $kategori2 = "hizmetler";
        $ozet2 = "Lüks yat kiralama veya alım/satım süreçlerinde uzman ekibimizle yanınızdayız.";
        $aciklama2 = "<p>Orient Yacht Charter olarak, lüks yat kiralama veya alım/satım süreçlerinde müşterilerimize özel çözümler sunuyoruz. Geniş filomuz ve pazar bilgimizle, bütçenize ve beklentilerinize en uygun tekneyi bulmanıza yardımcı oluyoruz.</p>
        <h3>Kiralama Hizmetlerimiz</h3>
        <ul>
        <li>Günlük, haftalık veya sezonluk kiralama seçenekleri</li>
        <li>Mürettebatlı veya mürettebatsız kiralama imkanı</li>
        <li>Özel etkinlikler için yat kiralama</li>
        <li>Kişiselleştirilmiş rota planlama</li>
        </ul>
        <h3>Alım/Satım Hizmetlerimiz</h3>
        <ul>
        <li>Alıcı ve satıcı temsilciliği</li>
        <li>Tekne değerleme danışmanlığı</li>
        <li>Tekne inceleme ve denetim süreçleri</li>
        <li>Pazarlık ve satış sonrası destek</li>
        </ul>";
        $resim2 = "yacht-charter.jpg";
        $icon2 = "fa-handshake";
        $anahtar2 = "yat kiralama, tekne kiralama, yat satın alma, tekne satma, lüks yat kiralama";
        $desc2 = "Orient Yacht Charter ile lüks yat kiralama ve alım/satım hizmetleri. Özel ihtiyaçlarınıza uygun yat seçenekleri ve profesyonel danışmanlık.";
        
        // İnşa ve Bakım Onarım hizmeti
        $baslik3 = "İnşa ve Bakım Onarım";
        $seflink3 = "insaat-bakim-onarim";
        $kategori3 = "hizmetler";
        $ozet3 = "Yat inşa projelerinde danışmanlık ve mevcut teknelerin bakım-onarım hizmetleri sunuyoruz.";
        $aciklama3 = "<p>Orient Yacht Charter ekibi olarak, yeni yat inşa süreçlerinde proje yönetimi ve danışmanlık hizmetleri sunuyoruz. Ayrıca mevcut teknelerin bakım-onarım ihtiyaçlarını profesyonel ekibimizle karşılıyoruz.</p>
        <h3>İnşa Hizmetlerimiz</h3>
        <ul>
        <li>Tersane seçimi ve proje değerlendirme</li>
        <li>Tasarım ve mühendislik danışmanlığı</li>
        <li>Malzeme seçimi ve tedarik yönetimi</li>
        <li>İnşa sürecinde kalite kontrol ve denetim</li>
        </ul>
        <h3>Bakım-Onarım Hizmetlerimiz</h3>
        <ul>
        <li>Düzenli bakım programları</li>
        <li>Motor ve teknik sistem onarımları</li>
        <li>Gövde ve iç mekan yenileme çalışmaları</li>
        <li>Acil durum müdahale ve tamir hizmetleri</li>
        </ul>";
        $resim3 = "yacht-maintenance.jpg";
        $icon3 = "fa-tools";
        $anahtar3 = "yat inşa, tekne bakım, tekne onarım, yat yenileme, tekne tamir";
        $desc3 = "Orient Yacht Charter ile yat inşa danışmanlığı ve bakım-onarım hizmetleri. Profesyonel teknik ekip ve kaliteli malzeme kullanımı.";
        
        // Transfer hizmeti
        $baslik4 = "Transfer";
        $seflink4 = "transfer";
        $kategori4 = "hizmetler";
        $ozet4 = "Deniz ve kara transferleriniz için lüks ve konforlu ulaşım çözümleri sunuyoruz.";
        $aciklama4 = "<p>Orient Yacht Charter olarak, misafirlerimizin konforunu düşünerek özel transfer hizmetleri sunuyoruz. Havaalanından marinaya, marinadan otele veya deniz üzerinde adadan adaya; her türlü transferiniz için lüks ve güvenli seçenekler sağlıyoruz.</p>
        <h3>Transfer Hizmetlerimiz</h3>
        <ul>
        <li>VIP havaalanı - marina transferleri</li>
        <li>Lüks tekne transferleri</li>
        <li>Özel kara araçları ile ulaşım</li>
        <li>Helikopter transferi</li>
        <li>Adalar arası deniz transferleri</li>
        </ul>
        <p>Tüm transfer süreçlerinizde, yüksek standartlarda hizmet, dakiklik ve konfor garantisi veriyoruz. Özel istekleriniz için lütfen bizimle iletişime geçin.</p>";
        $resim4 = "yacht-transfer.jpg";
        $icon4 = "fa-shuttle-van";
        $anahtar4 = "yat transferi, marina transferi, deniz transferi, lüks transfer, VIP transfer";
        $desc4 = "Orient Yacht Charter ile lüks ve konforlu transfer hizmetleri. Havaalanı-marina transferleri, adalar arası tekne transferleri ve özel VIP ulaşım çözümleri.";
        
        // SQL sorguları
        $insertSql = "INSERT INTO hizmetler (baslik, seflink, kategori, ozet, aciklama, resim, icon, anahtar, description, sira, durum, tarih) VALUES 
            (?, ?, ?, ?, ?, ?, ?, ?, ?, 1, 1, ?),
            (?, ?, ?, ?, ?, ?, ?, ?, ?, 2, 1, ?),
            (?, ?, ?, ?, ?, ?, ?, ?, ?, 3, 1, ?),
            (?, ?, ?, ?, ?, ?, ?, ?, ?, 4, 1, ?)";
            
        $stmt = $db->prepare($insertSql);
        $stmt->execute([
            $baslik1, $seflink1, $kategori1, $ozet1, $aciklama1, $resim1, $icon1, $anahtar1, $desc1, $tarih,
            $baslik2, $seflink2, $kategori2, $ozet2, $aciklama2, $resim2, $icon2, $anahtar2, $desc2, $tarih,
            $baslik3, $seflink3, $kategori3, $ozet3, $aciklama3, $resim3, $icon3, $anahtar3, $desc3, $tarih,
            $baslik4, $seflink4, $kategori4, $ozet4, $aciklama4, $resim4, $icon4, $anahtar4, $desc4, $tarih
        ]);
        
        echo "<p style='color:green'>Örnek hizmetler başarıyla eklendi.</p>";
    } else {
        echo "<p>hizmetler tablosunda ".htmlspecialchars($count['total'])." adet hizmet bulunuyor.</p>";
    }
} catch (PDOException $e) {
    echo "<p style='color:red'>Hizmetler tablosu işlemi sırasında hata: " . $e->getMessage() . "</p>";
}

echo "<p>Hizmetler modülü kurulum işlemi tamamlandı.</p>";
echo "<p><a href='index.php'>Admin paneline dön</a></p>";
?> 