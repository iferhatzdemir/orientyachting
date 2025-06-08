/**
 * Premium Luxury Header Effects
 * Advanced animations and transitions for a high-end experience
 */

(function($) {
    "use strict";
    
    // Variables for scroll tracking
    let lastScrollTop = 0;
    let delta = 5;
    let headerHeight = $('.luxury-header').outerHeight();
    let parallaxSpeed = 0.3;
    
    // Function to handle header state on scroll with smooth animations
    function handleHeaderScroll() {
        // Current scroll position
        let scrollTop = $(window).scrollTop();
        
        // Check if scrolled more than delta
        if (Math.abs(lastScrollTop - scrollTop) <= delta) {
            return;
        }
        
        // Add/remove scrolling class based on scroll position with smooth transition
        if (scrollTop > headerHeight) {
            if (!$('.luxury-header').hasClass('navbar-scrolling')) {
                $('.luxury-header').addClass('navbar-scrolling');
                setTimeout(function() {
                    $('.luxury-header').addClass('animate-complete');
                }, 600);
            }
        } else {
            $('.luxury-header').removeClass('navbar-scrolling animate-complete');
        }
        
        // Update last scroll position
        lastScrollTop = scrollTop;
        
        // Apply advanced parallax effect to header background
        if ($('.luxury-header').css('background-image') !== 'none') {
            $('.luxury-header').css({
                'background-position': 'center ' + -(scrollTop * parallaxSpeed) + 'px',
                'opacity': 1 - (scrollTop * 0.0015) // Subtle fade effect while scrolling
            });
        }
        
        // Add subtle scale effect to logo
        if (scrollTop > headerHeight * 2) {
            $('.navbar-brand img').css({
                'transform': 'scale(' + (0.9 - Math.min(scrollTop * 0.0001, 0.1)) + ')'
            });
        } else {
            $('.navbar-brand img').css({
                'transform': 'scale(1)'
            });
        }
    }
    
    // Initialize dropdown menus with enhanced animations
    function initDropdowns() {
        // Mobile dropdown toggle with smooth animations
        $('.mobile-nav .dropdown-toggle').on('click', function(e) {
            e.preventDefault();
            let $this = $(this);
            let $dropdownMenu = $this.next('.dropdown-menu');
            
            // Close other open dropdowns
            if (!$this.hasClass('opened')) {
                $('.mobile-nav .dropdown-toggle.opened').removeClass('opened').next('.dropdown-menu').slideUp(300);
            }
            
            // Toggle current dropdown
            $dropdownMenu.slideToggle(300);
            $this.toggleClass('opened');
            
            if ($this.hasClass('opened')) {
                $this.find('i').css('transform', 'rotate(180deg)');
            } else {
                $this.find('i').css('transform', 'rotate(0deg)');
            }
        });
        
        // Desktop dropdown hover effects with timing
        $('.luxury-navigation .has-dropdown').each(function() {
            let $this = $(this);
            let timer;
            
            $this.hover(
                function() {
                    clearTimeout(timer);
                    $('.luxury-navigation .has-dropdown').removeClass('hovered');
                    $this.addClass('hovered');
                    
                    // Add individual animation to each dropdown item
                    $this.find('.dropdown-menu li').each(function(index) {
                        let $item = $(this);
                        setTimeout(function() {
                            $item.addClass('animated');
                        }, 50 * index);
                    });
                },
                function() {
                    timer = setTimeout(function() {
                        $this.removeClass('hovered');
                        $this.find('.dropdown-menu li').removeClass('animated');
                    }, 200);
                }
            );
        });
    }
    
    // Handle search toggle with elegant animations
    function initSearch() {
        $('.search-toggle').on('click', function() {
            $('.header-search').addClass('open');
            setTimeout(function() {
                $('.search-global__input').focus();
            }, 300);
        });
        
        $('.search-close').on('click', function() {
            $('.header-search').removeClass('open');
        });
        
        // Close search on escape key
        $(document).keyup(function(e) {
            if (e.key === "Escape" && $('.header-search').hasClass('open')) {
                $('.header-search').removeClass('open');
            }
        });
    }
    
    // Add dynamic background image with parallax effects
    function setHeaderBackground() {
        // Detect if on homepage or inner page to apply different styles
        let isHomePage = $('body').hasClass('home') || window.location.pathname === '/' || window.location.pathname.includes('index');
        
        // Get current page's hero image if exists
        const heroImage = $('.header-slider, .page-banner, .b-main-slider').css('background-image');
        
        if (heroImage && heroImage !== 'none') {
            // Extract URL from background-image
            const imageUrl = heroImage.replace(/^url\(['"](.+)['"]\)$/, '$1');
            
            // Apply the same background to the header with premium parallax settings
            $('.luxury-header').css({
                'background-image': 'url(' + imageUrl + ')',
                'background-size': 'cover',
                'background-position': 'center top',
                'background-repeat': 'no-repeat'
            });
            
            // Add film grain texture overlay for luxury feel
            if (!$('.luxury-header').find('.grain-overlay').length) {
                $('.luxury-header').append('<div class="grain-overlay"></div>');
            }
        } else {
            // Default premium gradient if no hero image
            if (isHomePage) {
                $('.luxury-header').css({
                    'background-image': 'linear-gradient(135deg, rgba(0,0,0,0.9) 0%, rgba(16,16,24,0.85) 100%)'
                });
            } else {
                $('.luxury-header').css({
                    'background': 'rgba(16,16,24,0.9)',
                    'backdrop-filter': 'blur(15px) saturate(180%)'
                });
            }
        }
    }
    
    // Enhanced mobile menu interactions
    function initMobileMenu() {
        $('.luxury-menu-toggle').on('click', function() {
            $(this).toggleClass('is-active');
            
            if ($(this).hasClass('is-active')) {
                $('body').addClass('mobile-menu-open');
            } else {
                $('body').removeClass('mobile-menu-open');
            }
        });
        
        $('.mobile-menu-close').on('click', function() {
            $('[data-canvas="container"]').removeClass('js-open');
            $('.luxury-menu-toggle').removeClass('is-active');
            $('body').removeClass('mobile-menu-open');
        });
        
        // Animate mobile menu items
        if ($('.mobile-nav .nav-item').length) {
            $('.mobile-nav .nav-item').each(function(index) {
                let $this = $(this);
                $this.css({
                    'transition-delay': (index * 0.05) + 's'
                });
            });
        }
    }
    
    // Add scroll indicator
    function addScrollIndicator() {
        if ($('.luxury-header').length && !$('.scroll-indicator').length) {
            $('body').append('<div class="scroll-indicator"><span></span></div>');
            
            $(window).on('scroll', function() {
                let scrollTop = $(window).scrollTop();
                let docHeight = $(document).height();
                let winHeight = $(window).height();
                let scrollPercent = (scrollTop) / (docHeight - winHeight);
                let scrollPercentRounded = Math.round(scrollPercent * 100);
                
                $('.scroll-indicator span').css('width', scrollPercentRounded + '%');
                
                if (scrollTop > 100) {
                    $('.scroll-indicator').addClass('visible');
                } else {
                    $('.scroll-indicator').removeClass('visible');
                }
            });
        }
    }
    
    // Add animated hover effects to buttons
    function enhanceButtons() {
        $('.book-btn').on('mouseenter', function() {
            $(this).addClass('btn-hover');
        }).on('mouseleave', function() {
            let $this = $(this);
            $this.addClass('btn-leave');
            setTimeout(function() {
                $this.removeClass('btn-hover btn-leave');
            }, 300);
        });
    }
    
    // Document ready function with preloading animation
    $(document).ready(function() {
        // Add luxury preloader
        $('body').addClass('page-loaded');
        
        // Initialize all functions with slight delays for smoother loading
        setTimeout(function() {
            setHeaderBackground();
        }, 100);
        
        setTimeout(function() {
            initDropdowns();
            initSearch();
            initMobileMenu();
            enhanceButtons();
            addScrollIndicator();
        }, 300);
        
        // Initial check for header state
        handleHeaderScroll();
        
        // Scroll event listener with debounce for performance
        let scrollTimer;
        $(window).on('scroll', function() {
            if (scrollTimer) {
                clearTimeout(scrollTimer);
            }
            
            scrollTimer = setTimeout(function() {
                handleHeaderScroll();
            }, 10);
        });
        
        // Window resize event with debounce
        let resizeTimer;
        $(window).on('resize', function() {
            if (resizeTimer) {
                clearTimeout(resizeTimer);
            }
            
            resizeTimer = setTimeout(function() {
                headerHeight = $('.luxury-header').outerHeight();
                handleHeaderScroll();
            }, 250);
        });
        
        // Smooth page transitions
        $('a:not([href^="#"]):not([target="_blank"])').on('click', function() {
            let href = $(this).attr('href');
            if (href && href.indexOf('javascript:') !== 0 && href !== '#') {
                $('body').addClass('page-transitioning');
                setTimeout(function() {
                    window.location.href = href;
                }, 300);
                return false;
            }
        });
    });
    
})(jQuery); 