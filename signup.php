<?php

    require_once 'init.php';

    $params = array();
    $params['user'] = isset($_POST['user']) ? $_POST['user'] : null;
    $params['pass'] = isset($_POST['pass']) ? $_POST['pass'] : null;

    try {

        if ($_SERVER['REQUEST_METHOD'] == 'POST' ) {

            if (!$params['user'] || in_array($params['user'], $username_exceptions)) {
                throw new Exception("invalid username");
            }
            // if (!$params['pass']) {
            //     throw new Exception("invalid password");
            // }

            $dbh = get_db_connection();
            $dbh->beginTransaction();

            $sql = "INSERT INTO `clmpr`.`users` ( `user` , `pass` , `created` )
                    VALUES ( :user, PASSWORD(:pass), NOW() ) ";
            $q = $dbh->prepare($sql);
            $count = $q->execute( array( ':user' => $params['user'], ':pass' => $params['pass'] ));

            if ($count == 1) {
                // login newly registered user
                $sql = "SELECT * FROM `clmpr`.`users` WHERE `user` = ? AND `pass` = PASSWORD(?)";
                $q = $dbh->prepare($sql);
                $q->execute( array( $params['user'], $params['pass'] ));
                if ($q->rowCount() == 1) {
                    $res = $q->fetch();
                    $_SESSION['user'] = array( 'user' => $res['user'], 'id' => $res['id'] );
                    echo json_encode(array('success'=>true, 'res' => $res));
                } else {
                    $_SESSION['user'] = null;
                    echo json_encode(array('error'=>true, 'mssg' => 'invalid login'));
                }
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
