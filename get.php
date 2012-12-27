<?php

require_once 'init.php';

$params = array();
$params['user'] = isset($_GET['user']) ? $_GET['user'] : null;
$params['id']   = isset($_GET['id']) ? $_GET['id'] : null;

$pathinfo = pathinfo($_SERVER['REQUEST_URI']);
$format = isset($pathinfo['extension']) ? $pathinfo['extension'] : null;


$dbh = get_db_connection();
$dbh->beginTransaction();

$q = null;
$tag = null;


try {
    if ($params['user']) {
        $user = get_users($dbh, array('user' => $params['user'] ));
        if ($user) {
            $q = $dbh->prepare("SELECT *, clumps.id as clump_id
                                FROM clumps
                                JOIN users
                                    ON users.id = clumps.user_id
                                WHERE user_id = ?
                                ORDER BY date DESC");
            $q->execute( array( $user['id'] ));
        }
    }
    else if ($params['id']) {
        $q = $dbh->prepare("SELECT *, clumps.id as clump_id
                            FROM clumps
                            JOIN users
                                ON users.id = clumps.user_id
                            WHERE clumps.id = ?
                            ORDER BY date DESC");
        $q->execute( array( $params['id'] ));
    }
    else if ($endpoint == 'tag' && isset($urlparts[1])) {
        $tag = $urlparts[1];
        $q = $dbh->prepare("SELECT *, clumps.id as clump_id
                            FROM clumps
                            JOIN users
                                ON users.id = clumps.user_id
                            WHERE tags LIKE ?
                            ORDER BY date DESC");
        $q->execute( array('%'.$tag.'%') );
    }
    else {
        $q = $dbh->prepare("SELECT *, clumps.id as clump_id
                            FROM clumps
                            JOIN users
                                ON users.id = clumps.user_id
                            ORDER BY date DESC LIMIT 50 ");
        $q->execute();
    }
}
catch(PDOException $e)
{
    echo $e->getMessage();
    exit;
}

switch ($format) {
    case 'xml' :
    case 'rss' :
        include 'rss.php';
        exit;
}


?><!DOCTYPE html>

<head>
<title>clmpr</title>

<?php include 'head.html'; ?>

</head>
<body>

<?php include 'header.html'; ?>


<?php if ($tag) : ?>
<p>
<a href="/tags/">tags</a>: <i><?php echo $tag ?></i>
</p>
<?php else: ?>
<p>
bookmarklet:
<?php
    $js = file_get_contents('bookmarklet.js');
    $js = str_replace('{BASE_URL}', BASE_URL, $js);
?>
<a href="javascript:<?=$js?>">+</a>
</p>
<?php endif; ?>

<hr />

<script>
function deleteClump( id, elem ) {
    if (confirm("confirm delete")) {
        $.post('/delete.php', { clump_id : id }, function(result) {
            $(elem).hide();
        }, 'json');
        return false;
    }
}
</script>


<ul class="links">
<?php if ($q) : for($i = 0; $row = $q->fetch(); $i++ ):

    # skip private bookmarks of other users
    if ($user['user'] != $row['user'] && $row['private']) {
        continue;
    }

    # is private
    $private = $user['user'] == $row['user'] && $row['private'];

    # process description
    $hasDescription = $row['description'] || false;

    # process tags
    $row['tags'] = tag_string_to_array( $row['tags'] );

?>

    <li>

        <span class="url <?php echo $private ? 'private' : '';?>">
            <a href="<?php echo $row['url'] ?>">
                <?php echo $row['title'] ? $row['title'] : "&lt;title&gt;" ?>
            </a>
        </span>

        <div class="expand">
            <?php if ($hasDescription) : ?>
                <span class="desc">
                <?php echo nl2br(htmlentities($row['description'])); ?>
                </span><br />
            <?php endif; ?>
            <?php if (count($row['tags']) > 0) : ?>
            <ul class="tags">
                <?php foreach($row['tags'] as $tag) : ?>
                <li><a href="/tag/<?=$tag?>"><?=$tag?></a></li>
                <? endforeach; ?>
            </ul>
            <?php endif; ?>
        </div>

        <span class="meta">

            <a href="/get.php?id=<?php echo $row['clump_id'] ?>">#</a>

            <span title="<?php echo date('r', strtotime($row['date'])); ?>">
                <?php echo approximate_time(date('U') - strtotime($row['date'])) ?> ago</a>
                <a class="uname" href="/<?php echo $row['user'] ?>"><?php echo $row['user'] ?></a>
            </span>


        <?php if ($user['user'] == $row['user']): ?>
            <a href="/edit.php?id=<?php echo $row['clump_id'];?>" class="ui edit">edit</a>
            <a href="#" title="Delete" onClick="return deleteClump(<?php echo $row['clump_id']; ?>, this.parentNode.parentNode);" class="ui delete">delete</a>
        <?php endif; ?>

        </span>

    </li>

<?php endfor; endif; ?>
</ul>


<hr />

<?php include 'footer.html' ?>
