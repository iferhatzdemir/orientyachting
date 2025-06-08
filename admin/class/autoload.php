<?php
/**
 * Autoload file for classes
 * Provides basic autoloading functionality
 */

// Define the class directory
define('CLASS_DIR', __DIR__ . '/');

// Register autoload function
spl_autoload_register(function($className) {
    $classFile = CLASS_DIR . $className . '.php';
    
    if (file_exists($classFile)) {
        require_once $classFile;
        return true;
    }
    
    return false;
});

// Log autoload status
error_log("Autoload file loaded successfully");
?> 