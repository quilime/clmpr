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

<script type="text/javascript">
$(document).ready(function() {
    // user tags array
    var userTags = [];
    $("#tag-input").tagit({
        availableTags : userTags,
        animate : false,
        spaceChar : '-',
        tabIndex : 3
    });
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
    <label>url &nbsp;<a href="<?php echo $clump['url']; ?>" class="ui">go</a></label>
    <input type="text" name="url" value="<?php echo $clump['url']; ?>">
    </p>


    <label>tags <a href="#" class="ui tag-help" onClick="$('#tag-help').toggle();return false;">how to tag &raquo;</a></label>
    <ul id="tag-help" style="display:none">
        <li><span class="bull">&bull;</span>combine "multiple words" with quotes</li>
        <li><span class="bull">&bull;</span>separate tags by space, comma, or enter</li>
    </ul>
    <input type="text" id="tag-input" name="tags" value="<?php echo $clump['tags']; ?>">


    <p>
    <label>description</label>
    <input type="text" name="description" value="<?php echo htmlentities($clump['description']); ?>">
    </p>

    <p>
        <span class="meta" title="<?php echo date('r', strtotime($clump['date'])); ?>">
            saved <?php echo approximate_time(date('U') - strtotime($clump['date'])) ?> ago</a> by
            <a class="uname" href="/<?php echo $clump['user'] ?>"><?php echo $clump['user'] ?></a>
        </span>
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
