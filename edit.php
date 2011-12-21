<?php

require_once 'init.php';

$params = array();
$params['id'] = isset($_GET['id']) ? $_GET['id'] : null;

$dbh = get_db_connection();
$dbh->beginTransaction();

try {
    if ($params['id']) {
        $user = get_user();
        $q = $dbh->prepare("SELECT *, clumps.id as clump_id
                            FROM clumps
                            JOIN users
                                ON users.id = clumps.user_id
                            WHERE clumps.id = ?
                            ORDER BY date DESC
                            ");
        $q->execute( array( $params['id'] ));
        $clump = $q->fetch();
        $canEdit = $clump['user_id'] == $user['id'];
    }
}
catch(PDOException $e)
{
    echo $e->getMessage();
    exit;
}

?><!DOCTYPE html><head>

<?php include 'head.html'; ?>

<title>clmpr - <?=$clump['title']?></title>

<link rel="stylesheet" type="text/css" href="/lib/tag-it/css/jquery-ui.css" />
<link rel="stylesheet" type="text/css" href="/lib/tag-it/css/jquery.tagit.css" />
<link rel="stylesheet" type="text/css" href="/lib/tag-it/css/clmpr.tagit.css" />

<script src="/lib/tag-it/js/jquery-ui.min.js" type="text/javascript" charset="utf-8"></script>
<script src="/lib/tag-it/js/tag-it.js" type="text/javascript" charset="utf-8"></script>

<script>
$(document).ready(function() {

    // user tags array
    var userTags = [];

    $("#tag-input").tagit({
        availableTags : userTags,
        animate : false,
        spaceChar : '-',
        tabIndex : 3
    });
    $('.tagit input')[0].focus();

});

function deleteClump( id ) {
    if (confirm("confirm delete")) {
        $.post('delete.php', { clump_id : id }, function(result) {
            window.location = '/';
        }, 'json');
        return false;
    }
}
</script>

</head><body>

<?php include 'header.html'; ?>

<hr />

<form method="POST" action="update.php" class="new">

    <p>
    <label>title</label>
    <input type="text" name="title" value="<?php echo htmlentities($clump['title']); ?>">
    </p>

    <p>
    <label>url <a href="<?php echo $clump['url']; ?>" class="ui">visit&rarr;</a></label>
    <input type="text" name="url" value="<?php echo $clump['url']; ?>">
    </p>

    <p>
    <label>tags</label>
    <input type="text" id="tag-input" name="tags" value="<?php echo str_replace(' ', ',', $clump['tags']); ?>">
    </p>

    <p>
    <label>description</label>
    <input type="text" name="description" value="<?php echo $clump['description']; ?>">
    </p>

    <p>
        saved <?php echo date("Y-m-d", strtotime($clump['date'])) ?> by
        <a class="uname" href="/?user=<?php echo $clump['user'] ?>"><?php echo $clump['user'] ?></a>
    </p>

    <br />

    <?php if ($canEdit) : ?>
        <p>
        <input type="hidden" value="<?php echo $clump['clump_id']; ?>" name="id" />
        <input type="submit" value="save">
        <a href="#" onClick="return deleteClump(<?php echo $clump['clump_id']; ?>);">delete</a>
        </p>
    <? endif; ?>

    <br />
    <br />

</form>

<hr />

<?php include 'footer.html' ?>
