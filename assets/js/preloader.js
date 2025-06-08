/**
 * Orient Yachting Luxury Preloader
 * Controls the preloader animation and dismissal
 */

document.addEventListener('DOMContentLoaded', function() {
    // Get the preloader element - check for both IDs to support legacy code
    const preloader = document.getElementById('orient-preloader') || document.getElementById('yacht-preloader');
    if (!preloader) return;
    
    // Track loading progress
    let loadProgress = 0;
    const progressBar = preloader.querySelector('.loading-progress');
    
    // Function to update loading progress manually
    function updateProgress(progress) {
        if (!progressBar) return;
        
        loadProgress = Math.min(progress, 100);
        progressBar.style.width = loadProgress + '%';
        
        // When progress reaches 100%, start hiding the preloader
        if (loadProgress >= 100) {
            hidePreloader();
        }
    }
    
    // Function to hide preloader with animation
    function hidePreloader() {
        if (!preloader) return;
        
        // Add class to trigger CSS transition for smooth fade-out
        setTimeout(function() {
            // Support both class names for backward compatibility
            preloader.classList.add('loaded');
            preloader.classList.add('preloader-hidden');
            
            // After the transition completes, set display to none for better performance
            setTimeout(function() {
                preloader.style.display = 'none';
            }, 500);
        }, 500); // Slight delay for visual effect
    }
    
    // Simulate loading progress for demo purposes
    // In real scenario, this could be tied to actual resource loading
    const simulateLoading = function() {
        let progress = 0;
        const loadingInterval = setInterval(function() {
            progress += Math.random() * 10;
            updateProgress(progress);
            
            if (progress >= 100) {
                clearInterval(loadingInterval);
            }
        }, 300);
    };
    
    // Check if page has already loaded
    if (document.readyState === 'complete') {
        updateProgress(100);
    } else {
        // Simulate loading progress while page is loading
        simulateLoading();
        
        // When everything is loaded, ensure the preloader is hidden
        window.addEventListener('load', function() {
            updateProgress(100);
        });
        
        // Fallback - if for some reason the preloader gets stuck
        setTimeout(function() {
            updateProgress(100);
        }, 5000); // 5 second maximum wait time
    }
    
    // Add animation to the yacht for better visual effect
    const yachtSvg = preloader.querySelector('.yacht-svg');
    if (yachtSvg) {
        // Add subtle rotation to the yacht for more dynamic movement
        yachtSvg.style.transformOrigin = 'center center';
        
        // Create wave effect animation
        const waveAnimation = function() {
            let angle = 0;
            const rotationInterval = setInterval(function() {
                angle += 0.2;
                const rotation = Math.sin(angle * (Math.PI / 180)) * 2; // Gentle rotation
                yachtSvg.style.transform = `rotate(${rotation}deg)`;
                
                // Stop animation when preloader is hidden
                if (preloader.classList.contains('loaded') || preloader.classList.contains('preloader-hidden')) {
                    clearInterval(rotationInterval);
                }
            }, 50);
        };
        
        // Start yacht animation
        waveAnimation();
    }
}); 