<?php

require_once 'init.php';

$params = array();
$params['tag'] = isset($_GET['tag']) ? $_GET['tag'] : null;

$dbh = get_db_connection();
$dbh->beginTransaction();

try {
	/*
    if ($params['tag']) {
        $q = $dbh->prepare("SELECT *, tags.id as tag_id 
                            FROM tags 
                            WHERE tag = ? 
                            ORDER BY tag DESC");
        $q->execute( array( $params['tag'] ));
    }
    else {
    	*/
        $q = $dbh->prepare("SELECT * FROM tags 
                            ORDER BY count DESC, tag ASC");
        $q->execute();
    //}

}
catch(PDOException $e)
{
    echo $e->getMessage();
    exit;
}


?><!DOCTYPE html>

<head>
<title>clmpr</title>

<?php include 'head.html'; ?>

</head>
<body>

<?php include 'header.html'; ?>


<p>tags</p>
<hr />

<ul class="tags">
<?php for($i = 0; $row = $q->fetch(); $i++ ): 
?>
    
    <li><span class="tag"><a href="<?php echo $row['tag'] ?>"><?php echo $row['tag'] ?></a> <?php echo $row['count'] ?></span></li>

<?php endfor; ?>
</ul>

<hr />

<?php include 'footer.html' ?>


