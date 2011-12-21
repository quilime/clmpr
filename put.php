<?php

include 'init.php';

print_r($_POST);
exit;

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
        $tags = filter_tags($params['tags']);
        print_r($tags);
        echo '<br />TODO: figure out delimiter for spaces for database';
        exit;

        #insert tags
        if (count($tags) > 0) {
            foreach($tags as $key => $tag) {
                $q = $dbh->prepare("INSERT INTO `clmpr`.`tags` (`tag`, `count`)
                                    VALUES ( ?, 1 )
                                    ON DUPLICATE KEY
                                        UPDATE `count` = `count` + 1");
                $q->execute(array($tag));
            }
        }

        # insert clump
        $q = $dbh->prepare("INSERT INTO `clmpr`.`clumps`
                          ( `user_id`,
                            `title`,
                            `url`,
                            `tags`,
                            `description`,
                            `date` )
                        VALUES ( ?, ?, ?, ?, ?, NOW() ) ");
        $insert = $q->execute( array(
                    $user['id'],
                    $params['title'],
                    $params['url'],
                    implode(" ", $tags),
                    htmlentities($params['description'])));

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
