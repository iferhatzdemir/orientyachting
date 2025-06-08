/**
 * Gallery Functionality for Orient Yachting
 * Handles lightbox, hover effects, and video playback
 */

document.addEventListener('DOMContentLoaded', function() {
    initGalleryVideoPlayer();
    initGalleryHoverEffects();
    initManualLightbox(); // Explicitly initialize the manual lightbox
    
    // Initialize AOS animations if available
    if (typeof AOS !== 'undefined') {
        AOS.refresh();
    }
});

/**
 * Initialize video player functionality for gallery thumbnails
 */
function initGalleryVideoPlayer() {
    // Select video thumbnails
    const videoThumbnails = document.querySelectorAll('.video-thumbnail-container');
    const videoModal = document.querySelector('.video-modal');
    const videoPlayer = document.querySelector('.video-player');
    const videoModalClose = document.querySelector('.video-modal-close');
    
    if (!videoThumbnails.length || !videoModal) return;
    
    // Add click event to all video thumbnails
    videoThumbnails.forEach(thumbnail => {
        const videoUrl = thumbnail.getAttribute('data-video');
        if (!videoUrl) return;
        
        // Add click event to entire thumbnail
        thumbnail.addEventListener('click', function(e) {
            e.preventDefault();
            openVideoModal(videoUrl);
        });
        
        // Add separate click event to play button for better UX
        const playButton = thumbnail.querySelector('.play-button');
        if (playButton) {
            playButton.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                openVideoModal(videoUrl);
            });
        }
    });
    
    // Video modal opening function
    function openVideoModal(videoUrl) {
        if (!videoUrl) return;
        
        // Set video source
        const videoSource = videoPlayer.querySelector('source');
        if (videoSource) {
            videoSource.setAttribute('src', videoUrl);
            
            // Determine video type based on extension
            const fileExt = videoUrl.split('.').pop().toLowerCase();
            let mimeType = 'video/mp4'; // Default
            
            if (fileExt === 'webm') mimeType = 'video/webm';
            else if (fileExt === 'ogg') mimeType = 'video/ogg';
            else if (fileExt === 'mov') mimeType = 'video/quicktime';
            
            videoSource.setAttribute('type', mimeType);
            videoPlayer.load();
        }
        
        // Show modal with fade-in effect
        videoModal.style.display = 'flex';
        document.body.style.overflow = 'hidden'; // Prevent page scrolling
        
        setTimeout(() => {
            videoModal.style.opacity = '1';
            // Try to autoplay (may be blocked by browser)
            const playPromise = videoPlayer.play();
            if (playPromise !== undefined) {
                playPromise.catch(error => {
                    console.log('Autoplay prevented. Click to play.');
                });
            }
        }, 50);
    }
    
    // Close video modal
    function closeVideoModal() {
        videoModal.style.opacity = '0';
        
        setTimeout(() => {
            videoModal.style.display = 'none';
            document.body.style.overflow = ''; // Restore page scrolling
            
            // Stop video
            videoPlayer.pause();
            const videoSource = videoPlayer.querySelector('source');
            if (videoSource) {
                videoSource.setAttribute('src', '');
                videoPlayer.load();
            }
        }, 300);
    }
    
    // Close modal events
    if (videoModalClose) {
        videoModalClose.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            closeVideoModal();
        });
    }
    
    // Close when clicking outside video
    videoModal.addEventListener('click', function(e) {
        if (e.target === videoModal) {
            closeVideoModal();
        }
    });
    
    // Prevent closing when clicking on video
    videoPlayer.addEventListener('click', function(e) {
        e.stopPropagation();
    });
    
    // Close with ESC key
    document.addEventListener('keydown', function(e) {
        if (videoModal.style.display === 'flex' && e.key === 'Escape') {
            closeVideoModal();
        }
    });
}

/**
 * Add hover effects to gallery images
 */
