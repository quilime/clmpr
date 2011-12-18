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
        echo "clmpr is a place to save hyperlinks";
        echo '<br /><a href="https://github.com/quilime/clmpr">source on github</a>';
        echo '<br />&copy; 2011 <a href="http://quilime.com">quilime</a>';
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

<div class="header"><b>c</b>itation, <b>l</b>ogging and <b>m</b>ulti-<b>p</b>urpose a<b>r</b>chive</div>

<?php include 'signin.php'; ?>

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
<div id="footer">
      <a class="about" href="/about">about</a> | <a href="https://github.com/quilime/clmpr">source on github</a>
</div>
