<?php
/**
 * Translations Management
 * 
 * Admin interface for managing translations
 * 
 * @version 1.0
 */

// Check session for admin
if(!isset($_SESSION["ID"]) || empty($_SESSION["ID"])) {
    header("Location:login.php");
    exit;
}

// Initialize language controller
global $Lang, $VT;

// Get available languages
$availableLanguages = isset($Lang) && method_exists($Lang, 'getAvailableLanguages') 
    ? $Lang->getAvailableLanguages() 
    : ['tr', 'en', 'de', 'ru', 'fr'];

// Get current language for editing
$currentEditLang = isset($_GET['lang']) && in_array($_GET['lang'], $availableLanguages) 
    ? $_GET['lang'] 
    : $availableLanguages[0];

// Get selected context
$contexts = [
    'default' => 'Genel',
    'navigation' => 'Navigasyon',
    'yacht' => 'Yat Detayları',
    'contact' => 'İletişim',
    'home' => 'Ana Sayfa',
];
$currentContext = isset($_GET['context']) && array_key_exists($_GET['context'], $contexts) 
    ? $_GET['context'] 
    : 'default';

// Process actions
$message = '';

// Add/Edit Translation
if(isset($_POST['save_translation']) && isset($_POST['key']) && isset($_POST['value'])) {
    $key = trim($_POST['key']);
    $value = trim($_POST['value']);
    $context = trim($_POST['context']);
    
    if(!empty($key)) {
        if(isset($Lang) && method_exists($Lang, 'addDatabaseTranslation')) {
            $result = $Lang->addDatabaseTranslation($key, $value, $currentEditLang, $context);
            $message = $result 
                ? '<div class="alert alert-success">Çeviri başarıyla kaydedildi.</div>' 
                : '<div class="alert alert-danger">Çeviri kaydedilirken bir hata oluştu.</div>';
        } else {
            // Fallback if LanguageController not available
            $sql = "INSERT INTO translations (translation_key, translation_value, lang_code, context) 
                   VALUES (?, ?, ?, ?) 
                   ON DUPLICATE KEY UPDATE translation_value = VALUES(translation_value)";
            $result = $VT->SorguCalistir($sql, [$key, $value, $currentEditLang, $context], 1);
            $message = $result 
                ? '<div class="alert alert-success">Çeviri başarıyla kaydedildi.</div>' 
                : '<div class="alert alert-danger">Çeviri kaydedilirken bir hata oluştu.</div>';
        }
    }
}

// Delete Translation
if(isset($_GET['delete']) && isset($_GET['key'])) {
    $key = trim($_GET['key']);
    $context = isset($_GET['ctx']) ? trim($_GET['ctx']) : 'default';
    
    if(!empty($key)) {
        if(isset($Lang) && method_exists($Lang, 'deleteDatabaseTranslation')) {
            $result = $Lang->deleteDatabaseTranslation($key, $currentEditLang, $context);
            $message = $result 
                ? '<div class="alert alert-success">Çeviri başarıyla silindi.</div>' 
                : '<div class="alert alert-danger">Çeviri silinirken bir hata oluştu.</div>';
        } else {
            // Fallback if LanguageController not available
            $sql = "DELETE FROM translations 
                   WHERE translation_key = ? AND lang_code = ? AND context = ?";
            $result = $VT->SorguCalistir($sql, [$key, $currentEditLang, $context], 1);
            $message = $result 
                ? '<div class="alert alert-success">Çeviri başarıyla silindi.</div>' 
                : '<div class="alert alert-danger">Çeviri silinirken bir hata oluştu.</div>';
        }
    }
}

// Import Static Translations
if(isset($_POST['import_static'])) {
    $staticTranslations = [];
    $staticTranslationsFile = dirname(dirname(__DIR__)) . '/data/static_translations.php';
    
    if(file_exists($staticTranslationsFile)) {
        $staticTranslations = include $staticTranslationsFile;
        
        if(isset($staticTranslations[$currentEditLang])) {
            $imported = 0;
            
            foreach($staticTranslations[$currentEditLang] as $ctx => $translations) {
                foreach($translations as $key => $value) {
                    if(isset($Lang) && method_exists($Lang, 'addDatabaseTranslation')) {
                        $result = $Lang->addDatabaseTranslation($key, $value, $currentEditLang, $ctx);
                    } else {
                        // Fallback
                        $sql = "INSERT IGNORE INTO translations 
                               (translation_key, translation_value, lang_code, context) 
                               VALUES (?, ?, ?, ?)";
                        $result = $VT->SorguCalistir($sql, [$key, $value, $currentEditLang, $ctx], 1);
                    }
                    
                    if($result) {
                        $imported++;
                    }
                }
            }
            
            $message = '<div class="alert alert-success">' . $imported . ' çeviri başarıyla içe aktarıldı.</div>';
        } else {
            $message = '<div class="alert alert-warning">Seçilen dil için statik çeviriler bulunamadı.</div>';
        }
    } else {
        $message = '<div class="alert alert-danger">Statik çeviri dosyası bulunamadı.</div>';
    }
}

// Get translations for current language and context
$translations = [];
$sql = "SELECT translation_key, translation_value, context FROM translations 
       WHERE lang_code = ? AND context = ? 
       ORDER BY translation_key ASC";
$result = $VT->SorguCalistir($sql, [$currentEditLang, $currentContext], 2);

if($result != false) {
    $translations = $result;
}

// Get total translation count for each context for statistics
$contextCounts = [];
foreach ($contexts as $ctx => $name) {
    $sql = "SELECT COUNT(*) as count FROM translations WHERE lang_code = ? AND context = ?";
    $result = $VT->SorguCalistir($sql, [$currentEditLang, $ctx], 1);
    $contextCounts[$ctx] = $result ? $result[0]["count"] : 0;
}
$totalTranslations = array_sum($contextCounts);

