/**
 * Yacht Listing Page JavaScript
 * Provides interactive functionality for the yacht listing page
 */
document.addEventListener('DOMContentLoaded', function() {
  // Filtreleri açıp kapatma toggle fonksiyonu (mobil için)
  const filterToggles = document.querySelectorAll('.b-filter__title');
  
  // İlk yüklemede mobil görünümü ayarla
  const isMobile = window.innerWidth < 768;
  setMobileView(isMobile);
  
  // Toggle fonksiyonlarını ekle
  filterToggles.forEach(toggle => {
    toggle.addEventListener('click', function() {
      if (window.innerWidth < 768) {
        const content = this.nextElementSibling;
        
        if (content) {
          const isHidden = content.style.display === 'none' || !content.style.display;
          
          if (isHidden) {
            content.style.display = 'block';
            this.classList.add('active');
          } else {
            content.style.display = 'none';
            this.classList.remove('active');
          }
        }
      }
    });
  });
  
  // Sayfa boyutu değiştiğinde filtre görünürlüğünü ayarla
  window.addEventListener('resize', debounce(function() {
    const isMobile = window.innerWidth < 768;
    setMobileView(isMobile);
  }, 250));
  
  // Mobil görünümü ayarlama fonksiyonu
  function setMobileView(isMobile) {
    const filterContents = document.querySelectorAll('.b-filter__checkboxes, .b-filter__price, select.form-control[name="siralama"]');
    
    filterContents.forEach(content => {
      if (!isMobile) {
        content.style.display = 'block';
      } else {
        content.style.display = 'none';
        const titleElement = content.previousElementSibling;
        if (titleElement && titleElement.classList.contains('b-filter__title')) {
          titleElement.classList.remove('active');
        }
      }
    });
  }
  
  // Görüntüleri yükleme hatası yönetimi
  const yachtImages = document.querySelectorAll('.yacht-card-image img');
  const siteUrl = document.querySelector('meta[name="site-url"]')?.content || '/';
  
  yachtImages.forEach(img => {
    img.addEventListener('error', function() {
      this.src = siteUrl + 'assets/img/yacht-placeholder.jpg';
    });
    
    // Resmin yüklenip yüklenmediğini kontrol et
    if (img.complete && (img.naturalWidth === 0 || img.naturalHeight === 0)) {
      img.src = siteUrl + 'assets/img/yacht-placeholder.jpg';
    }
  });
  
  // Lazy loading için IntersectionObserver kullan
  if ('IntersectionObserver' in window) {
    const yachtCards = document.querySelectorAll('.yacht-card');
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('is-visible');
          observer.unobserve(entry.target);
        }
      });
    }, {
      rootMargin: '0px 0px 100px 0px',
      threshold: 0.1
    });
    
    yachtCards.forEach(card => {
      observer.observe(card);
    });
  }
  
  // Fiyat formatını düzenle
  const formatCurrency = () => {
    document.querySelectorAll('.price-value').forEach(price => {
      const priceText = price.textContent.trim();
      if (priceText.indexOf('₺') === -1) { // Prevent double formatting
        const numericValue = priceText.replace(/[^\d.,]/g, '');
        
        if (!isNaN(parseFloat(numericValue))) {
          const formattedPrice = parseInt(numericValue).toLocaleString('tr-TR');
          if (!price.querySelector('.currency')) {
            price.innerHTML = formattedPrice + ' <span class="currency">₺</span>';
          } else {
            const currencySpan = price.querySelector('.currency');
            price.innerHTML = formattedPrice;
            price.appendChild(currencySpan);
          }
        }
      }
    });
  };
  
  formatCurrency();
  
  // Utility: Debounce function (prevents excessive function calls)
  function debounce(func, wait, immediate) {
    let timeout;
    return function() {
      const context = this, args = arguments;
      const later = function() {
        timeout = null;
        if (!immediate) func.apply(context, args);
      };
      const callNow = immediate && !timeout;
      clearTimeout(timeout);
      timeout = setTimeout(later, wait);
      if (callNow) func.apply(context, args);
    };
  }
}); 