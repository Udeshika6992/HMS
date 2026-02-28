<?php
/**
 * Global View Helpers
 * Include this in all layout files to make BASE_URL available everywhere
 */

// Define global helper functions that all views can use
if (!function_exists('base_url')) {
    function base_url($path = '') {
        // Try to get BASE_URL from various sources
        if (defined('BASE_URL')) {
            $base = BASE_URL;
        } elseif (isset($GLOBALS['BASE_URL'])) {
            $base = $GLOBALS['BASE_URL'];
        } else {
            // Fallback - detect automatically
            $base = '/HMS/';
        }
        return $base . ltrim($path, '/');
    }
}

if (!function_exists('asset_url')) {
    function asset_url($path) {
        return base_url('assets/' . ltrim($path, '/'));
    }
}

if (!function_exists('upload_url')) {
    function upload_url($path) {
        return base_url('uploads/' . ltrim($path, '/'));
    }
}

// Make BASE_URL available as a global variable
if (defined('BASE_URL')) {
    $GLOBALS['BASE_URL'] = BASE_URL;
} else {
    $GLOBALS['BASE_URL'] = '/HMS/';
}

// Alias for easy access in views
$base = $GLOBALS['BASE_URL'];