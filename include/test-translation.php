<?php
// This file demonstrates how to use the translation system

// Output JavaScript translations for client-side use
outputJsTranslations();
?>

<div class="container my-5">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4><?php echo t('site.title'); ?> - <?php echo t('contact.title'); ?></h4>
                </div>
                <div class="card-body">
                    <h5 class="card-title"><?php echo t('contact.subtitle'); ?></h5>
                    <p class="card-text"><?php echo t('yacht.description'); ?></p>
                    
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5><?php echo t('yacht.title'); ?></h5>
                                </div>
                                <div class="card-body">
                                    <p><?php echo t('yacht.description'); ?></p>
                                    <ul class="list-group">
                                        <li class="list-group-item"><?php echo t('yacht.details'); ?></li>
                                        <li class="list-group-item"><?php echo t('yacht.features'); ?></li>
                                        <li class="list-group-item"><?php echo t('yacht.specifications'); ?></li>
                                    </ul>
                                    <div class="mt-3">
                                        <a href="#" class="btn btn-primary"><?php echo t('yacht.booking'); ?></a>
                                        <a href="#" class="btn btn-outline-secondary"><?php echo t('yacht.inquire'); ?></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5><?php echo t('contact.title'); ?></h5>
                                </div>
                                <div class="card-body">
                                    <form>
                                        <div class="form-group mb-3">
                                            <label for="name"><?php echo t('contact.name'); ?></label>
                                            <input type="text" class="form-control" id="name">
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="email"><?php echo t('contact.email'); ?></label>
                                            <input type="email" class="form-control" id="email">
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="phone"><?php echo t('contact.phone'); ?></label>
                                            <input type="tel" class="form-control" id="phone">
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="message"><?php echo t('contact.message'); ?></label>
                                            <textarea class="form-control" id="message" rows="3"></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-primary"><?php echo t('contact.submit'); ?></button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-5">
                        <h4>Language Switcher:</h4>
                        <div class="row">
                            <div class="col-md-4">
                                <h5>Dropdown Style:</h5>
                                <?php 
                                $style = 'dropdown';
                                include('include/components/language-switcher.php'); 
                                ?>
                            </div>
                            <div class="col-md-4">
                                <h5>Buttons Style:</h5>
                                <?php 
                                $style = 'buttons';
                                include('include/components/language-switcher.php'); 
                                ?>
                            </div>
                            <div class="col-md-4">
                                <h5>Minimal Style:</h5>
                                <?php 
                                $style = 'minimal';
                                include('include/components/language-switcher.php'); 
                                ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <h4>JavaScript Translation Example:</h4>
                        <div id="js-translation-example" class="alert alert-info p-3"></div>
                        <button id="change-text" class="btn btn-secondary">Change Text</button>
                    </div>
                </div>
                <div class="card-footer text-muted">
                    <?php echo t('footer.copyright'); ?> © <?php echo date('Y'); ?> <?php echo t('site.title'); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // JavaScript translation example
        document.getElementById('js-translation-example').textContent = t('yacht.title') + ' - ' + t('yacht.description');
        
        // Change text button
        document.getElementById('change-text').addEventListener('click', function() {
            document.getElementById('js-translation-example').textContent = t('contact.title') + ' - ' + t('contact.subtitle');
        });
    });
</script> 