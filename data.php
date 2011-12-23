<?php


function get_db_connection()
{
    try {
        return $dbh = new PDO(DB_DSN, DB_UNAME, DB_PW);
    }
    catch(PDOException $e) {
            return $e->getMessage();
    }
}


function get_user()
{
    return isset($_SESSION['user']) ? $_SESSION['user'] : false;
}


function get_users(&$dbh, $args)
{
    $user = isset($args['user']) ? $args['user'] : false;

    try {
        if ($user) {
            $sql = "SELECT * FROM users WHERE user = ?";
            $q = $dbh->prepare($sql);
            $q->execute( array( $user ));
            if ($q->rowCount() == 1) {
                return $q->fetch();
            }
        }
    }
    catch (PDOException $e) {
        echo $e->getMessage();
    }

    return false;
}


function tag_string_to_array($tagstr) {
    # if has commas, split on commas
    # else split on spaces
        // TODO
        # check for quotes (for "multiple words")
    return strpos($tagstr, ',') ? explode(',', $tagstr) : explode(' ', $tagstr);
}
