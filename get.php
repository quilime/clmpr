<?php

    require_once 'init.php';

    $params = array();
    $params['user'] = isset($_GET['user']) ? $_GET['user'] : null;


    $dbh = get_db_connection();
    $dbh->beginTransaction();

    try {
        if ($params['user']) {
            $user = get_users($dbh, array('user' => $params['user'] ));
            if ($user) {
                $q = $dbh->prepare(" SELECT * FROM clumps JOIN users ON users.id = clumps.user_id WHERE user_id = ? ORDER BY date DESC ");
                $q->execute( array( $user['id'] ));
            }
            else {
                throw( new PDOException(sprintf("user %s doesn't exist", $params['user'])));
            }
        }
        else
        {
            $q = $dbh->prepare("SELECT * FROM clumps JOIN users ON users.id = clumps.user_id ORDER BY date DESC");
            $q->execute();
        }
    }
    catch(PDOException $e)
    {
        echo $e->getMessage();
        exit;
    }

    include 'head.html';

    echo '<ul>';
    for($i = 0; $row = $q->fetch(); $i++ ) {
        echo '<li>';
        echo sprintf(
            '%s - <a href="/get.php?user=%s">%s</a> : <a href="%s">%s</a>'
            ,   $row['date']
            ,   $row['user']
            ,   $row['user']
            ,   $row['location']
            ,   $row['title']);
        echo $row['tags'] ? '<span class="">' . $row['tags'] . '</span>' : '';
        echo '</li>';
    }
    echo '</ul>';