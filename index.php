<?php

    require_once 'init.php';

    $url = trim($_GET['_url'], '/');

    list($endpoint) = explode('/', $url);


    $dbh = get_db_connection();
    $dbh->beginTransaction();
    switch($endpoint)
    {
        case 'get' :
            include 'get.php';
            exit;
        case 'put' :
            exit;
        default :
            if ($endpoint != '') {
                $user = get_users($dbh, array( 'user' => $endpoint ));
                if ( isset($user['user']) ) {
                    $get = function( $user ) {
                        $_GET['user'] = $user;
                        include 'get.php';
                        exit;
                    };
                    $get( $user['user'] );
                } else {
                    // else 404
                    $_GET['error'] = '404';
                    include 'error.php';
                    exit;
                }
            }
    }
    $dbh = null;

?><!DOCTYPE html>

<head>
<title>clmpr</title>

<?php include 'head.html'; ?>

</head>

<body>

<b>C</b>itation, <b>L</b>ogging and <b>M</b>ulti-<b>P</b>urpose a<b>R</b>chive

<?php include 'signin.php'; ?>

<p>
bookmarklet:
<?php
$js = file_get_contents('bookmarklet.js');
?>
<br />
<a href="javascript:<?=$js?>">+</a>