function initGalleryHoverEffects() {
    // Add overlays to gallery items that don't have them
    const galleryItems = document.querySelectorAll('.ui-gallery__img:not(.video-thumbnail-container)');
    
    galleryItems.forEach(item => {
        // Check if item already has overlay
        if (!item.querySelector('.gallery-item-overlay')) {
            // Create overlay template
            const overlay = document.createElement('div');
            overlay.className = 'gallery-item-overlay';
            
            // Add SVG frame
            overlay.innerHTML = `
                <svg class="gallery-frame" viewBox="0 0 100 100" preserveAspectRatio="none">
                    <path class="frame-line top" d="M0,0 L100,0"></path>
                    <path class="frame-line right" d="M100,0 L100,100"></path>
                    <path class="frame-line bottom" d="M100,100 L0,100"></path>
                    <path class="frame-line left" d="M0,100 L0,0"></path>
                </svg>
                <div class="gallery-zoom">
                    <i class="fas fa-search-plus"></i>
                </div>
            `;
            
            // Add overlay to gallery item
            item.appendChild(overlay);
        }
    });
    
    // Initialize parallax effect for gallery section
    const gallerySection = document.querySelector('.section-gallery');
    if (gallerySection) {
        const galleryImages = gallerySection.querySelectorAll('img.img-scale:not(.video-thumbnail img)');
        
        gallerySection.addEventListener('mousemove', function(e) {
            const sectionRect = gallerySection.getBoundingClientRect();
            const xPos = (e.clientX - sectionRect.left) / sectionRect.width - 0.5;
            const yPos = (e.clientY - sectionRect.top) / sectionRect.height - 0.5;
            
            galleryImages.forEach(img => {
                const intensity = 10; // Adjust for more/less movement
                img.style.transform = `translate(${xPos * intensity}px, ${yPos * intensity}px)`;
            });
        });
        
        gallerySection.addEventListener('mouseleave', function() {
            galleryImages.forEach(img => {
                img.style.transform = '';
            });
        });
    }
}

/**
 * Initialize lightbox for gallery images
 * Fixed version with proper navigation and closing
 */
function initManualLightbox() {
    // Cache DOM elements for better performance
    const galleryItems = document.querySelectorAll('.js-zoom-gallery__item');
    let lightbox = document.querySelector('.manual-lightbox');
    
    // Create lightbox elements if they don't exist
    if (!lightbox) {
        lightbox = document.createElement('div');
        lightbox.className = 'manual-lightbox';
        lightbox.innerHTML = `
            <div class="lightbox-container">
                <div class="lightbox-close">&times;</div>
                <img class="lightbox-image" src="" alt="">
                <div class="lightbox-caption"></div>
                <button class="lightbox-prev"><i class="fas fa-chevron-left"></i></button>
                <button class="lightbox-next"><i class="fas fa-chevron-right"></i></button>
                <div class="lightbox-counter"></div>
            </div>
        `;
        document.body.appendChild(lightbox);
    }
    
    // Store all gallery items in an array for navigation
    const galleryItemArray = Array.from(galleryItems);
    
    // Add click event to each gallery item
    galleryItems.forEach((item, index) => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const imageUrl = this.getAttribute('href');
            if (!imageUrl) return;
            
            currentLightboxIndex = index;
            openImageLightbox(imageUrl, this.querySelector('img')?.alt || '');
        });
    });
    
    // Set up the close button
    const closeBtn = lightbox.querySelector('.lightbox-close');
    if (closeBtn) {
        closeBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            closeLightbox();
        });
    }
    
    // Set up navigation buttons
    const prevBtn = lightbox.querySelector('.lightbox-prev');
    const nextBtn = lightbox.querySelector('.lightbox-next');
    
    if (prevBtn) {
        prevBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            navigateLightbox(-1);
        });
    }
    
    if (nextBtn) {
        nextBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            navigateLightbox(1);
        });
    }
    
    // Close when clicking on the background
    lightbox.addEventListener('click', function(e) {
        if (e.target === lightbox || e.target.classList.contains('lightbox-container')) {
            closeLightbox();
        }
    });
    
    // Prevent image clicks from closing
    const lightboxImage = lightbox.querySelector('.lightbox-image');
    if (lightboxImage) {
        lightboxImage.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    }
    
    // Keyboard navigation
    document.addEventListener('keydown', function(e) {
        if (lightbox.style.display !== 'flex') return;
        
        switch (e.key) {
            case 'Escape':
                closeLightbox();
                break;
            case 'ArrowLeft':
                navigateLightbox(-1);
                break;
            case 'ArrowRight':
                navigateLightbox(1);
                break;
        }
    });
    
    // Swipe navigation for touch devices
    let touchStartX = 0;
    let touchEndX = 0;
    
    lightbox.addEventListener('touchstart', function(e) {
        touchStartX = e.changedTouches[0].screenX;
    }, { passive: true });
    
    lightbox.addEventListener('touchend', function(e) {
        touchEndX = e.changedTouches[0].screenX;
        handleSwipe();
    }, { passive: true });
    
    function handleSwipe() {
        const minSwipeDistance = 50;
        if (touchStartX - touchEndX > minSwipeDistance) {
            // Swipe left
            navigateLightbox(1);
        } else if (touchEndX - touchStartX > minSwipeDistance) {
            // Swipe right
            navigateLightbox(-1);
        }
    }
}

