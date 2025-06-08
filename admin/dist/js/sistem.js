var SITE=$("html").data("url");
var ANASITE=$("html").data("anaurl");

$(function () {
  // AdminLTE dropdown fix - çabuk kapanmayı önle
  $('.sidebar .has-treeview > a').off('click').on('click', function(e) {
    e.preventDefault();
    e.stopPropagation(); // Eventi burada durdur
    
    var parent = $(this).parent('li');
    parent.toggleClass('menu-open');
    
    if (parent.hasClass('menu-open')) {
      parent.children('.nav-treeview').slideDown(350, function() {
        // Animasyon tamamlandıktan sonra scrollbar'ı güncelle
        if (typeof $.fn.overlayScrollbars !== 'undefined') {
          $('.sidebar').overlayScrollbars();
        }
      });
    } else {
      parent.children('.nav-treeview').slideUp(350);
    }
  });
  
  // Alt menü linklerinin çalışmasını sağla
  $('.sidebar .has-treeview .nav-treeview .nav-item .nav-link').off('click').on('click', function(e) {
    // Alt menü linklerinde normal davranış - sayfaya yönlendir
    window.location.href = $(this).attr('href');
  });
  
  // Sayfa yüklendiğinde aktif öğelerin dropdown'ları açık kalsın
  setTimeout(function() {
    var activeItems = $('.nav-sidebar .menu-open');
    activeItems.find('.nav-treeview').show();
  }, 100);
  
  // Active menu item
  var currentPath = window.location.pathname;
  $('.nav-sidebar .nav-link').each(function() {
    var linkHref = $(this).attr('href');
    if (linkHref && currentPath.indexOf(linkHref) !== -1) {
      $(this).addClass('active');
      
      // Parent dropdown - aktif öğenin parent'ı
      if ($(this).parents('.has-treeview').length) {
        $(this).parents('.has-treeview').addClass('menu-open');
        $(this).parents('.has-treeview').find('> a').addClass('active');
        $(this).parents('.nav-treeview').show();
      }
    }
  });
  
  // SweetAlert2 global settings
  if (typeof Swal !== 'undefined') {
    Swal.mixin({
      customClass: {
        confirmButton: 'btn btn-success',
        cancelButton: 'btn btn-danger'
      },
      buttonsStyling: false
    });
  }
  
  // Bootstrap Switch
  if ($.fn.bootstrapSwitch) {
    $("input[data-bootstrap-switch]").bootstrapSwitch();
  }
  
  // DataTables
  if ($.fn.dataTable) {
    $('[data-table="datatable"]').DataTable({
      "responsive": true,
      "autoWidth": false,
      "language": {
        "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Turkish.json"
      }
    });
  }
  
  // Bootstrap Tabs
  $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
    localStorage.setItem('lastTab', $(this).attr('href'));
  });
  
  // Restore last active tab
  var lastTab = localStorage.getItem('lastTab');
  if (lastTab) {
    $('[href="' + lastTab + '"]').tab('show');
  }
});

$(function () {
  $("#example1").DataTable();
  $('#example2').DataTable({
    "paging": true,
    "lengthChange": false,
    "searching": false,
    "ordering": true,
    "info": true,
    "autoWidth": false,
  });
});

$(function () {
  //Initialize Select2 Elements
  $('.select2').select2();

  //Initialize Select2 Elements
  $('.select2bs4').select2({
    theme: 'bootstrap4'
  });
});

$(function () {
  // Summernote
  $('.textarea').summernote();
});

function aktifpasif(ID,tablo)
{
  var durum=0;
 if($(".aktifpasif"+ID).is(':checked'))
 {
   durum=1;
 }
 else
 {
   durum=2;
 }
 
 $.ajax({
   method:"POST",
   url:SITE+"ajax.php",
   data:{"tablo":tablo,"ID":ID,"durum":durum},
   success: function(sonuc)
   {
     if(sonuc=="TAMAM")
     {
     }
     else
     {
       alert("İşleminiz şuan geçersizdir. Lütfen daha sonra tekrar deneyiniz.");
     }
   }
 });
}

function vitrinaktifpasif(ID,tablo)
{
  var durum=0;
 if($(".vitrinaktifpasif"+ID).is(':checked'))
 {
   durum=1;
 }
 else
 {
   durum=2;
 }
 
 $.ajax({
   method:"POST",
   url:SITE+"ajax.php",
   data:{"tablo":tablo,"ID":ID,"vitrindurum":durum},
   success: function(sonuc)
   {
     if(sonuc=="TAMAM")
     {
     }
     else
     {
       alert("İşleminiz şuan geçersizdir. Lütfen daha sonra tekrar deneyiniz.");
     }
   }
 });
}

function stokOlustur()
{
     $.ajax({
     method:"POST",
     url:SITE+"ajax.php",
     data:$(".urunEklemeFormu").serialize(),
     success: function(sonuc)
     {
       if(sonuc=="BOS")
       {
       }
       else
       {
         $(".stokYonetimAlani").html(sonuc);
       }
     }
   });
}

function secenekSil(secenekNo)
{
  $(".liste"+secenekNo).remove();
}

var listeSira=0;
function secenekEkleme(varyasyonID,varyasyonAdi)
    {
     listeSira=(listeSira+1);
      swal(varyasyonAdi+" Seçeneğiniz:", {
         content: "input",
       })
       .then((value) => {
         if(value==null)
         {

         }
         else
         {
          $(".secenekler_"+varyasyonID).append('<li class="liste'+listeSira+'">'+value+'<a class="btn btn-sm btn-danger" style="color:#fff;" onclick="secenekSil('+listeSira+');">Sil</a><input type="hidden" name="secenek'+varyasyonID+'[]" value="'+value+'" /></li>');
          
         }
         
       });
    }

$(function(){
  $(".silmeAlani").click(function(e){
    
    e.preventDefault();
    var yonlenecekAdres=e.currentTarget.getAttribute("href");

    swal({
title: "Kaldırmak istediğinizden emin misiniz?",
text: "Bu veriyi sildiğinizde bir daha geri alamayacaksınız.",
icon: "warning",
buttons: true,
dangerMode: true,
})
.then((willDelete) => {
  if (willDelete) {
   window.location.href=yonlenecekAdres;
  } else {
    swal("İşleminiz başarıyla iptal edildi.");
  }
});

   
   
    });

 

    var varyasyonNo=0;
   $(".varyasyonEkleme").click(function(){

      varyasyonNo=(varyasyonNo+1);
      swal("Varyasyon İsminiz:", {
        content: "input",
      })
      .then((value) => {
        if(value==null)
        {

        }
        else
        {
          $(".butonuKontrolEt").show();
          if(varyasyonNo>2)
          {
            swal("Maksimum 2 adet varyasyon tanımlayabilirsiniz.");
          }
          else
          {
          $(".varyasyonGrup").append('<div class="col-md-3 varyasyonNo_'+varyasyonNo+'"><h2>'+value+'<a class="btn btn-success varyasyonButon_'+varyasyonNo+'" onclick="secenekEkleme('+varyasyonNo+',\''+value+'\');" style="color:#fff; float:right;"><i class="fa fa-plus"></i> Seçenek Ekle</a><input type="hidden" name="varyasyon'+varyasyonNo+'" value="'+value+'" /></h2><ul class="secenekler_'+varyasyonNo+'"></ul></div>');
          }
        }
        


      });

   });

});