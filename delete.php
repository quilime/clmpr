<?php

    include 'init.php';

    $params = array();
    $params['clump_id'] = isset($_POST['clump_id']) ? $_POST['clump_id'] : null;

    try {

        $dbh = get_db_connection();
        $dbh->beginTransaction();

        if ($user = get_user()) {
            $sql = "DELETE FROM clmpr.clumps WHERE id = ? AND user_id = ?";
            $q = $dbh->prepare($sql);
            $count = $q->execute( array( $params['clump_id'], $user['id'] ));
            $dbh = null;
            echo json_encode(array('deleted' => true));
            exit;
        } else {
            echo json_encode(array('mssg' => 'must be logged in'));
        }
        exit;
    }
    catch(PDOException $e)
    {
        echo $e->getMessage();
    }

    exit;