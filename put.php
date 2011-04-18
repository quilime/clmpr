<?php

    include 'init.php';

    $params = array();
    $params['title'] = isset($_GET['title']) ? $_GET['title'] : null;
    $params['location']  = isset($_GET['location'])  ? $_GET['location']  : null;

    try {

        $dbh = get_db_connection();
        $dbh->beginTransaction();

        if ($user = get_user()) {

            $user['user'];

            $sql = "INSERT INTO `clmpr`.`clumps` ( `user_id`, `title` , `location` , `date` )
                    VALUES ( ?, ?, ?, NOW() ) ";
            $q = $dbh->prepare($sql);
            $count = $q->execute( array( $user['id'], $params['title'],$params['location'] ));

            $dbh = null;

            echo "clumped.<br/><br/>";
            echo '<a href="javascript:window.close();">ok</a>';

        } else {

            include 'head.html';
            include 'signin.php';

        }
        exit;
    }
    catch(PDOException $e)
    {
        echo $e->getMessage();
    }

    exit;