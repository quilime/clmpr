<?php

    include 'init.php';

    $params = array();
    $params['user'] = isset($_GET['user']) ? $_GET['user'] : null;

    $dbh = get_db_connection();
    $dbh->beginTransaction();

    try {

        if ($params['user']) {

            $user = get_users($dbh, array('user' => $params['user'] ));

            if ($user) {
                $q = $dbh->prepare(" SELECT * FROM `clumps` JOIN users ON users.id = clumps.user_id WHERE `user_id` = ? ORDER BY date DESC ");
                $q->execute( array( $user['id'] ));
            }

        } else
        {
            $q = $dbh->prepare("SELECT * FROM `clumps` JOIN users ON users.id = clumps.user_id ORDER BY date DESC");
            $q->execute();
        }
    }
    catch(PDOException $e)
    {
        echo $e->getMessage();
    }


    for($i = 0; $row = $q->fetch(); $i++ ) {
        echo $row['date'] . ' - <a href="/get.php?user='.$row['user'].'">' . $row['user'] . '</a>: <a href="' . $row['location'] . '">' . $row['title'] . '</a><br />';
    }
