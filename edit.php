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

?><!DOCTYPE html>

<head>
<title>clmpr - <?=$clump['title']?></title>

<?php include 'head.html'; ?>

<script>
function deleteClump( id ) {
    if (confirm("confirm delete")) {
        $.post('delete.php', { clump_id : id }, function(result) {
            window.location = '/';
        }, 'json');
        return false;
    }
}
</script>

</head>
<body>

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
    <label>tags (space delimited)</label>
    <input type="text" name="tags" value="<?php echo $clump['tags']; ?>">
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
