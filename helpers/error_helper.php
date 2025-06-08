<?php
/**
 * Error handling helper functions for Orient Yachting
 * 
 * PHP version 7.3
 */

// Make sure we don't redefine functions
if (!function_exists('log_error')) {
    /**
     * Log error with context information
     * 
     * @param string $message Error message
     * @param string $type Error type (db, file, image, etc.)
     * @param array $context Additional context data
     * @return void
     */
    function log_error($message, $type = 'general', $context = []) {
        // Format message with type prefix
        $log_message = "[$type] $message";
        
        // Add request info to context
        $context['url'] = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : 'Unknown URL';
        $context['method'] = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'Unknown Method';
        $context['ip'] = get_client_ip();
        
        // Add context as JSON if not empty
        if (!empty($context)) {
            $log_message .= ' | Context: ' . json_encode($context);
        }
        
        // Log to PHP error log
        error_log($log_message);
    }
}

if (!function_exists('display_error')) {
    /**
     * Display user-friendly error message
     * 
     * @param string $message Error message for user
     * @param string $technical_message Technical error message (will be logged)
     * @param int $status_code HTTP status code
     * @return void
     */
    function display_error($message = '', $technical_message = '', $status_code = 500) {
        // Log technical message if provided
        if (!empty($technical_message)) {
            log_error($technical_message, 'display_error');
        }
        
        // Set HTTP status if headers not sent
        if (function_exists('http_response_code') && http_response_code() !== false && !headers_sent()) {
            http_response_code($status_code);
        }
        
        // Default message if empty
        if (empty($message)) {
            $message = 'Bir hata oluştu. Lütfen daha sonra tekrar deneyiniz.';
        }
        
        // Return formatted HTML for error message
        $html = '<div class="alert alert-danger">';
        $html .= '<h4>Hata</h4>';
        $html .= '<p>' . htmlspecialchars($message) . '</p>';
        $html .= '</div>';
        
        return $html;
    }
}

if (!function_exists('ajax_error')) {
    /**
     * Return JSON error for AJAX requests
     *
     * @param string $message Error message
     * @param string $technical_message Technical error details (will be logged)
     * @param int $status_code HTTP status code
     * @return void
     */
    function ajax_error($message, $technical_message = '', $status_code = 400) {
        // Log technical message if provided
        if (!empty($technical_message)) {
            log_error($technical_message, 'ajax_error');
        }
        
        // Set HTTP status if headers not sent
        if (function_exists('http_response_code') && http_response_code() !== false && !headers_sent()) {
            http_response_code($status_code);
        }
        
        // Set content type
        if (!headers_sent()) {
            header('Content-Type: application/json');
        }
        
        // Output JSON response
        echo json_encode([
            'success' => false,
            'message' => $message
        ]);
        
        // End script execution
        exit();
    }
}

if (!function_exists('get_client_ip')) {
    /**
     * Get client IP address
     *
     * @return string IP address
     */
    function get_client_ip() {
        if (isset($_SERVER["HTTP_CLIENT_IP"])) {
            $ip = $_SERVER["HTTP_CLIENT_IP"];
        } elseif (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
            $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        } else {
            $ip = isset($_SERVER["REMOTE_ADDR"]) ? $_SERVER["REMOTE_ADDR"] : 'Unknown';
        }
        return $ip;
    }
}

if (!function_exists('exception_handler')) {
    /**
     * Custom exception handler
     * 
     * @param Exception $exception The exception
     * @return void
     */
    function exception_handler($exception) {
        // Log the exception
        $error_message = "Uncaught exception: " . $exception->getMessage();
        $error_message .= " in file " . $exception->getFile() . " on line " . $exception->getLine();
        log_error($error_message, 'exception');
        
        // Check if this is an AJAX request
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            ajax_error('İşlem sırasında bir hata oluştu. Lütfen daha sonra tekrar deneyiniz.');
        } else {
            // Display user-friendly error page
            if (!headers_sent()) {
                header('HTTP/1.1 500 Internal Server Error');
                include_once('error_page.php');
            } else {
                echo display_error();
            }
        }
        
        exit();
    }
}

if (!function_exists('error_handler')) {
    /**
     * Custom error handler
     * 
     * @param int $error_level Error level
     * @param string $error_message Error message
     * @param string $error_file File where error occurred
     * @param int $error_line Line where error occurred
     * @return bool
     */
    function error_handler($error_level, $error_message, $error_file, $error_line) {
        // Check error reporting level
        if (!(error_reporting() & $error_level)) {
            return false;
        }
        
        // Format the error message
        $error_type = 'ERROR';
        switch ($error_level) {
            case E_WARNING:
                $error_type = 'WARNING';
                break;
            case E_NOTICE:
                $error_type = 'NOTICE';
                break;
            case E_USER_ERROR:
                $error_type = 'USER ERROR';
                break;
            case E_USER_WARNING:
                $error_type = 'USER WARNING';
                break;
            case E_USER_NOTICE:
                $error_type = 'USER NOTICE';
                break;
            case E_STRICT:
                $error_type = 'STRICT';
                break;
            case E_RECOVERABLE_ERROR:
                $error_type = 'RECOVERABLE ERROR';
                break;
            case E_DEPRECATED:
                $error_type = 'DEPRECATED';
                break;
            case E_USER_DEPRECATED:
                $error_type = 'USER DEPRECATED';
                break;
        }
        
        // Log the error
        $log_message = "$error_type: $error_message in $error_file on line $error_line";
        log_error($log_message, 'php_error');
        
        // Fatal errors should display the error page
        if ($error_level == E_USER_ERROR) {
            if (!headers_sent()) {
                header('HTTP/1.1 500 Internal Server Error');
                include_once('error_page.php');
            } else {
                echo display_error();
            }
            exit();
        }
        
        // Error handled
        return true;
    }
}

// Register error handlers
set_exception_handler('exception_handler');
set_error_handler('error_handler'); 