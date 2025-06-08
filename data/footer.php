<?php
/**
 * Footer template
 */
?>
<footer class="footer">
			<div class="container">
				<div class="row">
					<div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="100">
						<div class="footer-section">
							<a class="footer__logo" href="<?=SITE?>"><img class="img-fluid" src="<?=SITE?>assets/img/logo-light.png" alt="<?=$sitebaslik?>"></a>
							<div class="footer-info">Orient Yacht Charter ile lüks bir deniz deneyimi yaşayın. Premium yatlarımız ve profesyonel ekibimizle hayallerinizin ötesinde bir tatil sizi bekliyor.</div>
						</div>
						<section class="footer-section">
							<h3 class="footer-section__title footer-section__title_sm">Bültene Abone Olun</h3>
							<form class="footer-form">
								<div class="form-group">
									<input class="footer-form__input form-control" type="email" placeholder="E-posta adresiniz"><i class="ic far fa-envelope-open"></i> </div>
							</form>
						</section>
					</div>
					<div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
						<section class="footer-section footer-section_link pl-5">
							<h3 class="footer-section__title">Yat Hizmetlerimiz</h3>
							<ul class="footer-list list-unstyled">
								<li><a href="#">Düğün Organizasyonları</a></li>
								<li><a href="#">Mavi Turlar</a></li>
								<li><a href="#">Özel Davetler</a></li>
								<li><a href="#">Kurumsal Etkinlikler</a></li>
								<li><a href="#">Balık Avı Turları</a></li>
								<li><a href="#">Konaklama</a></li>
								<li><a href="#">Doğum Günü Partileri</a></li>
								<li><a href="#">Yat Kiralama</a></li>
							</ul>
						</section>
					</div>
					<div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
						<section class="footer-section footer-section_link footer-section_link_about">
							<h3 class="footer-section__title">Orient Yacht</h3>
							<ul class="footer-list list-unstyled">
								<li><a href="<?=SITE?>">Ana Sayfa</a></li>
								<li><a href="<?=SITE?>hizmetler">Hizmetlerimiz</a></li>
								<li><a href="<?=SITE?>hakkimizda">Hakkımızda</a></li>
								<li><a href="<?=SITE?>yatlar">Yat Filomu</a></li>
								<li><a href="<?=SITE?>blog">Blog</a></li>
								<li><a href="<?=SITE?>iletisim">İletişim</a></li>
								<li><a href="<?=SITE?>yat-al-sat">Yat Alım Satım</a></li>
								<li><a href="<?=SITE?>ozel-yatlar">Öne Çıkan Yatlar</a></li>
							</ul>
						</section>
					</div>
					<div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="400">
						<section class="footer-section">
							<h3 class="footer-section__title">Bize Ulaşın</h3>
							<div class="footer-contacts">
								<div class="footer-contacts__item"><i class="ic icon-location-pin"></i><?=$siteadres?></div>
								<div class="footer-contacts__item"><i class="ic icon-envelope"></i><a href="mailto:<?=$sitemail?>"><?=$sitemail?></a></div>
								<div class="footer-contacts__item"><i class="ic icon-earphones-alt"></i> Telefon: <a class="footer-contacts__phone" href="tel:<?=$sitetelefon?>"><?=$sitetelefon?></a> </div>
							</div>
							<ul class="footer-soc list-unstyled">
								<li class="footer-soc__item"><a class="footer-soc__link" href="#" target="_blank"><i class="ic fab fa-twitter"></i></a></li>
								<li class="footer-soc__item"><a class="footer-soc__link" href="#" target="_blank"><i class="ic fab fa-facebook-f"></i></a></li>
								<li class="footer-soc__item"><a class="footer-soc__link" href="#" target="_blank"><i class="ic fab fa-instagram"></i></a></li>
								<li class="footer-soc__item"><a class="footer-soc__link" href="#" target="_blank"><i class="ic fab fa-youtube"></i></a></li>
							</ul>
							<a class="btn btn-white" href="<?=SITE?>iletisim">Rezervasyon Yap</a> 
						</section>
					</div>
				</div>
			</div>
			<div class="footer-copyright">
				<div class="container">&copy; <?=date("Y")?> Orient Yacht Charter. Tüm hakları saklıdır.</div>
			</div>
		</footer>

<!-- AOS Animation Library -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

<!-- Custom script for animations and fixed header -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize AOS
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true,
            mirror: false
        });
        
        // Fixed header
        jQuery(document).ready(function($) {
            // Reapply scroll handler after page loads completely
            setTimeout(function() {
                if (typeof handleScroll === 'function') {
                    handleScroll();
                    
                    // Extra check for scroll events
                    $(window).trigger('scroll');
                }
            }, 500);
            
            // Smooth scroll for anchor links
            $('a[href*="#"]:not([href="#"])').click(function() {
                if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && location.hostname == this.hostname) {
                    var target = $(this.hash);
                    target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
                    if (target.length) {
                        $('html, body').animate({
                            scrollTop: target.offset().top - 100
                        }, 1000);
                        return false;
                    }
                }
            });
        });
    });
</script>

<script src="<?= SITE ?>assets/js/theme.js"></script>

<!-- AOS Animation Library -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    // Initialize AOS
    AOS.init({
        duration: 800,
        once: true,
        offset: 100
    });
    
    // Initialize Parallax
    document.addEventListener('DOMContentLoaded', function() {
        const parallaxContainers = document.querySelectorAll('.parallax-container');
        parallaxContainers.forEach(container => {
            new Parallax(container, {
                relativeInput: true,
                clipRelativeInput: true,
                hoverOnly: true
            });
        });
    });
</script>

<!-- Gallery Functionality -->
<script src="<?= SITE ?>assets/js/gallery-functions.js"></script>
</body>
</html>