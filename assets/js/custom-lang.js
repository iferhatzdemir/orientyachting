/**
 * Custom JavaScript to fix language selector display in Orient Yacht theme
 */
document.addEventListener('DOMContentLoaded', function() {
    // Get the current language
    const currentLang = document.documentElement.lang || 'en';
    
    // First approach: Find and modify any TR buttons in main nav
    const allLinks = document.querySelectorAll('a');
    allLinks.forEach(link => {
        const text = link.textContent.trim();
        if (text === 'TR' || text === 'EN' || text === 'DE' || text === 'RU') {
            console.log('Found language button:', text);
            // Check if this is within the main navigation
            if (link.closest('.main-menu') || link.closest('.header-main')) {
                console.log('In main nav, updating...');
                // This is a language button in the main nav - update it
                const newText = currentLang.toUpperCase();
                
                // Clear existing content
                link.innerHTML = '';
                
                // Create flag image
                const img = document.createElement('img');
                img.src = `${window.location.origin}/orient/assets/img/flags/${currentLang.toLowerCase()}-flag.png`;
                img.alt = currentLang;
                img.style.width = '24px';
                img.style.height = '16px';
                img.style.marginRight = '5px';
                
                // Add flag and text
                link.appendChild(img);
                link.appendChild(document.createTextNode(newText));
                
                // Add click handler to show the real language dropdown
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    // Find and click the language switcher
                    const langSwitcher = document.getElementById('langSwitcher');
                    if (langSwitcher) {
                        const currentLangEl = langSwitcher.querySelector('.current-lang');
                        if (currentLangEl) {
                            currentLangEl.click();
                        }
                    }
                });
            }
        }
    });
    
    // Second approach: Find all top-level links in the navigation that are not in dropdowns
    const navLinks = document.querySelectorAll('.navbar-nav > li > a');
    navLinks.forEach(link => {
        // If this is near the search icon and reservation button, it might be the TR button
        const nextEl = link.parentElement.nextElementSibling;
        if (nextEl && 
            (nextEl.querySelector('.icon-magnifier') || 
             nextEl.querySelector('.header-main__btn') ||
             nextEl.textContent.includes('Reservation') || 
             nextEl.textContent.includes('Rezervasyon'))) {
            
            console.log('Found potential language button by position:', link.textContent);
            
            // This might be our TR button - update it
            const newText = currentLang.toUpperCase();
            
            // Clear existing content
            link.innerHTML = '';
            
            // Create flag image
            const img = document.createElement('img');
            img.src = `${window.location.origin}/orient/assets/img/flags/${currentLang.toLowerCase()}-flag.png`;
            img.alt = currentLang;
            img.style.width = '24px';
            img.style.height = '16px';
            img.style.marginRight = '5px';
            
            // Add flag and text
            link.appendChild(img);
            link.appendChild(document.createTextNode(newText));
            
            // Add click handler to show the real language dropdown
            link.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                // Find and click the language switcher
                const langSwitcher = document.getElementById('langSwitcher');
                if (langSwitcher) {
                    const currentLangEl = langSwitcher.querySelector('.current-lang');
                    if (currentLangEl) {
                        currentLangEl.click();
                    }
                }
            });
        }
    });
    
    // Special case for the hardcoded TR button in demo screenshot
    const reservationButton = document.querySelector('.header-main__btn');
    if (reservationButton) {
        const parent = reservationButton.parentElement;
        const children = Array.from(parent.children);
        
        for (let i = 0; i < children.length; i++) {
            const child = children[i];
            if (child !== reservationButton && 
                child.tagName === 'A' && 
                child.textContent.trim() === 'TR') {
                
                console.log('Found TR button by reservation proximity');
                
                // This is the TR button - update it
                const newText = currentLang.toUpperCase();
                
                // Clear existing content
                child.innerHTML = '';
                
                // Create flag image
                const img = document.createElement('img');
                img.src = `${window.location.origin}/orient/assets/img/flags/${currentLang.toLowerCase()}-flag.png`;
                img.alt = currentLang;
                img.style.width = '24px';
                img.style.height = '16px';
                img.style.marginRight = '5px';
                
                // Add flag and text
                child.appendChild(img);
                child.appendChild(document.createTextNode(newText));
                
                // Add click handler to show the real language dropdown
                child.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    // Find and click the language switcher
                    const langSwitcher = document.getElementById('langSwitcher');
                    if (langSwitcher) {
                        const currentLangEl = langSwitcher.querySelector('.current-lang');
                        if (currentLangEl) {
                            currentLangEl.click();
                        }
                    }
                });
                
                break;
            }
        }
    }
    
    // Finally, make sure the real language switcher works correctly
    const langSwitcher = document.getElementById('langSwitcher');
    if (langSwitcher) {
        langSwitcher.style.display = 'flex';
        langSwitcher.style.alignItems = 'center';
        
        // Ensure it's properly positioned in mobile view too
        const adjustLangSwitcher = () => {
            if (window.innerWidth < 992) {
                // Mobile view - adjust as needed
                langSwitcher.style.margin = '0 10px';
            } else {
                // Desktop view
                langSwitcher.style.margin = '0 15px';
            }
        };
        
        // Initial adjustment
        adjustLangSwitcher();
        
        // Adjust on window resize
        window.addEventListener('resize', adjustLangSwitcher);
    }
}); 