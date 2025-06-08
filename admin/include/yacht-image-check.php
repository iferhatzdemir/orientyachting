<?php
// Yacht Image Diagnostic and Repair Tool
// This tool helps admins diagnose and fix issues with yacht images

// Security Check
if(!isset($_SESSION["admin"]) || $_SESSION["admin"] != true) {
  echo '<div class="alert alert-danger">Unauthorized access! Please log in as admin.</div>';
  exit;
}

// Include database connection
include_once(SINIF."VT.php");
$VT = new VT();

// Function to check if image exists in any possible directory
function checkImageExists($filename) {
  $possible_paths = array(
    "images/yachts/".$filename,
    "images/resimler/".$filename,
    "images/yacht/".$filename,
    "images/yatlar/".$filename,
    "images/".$filename
  );
  
  foreach($possible_paths as $path) {
    $file_path = $_SERVER['DOCUMENT_ROOT'] . '/' . ltrim(SITE, '/') . $path;
    if(file_exists($file_path)) {
      return $path; // Return the working path
    }
  }
  
  return false; // Image not found in any directory
}

// Function to create directory recursively
function createDirectory($path) {
  if(!file_exists($path)) {
    return mkdir($path, 0777, true);
  }
  return true;
}

// Handle forms and actions
$message = "";
$error = "";

// Handle Fix Database action
if(isset($_POST["fix_database"]) && isset($_POST["yacht_id"])) {
  $yacht_id = $_POST["yacht_id"];
  
  // Fix 'tablo' field in resimler table
  $update_count = $VT->SorguCalistir("UPDATE resimler SET tablo = 'yachts' WHERE KID = ? AND (tablo IS NULL OR tablo = '')", array($yacht_id));
  
  if($update_count) {
    $message .= "Updated ".$update_count->rowCount()." records in resimler table to use tablo='yachts'.<br>";
  }
  
  // Check all image files and ensure they are in the correct directory
  $images = $VT->VeriGetir("resimler", "WHERE KID=?", array($yacht_id));
  
  if($images) {
    $fixed_images = 0;
    
    // Ensure yacht image directory exists
    $yacht_dir = $_SERVER['DOCUMENT_ROOT'] . '/' . ltrim(SITE, '/') . 'images/yachts/';
    createDirectory($yacht_dir);
    
    foreach($images as $img) {
      $existing_path = checkImageExists($img["resim"]);
      
      if($existing_path && $existing_path != "images/yachts/".$img["resim"]) {
        // Image exists but in wrong directory, copy to yachts directory
        $source = $_SERVER['DOCUMENT_ROOT'] . '/' . ltrim(SITE, '/') . $existing_path;
        $destination = $yacht_dir . basename($img["resim"]);
        
        if(copy($source, $destination)) {
          $fixed_images++;
        }
      }
    }
    
    if($fixed_images > 0) {
      $message .= "Copied ".$fixed_images." images to the correct 'images/yachts/' directory.<br>";
    }
  }
}

// Handle Import Missing Images action
if(isset($_POST["import_images"]) && isset($_POST["yacht_id"])) {
  $yacht_id = $_POST["yacht_id"];
  $yacht = $VT->VeriGetir("yachts", "WHERE ID=?", array($yacht_id));
  
  if($yacht) {
    $yacht = $yacht[0];
    $yacht_name = $yacht["baslik"];
    
    // Create yacht directory if needed
    $yacht_dir = $_SERVER['DOCUMENT_ROOT'] . '/' . ltrim(SITE, '/') . 'images/yachts/';
    if(createDirectory($yacht_dir)) {
      $message .= "Created or verified yacht image directory.<br>";
    }
    
    // Check and import images from the yachts table
    if(isset($yacht["resim"]) && !empty($yacht["resim"])) {
      $existing_path = checkImageExists($yacht["resim"]);
      
      if(!$existing_path) {
        // Main image is missing, we need to create a placeholder
        $message .= "Main yacht image is missing. You need to upload a new one.<br>";
      }
    }
    
    // Check images in resimler table
    $resimler = $VT->VeriGetir("resimler", "WHERE KID=?", array($yacht_id));
    $missing_count = 0;
    
    if($resimler) {
      foreach($resimler as $img) {
        $existing_path = checkImageExists($img["resim"]);
        
        if(!$existing_path) {
          $missing_count++;
        }
      }
      
      if($missing_count > 0) {
        $message .= "Found ".$missing_count." missing images. Please upload them below.<br>";
      } else {
        $message .= "All images in the database exist on disk.<br>";
      }
    } else {
      $message .= "No additional images found in the database for this yacht.<br>";
    }
  } else {
    $error = "Yacht not found with ID ".$yacht_id;
  }
}

