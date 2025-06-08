<?php
// AJAX handler for yacht filtering
include_once("../data/baglanti.php");

// Set proper content type for AJAX response
header('Content-Type: text/html; charset=utf-8');

try {
    // Make sure VT is defined (database connection)
    if (!isset($VT) || !is_object($VT)) {
        throw new Exception("Database connection not available");
    }
    
    // Check if we're receiving data via POST or GET
    $requestMethod = $_SERVER['REQUEST_METHOD'];
    $inputData = ($requestMethod === 'POST') ? $_POST : $_GET;
    
    // Get filter parameters
    $type_filter = isset($inputData["type"]) ? $inputData["type"] : "";
    $location_filter = isset($inputData["location"]) ? $inputData["location"] : "";
    $length_filter = isset($inputData["length"]) ? $inputData["length"] : "";
    $capacity_filter = isset($inputData["capacity"]) ? $inputData["capacity"] : "";
    $price_max = isset($inputData["price_max"]) ? $inputData["price_max"] : "";
    $sort = isset($inputData["sort"]) ? $inputData["sort"] : "price_asc";
    
    // Build the query
    $whereClause = "WHERE durum=?";
    $queryParams = array(1);
    
    // Apply filters
    if(!empty($type_filter)) {
      $whereClause .= " AND type_id=?";
      $queryParams[] = $type_filter;
    }
    
    if(!empty($location_filter)) {
      $whereClause .= " AND location_id=?";
      $queryParams[] = $location_filter;
    }
    
    if(!empty($length_filter)) {
      if(strpos($length_filter, "-") !== false) {
        $length_range = explode("-", $length_filter);
        $whereClause .= " AND length_m BETWEEN ? AND ?";
        $queryParams[] = $length_range[0];
        $queryParams[] = $length_range[1];
      } else if(strpos($length_filter, "+") !== false) {
        $min_length = intval($length_filter);
        $whereClause .= " AND length_m >= ?";
        $queryParams[] = $min_length;
      }
    }
    
    if(!empty($capacity_filter)) {
      if(strpos($capacity_filter, "-") !== false) {
        $capacity_range = explode("-", $capacity_filter);
        $whereClause .= " AND capacity BETWEEN ? AND ?";
        $queryParams[] = $capacity_range[0];
        $queryParams[] = $capacity_range[1];
      } else if(strpos($capacity_filter, "+") !== false) {
        $min_capacity = intval($capacity_filter);
        $whereClause .= " AND capacity >= ?";
        $queryParams[] = $min_capacity;
      }
    }
    
    if(!empty($price_max) && $price_max != "10000") {
      $whereClause .= " AND price_per_day <= ?";
      $queryParams[] = $price_max;
    }
    
    // Sorting
    $orderBy = "ORDER BY ";
    
    switch($sort) {
      case "price_desc":
        $orderBy .= "price_per_day DESC";
        break;
      case "length_asc":
        $orderBy .= "length_m ASC";
        break;
      case "length_desc":
        $orderBy .= "length_m DESC";
        break;
      case "newest":
        $orderBy .= "ID DESC";
        break;
      default:
        $orderBy .= "price_per_day ASC";
    }
    
    // Get yachts from database
    $yachts = $VT->VeriGetir("yachts", $whereClause, $queryParams, $orderBy);
    
    // Start HTML output
    ob_start();
    
    if($yachts != false) {
      echo '<div class="row yacht-cards-container">';
      
      foreach($yachts as $yacht) {
        // Get location name
        $location_name = "";
        if(!empty($yacht["location_id"])) {
          $location_data = $VT->VeriGetir("yacht_locations", "WHERE ID=?", array($yacht["location_id"]), "ORDER BY ID ASC", 1);
          if($location_data != false) {
            $location_name = $location_data[0]["baslik"];
          }
        }
        
        // Get yacht type
        $yacht_type = "";
        if(!empty($yacht["type_id"])) {
          $type_data = $VT->VeriGetir("yacht_types", "WHERE ID=?", array($yacht["type_id"]), "ORDER BY ID ASC", 1);
          if($type_data != false) {
            $yacht_type = $type_data[0]["baslik"];
          }
        }
        
        // Get main image
        $image_path = "assets/img/yacht-placeholder.jpg";
        if(!empty($yacht["resim"])) {
          $image_path = "images/yachts/" . $yacht["resim"];
        }
        ?>
        <div class="col-md-6 col-xl-4 yacht-card-wrapper" data-aos="fade-up">
          <div class="yacht-card">
            <div class="yacht-card-image">
              <a href="<?=SITE?>yat/<?=$yacht["seflink"]?>">
                <img src="<?=SITE?><?=$image_path?>" alt="<?=$yacht["baslik"]?>" loading="lazy">
              </a>
              <?php if($yacht["availability"] == 1): ?>
              <div class="yacht-status available">Available</div>
              <?php else: ?>
              <div class="yacht-status booked">Booked</div>
              <?php endif; ?>
              
              <?php if(!empty($yacht_type)): ?>
              <div class="yacht-type"><?=$yacht_type?></div>
              <?php endif; ?>
            </div>
            
            <div class="yacht-card-content">
              <h3 class="yacht-title">
                <a href="<?=SITE?>yat/<?=$yacht["seflink"]?>"><?=$yacht["baslik"]?></a>
              </h3>
              
              <?php if(!empty($location_name)): ?>
              <div class="yacht-location">
                <i class="fas fa-map-marker-alt"></i> <?=$location_name?>
              </div>
              <?php endif; ?>
              
              <div class="yacht-specs">
                <div class="spec-item">
                  <i class="fas fa-ruler-horizontal"></i>
                  <span><?=$yacht["length_m"]?> m</span>
                </div>
                <div class="spec-item">
                  <i class="fas fa-users"></i>
                  <span><?=$yacht["capacity"]?> <?=$yacht["capacity"] > 1 ? 'guests' : 'guest'?></span>
                </div>
                <div class="spec-item">
                  <i class="fas fa-bed"></i>
                  <span><?=$yacht["cabins"]?> <?=$yacht["cabins"] > 1 ? 'cabins' : 'cabin'?></span>
                </div>
              </div>
              
              <div class="yacht-price">
                <div class="price-amount">€<?=number_format($yacht["price_per_day"], 0, '.', ',')?></div>
                <div class="price-period">per day</div>
              </div>
              
              <div class="yacht-actions">
                <a href="<?=SITE?>yat/<?=$yacht["seflink"]?>" class="btn btn-outline-primary">View Details</a>
                <a href="<?=SITE?>reservation?yacht=<?=$yacht["ID"]?>" class="btn btn-primary">Book Now</a>
              </div>
            </div>
          </div>
        </div>
        <?php
      }
      
      echo '</div>';
      
      // If no yachts found
      if(count($yachts) == 0) {
        echo '<div class="no-results" data-aos="fade-up">';
        echo '<img src="'.SITE.'assets/img/no-results.svg" alt="No results found">';
        echo '<h3>No Yachts Found</h3>';
        echo '<p>Please try adjusting your search filters or contact our yacht specialists directly for personalized recommendations.</p>';
        echo '</div>';
      }
    } else {
      // If no yachts found
      echo '<div class="no-results" data-aos="fade-up">';
      echo '<img src="'.SITE.'assets/img/no-results.svg" alt="No results found">';
      echo '<h3>No Yachts Found</h3>';
      echo '<p>Please try adjusting your search filters or contact our yacht specialists directly for personalized recommendations.</p>';
      echo '</div>';
    }
    
    $html = ob_get_clean();
    echo $html;

} catch (Exception $e) {
    // Log the error
    error_log("Yacht filter error: " . $e->getMessage());
    
    // Return a user-friendly error
    echo '<div class="no-results" data-aos="fade-up">';
    echo '<img src="'.SITE.'assets/img/no-results.svg" alt="Error occurred">';
    echo '<h3>Oops! Something went wrong</h3>';
    echo '<p>There was an error loading the results. Please try again or contact us for assistance.</p>';
    echo '<button class="btn btn-primary mt-3" onclick="window.location.reload()">Try Again</button>';
    echo '</div>';
}
?> 