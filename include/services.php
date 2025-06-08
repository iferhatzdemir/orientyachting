<?php
if(!defined("SABIT")) define("SABIT", true);

// SEO bilgileri
$seo = $VT->VeriGetir("seo", "WHERE seo_url=? AND durum=?", array("services", 1), "ORDER BY ID ASC", 1);
?>

<title><?=$seo[0]["title"]?></title>
<meta name="description" content="<?=$seo[0]["description"]?>">
<meta name="keywords" content="<?=$seo[0]["keywords"]?>">

<!-- Open Graph -->
<meta property="og:title" content="<?=$seo[0]["title"]?>">
<meta property="og:description" content="<?=$seo[0]["description"]?>">
<meta property="og:url" content="<?=SITE?>services">
<meta property="og:type" content="website">
<?php if(!empty($seo[0]["resim"])) { ?>
<meta property="og:image" content="<?=SITE?>images/seo/<?=$seo[0]["resim"]?>">
<?php } ?>

<!-- Twitter Card -->
<meta name="twitter:title" content="<?=$seo[0]["title"]?>">
<meta name="twitter:description" content="<?=$seo[0]["description"]?>">
<?php if(!empty($seo[0]["resim"])) { ?>
<meta name="twitter:image" content="<?=SITE?>images/seo/<?=$seo[0]["resim"]?>">
<meta name="twitter:card" content="summary_large_image">
<?php } else { ?>
<meta name="twitter:card" content="summary">
<?php } ?>



<div class="luxury-hero" style="background: linear-gradient(rgba(11, 34, 66, 0.85), rgba(11, 34, 66, 0.85)), url('<?=SITE?>assets/img/hero-bg.jpg') no-repeat center center/cover;">
    <div class="container">
        <div class="hero-content text-center" data-aos="fade-up" data-aos-duration="1000">
            <h1 class="display-title">Services</h1>
            <div class="title-separator">
                <span class="diamond"></span>
            </div>
            <p class="hero-subtitle">Experience the epitome of maritime luxury</p>
            
         

          
        </div>
    </div>
    <div class="hero-waves">
        <svg class="waves" xmlns="http://www.w3.org/2000/svg" viewBox="0 24 150 28" preserveAspectRatio="none">
            <defs>
                <path id="wave-path" d="M-160 44c30 0 58-18 88-18s 58 18 88 18 58-18 88-18 58 18 88 18 v44h-352z"></path>
            </defs>
            <g class="wave-parallax">
                <use href="#wave-path" x="48" y="0" fill="rgba(255,255,255,0.7"></use>
                <use href="#wave-path" x="48" y="3" fill="rgba(255,255,255,0.5)"></use>
                <use href="#wave-path" x="48" y="5" fill="rgba(255,255,255,0.3)"></use>
                <use href="#wave-path" x="48" y="7" fill="#fff"></use>
            </g>
        </svg>
    </div>