// Language names for display
$languageNames = [
    'tr' => 'Türkçe',
    'en' => 'İngilizce',
    'de' => 'Almanca',
    'ru' => 'Rusça',
    'fr' => 'Fransızca',
];
?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Çeviri Yönetimi</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="index.php">Anasayfa</a></li>
                        <li class="breadcrumb-item active">Çeviriler</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <?php echo $message; ?>
            
            <!-- Language and Context Selection -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Çeviri Dili ve Kategorisi</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Çeviri Dili</label>
                                <select class="form-control" id="language-select" onchange="changeLanguage(this.value)">
                                    <?php foreach($availableLanguages as $langCode): ?>
                                        <option value="<?php echo $langCode; ?>" <?php echo $langCode == $currentEditLang ? 'selected' : ''; ?>>
                                            <?php echo isset($languageNames[$langCode]) ? $languageNames[$langCode] : $langCode; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Çeviri Kategorisi</label>
                                <select class="form-control" id="context-select" onchange="changeContext(this.value)">
                                    <?php foreach($contexts as $ctx => $name): ?>
                                        <option value="<?php echo $ctx; ?>" <?php echo $ctx == $currentContext ? 'selected' : ''; ?>>
                                            <?php echo $name; ?> (<?php echo $contextCounts[$ctx]; ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="info-box">
                                <span class="info-box-icon bg-info"><i class="fas fa-language"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Toplam Çeviri</span>
                                    <span class="info-box-number"><?php echo $totalTranslations; ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <form method="post" action="">
                                <button type="submit" name="import_static" class="btn btn-warning">
                                    <i class="fas fa-file-import"></i> Statik Çevirileri İçe Aktar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Add/Edit Translation -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Çeviri Ekle/Düzenle</h3>
                </div>
                <div class="card-body">
                    <form method="post" action="">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Çeviri Anahtarı</label>
                                    <input type="text" class="form-control" name="key" id="edit-key" required>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Kategori</label>
                                    <select class="form-control" name="context">
                                        <?php foreach($contexts as $ctx => $name): ?>
                                            <option value="<?php echo $ctx; ?>" <?php echo $ctx == $currentContext ? 'selected' : ''; ?>>
                                                <?php echo $name; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Çeviri Metni</label>
                                    <input type="text" class="form-control" name="value" id="edit-value" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <button type="submit" name="save_translation" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Kaydet
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Translations List -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        Çeviriler - 
                        <?php echo isset($languageNames[$currentEditLang]) ? $languageNames[$currentEditLang] : $currentEditLang; ?> / 
                        <?php echo $contexts[$currentContext]; ?>
                    </h3>
                    <div class="card-tools">
                        <div class="input-group input-group-sm" style="width: 250px;">
                            <input type="text" id="search-box" class="form-control float-right" placeholder="Çeviri Ara...">
                            <div class="input-group-append">
                                <button type="button" class="btn btn-default" onclick="searchTranslations()">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap" id="translations-table">
                        <thead>
                            <tr>
                                <th>Anahtar</th>
                                <th>Çeviri</th>
                                <th style="width: 100px;">İşlemler</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(empty($translations)): ?>
                                <tr>
                                    <td colspan="3" class="text-center">Henüz çeviri bulunmamaktadır.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach($translations as $translation): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($translation["translation_key"]); ?></td>
                                        <td><?php echo htmlspecialchars($translation["translation_value"]); ?></td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-sm btn-info" 
                                                    onclick="editTranslation('<?php echo addslashes($translation["translation_key"]); ?>', '<?php echo addslashes($translation["translation_value"]); ?>')">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <a href="index.php?sayfa=translations&lang=<?php echo $currentEditLang; ?>&context=<?php echo $currentContext; ?>&delete=1&key=<?php echo urlencode($translation["translation_key"]); ?>&ctx=<?php echo urlencode($translation["context"]); ?>" 
                                                   class="btn btn-sm btn-danger" 
                                                   onclick="return confirm('Bu çeviriyi silmek istediğinizden emin misiniz?');">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
// Change language
function changeLanguage(lang) {
    window.location.href = 'index.php?sayfa=translations&lang=' + lang + '&context=<?php echo $currentContext; ?>';
}

// Change context
function changeContext(context) {
    window.location.href = 'index.php?sayfa=translations&lang=<?php echo $currentEditLang; ?>&context=' + context;
}

// Edit translation
function editTranslation(key, value) {
    document.getElementById('edit-key').value = key;
    document.getElementById('edit-value').value = value;
    document.getElementById('edit-key').focus();
    window.scrollTo(0, 0);
}

// Search translations
function searchTranslations() {
    const input = document.getElementById('search-box').value.toLowerCase();
    const table = document.getElementById('translations-table');
    const rows = table.getElementsByTagName('tr');
    
    for (let i = 1; i < rows.length; i++) { // Start from 1 to skip header row
        const keyCell = rows[i].getElementsByTagName('td')[0];
        const valueCell = rows[i].getElementsByTagName('td')[1];
        
        if (keyCell && valueCell) {
            const key = keyCell.textContent || keyCell.innerText;
            const value = valueCell.textContent || valueCell.innerText;
            
            if (key.toLowerCase().indexOf(input) > -1 || value.toLowerCase().indexOf(input) > -1) {
                rows[i].style.display = '';
            } else {
                rows[i].style.display = 'none';
            }
        }
    }
}

// Add event listener for search box
document.getElementById('search-box').addEventListener('keyup', searchTranslations);
</script> 