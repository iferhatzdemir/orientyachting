<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=$sitebaslik?></title>
    
    <!-- Required CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&display=swap">
    <link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?=SITE?>assets/css/style.css">
    
    <!-- Required JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/parallax.js/1.5.0/parallax.min.js"></script>
</head>
<body>
<div class="header-search open-search">
			<div class="container">
				<div class="row">
					<div class="col-sm-8 offset-sm-2 col-10 offset-1">
						<div class="navbar-search">
							<form class="search-global">
								<input class="search-global__input" type="text" placeholder="Type to search"
									autocomplete="off" name="s" value="" />
								<button class="search-global__btn"><i class="icon stroke icon-Search"></i></button>
								<div class="search-global__note">Begin typing your search above and press return to
									search.</div>
							</form>
						</div>
					</div>
				</div>
			</div>
			<button class="search-close close" type="button"><i class="fa fa-times"></i></button>
		</div>
		<!-- ==========================-->
		<!-- MOBILE MENU-->
		<!-- ==========================-->
		<div data-off-canvas="mobile-slidebar left overlay" class="luxury-mobile-menu">
			<!-- Mobile Menu Luxury Header -->
			<div class="mobile-menu-header">
				<div class="mobile-menu-logo">
					<img src="<?=SITE?>assets/img/logo-light.png" alt="<?=$sitebaslik?>" class="mobile-logo">
				</div>
				<div class="mobile-menu-close">
					<span class="close-icon">&times;</span>
				</div>
			</div>
			
			<!-- Luxury Mobile Navigation -->
			<nav class="luxury-mobile-nav">
				<ul class="luxury-nav-list">
					<li class="luxury-nav-item active">
						<a class="luxury-nav-link" href="index.php">
							<span class="link-icon"><i class="fas fa-home"></i></span>
							<span class="link-text">Home</span>
							<span class="link-hover-effect"></span>
						</a>
					</li>
			
					
					<li class="luxury-nav-item has-submenu">
						<a class="luxury-nav-link submenu-toggle" href="javascript:void(0)">
							<span class="link-icon"><i class="fas fa-ship"></i></span>
							<span class="link-text">Yachts</span>
							<span class="submenu-arrow"><i class="fas fa-chevron-down"></i></span>
							<span class="link-hover-effect"></span>
						</a>
						
						<ul class="luxury-submenu">
							<li class="luxury-submenu-item">
								<a class="luxury-submenu-link" href="<?=SITE?>yatlar">
									<span class="submenu-diamond"></span>
									All Yachts
								</a>
							</li>
							<?php
							// Modified query to only show yacht categories that have active yachts
							$yachtCategories=$VT->VeriGetir("yacht_categories 
								INNER JOIN yacht_category_rel ON yacht_categories.ID = yacht_category_rel.category_id
								INNER JOIN yachts ON yacht_category_rel.yacht_id = yachts.id",
								"WHERE yacht_categories.durum=? AND yachts.is_active=?",
								array(1, 1),
								"GROUP BY yacht_categories.ID ORDER BY yacht_categories.sirano ASC");
								
							if($yachtCategories!=false)
							{
								for ($i=0; $i <count($yachtCategories) ; $i++) { 
									?>
								<li class="luxury-submenu-item">
									<a class="luxury-submenu-link" href="<?=SITE?>kategori/<?=$yachtCategories[$i]["seflink"]?>">
										<span class="submenu-diamond"></span>
										<?=stripslashes($yachtCategories[$i]["baslik"])?>
									</a>
								</li>
									<?php
								}
							}
<?php
// Query to list yacht types that have active yachts
$yachtTypes=$VT->VeriGetir("yacht_types \
    INNER JOIN yachts ON yacht_types.id = yachts.type_id",
    "WHERE yacht_types.durum=? AND yachts.is_active=?",
    array(1, 1),
    "GROUP BY yacht_types.id ORDER BY yacht_types.sirano ASC");

