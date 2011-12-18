<?php

include 'init.php';

$params = array();
$params['title'] = isset($_POST['title']) ? $_POST['title'] : null;
$params['url']   = isset($_POST['url'])  ? $_POST['url']  : null;
$params['tags']  = isset($_POST['tags'])  ? $_POST['tags']  : null;

include 'head.html';

try {

    if ($user = get_user()) {

        $dbh = get_db_connection();
        $dbh->beginTransaction();

        # insert clump
        $sql = "INSERT INTO `clmpr`.`clumps` ( `user_id`, `title` , `url` , `tags`, `date` )
                VALUES ( ?, ?, ?, ?, NOW() ) ";
        $q = $dbh->prepare($sql);
        $insert = $q->execute( array( $user['id'], $params['title'], $params['url'], $params['tags'] ));

        # process tags
        $tags = explode(" ", $params['tags']);
        if (count($tags) > 0) {
            foreach($tags as $tag) {
                $sql = "INSERT INTO `clmpr`.`tags` ( `tag` ) VALUES ( ? ) ";
                $q = $dbh->prepare($sql);
                $q->execute( array( $tag ));
            }
        }

        $dbh = null;

        echo "clumped.<br/><br/>";
        //echo '<a href="javascript:window.close();">ok</a>';
        echo '<script>window.close();</script>';

        include 'foot.html';

    } else {

        include 'signin.php';

    }
}
catch(PDOException $e)
{
    echo $e->getMessage();
}

exit;
