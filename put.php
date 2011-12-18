<?php

include 'init.php';

$params = array();
$params['title'] = isset($_GET['title']) ? $_GET['title'] : null;
$params['url']  = isset($_GET['url'])  ? $_GET['url']  : null;

try {

    $dbh = get_db_connection();
    $dbh->beginTransaction();

    if ($user = get_user()) {

        $sql = "INSERT INTO `clmpr`.`clumps` ( `user_id`, `title` , `url` , `date` )
                VALUES ( ?, ?, ?, NOW() ) ";
        $q = $dbh->prepare($sql);
        $count = $q->execute( array( $user['id'], $params['title'],$params['url'] ));

        $dbh = null;

        echo "clumped.<br/><br/>";
        echo '<a href="javascript:window.close();">ok</a>';

    } else {

        include 'head.html';
        include 'signin.php';

    }
}
catch(PDOException $e)
{
    echo $e->getMessage();
}

exit;
