<?php

    require_once 'init.php';

    $url = trim($_GET['_url'], '/');

    list($endpoint) = explode('/', $url);

    $pathinfo = pathinfo($_SERVER['SCRIPT_URL']);
    $format = isset($pathinfo['extension']) ? $pathinfo['extension'] : null;

    $endpoint = str_replace('.'.$format, '', $endpoint);

    switch($endpoint)
    {
        case 'about' :
            break;

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
            include 'get.php';
            exit;
    }

?><!DOCTYPE html>

<head>
<title>clmpr</title>

<?php include 'head.html'; ?>

</head>

<body>

<div class="header"><b>C</b>itation, <b>L</b>ogging and <b>M</b>ulti-<b>P</b>urpose a<b>R</b>chive</div>

<?php include 'signin.php'; ?>

<p>
bookmarklet:
<?php
$js = file_get_contents('bookmarklet.js');
?>
 <a href="javascript:<?=$js?>">+</a>

<hr />

<?php include 'get.php'; ?>

<hr />
<div id="footer">
    <a class="about" href="/about">about</a>
</div>