<?php

/**
 * Amanah Safar - Root Front Controller Fallback
 * Berfungsi sebagai jembatan jika server web (Nginx/LiteSpeed/Apache)
 * langsung membaca file index di direktori utama (root).
 */

// Arahkan eksekusi ke front-controller CodeIgniter 4 di dalam folder public
require_once __DIR__ . '/public/index.php';
