<?php
/**
 * Root Router / Forwarder
 * 
 * This file forwards all requests to the public directory.
 * Used when the web server is pointed to the project root instead of the public folder.
 */

// If the requested file or directory actually exists in the root, serve it (except for this file)
if ($_SERVER['REQUEST_URI'] !== '/' && file_exists(__DIR__ . $_SERVER['REQUEST_URI'])) {
    return false;
}

// Otherwise, require the public/index.php file
require_once __DIR__ . '/public/index.php';