<?php

require_once 'init.php';

$url = isset($_GET['_url']) ? trim($_GET['_url'], '/') : '/';

list($endpoint) = explode('/', $url);

$pathinfo = pathinfo($_SERVER['REQUEST_URI']);
$format = isset($pathinfo['extension']) ? $pathinfo['extension'] : null;

$endpoint = str_replace('.'.$format, '', $endpoint);

switch($endpoint)
{
    case 'about' :
        include 'head.html';
        include 'header.html';
        echo '<hr />';

        ?>
        <p>
        clmpr saves internet links
        
        <ul class="tags">
        tags:
        <li>delicious</li>
        <li>clone</li>
        <li>bookmarks</li>
        <li>webservice</li>
        <li>database</li>
        <li>mindmap</li>
        <li>citation</li>
        <li>semantic</li>
        </ul>

        </p>

        <?

        echo '&copy; 2011 <a href="http://quilime.com">gabriel dunne</a>';
        echo '<hr />';
        include 'footer.html';
        exit;

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
