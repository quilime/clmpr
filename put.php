<?php

include 'init.php';

$params = array();
$params['title'] = isset($_POST['title']) ? $_POST['title'] : null;
$params['url'] = isset($_POST['url']) ? $_POST['url'] : null;
$params['tags'] = isset($_POST['tags']) ? $_POST['tags'] : null;
$params['description'] = isset($_POST['description']) ? $_POST['description'] : null;
$params['private'] = isset($_POST['private']) ? true : false;

//print_r($_POST);
//exit;

include 'head.html';

try {

    if ($user = get_user()) {

        $dbh = get_db_connection();
        $dbh->beginTransaction();

        # process tags
        $tags = tag_string_to_array( $params['tags'] );
        if (count($tags) > 0) {
            foreach($tags as $key => $tag) {
                $q = $dbh->prepare("INSERT INTO `tags` (`tag`, `count`)
                                    VALUES ( ?, 1 )
                                    ON DUPLICATE KEY
                                    UPDATE `count` = `count` + 1");
                $q->execute(array($tag));
            }
        }

        # insert clump
        $q = $dbh->prepare("INSERT INTO `clumps`
                          ( `user_id`, `title`, `url`,
                            `tags`, `description`, `date`, `private` )
                            VALUES ( ?, ?, ?, ?, ?, NOW(), ? ) ");
        $insert = $q->execute( array(
                    $user['id'], $params['title'], $params['url'],
                    $params['tags'], $params['description'], $params['private']));

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
