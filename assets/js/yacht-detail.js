/**
 * ORIENT YACHTING - Luxury Yacht Detail Page
 * Premium JavaScript by Senior Front End Developer
 * v1.0 - Ultra-Modern Luxury Interactions
 * ================================================== 
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize all components
    initLightbox();
    initVideoPlayer();
    initScrollAnimations();
    initBookingForm();
    initStickyNav();
    initMobileBookingButton();
});

/**
 * Lightbox Gallery Implementation
 */
function initLightbox() {
    // Get all gallery items
    const galleryItems = document.querySelectorAll('.gallery-item:not(.video-item)');
    const lightboxOverlay = document.querySelector('.lightbox-overlay');
    const lightboxImage = document.querySelector('.lightbox-image');
    const lightboxVideoContainer = document.querySelector('.lightbox-video-container');
    const lightboxVideo = document.querySelector('.lightbox-video');
    const lightboxClose = document.querySelector('.lightbox-close');
    const lightboxPrev = document.querySelector('.lightbox-prev');
    const lightboxNext = document.querySelector('.lightbox-next');
    
    let currentIndex = 0;
    const images = [];
    
    // Collect all gallery images
    galleryItems.forEach((item, index) => {
        const img = item.querySelector('img');
        if (img) {
            const imgSrc = img.getAttribute('src');
            images.push(imgSrc);
            
            // Add click event to open lightbox
            item.addEventListener('click', function(e) {
                e.preventDefault();
                openLightbox(index);
            });
        }
    });
    
    // Open lightbox function
    function openLightbox(index) {
        if (images.length === 0) return;
        
        currentIndex = index;
        
        // Show image content, hide video content
        lightboxImage.style.display = 'block';
        lightboxVideoContainer.style.display = 'none';
        
        // Set the image source
        lightboxImage.setAttribute('src', images[currentIndex]);
        lightboxOverlay.style.display = 'flex';
        document.body.style.overflow = 'hidden'; // Prevent scrolling
        
        // Animation for smooth appearance
        setTimeout(() => {
            lightboxOverlay.style.opacity = '1';
        }, 10);
    }
    
    // Close lightbox function
    function closeLightbox() {
        lightboxOverlay.style.opacity = '0';
        setTimeout(() => {
            lightboxOverlay.style.display = 'none';
            document.body.style.overflow = ''; // Enable scrolling
            
            // If video is playing, pause it
            if (lightboxVideo && !lightboxVideo.paused) {
                lightboxVideo.pause();
            }
        }, 300);
    }
    
    // Navigate to previous image
    function prevImage() {
        currentIndex = (currentIndex - 1 + images.length) % images.length;
        lightboxImage.style.opacity = '0';
        setTimeout(() => {
            lightboxImage.setAttribute('src', images[currentIndex]);
            lightboxImage.style.opacity = '1';
        }, 200);
    }
    
    // Navigate to next image
    function nextImage() {
        currentIndex = (currentIndex + 1) % images.length;
        lightboxImage.style.opacity = '0';
        setTimeout(() => {
            lightboxImage.setAttribute('src', images[currentIndex]);
            lightboxImage.style.opacity = '1';
        }, 200);
    }
    
    // Event listeners for lightbox controls
    if (lightboxClose) {
        lightboxClose.addEventListener('click', closeLightbox);
    }
    
    if (lightboxPrev) {
        lightboxPrev.addEventListener('click', prevImage);
    }
    
    if (lightboxNext) {
        lightboxNext.addEventListener('click', nextImage);
    }
    
    // Close lightbox when clicking outside of image
    if (lightboxOverlay) {
        lightboxOverlay.addEventListener('click', function(e) {
            if (e.target === lightboxOverlay) {
                closeLightbox();
            }
        });
    }
    
    // Keyboard navigation
    document.addEventListener('keydown', function(e) {
        if (lightboxOverlay.style.display === 'flex') {
            if (e.key === 'Escape') {
                closeLightbox();
            } else if (e.key === 'ArrowLeft') {
                prevImage();
            } else if (e.key === 'ArrowRight') {
                nextImage();
            }
        }
    });
}

/**
 * Video Player Implementation
 */