/**
 * Open image in lightbox
 * @param {string} imageUrl URL of the image to display
 * @param {string} caption Caption for the image
 */
function openImageLightbox(imageUrl, caption) {
    const lightbox = document.querySelector('.manual-lightbox');
    if (!lightbox) return;
    
    const lightboxImage = lightbox.querySelector('.lightbox-image');
    const lightboxCaption = lightbox.querySelector('.lightbox-caption');
    const lightboxCounter = lightbox.querySelector('.lightbox-counter');
    
    // Add loading indicator
    if (lightboxImage) {
        lightboxImage.classList.add('loading');
        lightboxImage.style.opacity = '0.5';
    }
    
    // Create new image to preload
    const img = new Image();
    img.onload = function() {
        if (lightboxImage) {
            lightboxImage.src = imageUrl;
            lightboxImage.classList.remove('loading');
            lightboxImage.style.opacity = '1';
        }
    };
    img.onerror = function() {
        if (lightboxImage) {
            lightboxImage.src = '';
            lightboxImage.classList.remove('loading');
            lightboxImage.style.opacity = '1';
            
            // Show error message
            if (lightboxCaption) {
                lightboxCaption.textContent = 'Error loading image';
                lightboxCaption.style.display = 'block';
            }
        }
    };
    img.src = imageUrl;
    
    // Set caption
    if (lightboxCaption) {
        if (caption) {
            lightboxCaption.textContent = caption;
            lightboxCaption.style.display = 'block';
        } else {
            lightboxCaption.style.display = 'none';
        }
    }
    
    // Update counter
    const galleryItems = document.querySelectorAll('.js-zoom-gallery__item');
    if (lightboxCounter && galleryItems.length > 1) {
        lightboxCounter.textContent = `${currentLightboxIndex + 1} / ${galleryItems.length}`;
        lightboxCounter.style.display = 'block';
    } else if (lightboxCounter) {
        lightboxCounter.style.display = 'none';
    }
    
    // Show/hide navigation buttons based on gallery length
    const prevBtn = lightbox.querySelector('.lightbox-prev');
    const nextBtn = lightbox.querySelector('.lightbox-next');
    
    if (prevBtn && nextBtn) {
        if (galleryItems.length <= 1) {
            prevBtn.style.display = 'none';
            nextBtn.style.display = 'none';
        } else {
            prevBtn.style.display = 'block';
            nextBtn.style.display = 'block';
        }
    }
    
    // Display lightbox
    lightbox.style.display = 'flex';
    document.body.style.overflow = 'hidden'; // Prevent page scrolling
    
    setTimeout(() => {
        lightbox.style.opacity = '1';
    }, 10);
}

/**
 * Close the lightbox
 */
function closeLightbox() {
    const lightbox = document.querySelector('.manual-lightbox');
    if (!lightbox) return;
    
    lightbox.style.opacity = '0';
    
    setTimeout(() => {
        lightbox.style.display = 'none';
        document.body.style.overflow = ''; // Restore page scrolling
        
        // Clear image source
        const lightboxImage = lightbox.querySelector('.lightbox-image');
        if (lightboxImage) {
            lightboxImage.src = '';
        }
    }, 300);
}

/**
 * Navigate between images in lightbox
 * @param {number} direction Direction to navigate: -1 for previous, 1 for next
 */
let currentLightboxIndex = 0;

function navigateLightbox(direction) {
    const galleryItems = Array.from(document.querySelectorAll('.js-zoom-gallery__item'));
    if (galleryItems.length <= 1) return;
    
    // Calculate new index with wraparound
    currentLightboxIndex = (currentLightboxIndex + direction + galleryItems.length) % galleryItems.length;
    
    // Get new image URL and caption
    const nextItem = galleryItems[currentLightboxIndex];
    if (!nextItem) return;
    
    const imageUrl = nextItem.getAttribute('href');
    if (!imageUrl) return;
    
    const caption = nextItem.querySelector('img')?.alt || '';
    
    // Update lightbox content
    openImageLightbox(imageUrl, caption);
} 