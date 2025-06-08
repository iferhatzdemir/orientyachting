<?php
/**
 * Class Autoloader
 * 
 * Automatically loads class files when they are instantiated
 * 
 * @version 1.0
 */

// Define the classes directory if not already defined
if (!defined('SINIF')) {
    define('SINIF', __DIR__ . '/');
}

/**
 * Autoloader function
 * Loads class files when they are instantiated
 * 
 * @param string $className Name of the class to load
 * @return void
 */
function orientAutoloader($className) {
    // Class file path
    $classFile = SINIF . $className . '.php';
    
    // Load class file if it exists
    if (file_exists($classFile)) {
        include $classFile;
        return;
    }
    
    // Search in subdirectories if main directory doesn't contain the class
    if (is_dir(SINIF)) {
        $dirs = glob(SINIF . '*', GLOB_ONLYDIR);
        foreach ($dirs as $dir) {
            $classFile = $dir . '/' . $className . '.php';
            if (file_exists($classFile)) {
                include $classFile;
                return;
            }
        }
    }
    
    // Try with class name transformation (e.g., UserModel => Models/User.php)
    if (substr($className, -5) === 'Model') {
        $baseClassName = substr($className, 0, -5);
        $modelClassFile = SINIF . 'Models/' . $baseClassName . '.php';
        if (file_exists($modelClassFile)) {
            include $modelClassFile;
            return;
        }
    }
    
    // Try for controllers
    if (substr($className, -10) === 'Controller') {
        $baseClassName = substr($className, 0, -10);
        $controllerClassFile = SINIF . 'Controllers/' . $baseClassName . '.php';
        if (file_exists($controllerClassFile)) {
            include $controllerClassFile;
            return;
        }
    }
    
    // Log error if class file not found (but don't throw exception to allow fallback)
    error_log("Class file not found for: $className");
}

// Register autoloader
spl_autoload_register('orientAutoloader');

// Debug info
error_log("Autoloader registered for classes in: " . SINIF);
?> 