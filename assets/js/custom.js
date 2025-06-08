/*
| ----------------------------------------------------------------------------------
| TABLE OF CONTENT
| ----------------------------------------------------------------------------------
-Preloader
-Scroll Animation
-Scale images
-Chars Start
-Loader blocks
-Zoom Images
-Select customization
-Main slider
-Sliders
-Sliders with thumbnails
-Slider numbers
-Video player
-View catalog
*/

// Use IIFE to isolate scope and avoid conflicts
(function($) {
    "use strict";
    
    $(document).ready(function() {

        /////////////////////////////////////////////////////////////////
        // Preloader
        /////////////////////////////////////////////////////////////////


        /////////////////////////////////////
        //  Scroll Animation
        /////////////////////////////////////

        if ($('.scrollreveal').length) {
            window.sr = ScrollReveal({
                reset:true,
                duration: 1000,
                delay: 200
            });

            sr.reveal('.scrollreveal');
        }


        /////////////////////////////////////////////////////////////////
        // Scale images
        /////////////////////////////////////////////////////////////////

        if ($('.img-scale').length) {
            $(function () { objectFitImages('.img-scale') });
        }


        /////////////////////////////////////
        //  Chars Start
        /////////////////////////////////////


        if ($('body').length) {
            $(window).on('scroll', function() {
                var winH = $(window).scrollTop();

                $('.b-progress-list').waypoint(function() {
                    $('.js-chart').each(function() {
                        CharsStart();
                    });
                }, {
                    offset: '80%'
                });
            });
        }


        function CharsStart() {

            $('.js-chart').easyPieChart({
                barColor: false,
                trackColor: false,
                scaleColor: false,
                scaleLength: false,
                lineCap: false,
                lineWidth: false,
                size: false,
                animate: 5000,

                onStep: function(from, to, percent) {
                    $(this.el).find('.js-percent').text(Math.round(percent));
                }
            });
        }

        
        
        



        /////////////////////////////////////
        //  Loader blocks
        /////////////////////////////////////

        $( ".js-scroll-next" ).on( "click", function() {

            var hiddenContent =  $( ".js-scroll-next + .js-scroll-content") ;

            $(".js-scroll-next").hide() ;
            hiddenContent.show() ;
            hiddenContent.addClass("animated");
            hiddenContent.addClass("animation-done");
            hiddenContent.addClass("bounceInUp");

        });



        /////////////////////////////////////
        //  Zoom Images
        /////////////////////////////////////

        if ($('.js-zoom-gallery').length) {
            $('.js-zoom-gallery').each(function() { // the containers for all your galleries
                $(this).magnificPopup({
                    delegate: '.js-zoom-gallery__item', // the selector for gallery item
                    type: 'image',
                    gallery: {
                      enabled:true
                    },
          mainClass: 'mfp-with-zoom', // this class is for CSS animation below

          zoom: {
            enabled: true, // By default it's false, so don't forget to enable it

            duration: 300, // duration of the effect, in milliseconds
            easing: 'ease-in-out', // CSS transition easing function

            // The "opener" function should return the element from which popup will be zoomed in
            // and to which popup will be scaled down
            // By defailt it looks for an image tag:
            opener: function(openerElement) {
              // openerElement is the element on which popup was initialized, in this case its <a> tag
              // you don't need to add "opener" option if this code matches your needs, it's defailt one.
              return openerElement.is('img') ? openerElement : openerElement.find('img');
            }
          }
            });
        });
      }


      if ($('.js-zoom-images').length) {
          $('.js-zoom-images').magnificPopup({
            type: 'image',
            mainClass: 'mfp-with-zoom', // this class is for CSS animation below

            zoom: {
              enabled: true, // By default it's false, so don't forget to enable it

              duration: 300, // duration of the effect, in milliseconds
              easing: 'ease-in-out', // CSS transition easing function

              // The "opener" function should return the element from which popup will be zoomed in
              // and to which popup will be scaled down
              // By defailt it looks for an image tag:
              opener: function(openerElement) {
                // openerElement is the element on which popup was initialized, in this case its <a> tag
                // you don't need to add "opener" option if this code matches your needs, it's defailt one.
                return openerElement.is('img') ? openerElement : openerElement.find('img');
              }
            }
          });

        }


      if ($('.popup-youtube').length) {
        $('.popup-youtube').magnificPopup({
            disableOn: 700,
            type: 'iframe',
            mainClass: 'mfp-fade',
            removalDelay: 160,
            preloader: false,

            fixedContentPos: false
          });
      }



        /////////////////////////////////////
        // Select customization
        /////////////////////////////////////

        if ($('.selectpicker').length) {
          $('.selectpicker').selectpicker();
        }



        //////////////////////////////////////////////
        // Main slider (disabled - using custom fullscreen slider)
        /////////////////////////////////////////////

        // Main slider initialization is now handled by our custom fullscreen slider


        //////////////////////////////////////////////
        // Sliders
        /////////////////////////////////////////////

        if ($('.js-slider').length) {
          $('.js-slider').slick();
        }


        //////////////////////////////////////////////
        // Sliders with thumbnails
        /////////////////////////////////////////////

        if ($('.js-slider-for').length) {
          $('.js-slider-for').slick({
            arrows: true,
            fade: true,
            asNavFor: '.js-slider-nav'
          });
          $('.js-slider-nav').slick({
            slidesToShow: 5,
            slidesToScroll: 1,
            asNavFor: '.js-slider-for',
            focusOnSelect: true
          });
        }

        //////////////////////////////////////////////////////////////////
        // Slider numbers
        //////////////////////////////////////////////////////////////////


        if ($('#filterPrice').length) {

          var keypressSlider = document.getElementById('filterPrice');
            var input0 = document.getElementById('input-with-keypress-0');
            var input1 = document.getElementById('input-with-keypress-1');
            var inputs = [input0, input1];

            noUiSlider.create(keypressSlider, {
                start: [5000, 35000],
                connect: true,
                step: 100,
                format: wNumb({
                  decimals: 0,
                  prefix: '$',
                  thousand: ','
                }),
                range: {
                    'min': 1000,
                    'max': 50000
                }
            });

            keypressSlider.noUiSlider.on('update', function (values, handle) {
                inputs[handle].value = values[handle];
            });
        }


        if ($('#sliderRange').length) {
          var keypressSliderRange = document.getElementById('sliderRange');
            var inputRange = document.getElementById('input-range');
            var inputsRange = [inputRange];

          noUiSlider.create(keypressSliderRange, {
                start: 100,
                connect: true,
                step: 10,
                format: wNumb({
                  decimals: 0,
                  prefix: 'within ',
                  suffix: 'km',
                  thousand: ','
                }),
                range: {
                    'min': 0,
                    'max': 200
                }
          });


            keypressSliderRange.noUiSlider.on('update', function (values, handle) {
                inputsRange[handle].value = values[handle];
            });
          }


        /////////////////////////////////////
        //  Video player
        /////////////////////////////////////


        if ($('.player').length) {
          $(".player").flowplayer();
        }


        //////////////////////////////////////////////
        // View catalog
        /////////////////////////////////////////////


        $('.btns-switch__item').on( 'click', function() {
          $('.btns-switch').find('.active').removeClass('active');
          $( this ).addClass('active');
        });

        $('.js-view-th').on( 'click', function() {
          $('.b-goods-group > .col-12').removeClass('col-12').addClass('col-lg-4 col-md-6');
          $('.b-goods').removeClass('b-goods_list');
          $('.b-filter-goods').addClass('b-filter-goods_brd');
        });

        $('.js-view-list').on( 'click', function() {
          $('.b-goods-group > .col-lg-4').addClass('col-12').removeClass('col-lg-4 col-md-6');
          $('.b-goods').addClass('b-goods_list');
          $('.b-filter-goods').removeClass('b-filter-goods_brd');
        });


        $('.flip-btn').on( 'click', function() {
          $( this ).parent().toggleClass('flip-active');
        });


        $('.flip-btn-hide').on( 'click', function() {
          $(this).parents('.b-goods').removeClass('flip-active');
        });


        if ($(window).width() < 768) {
          $('.b-goods-group > .col-12').removeClass('col-12').addClass('col-lg-4 col-md-6');
          $('.b-goods').removeClass('b-goods_list');
        }

        //////////////////////////////////////////////////////////////////
        // Luxury Fullscreen Hero Slider
        //////////////////////////////////////////////////////////////////

        if ($('.fullscreen-hero-slider').length) {
            var sliderInterval;
            var currentSlide = 0;
            var totalSlides = $('.slider-slide').length;
            
            // Function to set the active slide
            function setActiveSlide(index) {
                // Remove active class from current slide
                $('.slider-slide.active').removeClass('active');
                $('.slider-dot.active').removeClass('active');
                
                // Set new active slide
                $('.slider-slide[data-slide-index="' + index + '"]').addClass('active');
                $('.slider-dot[data-slide="' + index + '"]').addClass('active');
                
                // Update current slide
                currentSlide = index;
            }
            
            // Function to go to the next slide
            function nextSlide() {
                var newIndex = (currentSlide + 1) % totalSlides;
                setActiveSlide(newIndex);
            }
            
            // Function to go to the previous slide
            function prevSlide() {
                var newIndex = (currentSlide - 1 + totalSlides) % totalSlides;
                setActiveSlide(newIndex);
            }
            
            // Start automatic slide transition
            function startSlideInterval() {
                sliderInterval = setInterval(function() {
                    nextSlide();
                }, 6000); // 6 seconds interval
            }
            
            // Reset interval when manually changing slides
            function resetInterval() {
                clearInterval(sliderInterval);
                startSlideInterval();
            }
            
            // Initialize slider
            startSlideInterval();
            
            // Event listeners for navigation
            $('.slider-next').on('click', function(e) {
                e.preventDefault();
                nextSlide();
                resetInterval();
            });
            
            $('.slider-prev').on('click', function(e) {
                e.preventDefault();
                prevSlide();
                resetInterval();
            });
            
            $('.slider-dot').on('click', function(e) {
                e.preventDefault();
                var dotIndex = parseInt($(this).data('slide'));
                if (dotIndex !== currentSlide) {
                    setActiveSlide(dotIndex);
                    resetInterval();
                }
            });
        }

        // Luxury Yacht Slider Configuration
        if ($('.luxury-slider .js-slider-for').length) {
            $('.luxury-slider .js-slider-for').slick({
                slidesToShow: 1,
                slidesToScroll: 1,
                arrows: true,
                fade: true,
                asNavFor: '.js-slider-nav',
                autoplay: true,
                autoplaySpeed: 5000,
                speed: 800,
                infinite: true,
                lazyLoad: 'ondemand',
                prevArrow: '<button type="button" class="slick-prev"><i class="fa fa-angle-left"></i></button>',
                nextArrow: '<button type="button" class="slick-next"><i class="fa fa-angle-right"></i></button>'
            });
            
            $('.luxury-slider .js-slider-nav').slick({
                slidesToShow: 5,
                slidesToScroll: 1,
                asNavFor: '.js-slider-for',
                dots: false,
                arrows: false,
                centerMode: false,
                focusOnSelect: true,
                infinite: true,
                responsive: [
                    {
                        breakpoint: 1199,
                        settings: {
                            slidesToShow: 4
                        }
                    },
                    {
                        breakpoint: 991,
                        settings: {
                            slidesToShow: 3
                        }
                    },
                    {
                        breakpoint: 767,
                        settings: {
                            slidesToShow: 3
                        }
                    },
                    {
                        breakpoint: 575,
                        settings: {
                            slidesToShow: 2
                        }
                    }
                ]
            });
        }
        
        // Image error handling
        $('.luxury-slider img').on('error', function() {
            $(this).attr('src', site_url + 'assets/img/yacht-placeholder.jpg');
        });
        
        // Add fade in animation to slider images
        $('.luxury-slider img').each(function() {
            $(this).addClass('aos-animate');
        });

        /////////////////////////////////////
        // Yacht Detail Page Slider
        /////////////////////////////////////
        
        if ($('.owl-carousel.main').length) {
            $('.owl-carousel.main').owlCarousel({
                items: 1,
                loop: true,
                margin: 0,
                nav: false,
                dots: false,
                autoplay: false,
                smartSpeed: 500
            });
            
            $('.owl-carousel.thumbs').owlCarousel({
                items: 5,
                loop: false,
                margin: 10,
                nav: false,
                dots: false,
                autoplay: false,
                smartSpeed: 500
            });
            
            // Custom Navigation Events
            $('.right').click(function() {
                $('.owl-carousel.main').trigger('next.owl.carousel');
            });
            $('.left').click(function() {
                $('.owl-carousel.main').trigger('prev.owl.carousel');
            });
            
            $('.right-t').click(function() {
                $('.owl-carousel.thumbs').trigger('next.owl.carousel');
            });
            $('.left-t').click(function() {
                $('.owl-carousel.thumbs').trigger('prev.owl.carousel');
            });
            
            // Sync Main Carousel with Thumbnail Carousel
            $('.owl-carousel.thumbs .item').click(function() {
                var position = $(this).index();
                $('.owl-carousel.main').trigger('to.owl.carousel', [position, 300]);
                $('.owl-carousel.thumbs .item').removeClass('active');
                $(this).addClass('active');
            });
        }

        /////////////////////////////////////
        // Luxury Yacht Detail Slider
        /////////////////////////////////////
        
        if (document.querySelector('.luxury-yacht-slider')) {
            // Initialize slider
            const slides = document.querySelectorAll('.yacht-slide');
            const thumbnails = document.querySelectorAll('.yacht-thumb');
            const prevBtn = document.querySelector('.yacht-slider-prev');
            const nextBtn = document.querySelector('.yacht-slider-next');
            let currentSlide = 0;
            
            // Function to show a specific slide
            function showSlide(index) {
                // Remove active class from all slides
                slides.forEach(slide => {
                    slide.classList.remove('active');
                });
                
                // Remove active class from all thumbnails
                thumbnails.forEach(thumb => {
                    thumb.classList.remove('active');
                });
                
                // Add active class to current slide and thumbnail
                slides[index].classList.add('active');
                thumbnails[index].classList.add('active');
                
                // Update current slide index
                currentSlide = index;
            }
            
            // Initialize first slide
            showSlide(0);
            
            // Previous button click event
            prevBtn.addEventListener('click', function() {
                let newIndex = currentSlide - 1;
                if (newIndex < 0) {
                    newIndex = slides.length - 1; // Loop to last slide
                }
                showSlide(newIndex);
            });
            
            // Next button click event
            nextBtn.addEventListener('click', function() {
                let newIndex = currentSlide + 1;
                if (newIndex >= slides.length) {
                    newIndex = 0; // Loop to first slide
                }
                showSlide(newIndex);
            });
            
            // Thumbnail click events
            thumbnails.forEach((thumb, index) => {
                thumb.addEventListener('click', function() {
                    showSlide(index);
                });
            });
            
            // Auto slideshow
            let slideInterval = setInterval(function() {
                let newIndex = currentSlide + 1;
                if (newIndex >= slides.length) {
                    newIndex = 0;
                }
                showSlide(newIndex);
            }, 7000); // Change slide every 7 seconds
            
            // Pause slideshow on hover
            const sliderContainer = document.querySelector('.yacht-slider-container');
            sliderContainer.addEventListener('mouseenter', function() {
                clearInterval(slideInterval);
            });
            
            sliderContainer.addEventListener('mouseleave', function() {
                slideInterval = setInterval(function() {
                    let newIndex = currentSlide + 1;
                    if (newIndex >= slides.length) {
                        newIndex = 0;
                    }
                    showSlide(newIndex);
                }, 7000);
            });
            
            // Touch swipe support
            let touchStartX = 0;
            let touchEndX = 0;
            
            sliderContainer.addEventListener('touchstart', function(event) {
                touchStartX = event.changedTouches[0].screenX;
            }, false);
            
            sliderContainer.addEventListener('touchend', function(event) {
                touchEndX = event.changedTouches[0].screenX;
                handleSwipe();
            }, false);
            
            function handleSwipe() {
                if (touchEndX < touchStartX) {
                    // Swipe left - next slide
                    let newIndex = currentSlide + 1;
                    if (newIndex >= slides.length) {
                        newIndex = 0;
                    }
                    showSlide(newIndex);
                } else if (touchEndX > touchStartX) {
                    // Swipe right - previous slide
                    let newIndex = currentSlide - 1;
                    if (newIndex < 0) {
                        newIndex = slides.length - 1;
                    }
                    showSlide(newIndex);
                }
            }
            
            // Keyboard navigation
            document.addEventListener('keydown', function(event) {
                if (event.key === 'ArrowLeft') {
                    // Left arrow - previous slide
                    let newIndex = currentSlide - 1;
                    if (newIndex < 0) {
                        newIndex = slides.length - 1;
                    }
                    showSlide(newIndex);
                } else if (event.key === 'ArrowRight') {
                    // Right arrow - next slide
                    let newIndex = currentSlide + 1;
                    if (newIndex >= slides.length) {
                        newIndex = 0;
                    }
                    showSlide(newIndex);
                }
            });
        }

    }); // End document ready

})(jQuery); // Pass jQuery to IIFE 