// Get all yachts for dropdown
$all_yachts = $VT->VeriGetir("yachts", "", array(), "ORDER BY baslik ASC");
?>

<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Yacht Image Diagnostic Tool</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item active">Yacht Image Diagnostic</li>
          </ol>
        </div>
      </div>
    </div>
  </section>

  <section class="content">
    <div class="container-fluid">
      <?php if(!empty($message)): ?>
      <div class="alert alert-success">
        <?=$message?>
      </div>
      <?php endif; ?>
      
      <?php if(!empty($error)): ?>
      <div class="alert alert-danger">
        <?=$error?>
      </div>
      <?php endif; ?>
      
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Select Yacht to Diagnose</h3>
        </div>
        
        <div class="card-body">
          <form method="get" action="">
            <input type="hidden" name="sayfa" value="yacht-image-check">
            
            <div class="form-group">
              <label>Select Yacht</label>
              <select name="yacht_id" class="form-control">
                <option value="">-- Select a Yacht --</option>
                <?php foreach($all_yachts as $y): ?>
                <option value="<?=$y["ID"]?>" <?=isset($_GET["yacht_id"]) && $_GET["yacht_id"] == $y["ID"] ? 'selected' : ''?>>
                  <?=$y["baslik"]?> (ID: <?=$y["ID"]?>)
                </option>
                <?php endforeach; ?>
              </select>
            </div>
            
            <button type="submit" class="btn btn-primary">Diagnose Selected Yacht</button>
          </form>
        </div>
      </div>
      
      <?php if(isset($_GET["yacht_id"]) && !empty($_GET["yacht_id"])): 
        $yacht_id = $_GET["yacht_id"];
        $yacht = $VT->VeriGetir("yachts", "WHERE ID=?", array($yacht_id));
        
        if($yacht):
          $yacht = $yacht[0];
          $yacht_name = $yacht["baslik"];
          $yacht_seo = $yacht["seflink"];
      ?>
      
      <div class="card">
        <div class="card-header bg-primary">
          <h3 class="card-title">Diagnosis for: <?=$yacht_name?></h3>
        </div>
        
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <h4>Basic Information</h4>
              <table class="table table-bordered">
                <tr>
                  <th>Yacht ID</th>
                  <td><?=$yacht["ID"]?></td>
                </tr>
                <tr>
                  <th>Name</th>
                  <td><?=$yacht["baslik"]?></td>
                </tr>
                <tr>
                  <th>SEO Link</th>
                  <td><?=$yacht["seflink"]?></td>
                </tr>
                <tr>
                  <th>Main Image</th>
                  <td>
                    <?php 
                    $main_image = isset($yacht["resim"]) ? $yacht["resim"] : "";
                    $main_image_path = checkImageExists($main_image);
                    
                    if($main_image_path): 
                    ?>
                      <div class="text-success">
                        <i class="fas fa-check-circle"></i> Found at: <?=$main_image_path?>
                      </div>
                      <img src="<?=SITE.$main_image_path?>" alt="<?=$yacht_name?>" style="max-width: 200px; max-height: 100px;">
                    <?php else: ?>
                      <div class="text-danger">
                        <i class="fas fa-times-circle"></i> Not found on disk (filename: <?=$main_image?>)
                      </div>
                    <?php endif; ?>
                  </td>
                </tr>
              </table>
              
              <h4>Directory Check</h4>
              <table class="table table-bordered">
                <?php
                $dir_paths = array(
                  "images/",
                  "images/yachts/",
                  "images/resimler/",
                  "images/yacht/",
                  "images/yatlar/"
                );
                
                foreach($dir_paths as $dir):
                  $full_path = $_SERVER['DOCUMENT_ROOT'] . '/' . ltrim(SITE, '/') . $dir;
                  $exists = is_dir($full_path);
                ?>
                <tr>
                  <th><?=$dir?></th>
                  <td>
                    <?php if($exists): ?>
                      <div class="text-success">
                        <i class="fas fa-check-circle"></i> Directory exists
                      </div>
                    <?php else: ?>
                      <div class="text-danger">
                        <i class="fas fa-times-circle"></i> Directory missing
                      </div>
                    <?php endif; ?>
                  </td>
                </tr>
                <?php endforeach; ?>
              </table>
              
              <div class="mt-4">
                <form method="post" action="">
                  <input type="hidden" name="yacht_id" value="<?=$yacht_id?>">
                  <button type="submit" name="fix_database" class="btn btn-warning">
                    <i class="fas fa-tools"></i> Fix Database Issues
                  </button>
                  
                  <button type="submit" name="import_images" class="btn btn-info ml-2">
                    <i class="fas fa-image"></i> Check Missing Images
                  </button>
                  
                  <a href="<?=SITE?>index.php?sayfa=yacht-detay&seflink=<?=$yacht_seo?>" 
                     target="_blank" class="btn btn-success ml-2">
                    <i class="fas fa-eye"></i> View Yacht Page
                  </a>
                </form>
              </div>
            </div>
            
            <div class="col-md-6">
              <h4>Additional Images</h4>
              <?php
              // Check images in resimler table
              $images_tablo_yachts = $VT->VeriGetir("resimler", "WHERE tablo=? AND KID=?", 
                array("yachts", $yacht_id));
              
              $images_all = $VT->VeriGetir("resimler", "WHERE KID=?", array($yacht_id));
              
              echo '<div class="alert alert-info">';
              echo 'Found ' . ($images_tablo_yachts ? count($images_tablo_yachts) : 0) . 
                   ' images with tablo="yachts" and KID='.$yacht_id.'<br>';
              echo 'Found ' . ($images_all ? count($images_all) : 0) . 
                   ' images with just KID='.$yacht_id.' (any tablo value)';
              echo '</div>';
              
              if($images_all):
              ?>
              <div class="table-responsive">
                <table class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Tablo</th>
                      <th>Filename</th>
                      <th>Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach($images_all as $img): 
                      $img_path = checkImageExists($img["resim"]);
                    ?>
                    <tr>
                      <td><?=$img["ID"]?></td>
                      <td><?=$img["tablo"] ?? '<span class="text-danger">NULL</span>'?></td>
                      <td><?=$img["resim"]?></td>
                      <td>
                        <?php if($img_path): ?>
                          <div class="text-success">
                            <i class="fas fa-check-circle"></i> Found at: <?=$img_path?>
                          </div>
                          <img src="<?=SITE.$img_path?>" alt="Image" style="max-width: 100px; max-height: 50px;">
                        <?php else: ?>
                          <div class="text-danger">
                            <i class="fas fa-times-circle"></i> File not found on disk
                          </div>
                        <?php endif; ?>
                      </td>
                    </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
              <?php else: ?>
              <div class="alert alert-warning">
                No additional images found in the database for this yacht.
              </div>
              <?php endif; ?>
              
              <h4 class="mt-4">Image Upload</h4>
              <div class="alert alert-info">
                If you need to add new images, use the standard image upload tool.
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <?php else: ?>
        <div class="alert alert-warning">
          Yacht not found with ID <?=$yacht_id?>
        </div>
      <?php endif; ?>
      <?php endif; ?>
      
    </div>
  </section>
</div> 