if($yachtTypes!=false)
{
    for ($i=0; $i <count($yachtTypes); $i++) {
        ?>
<li class="luxury-submenu-item">
    <a class="luxury-submenu-link" href="<?=SITE?>yacht-type/<?=$yachtTypes[$i]["seflink"]?>">
        <span class="submenu-diamond"></span>
        <?=stripslashes($yachtTypes[$i]["title"])?></a>
</li>
        <?php
    }
}
?>
						</ul>
					</li>
					
					<li class="luxury-nav-item">
						<a class="luxury-nav-link" href="index.php?sayfa=yachts">
							<span class="link-icon"><i class="fas fa-anchor"></i></span>
							<span class="link-text">Our Fleet</span>
							<span class="link-hover-effect"></span>
						</a>
					</li>
					
					<li class="luxury-nav-item">
						<a class="luxury-nav-link" href="index.php?sayfa=tours">
							<span class="link-icon"><i class="fas fa-compass"></i></span>
							<span class="link-text">Yacht Management</span>
							<span class="link-hover-effect"></span>
						</a>
					</li>
					
			
					
					<li class="luxury-nav-item">
						<a class="luxury-nav-link" href="index.php?sayfa=contact">
							<span class="link-icon"><i class="fas fa-envelope"></i></span>
							<span class="link-text">Contact</span>
							<span class="link-hover-effect"></span>
						</a>
					</li>
				</ul>
			</nav>
			
			<!-- Luxury Contact Details -->
			<div class="mobile-menu-footer">
				<div class="mobile-social-links">
					<a href="#" class="social-link"><i class="fab fa-facebook-f"></i></a>
					<a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
					<a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
					<a href="#" class="social-link"><i class="fab fa-youtube"></i></a>
				</div>
				<div class="mobile-contact-info">
					<p><i class="fas fa-phone-alt"></i> +1 755 302 8549</p>
					<p><i class="fas fa-envelope"></i> info@orientyachting.com</p>
				</div>
			</div>
			
			<!-- Luxury Mobile Menu Styling -->
			<style>
				/* Luxury Mobile Menu Styling */
				.luxury-mobile-menu {
					background: linear-gradient(135deg, #0a1f35 0%, #102a43 100%);
					color: #fff;
					font-family: 'Montserrat', sans-serif;
					padding: 0;
					overflow-y: auto;
					overflow-x: hidden;
					-webkit-overflow-scrolling: touch;
					width: 280px !important; /* Fixed width to prevent overflow */
					max-width: 85vw; /* Responsive fallback */
				}
				
				/* Mobile Menu Header */
				.mobile-menu-header {
					display: flex;
					align-items: center;
					justify-content: space-between;
					padding: 15px;
					border-bottom: 1px solid rgba(200, 169, 126, 0.2);
					position: relative;
				}
				
				.mobile-menu-header:after {
					content: '';
					position: absolute;
					bottom: 0;
					left: 15%;
					width: 70%;
					height: 1px;
					background: linear-gradient(90deg, transparent, rgba(200, 169, 126, 0.5), transparent);
				}
				
				.mobile-menu-logo {
					flex: 1;
					text-align: center;
				}
				
				.mobile-logo {
					max-height: 40px;
					max-width: 150px;
					display: inline-block;
					object-fit: contain;
				}
				
				.mobile-menu-close {
					width: 30px;
					height: 30px;
					display: flex;
					align-items: center;
					justify-content: center;
					cursor: pointer;
					color: #c8a97e;
					font-size: 24px;
					transition: all 0.3s ease;
					padding: 0;
					margin-left: 10px;
				}
				
				.mobile-menu-close:hover {
					transform: rotate(90deg);
					color: #fff;
				}
				
				/* Luxury Navigation */
				.luxury-mobile-nav {
					padding: 15px 0;
					padding-left: 8px;
				}
				
				.luxury-nav-list {
					list-style: none;
					margin: 0;
					padding: 0;
				}
				
				.luxury-nav-item {
					position: relative;
					margin-bottom: 4px;
				}
				
				.luxury-nav-link {
					display: flex;
					align-items: center;
					padding: 12px 20px;
					padding-left: 25px;
					color: rgba(255, 255, 255, 0.8);
					text-decoration: none;
					position: relative;
					transition: all 0.3s ease;
					overflow: hidden;
					font-size: 14px;
				}
				
				.luxury-nav-link:before {
					content: '';
					position: absolute;
					left: 0;
					top: 0;
					height: 100%;
					width: 3px;
					background-color: #c8a97e;
					transform: scaleY(0);
					transition: transform 0.3s ease;
				}
				
				.luxury-nav-item.active .luxury-nav-link,
				.luxury-nav-link:hover {
					color: #fff;
					background-color: rgba(255, 255, 255, 0.05);
				}
				
				.luxury-nav-item.active .luxury-nav-link:before,
				.luxury-nav-link:hover:before {
					transform: scaleY(1);
				}
				
				.link-hover-effect {
					position: absolute;
					bottom: 0;
					left: 30px;
					width: 0;
					height: 1px;
					background-color: #c8a97e;
					transition: width 0.3s ease;
				}
				
				.luxury-nav-link:hover .link-hover-effect,
				.luxury-nav-item.active .link-hover-effect {
					width: calc(100% - 30px);
				}
				
				.link-icon {
					width: 20px;
					margin-right: 15px;
					color: #c8a97e;
					text-align: center;
					flex-shrink: 0;
					font-size: 14px;
				}
				
				.link-text {
					flex: 1;
					font-size: 14px;
					letter-spacing: 0.5px;
					font-weight: 500;
					white-space: nowrap;
					overflow: hidden;
					text-overflow: ellipsis;
				}
				
				.submenu-arrow {
					width: 20px;
					height: 20px;
					display: flex;
					align-items: center;
					justify-content: center;
					font-size: 10px;
					color: #c8a97e;
					transition: all 0.3s ease;
					flex-shrink: 0;
				}
				
				.luxury-nav-item.open .submenu-arrow {
					transform: rotate(180deg);
				}
				
				/* Luxury Submenu */
				.luxury-submenu {
					list-style: none;
					padding: 0;
					margin: 0 0 0 30px;
					max-height: 0;
					overflow: hidden;
					transition: max-height 0.4s cubic-bezier(0, 1, 0, 1);
				}
				
				.luxury-nav-item.open .luxury-submenu {
					max-height: 500px;
					transition: max-height 0.4s ease-in-out;
				}
				
				.luxury-submenu-item {
					position: relative;
				}
				
				.luxury-submenu-link {
					display: flex;
					align-items: center;
					padding: 10px 12px;
					padding-left: 18px;
					color: rgba(255, 255, 255, 0.7);
					text-decoration: none;
					font-size: 13px;
					font-weight: 400;
					transition: all 0.3s ease;
					position: relative;
					overflow: hidden;
					white-space: nowrap;
					text-overflow: ellipsis;
				}
				
				.luxury-submenu-link:hover {
					color: #fff;
					background-color: rgba(255, 255, 255, 0.03);
				}
				
				.submenu-diamond {
					display: inline-block;
					width: 5px;
					height: 5px;
					background-color: #c8a97e;
					margin-right: 10px;
					opacity: 0.7;
					transform: rotate(45deg);
					transition: all 0.3s ease;
					flex-shrink: 0;
				}
				
				.luxury-submenu-link:hover .submenu-diamond {
					opacity: 1;
					transform: rotate(315deg);
				}
				
				/* Mobile Menu Footer */
				.mobile-menu-footer {
					padding: 15px;
					padding-left: 25px;
					border-top: 1px solid rgba(200, 169, 126, 0.2);
					position: relative;
				}
				
				.mobile-menu-footer:before {
					content: '';
					position: absolute;
					top: 0;
					left: 15%;
					width: 70%;
					height: 1px;
					background: linear-gradient(90deg, transparent, rgba(200, 169, 126, 0.5), transparent);
				}
				
				.mobile-social-links {
					display: flex;
					justify-content: center;
					margin-bottom: 15px;
				}
				
				.social-link {
					width: 32px;
					height: 32px;
					border-radius: 50%;
					border: 1px solid rgba(200, 169, 126, 0.4);
					display: flex;
					align-items: center;
					justify-content: center;
					color: #c8a97e;
					margin: 0 6px;
					font-size: 12px;
					transition: all 0.3s ease;
				}
				
				.social-link:hover {
					background-color: #c8a97e;
					color: #0a1f35;
					border-color: #c8a97e;
					transform: translateY(-3px);
				}
				
				.mobile-contact-info {
					text-align: center;
					color: rgba(255, 255, 255, 0.7);
					font-size: 12px;
				}
				
				.mobile-contact-info p {
					margin-bottom: 6px;
					white-space: nowrap;
					overflow: hidden;
					text-overflow: ellipsis;
				}
				
				.mobile-contact-info i {
					color: #c8a97e;
					margin-right: 6px;
				}
				
				/* Entrance Animation */
				@keyframes slideInLeft {
					from {
						transform: translateX(-100%);
						opacity: 0;
					}
					to {
						transform: translateX(0);
						opacity: 1;
					}
				}
				
				.js-open-slidebar .luxury-mobile-menu {
					animation: slideInLeft 0.3s forwards;
				}
				
				/* Item animations */
				.luxury-nav-item {
					opacity: 0;
					transform: translateX(-20px);
					animation: fadeInRight 0.5s forwards;
					animation-delay: calc(0.1s * var(--item-index, 0));
				}
				
				@keyframes fadeInRight {
					to {
						opacity: 1;
						transform: translateX(0);
					}
				}
				
				/* Enhanced hover effects */
				.luxury-nav-link {
					position: relative;
					z-index: 1;
				}
				
				.luxury-nav-link:after {
					content: '';
					position: absolute;
					top: 0;
					left: 0;
					width: 100%;
					height: 100%;
					background: rgba(200, 169, 126, 0.1);
					opacity: 0;
					z-index: -1;
					transform: scaleX(0.9) scaleY(0.8);
					transform-origin: center;
					transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
				}
				
				.luxury-nav-link:hover:after,
				.luxury-nav-item.active .luxury-nav-link:after {
					opacity: 1;
					transform: scaleX(1) scaleY(1);
				}
			</style>
			
			<!-- Luxury Mobile Menu JavaScript -->
			<script>
				document.addEventListener('DOMContentLoaded', function() {
					// Add animation delay based on item index
					var navItems = document.querySelectorAll('.luxury-nav-item');
					navItems.forEach(function(item, index) {
						item.style.setProperty('--item-index', index);
					});
					
					// Toggle submenu with smooth animation
					var submenuToggles = document.querySelectorAll('.submenu-toggle');
					
					submenuToggles.forEach(function(toggle) {
						toggle.addEventListener('click', function(e) {
							e.preventDefault();
							
							var menuItem = this.closest('.luxury-nav-item');
							
							// Close other open submenus
							document.querySelectorAll('.luxury-nav-item.open').forEach(function(item) {
								if (item !== menuItem) {
									item.classList.remove('open');
								}
							});
							
							// Toggle current submenu
							menuItem.classList.toggle('open');
							
							// Add ripple effect on click
							var ripple = document.createElement('span');
							ripple.classList.add('menu-ripple');
							this.appendChild(ripple);
							
							// Position the ripple
							var rect = this.getBoundingClientRect();
							var size = Math.max(rect.width, rect.height);
							ripple.style.width = ripple.style.height = size + 'px';
							ripple.style.left = (e.clientX - rect.left - size/2) + 'px';
							ripple.style.top = (e.clientY - rect.top - size/2) + 'px';
							
							// Remove ripple after animation completes
							setTimeout(function() {
								ripple.remove();
							}, 500);
						});
					});
					
					// Close menu on X click with animation
					var closeButton = document.querySelector('.mobile-menu-close');
					
					if (closeButton) {
						closeButton.addEventListener('click', function() {
							var menu = document.querySelector('.luxury-mobile-menu');
							menu.style.animation = 'slideOutLeft 0.3s forwards';
							
							setTimeout(function() {
								// This assumes the off-canvas menu has a close method
								// Update this based on your actual menu system
								$('[data-canvas]').offcanvas('hide');
								menu.style.animation = '';
							}, 300);
						});
					}
					
					// Add keyframe animation for slide out
					var style = document.createElement('style');
					style.textContent = `
						@keyframes slideOutLeft {
							from {
								transform: translateX(0);
								opacity: 1;
							}
							to {
								transform: translateX(-100%);
								opacity: 0;
							}
						}
						
						.menu-ripple {
							position: absolute;
							border-radius: 50%;
							background-color: rgba(255, 255, 255, 0.3);
							transform: scale(0);
							animation: ripple 0.5s linear;
							pointer-events: none;
						}
						
						@keyframes ripple {
							to {
								transform: scale(2);
								opacity: 0;
							}
						}
					`;
					document.head.appendChild(style);
				});
			</script>
		</div>
		<header class="header">
			<div class="top-bar">
				<div class="container">
					<div class="row justify-content-between align-items-center">
						<div class="col-auto">
							<div class="top-bar__item"><i class="fas fa-phone-square"></i> Phone: 755 302 8549 </div>
							<div class="top-bar__item"><i class="fas fa-envelope-square"></i> Email: support@example.com
							</div>
						</div>
						<div class="col-auto">
							<ul class="header-soc list-unstyled">
								<li class="header-soc__item"><a class="header-soc__link" href="#" target="_blank"><i
											class="ic fab fa-twitter"></i></a></li>
								<li class="header-soc__item"><a class="header-soc__link" href="#" target="_blank"><i
											class="ic fab fa-behance"></i></a></li>
								<li class="header-soc__item"><a class="header-soc__link" href="#" target="_blank"><i
											class="ic fab fa-facebook-f"></i></a></li>
								<li class="header-soc__item"><a class="header-soc__link" href="#" target="_blank"><i
											class="ic fab fa-instagram"></i></a></li>
								<li class="header-soc__item"><a class="header-soc__link" href="#" target="_blank"><i
											class="ic fab fa-youtube"></i></a></li>
							</ul>
						</div>
					</div>
				</div>
			</div>
			<div class="header-main">
				<div class="container">
					<div class="row align-items-center justify-content-between">
						<div class="col-auto">
							<!-- Logo Section - Orient Yachting Header -->
							<div id="logo">
								<!-- 
								LOGO IMAGE - CUSTOMIZATION POINT
								- src: Change the path to your logo image
								- alt: Update the alt text for accessibility 
								- width/height: Adjust dimensions as needed
								
								Note: <?=SITE?> is a PHP variable that contains the base URL of the website
								This ensures paths work correctly regardless of installation directory
								-->
								<a href="<?=SITE?>index.php">
									<img 
										src="<?=SITE?>assets/img/logo-light.png" 
										alt="Orient Yachting" 
										width="200px;" 
										height="35"
										class="normal-logo img-fluid" 
									/>
								</a>
							</div>
						</div>
						<div class="col-auto d-xl-none">
							<!-- Mobile Trigger Start-->
							<button class="menu-mobile-button js-toggle-mobile-slidebar toggle-menu-button"><i
									class="toggle-menu-button-icon"><span></span><span></span><span></span><span></span><span></span><span></span></i></button>
							<!-- Mobile Trigger End-->
						</div>
						<div class="col-xl d-none d-xl-block">
							<nav class="navbar navbar-expand-lg justify-content-end" id="nav">
								<ul class="yamm main-menu navbar-nav">
									<li class="nav-item active"><a class="nav-link" href="<?=SITE?>index.php">Home</a> </li>
									<li class="nav-item"><a class="nav-link" href="<?=SITE?>index.php?sayfa=about">About Us</a> </li>

									<li class="nav-item dropdown"> <a class="nav-link dropdown-toggle" href="<?=SITE?>index.php?sayfa=yachts"> Yachts</a>
										<div class="dropdown-menu">
											<a class="dropdown-item" href="<?=SITE?>yacht-listing">All Yachts</a>
											<a class="dropdown-item" href="<?=SITE?>yacht-types">Yacht Types</a>
											<?php
											// Modified query to only show yacht categories that have active yachts
											$yachtCategories=$VT->VeriGetir("yacht_categories 
												INNER JOIN yacht_category_rel ON yacht_categories.ID = yacht_category_rel.category_id
												INNER JOIN yachts ON yacht_category_rel.yacht_id = yachts.id",
												"WHERE yacht_categories.durum=? AND yachts.is_active=?",
												array(1, 1),
												"GROUP BY yacht_categories.ID ORDER BY yacht_categories.sirano ASC");
											
											if($yachtCategories!=false)
											{
												for ($i=0; $i <count($yachtCategories) ; $i++) { 
													?>
												<a class="dropdown-item" href="<?=SITE?>kategori/<?=$yachtCategories[$i]["seflink"]?>"><?=stripslashes($yachtCategories[$i]["baslik"])?></a>
													<?php
												}
											}
											?>
											
											<div class="dropdown-divider"></div>
											<div class="dropdown-header">Yacht Types</div>
											<?php
											// Query to only show yacht types that have active yachts
											$yachtTypes=$VT->VeriGetir("yacht_types 
												INNER JOIN yachts ON yacht_types.id = yachts.type_id",
												"WHERE yacht_types.durum=? AND yachts.is_active=?",
												array(1, 1),
												"GROUP BY yacht_types.id ORDER BY yacht_types.sirano ASC");
											
											if($yachtTypes!=false)
											{
												for ($i=0; $i <count($yachtTypes) ; $i++) { 
													?>
												<a class="dropdown-item" href="<?=SITE?>yacht-type/<?=$yachtTypes[$i]["seflink"]?>"><?=stripslashes($yachtTypes[$i]["title"])?></a>
													<?php
												}
											}
											?>
										</div>
									</li>

									<li class="nav-item dropdown"> <a class="nav-link dropdown-toggle" href="<?=SITE?>index.php?sayfa=yachts"> Services</a>
										<div class="dropdown-menu">
										<?php
										$kurumsal=$VT->VeriGetir("services","WHERE durum=?",array(1),"ORDER BY sirano ASC");
										if($kurumsal!=false)
										{
											for ($i=0; $i <count($kurumsal) ; $i++) { 
												?>
											<a class="dropdown-item" href="<?=SITE?>services/<?=$kurumsal[$i]["seflink"]?>"><?=stripslashes($kurumsal[$i]["baslik"])?></a>
													<?php
												}
											}
											?>
										</div>
									</li>
									
									<li class="nav-item"><a class="nav-link" href="<?=SITE?>index.php?sayfa=about">Yacht Management</a> </li>
									<li class="nav-item dropdown"><a class="nav-link dropdown-toggle" href="<?=SITE?>index.php?sayfa=blog">News</a>
										<div class="dropdown-menu">
											<a class="dropdown-item" href="<?=SITE?>index.php?sayfa=blog">Blog</a>
											<a class="dropdown-item" href="<?=SITE?>index.php?sayfa=events">Events</a>
										</div>
									</li>
									<li class="nav-item"><a class="nav-link" href="<?=SITE?>index.php?sayfa=contact">Contact</a></li>
								</ul> <span class="header-main__link btn_header_search"><i
										class="ic icon-magnifier"></i></span>
								<button class="header-main__btn btn btn-secondary">Book Now</button>
							</nav>
						</div>
					</div>
				</div>
			</div>
		</header>

    <link rel="stylesheet" href="<?= SITE ?>assets/css/custom.css">
    
    <!-- Preloader Style -->
    <link rel="stylesheet" href="<?= SITE ?>assets/css/preloader.css">
    
    <!-- Gallery Styles -->
    <link rel="stylesheet" href="<?= SITE ?>assets/css/gallery-styles.css">
    
    <!-- AOS Animation Library -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <!-- Premium Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Montserrat:wght@300;400;500;600;700&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    
    <!-- Custom Logo Styles -->
    <style>
    .logo-container {
        padding: 10px 0;
        margin-bottom: 5px;
    }
    
    .navbar-brand {
        display: inline-block;
        padding: 5px;
        margin: 0 auto;
        text-align: center;
        background-color: transparent;
        transition: all 0.3s ease;
    }
    
    .navbar-brand:hover {
        transform: translateY(-3px);
    }
    
    .navbar-brand img.normal-logo {
        max-width: 120px;
        height: auto;
    }
    
    @media (max-width: 768px) {
        .navbar-brand img.normal-logo {
            max-width: 100px;
        }
    }
    
    /* Dark mode styles */
    .l-theme_dark .navbar-brand img.normal-logo {
        filter: brightness(1.2);
    }
    </style>
</body>
</html>