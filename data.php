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
    $tags = array();
    if (strpos($tagstr, ',')) {
        $tags = explode(',', $tagstr);
    } else {
        # match all quoted strings
        preg_match_all('/"(.*?)"/', $tagstr, $quoted_tags);
        # strip quoted strings from tag string
        $tagstr_noquotes = str_replace($quoted_tags[0], '', $tagstr);
        # split new string on spaces
        $tags_woutquotes = explode(' ', $tagstr_noquotes);
        # merge arrays
        $tags = array_merge($tags_woutquotes, $quoted_tags[1]);
    }
    # trim all values
    array_walk($tags, function(&$item, $key) {
             $item = trim($item);
    });

    return array_filter($tags);
}
