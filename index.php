<?php

require_once 'init.php';

$url = isset($_GET['_url']) ? trim($_GET['_url'], '/') : '/';

$urlparts = explode('/', $url);
list($endpoint) = $urlparts;

$pathinfo = pathinfo($_SERVER['REQUEST_URI']);
$format = isset($pathinfo['extension']) ? $pathinfo['extension'] : null;

$endpoint = str_replace('.'.$format, '', $endpoint);

switch($endpoint)
{
    case 'about' :
        include 'head.html';
        include 'header.html';
        include 'about.html';
        include 'footer.html';
        exit;

    case 'tag' : 
    case 'tags' : 
        if (count($urlparts) == 1) {
            include 'tags.php';
            exit;
        }
    
    default :
        if ($endpoint != '') {
            $dbh = get_db_connection();
            $dbh->beginTransaction();
            $user = get_users($dbh, array( 'user' => $endpoint ));
            $dbh = null;
            if ( isset($user['user']) ) {
                $get = function( $user ) {
                    $_GET['user'] = $user;
                };
                $get( $user['user'] );
            }
        }
}

include 'get.php';
