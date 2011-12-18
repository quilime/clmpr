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

switch ($format) {
    case 'rss' :
    case 'xml' :
        include 'get.php';
        exit;
}

?><!DOCTYPE html>

<head>
<title>clmpr</title>

<?php include 'head.html'; ?>

</head>
<body>

<?php include 'header.html'; ?>

<p>
bookmarklet:
<?php
    $js = file_get_contents('bookmarklet.js');
?>
<a href="javascript:<?=$js?>">+</a>
</p>


<hr />

<?php include 'get.php'; ?>

<hr />

<?php include 'footer.html' ?>
