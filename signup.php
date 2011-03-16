<?php

    require_once 'init.php';

    $params = array();
    $params['user'] = isset($_POST['user']) ? $_POST['user'] : null;
    $params['pass'] = isset($_POST['pass']) ? $_POST['pass'] : null;

    try {

        if ($_SERVER['REQUEST_METHOD'] == 'POST' ) {

            $dbh = get_db_connection();
            $dbh->beginTransaction();

            $sql = "INSERT INTO `clmpr`.`users` ( `user` , `pass` , `created` )
                    VALUES ( :user, PASSWORD(:pass), NOW() ) ";
            $q = $dbh->prepare($sql);
            $count = $q->execute( array( ':user' => $params['user'], ':pass' => $params['pass'] ));

            if ($count == 1) {
                echo json_encode(array('success' => true, 'mssg' => 'welcome, ' . $params['user'] . '. your password is <b>' . $params['pass'] . '</b>' ));
            } else {
                echo json_encode(array('exists' => true, 'mssg' => 'user already exists' ));
            }

            $dbh = null;


        }

    }
    catch(PDOException $e)
    {
        echo json_encode(array('success' => true, 'mssg' => $e->getMessage() ));
    }

    exit;