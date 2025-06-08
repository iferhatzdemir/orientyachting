/**
 * TR Button Fix for Orient Yacht Theme
 * This script finds and updates the static TR language button in the navbar
 */
document.addEventListener('DOMContentLoaded', function() {
    // Get the current language
    const currentLang = document.documentElement.lang || 'en';
    const flagImgSrc = window.location.origin + '/orient/assets/img/flags/' + currentLang.toLowerCase() + '-flag.png';
    
    // Direct approach to find elements in the navbar
    const searchForTRButton = function() {
        // Find all static language buttons in the navigation
        document.querySelectorAll('a').forEach(function(link) {
            const linkText = link.textContent.trim();
            if (linkText === 'TR' && link.parentElement.tagName !== 'LI') {
                // We've found the hard-coded TR button
                console.log('Found hard-coded TR button:', link);
                
                // Save the original styles
                const originalStyles = window.getComputedStyle(link);
                
                // Clear the link content
                link.innerHTML = '';
                
                // Create a container for better styling
                const container = document.createElement('span');
                container.style.display = 'flex';
                container.style.alignItems = 'center';
                
                // Create and add the flag image
                const img = document.createElement('img');
                img.src = flagImgSrc;
                img.alt = currentLang;
                img.style.width = '24px';
                img.style.height = '16px';
                img.style.marginRight = '5px';
                img.style.verticalAlign = 'middle';
                container.appendChild(img);
                
                // Add the language text
                const text = document.createTextNode(currentLang.toUpperCase());
                container.appendChild(text);
                
                // Add the container to the link
                link.appendChild(container);
                
                // Preserve original styles
                link.style.backgroundColor = originalStyles.backgroundColor;
                link.style.color = originalStyles.color;
                link.style.padding = originalStyles.padding;
                
                // Add click handler to redirect to language selector
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    // Modify the URL to change language
                    const url = new URL(window.location.href);
                    url.searchParams.set('lang', currentLang);
                    window.location.href = url.toString();
                });
            }
        });
    };
    
    // Run immediately
    searchForTRButton();
    
    // Also run after the page is fully loaded and a short delay
    window.addEventListener('load', function() {
        setTimeout(searchForTRButton, 500);
    });
}); 