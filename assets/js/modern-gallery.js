(function(){
    const modal = document.getElementById('modernGalleryModal');
    if(!modal) return;
    const img = document.getElementById('modernModalImg');
    const video = document.getElementById('modernModalVideo');
    const source = video.querySelector('source');
    const counter = modal.querySelector('.modern-counter');
    const media = window.galleryMedia || [];
    let current = 0;

    window.openGallery = function(idx){
        current = idx;
        show();
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    };

    window.closeGallery = function(){
        modal.style.display = 'none';
        video.pause();
        document.body.style.overflow = '';
    };

    window.changeGallery = function(step){
        if(!media.length) return;
        current = (current + step + media.length) % media.length;
        show();
    };

    function show(){
        const item = media[current];
        if(!item) return;
        counter.textContent = (current+1) + ' / ' + media.length;
        if(item.type === 'video'){
            img.style.display = 'none';
            video.style.display = 'block';
            source.src = item.url;
            video.load();
        }else{
            video.pause();
            video.style.display = 'none';
            source.src = '';
            img.style.display = 'block';
            img.src = item.url;
        }
    }

    document.addEventListener('keydown', function(e){
        if(modal.style.display === 'flex'){
            if(e.key === 'Escape') closeGallery();
            else if(e.key === 'ArrowLeft') changeGallery(-1);
            else if(e.key === 'ArrowRight') changeGallery(1);
        }
    });
})();