function initVideoPlayer() {
    // Get all video items
    const videoItems = document.querySelectorAll('.video-item');
    const videoModal = document.querySelector('.video-modal');
    const videoPlayer = document.querySelector('.video-player');
    const videoModalClose = document.querySelector('.video-modal-close');
    
    console.log('Video items found:', videoItems.length);
    
    if (!videoItems.length) return;
    
    // Add click event to all video thumbnails
    videoItems.forEach((item, index) => {
        console.log('Setting up video item:', index);
        const videoThumbnail = item.querySelector('.video-thumbnail');
        if (videoThumbnail) {
            const videoSrc = videoThumbnail.getAttribute('data-video');
            console.log('Video source:', videoSrc);
            
            // Add click event to open video modal
            videoThumbnail.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                console.log('Video thumbnail clicked, opening video:', videoSrc);
                openVideoModal(videoSrc);
            });
            
            // Make sure the entire video item and children are clickable
            item.addEventListener('click', function(e) {
                e.preventDefault();
                console.log('Video item clicked, opening video:', videoSrc);
                openVideoModal(videoSrc);
            });
            
            // Make sure play button is clickable
            const playButton = item.querySelector('.play-button');
            if (playButton) {
                playButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    console.log('Play button clicked, opening video:', videoSrc);
                    openVideoModal(videoSrc);
                });
            }
        }
    });
    
    // Open video modal function
    function openVideoModal(videoSrc) {
        if (!videoSrc) {
            console.error('No video source provided');
            return;
        }
        
        console.log('Opening video modal with source:', videoSrc);
        
        // Set video source and type
        const videoSourceElement = videoPlayer.querySelector('source');
        if (videoSourceElement) {
            videoSourceElement.setAttribute('src', videoSrc);
            
            // Determine video type from source URL
            const fileExtension = videoSrc.split('.').pop().toLowerCase();
            let videoType = 'video/mp4'; // Default
            
            if (fileExtension === 'webm') {
                videoType = 'video/webm';
            } else if (fileExtension === 'ogg') {
                videoType = 'video/ogg';
            } else if (fileExtension === 'mov') {
                videoType = 'video/quicktime';
            }
            
            console.log('Setting video type:', videoType);
            videoSourceElement.setAttribute('type', videoType);
            videoPlayer.load(); // Important: reload the video with new source
        } else {
            console.error('Video source element not found');
        }
        
        // Display the modal
        videoModal.style.display = 'flex';
        document.body.style.overflow = 'hidden'; // Prevent scrolling
        
        // Start playing after a short delay to ensure the video is loaded
        setTimeout(() => {
            // Start playing
            const playPromise = videoPlayer.play();
            
            if (playPromise !== undefined) {
                playPromise.then(() => {
                    console.log('Video playback started successfully');
                }).catch(error => {
                    console.error('Error playing video:', error);
                    // Auto-play might be blocked, show play button or message
                    alert('Video oynatma başlatılamadı. Lütfen video üzerine tıklayarak oynatın.');
                });
            }
        }, 100);
        
        // Animation for smooth appearance
        setTimeout(() => {
            videoModal.style.opacity = '1';
        }, 10);
    }
    
    // Close video modal function
    function closeVideoModal() {
        console.log('Closing video modal');
        videoModal.style.opacity = '0';
        setTimeout(() => {
            videoModal.style.display = 'none';
            document.body.style.overflow = ''; // Enable scrolling
            
            // Pause video when closing
            if (videoPlayer && !videoPlayer.paused) {
                videoPlayer.pause();
            }
            
            // Clear video source
            const videoSourceElement = videoPlayer.querySelector('source');
            if (videoSourceElement) {
                videoSourceElement.setAttribute('src', '');
                videoPlayer.load();
            }
        }, 300);
    }
    
    // Event listeners for video modal controls
    if (videoModalClose) {
        videoModalClose.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            closeVideoModal();
        });
    } else {
        console.error('Video modal close button not found');
    }
    
    // Close video modal when clicking outside of video
    if (videoModal) {
        videoModal.addEventListener('click', function(e) {
            if (e.target === videoModal) {
                closeVideoModal();
            }
        });
    } else {
        console.error('Video modal element not found');
    }
    
    // Video element itself shouldn't close the modal when clicked
    if (videoPlayer) {
        videoPlayer.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    }
    
    // Keyboard navigation for video modal
    document.addEventListener('keydown', function(e) {
        if (videoModal && videoModal.style.display === 'flex') {
            if (e.key === 'Escape') {
                closeVideoModal();
            }
        }
    });
}

/**
 * Scroll Animations using Intersection Observer
 */
function initScrollAnimations() {
    // Check if IntersectionObserver is supported
    if ('IntersectionObserver' in window) {
        const fadeElements = document.querySelectorAll('.fade-in');
        
        const appearOptions = {
            threshold: 0.15,
            rootMargin: '0px 0px -50px 0px'
        };
        
        const appearOnScroll = new IntersectionObserver(function(entries, appearOnScroll) {
            entries.forEach(entry => {
                if (!entry.isIntersecting) return;
                entry.target.classList.add('visible');
                appearOnScroll.unobserve(entry.target);
            });
        }, appearOptions);
        
        fadeElements.forEach(element => {
            appearOnScroll.observe(element);
        });
    } else {
        // Fallback for browsers that don't support IntersectionObserver
        const fadeElements = document.querySelectorAll('.fade-in');
        fadeElements.forEach(element => {
            element.classList.add('visible');
        });
    }
}

/**
 * Booking Form Validation & Interaction
 */
