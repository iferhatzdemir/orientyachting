<?php
if(!defined("SABIT")) define("SABIT", true);
?>

<div class="section-title-page area-bg area-bg_dark area-bg_op_60">
    <div class="area-bg__inner">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <h1 class="b-title-page bg-primary_a">404 - SAYFA BULUNAMADI</h1>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-md-12 text-center my-5 py-5">
            <div class="error-container">
                <h2 class="ui-title-block mb-4">Aradığınız Sayfayı Bulamadık</h2>
                <p class="mb-5">Üzgünüz, aradığınız sayfa artık mevcut değil veya taşınmış olabilir.</p>
                
                <div class="row">
                    <div class="col-md-8 offset-md-2">
                        <div class="error-options">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="error-option mb-4">
                                        <i class="fa fa-home fa-3x mb-3 text-primary"></i>
                                        <h4>Ana Sayfaya Dön</h4>
                                        <p>Ana sayfaya dönerek yeniden başlayabilirsiniz</p>
                                        <a href="<?=SITE?>" class="btn btn-primary">Ana Sayfa</a>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="error-option mb-4">
                                        <i class="fa fa-ship fa-3x mb-3 text-primary"></i>
                                        <h4>Yatlarımıza Göz Atın</h4>
                                        <p>Lüks yat kiralama seçeneklerimizi keşfedin</p>
                                        <a href="<?=SITE?>yatlar" class="btn btn-primary">Yat Kiralama</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-5 pt-5">
                    <p>Aradığınız bilgiyi bulamadıysanız bizimle iletişime geçmekten çekinmeyin:</p>
                    <a href="<?=SITE?>iletisim" class="btn btn-secondary">İletişim</a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.error-container {
    padding: 40px 0;
}

.error-option {
    padding: 25px;
    border-radius: 5px;
    transition: all 0.3s ease;
    border: 1px solid #eee;
}

.error-option:hover {
    background-color: #f9f9f9;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    transform: translateY(-5px);
}

.text-primary {
    color: #0d3d63 !important;
}

.btn-primary {
    background-color: #0d3d63;
    border-color: #0d3d63;
}

.btn-primary:hover {
    background-color: #0a2e4a;
    border-color: #0a2e4a;
}

.btn-secondary {
    background-color: #6c757d;
    border-color: #6c757d;
}

.btn-secondary:hover {
    background-color: #5a6268;
    border-color: #545b62;
}
</style> 