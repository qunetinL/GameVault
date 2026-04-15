<?php

require_once __DIR__ . '/../vendor/autoload.php';

// Start session for tests that depend on $_SESSION
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