function initBookingForm() {
    const bookingForm = document.getElementById('booking-form');
    const bookingResponse = document.getElementById('booking-response');
    const bookingSubmit = document.getElementById('booking-submit');
    
    if (bookingForm && bookingResponse) {
        bookingForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Show loading state
            if (bookingSubmit) {
                bookingSubmit.disabled = true;
                bookingSubmit.innerHTML = 'Processing...';
            }
            
            // Reset response
            bookingResponse.style.display = 'none';
            bookingResponse.innerHTML = '';
            bookingResponse.className = 'alert';
            
            // Collect form data
            const formData = new FormData(bookingForm);
            
            // Basic validation
            const startDate = new Date(formData.get('start_date'));
            const endDate = new Date(formData.get('end_date'));
            
            if (endDate <= startDate) {
                showBookingResponse('error', 'End date must be after start date.');
                return;
            }
            
            // Send the AJAX request
            fetch(SITE + 'index.php?sayfa=rezervasyon-islem', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    // Show success message
                    showBookingResponse('success', data.message);
                    
                    // Clear form
                    bookingForm.reset();
                    
                    // Scroll to response
                    bookingResponse.scrollIntoView({ behavior: 'smooth' });
                    
                    // Show confirmation in modal
                    showBookingConfirmation(data);
                } else {
                    // Show error message
                    showBookingResponse('error', data.message || 'An error occurred. Please try again.');
                }
            })
            .catch(error => {
                console.error('Booking error:', error);
                showBookingResponse('error', 'Network error. Please try again.');
            })
            .finally(() => {
                // Reset button
                if (bookingSubmit) {
                    bookingSubmit.disabled = false;
                    bookingSubmit.innerHTML = 'Request Booking';
                }
            });
        });
    }
    
    // Display booking response message
    function showBookingResponse(type, message) {
        if (bookingResponse) {
            bookingResponse.innerHTML = message;
            bookingResponse.className = type === 'success' ? 'alert alert-success' : 'alert alert-danger';
            bookingResponse.style.display = 'block';
        }
    }
    
    // Show booking confirmation modal
    function showBookingConfirmation(data) {
        const htmlContent = `
            <div class="booking-confirmation">
                <div class="confirmation-header">
                    <h3>Booking Request Confirmed</h3>
                    <p>Your booking request has been successfully submitted.</p>
                </div>
                <div class="confirmation-details">
                    <p><strong>Confirmation Code:</strong> ${data.confirmation_code}</p>
                    <p>We've sent a confirmation email to your address with all the details.</p>
                    <p>Our team will contact you shortly to finalize your booking.</p>
                </div>
                <div class="confirmation-footer">
                    <button class="btn-booking close-confirmation">Close</button>
                </div>
            </div>
        `;
        
        // Create modal
        const modal = document.createElement('div');
        modal.className = 'custom-modal';
        modal.innerHTML = `
            <div class="custom-modal-overlay"></div>
            <div class="custom-modal-container">
                ${htmlContent}
            </div>
        `;
        
        // Add to document
        document.body.appendChild(modal);
        
        // Add close button functionality
        const closeBtn = modal.querySelector('.close-confirmation');
        const overlay = modal.querySelector('.custom-modal-overlay');
        
        if (closeBtn) {
            closeBtn.addEventListener('click', () => {
                document.body.removeChild(modal);
            });
        }
        
        if (overlay) {
            overlay.addEventListener('click', () => {
                document.body.removeChild(modal);
            });
        }
    }
    
    // Date validation
    const startDateInput = document.getElementById('start-date');
    const endDateInput = document.getElementById('end-date');
    
    if (startDateInput && endDateInput) {
        startDateInput.addEventListener('change', function() {
            const minEndDate = new Date(this.value);
            minEndDate.setDate(minEndDate.getDate() + 1);
            
            const formattedDate = minEndDate.toISOString().split('T')[0];
            endDateInput.min = formattedDate;
            
            // If current end date is before new min date, update it
            if (endDateInput.value && new Date(endDateInput.value) < minEndDate) {
                endDateInput.value = formattedDate;
            }
        });
    }
}

/**
 * Sticky Navigation on Scroll
 */
function initStickyNav() {
    const header = document.querySelector('.yacht-header');
    const navHeight = 80; // Approximate height of nav when sticky
    
    if (!header) return;
    
    window.addEventListener('scroll', function() {
        if (window.scrollY > 100) {
            header.classList.add('sticky');
        } else {
            header.classList.remove('sticky');
        }
    });
}

/**
 * Mobile Booking Button
 */
function initMobileBookingButton() {
    const floatingBtn = document.querySelector('.floating-booking-btn');
    const bookingSection = document.querySelector('#booking-section');
    
    if (!floatingBtn || !bookingSection) return;
    
    floatingBtn.addEventListener('click', function() {
        bookingSection.scrollIntoView({ behavior: 'smooth' });
    });
    
    // Hide button when near booking section
    window.addEventListener('scroll', function() {
        const bookingSectionTop = bookingSection.offsetTop;
        const scrollPosition = window.scrollY + window.innerHeight;
        
        if (scrollPosition > bookingSectionTop - 200) {
            floatingBtn.style.opacity = '0';
            floatingBtn.style.pointerEvents = 'none';
        } else {
            floatingBtn.style.opacity = '1';
            floatingBtn.style.pointerEvents = 'auto';
        }
    });
} 