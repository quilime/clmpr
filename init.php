<?php

session_start();

# timezone
putenv('TZ=America/Los_Angeles');

# include folders
ini_set('include_path', dirname(realpath(__FILE__)));
define('TMP_DIR', dirname(realpath(__FILE__)) .'/../tmp');

# settings
define('BASE_URL', 'example.com');

# database
define('DB_DSN',    "mysql:host=<mysql.hostname.com>;dbname=<dbnam>");
define('DB_UNAME',  "<user>");
define('DB_PW',     "<pass>");

ini_set('display_errors', 1);
error_reporting( E_ALL );

include 'data.php';
