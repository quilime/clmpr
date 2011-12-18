<?php

include 'init.php';

$params = array();
$params['id']    = isset($_POST['id']) ? $_POST['id'] : null;
$params['title'] = isset($_POST['title']) ? $_POST['title'] : null;
$params['url']   = isset($_POST['url'])   ? $_POST['url']  : null;
$params['tags']  = isset($_POST['tags'])  ? $_POST['tags']  : null;
$params['description']  = isset($_POST['description'])  ? $_POST['description']  : null;

include 'head.html';

try {

    if ($user = get_user() && $params['id']) {

        $dbh = get_db_connection();
        $dbh->beginTransaction();

        if ($params['id']) {
            $q = $dbh->prepare("SELECT *, clumps.id as clump_id 
                                FROM clumps 
                                JOIN users 
                                    ON users.id = clumps.user_id 
                                WHERE clumps.id = ? 
                                ORDER BY date DESC");
            $q->execute( array( $params['id'] ));        
        }
        $clump = $q->fetch();
        $clump['tags'] = explode(" ", $clump['tags']);
 
        # process tags
        $tags = explode(" ", $params['tags']);
        $tags = array_unique($tags);
        $tags_to_keep = array_intersect($tags, $clump['tags']);
        $tags_to_delete = array_diff($clump['tags'], $tags_to_keep);
        $tags_to_add = array_diff($tags, $tags_to_keep);

        # add/increment new tags
        if (count($tags_to_add) > 0) {
            foreach($tags_to_add as $key => $tag) {
                $sql = "INSERT INTO `clmpr`.`tags` ( `tag`, `count` ) 
                        VALUES ( ?, 1 ) 
                        ON DUPLICATE KEY UPDATE
                            `count` = `count` + 1";
                $q = $dbh->prepare($sql);
                $q->execute( array( $tag ));
            }
        }

        # decrement old tags
        # note: leaves tags in database with count of '0' if not used
        if (count($tags_to_delete) > 0) {
            foreach($tags_to_delete as $key => $tag) {
                $sql = "UPDATE tags
                        SET count = count - 1
                        WHERE tag = ? AND count > 0";
                $q = $dbh->prepare($sql);
                $q->execute( array( $tag ));
            }
        }               

        # update clump
        $sql = "UPDATE `clumps` 
                SET `url` = ?, `tags` = ?, `title` = ?, `description` = ? 
                WHERE `id` = ?";
        $q = $dbh->prepare($sql);
        $insert = $q->execute( array( $params['url'], implode(" ", $tags), $params['title'], $params['description'], $params['id']));

        header('Location: /get.php?id=' . $params['id']);
        //echo "clumped.<br/><br/>";
        //echo '<a href="javascript:window.close();">ok</a>';
        //echo '<script>window.close();</script>';

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
