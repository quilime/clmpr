<?php

include 'init.php';

$params = array();
$params['clump_id'] = isset($_POST['clump_id']) ? $_POST['clump_id'] : null;

try {

    $dbh = get_db_connection();
    $dbh->beginTransaction();
    $user = get_user();

    if ($user && $params['clump_id']) {

        # get current clump
        $q = $dbh->prepare("SELECT *
                            FROM clumps
                            JOIN users ON users.id = clumps.user_id
                            WHERE user_id = ? AND clumps.id = ?
                            LIMIT 1");
        $q->execute( array( $user['id'], $params['clump_id'] ));
        $row = $q->fetch();

        # decrement tag count in DB
        # note: leaves tags in database with count of '0' if not used
        $tags = explode(",", $row['tags']);
        if (count($tags) > 0) {
            foreach($tags as $key => $tag) {
                $sql = "UPDATE tags
                        SET count = count - 1
                        WHERE tag = ? AND count > 0";
                $q = $dbh->prepare($sql);
                $q->execute( array( $tag ));
            }
        }

        # delete clump
        $sql = "DELETE FROM clumps WHERE id = ? AND user_id = ?";
        $q = $dbh->prepare($sql);
        $count = $q->execute( array( $params['clump_id'], $user['id'] ));
        echo json_encode(array('deleted' => true));
    } else {
        echo json_encode(array('mssg' => 'must be logged in'));
    }

    $dbh = null;
    $q = null;

}
catch(PDOException $e)
{
    echo $e->getMessage();
}

exit;
