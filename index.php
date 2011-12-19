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
        use clmpr to save links on the internet
        

        <ul class="tags">
        keywords:
        <li>bookmarks</li>
        <li>delicious-clone</li>
        <li>webservice</li>
        <li>database</li>
        <li>mindmap</li>
        <li>citation</li>
        <li>semantic</li>
        </ul>

        </p>

        <p>
        clmpr is open source. fork the project on <a href="http://github.com/quilime/clmpr">github</a>
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
