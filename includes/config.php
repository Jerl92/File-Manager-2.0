<?php

// Base URL of the application
define('BASE_URL','https://'. $_SERVER['HTTP_HOST'].'/wp-content/plugins/file-manager/includes/');

// Path of the download-link.php file
define('DOWNLOAD_PATH', BASE_URL.'download.php');

// Path of the token directory to store keys
define('TOKEN_DIR', dirname(__FILE__) . '/tokens');

// Expiration time of the link (examples: +1 year, +1 month, +5 days, +10 hours)
define('EXPIRATION_TIME', '+12 hours');