</div>
<section class="b-services section-default">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="text-center">
                    <h2 class="ui-title">Hizmetlerimiz</h2>
                    <div class="row">
                        <div class="col-md-8 offset-md-2">
                            <p>Orient Yacht Charter olarak, sizlere hayallerinizin ötesinde bir deniz tatili sunmak için
                            en lüks ve konforlu yatlarımızla kaliteli hizmet veriyoruz. Müşteri memnuniyeti bizim önceliğimizdir.</p>
                            <img src="assets/img/decore04.png" alt="Hizmetlerimiz">
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <?php
                    // Hizmetleri getir
                    $hizmetler = $VT->VeriGetir("services", "WHERE durum=?", array(1), "ORDER BY sirano ASC");
                    
                    if($hizmetler != false) {
                        foreach($hizmetler as $hizmet) {
                            // Mevcut dil için çeviri varsa, onu kullan
                            $baslik = $hizmet["baslik"];
                            $aciklama = $hizmet["aciklama"];
                            
                            // Dil çevirisini kontrol et
                            if(isset($_SESSION["dil"]) && $_SESSION["dil"] != "tr") {
                                $ceviriler = $VT->VeriGetir("services_dil", "WHERE service_id=? AND lang=?", array($hizmet["ID"], $_SESSION["dil"]), "ORDER BY ID ASC", 1);
                                if($ceviriler != false) {
                                    if(!empty($ceviriler[0]["baslik"])) {
                                        $baslik = $ceviriler[0]["baslik"];
                                    }
                                    if(!empty($ceviriler[0]["aciklama"])) {
                                        $aciklama = $ceviriler[0]["aciklama"];
                                    }
                                }
                            }
                    ?>
                    <div class="col-lg-4 col-md-6">
                        <div class="b-advantages b-advantages-2">
                            <?php if(!empty($hizmet["icon"])) { ?>
                                <div class="ic <?=$hizmet["icon"]?> text-secondary"></div>
                            <?php } else if(!empty($hizmet["resim"])) { ?>
                                <div class="b-advantages-2__icon">
                                    <img src="<?=SITE?>images/services/<?=$hizmet["resim"]?>" alt="<?=$baslik?>">
                                </div>
                            <?php } else { ?>
                                <div class="ic flaticon-rudder-1 text-secondary"></div>
                            <?php } ?>
                            <div class="b-advantages__main">
                                <div class="b-advantages__title"><?=stripslashes($baslik)?></div>
                                <div class="decore01"></div>
                                <div class="b-advantages__info"><?=stripslashes($aciklama)?></div>
                                <div class="mt-3">
                                    <a href="<?=SITE?>services/<?=$hizmet["seflink"]?>" class="btn btn-outline-primary btn-sm">Daha fazla</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                        }
                    } else {
                        echo '<div class="col-12 text-center"><p>Henüz hizmet kaydı bulunmamaktadır.</p></div>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section-default">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="text-center">
                    <h2 class="ui-title">Neden Bizi Seçmelisiniz?</h2>
                    <div class="row">
                        <div class="col-md-8 offset-md-2">
                            <p>Orient Yacht Charter olarak, deniz tutkusunu paylaşan herkese premium kalitede hizmet sunuyoruz.
                            Tecrübeli ekibimiz ve kusursuz filomuzla hayallerinizi gerçeğe dönüştürüyoruz.</p>
                            <img src="assets/img/decore03.png" alt="Neden Bizi Seçmelisiniz?">
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-lg-4">
                        <div class="b-advantages">
                            <div class="ic flaticon-rudder-1 text-secondary"></div>
                            <div class="b-advantages__main">
                                <div class="b-advantages__title">Profesyonel Ekip</div>
                                <div class="decore01"></div>
                                <div class="b-advantages__info">Deneyimli ve profesyonel ekibimiz, denizde geçireceğiniz her anın güvenli ve keyifli olmasını sağlar.</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="b-advantages">
                            <div class="ic flaticon-snorkel text-secondary"></div>
                            <div class="b-advantages__main">
                                <div class="b-advantages__title">Premium Yatlar</div>
                                <div class="decore01"></div>
                                <div class="b-advantages__info">Filomuzda yer alan her yat, yüksek kalite standartlarında ve konfor düşünülerek seçilmiştir.</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="b-advantages">
                            <div class="ic flaticon-sailor text-secondary"></div>
                            <div class="b-advantages__main">
                                <div class="b-advantages__title">Eşsiz Rotalar</div>
                                <div class="decore01"></div>
                                <div class="b-advantages__info">Size özel olarak planlanan rotalarda, Türkiye'nin eşsiz koylarını ve tarihi yerlerini keşfetme imkanı sunuyoruz.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section-form bg-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="text-left">
                    <h2 class="ui-title">Bizimle İletişime Geçin</h2>
                    <p>Daha fazla bilgi almak ve rezervasyon yapmak için bizimle iletişime geçebilirsiniz. Size en kısa sürede dönüş yapacağız.</p>
                    <img src="assets/img/decore03.png" alt="İletişim">
                    
                    <form action="<?=SITE?>teklif-gonder" method="post">
                        <div class="row row-form-b">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input class="form-control" type="text" name="adsoyad" placeholder="Ad Soyad" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input class="form-control" type="tel" name="telefon" placeholder="Telefon" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input class="form-control" type="email" name="email" placeholder="E-posta" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input class="form-control" type="text" name="konu" placeholder="Konu" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <textarea class="form-control" name="mesaj" rows="6" placeholder="Mesajınız" required></textarea>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-secondary">Gönder</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="text-left title-padding-m-top">
                    <h2 class="ui-title">Sık Sorulan Sorular</h2>
                    <p>Merak ettiğiniz soruların bazılarını burada yanıtladık. Daha fazla bilgi için lütfen bizimle iletişime geçin.</p>
                    <img src="assets/img/decore03.png" alt="SSS">
                </div>
                
                <div class="ui-accordion accordion" id="accordion-1">
                    <div class="card">
                        <div class="card-header" id="heading1">
                            <h3 class="mb-0">
                                <button class="ui-accordion__link collapsed" type="button" data-toggle="collapse" data-target="#collapse1" aria-expanded="true" aria-controls="collapse1">
                                    <span class="ui-accordion__number">01</span>Yat kiralama süreci nasıl işliyor?<i class="ic fas fa-chevron-down"></i>
                                </button>
                            </h3>
                        </div>
                        <div class="collapse show" id="collapse1" data-aria-labelledby="heading1" data-parent="#accordion-1">
                            <div class="card-body">
                                Orient Yacht Charter ile yat kiralama sürecinde öncelikle size uygun yatı seçiyor, ardından seyahat planınıza göre rezervasyon yapıyoruz. Tüm detayları sizinle birlikte planladıktan sonra, ödeme işlemlerini tamamlıyor ve tatil gününüz geldiğinde marinada sizi ağırlıyoruz.
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header" id="heading2">
                            <h3 class="mb-0">
                                <button class="ui-accordion__link collapsed" type="button" data-toggle="collapse" data-target="#collapse2" aria-expanded="false" aria-controls="collapse2">
                                    <span class="ui-accordion__number">02</span>Kaptanlı ve kaptansız kiralama seçenekleri var mı?<i class="ic fas fa-chevron-down"></i>
                                </button>
                            </h3>
                        </div>
                        <div class="collapse" id="collapse2" data-aria-labelledby="heading2" data-parent="#accordion-1">
                            <div class="card-body">
                                Evet, hem kaptanlı hem de kaptansız kiralama seçeneklerimiz mevcuttur. Kaptansız kiralama için geçerli bir amatör denizci belgesi veya yat kaptanı lisansına sahip olmanız gerekmektedir. Kaptanlı kiralamada ise deneyimli kaptanlarımız size eşlik ederek güvenli ve keyifli bir seyahat deneyimi sunar.
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header" id="heading3">
                            <h3 class="mb-0">
                                <button class="ui-accordion__link collapsed" type="button" data-toggle="collapse" data-target="#collapse3" aria-expanded="false" aria-controls="collapse3">
                                    <span class="ui-accordion__number">03</span>Rezervasyon için ne kadar önce başvurmalıyım?<i class="ic fas fa-chevron-down"></i>
                                </button>
                            </h3>
                        </div>
                        <div class="collapse" id="collapse3" data-aria-labelledby="heading3" data-parent="#accordion-1">
                            <div class="card-body">
                                Yüksek sezon dönemlerinde (Haziran-Eylül arası) en az 2-3 ay önceden rezervasyon yapmanızı öneririz. Düşük sezonda ise 1 ay önceden rezervasyon yeterli olacaktır. Ancak yat müsaitliği her zaman değişkenlik gösterdiği için, planlarınızı ne kadar erken netleştirirseniz, istediğiniz yatı kiralama olasılığınız o kadar yüksek olur.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section> 