<?php

include 'init.php';

$params = array();
$params['title'] = isset($_POST['title']) ? $_POST['title'] : null;
$params['url']   = isset($_POST['url'])  ? $_POST['url']  : null;
$params['tags']  = isset($_POST['tags'])  ? $_POST['tags']  : null;
$params['description']  = isset($_POST['description'])  ? $_POST['description']  : null;

include 'head.html';

try {

    if ($user = get_user()) {

        $dbh = get_db_connection();
        $dbh->beginTransaction();

        # process tags
        $tags = explode(" ", $params['tags']);
        $tags = array_unique($tags);
        if (count($tags) > 0) {
            foreach($tags as $key => $tag) {
                $sql = "INSERT INTO `clmpr`.`tags` ( `tag`, `count` ) 
                        VALUES ( ?, 1 ) 
                        ON DUPLICATE KEY UPDATE
                            `count` = `count` + 1";
                $q = $dbh->prepare($sql);
                $q->execute( array( $tag ));
            }
        }

        # insert clump
        $sql = "INSERT INTO `clmpr`.`clumps` ( `user_id`, `title` , `url` , `tags`, `description`, `date` )
                VALUES ( ?, ?, ?, ?, ?, NOW() ) ";  
        $q = $dbh->prepare($sql);
        $insert = $q->execute( array( $user['id'], $params['title'], $params['url'], implode(" ", $tags), htmlentities($params['description']) ));

        echo "clumped.<br/><br/>";
        echo '<a href="javascript:window.close();">ok</a>';
        echo '<script>window.close();</script>';

        $dbh = null;
        $q = null;    

    } else {
        include 'signin.php';
    }
}
catch(PDOException $e)
{
    echo $e->getMessage();
}

exit;
