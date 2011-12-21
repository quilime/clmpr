<?php

require_once 'init.php';

$params = array();
$params['user'] = isset($_GET['user']) ? $_GET['user'] : null;
$params['id']   = isset($_GET['id']) ? $_GET['id'] : null;

$pathinfo = pathinfo($_SERVER['REQUEST_URI']);
$format = isset($pathinfo['extension']) ? $pathinfo['extension'] : null;

$endpoint = '';
$endpoint = str_replace('.'.$format, '', $endpoint);

$dbh = get_db_connection();
$dbh->beginTransaction();

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
    else {
        $q = $dbh->prepare("SELECT *, clumps.id as clump_id 
                            FROM clumps 
                            JOIN users 
                                ON users.id = clumps.user_id 
                            ORDER BY date DESC");
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

<p>
bookmarklet:
<?php
    $js = file_get_contents('bookmarklet.js');
    $js = str_replace('{BASE_URL}', BASE_URL, $js);
?>
<a href="javascript:<?=$js?>">+</a>
</p>

<p>
<a href="http://<?php echo BASE_URL; ?><?php echo $_SERVER['REQUEST_URI'] ?>.xml">rss</a>
</p>

<hr />


<script>
function deleteClump( id, elem ) {
    if (confirm("confirm delete")) {
        $.post('delete.php', { clump_id : id }, function(result) {
            $(elem).hide();
        }, 'json');
        return false;
    }
}
</script>

<ul class="links">
<?php for($i = 0; $row = $q->fetch(); $i++ ): 

    # process description
    $hasDescription = $row['description'] || false;

    # process tags
    if ($row['tags'] == '')
        $row['tags'] = array();
    else
        $row['tags'] = explode(" ", $row['tags']);

?>
    
    <li>
            
        <span class="url">
            <a href="<?php echo $row['url'] ?>">
                <?php echo $row['title'] ? $row['title'] : "&lt;title&gt;" ?>
            </a>
        </span>     

        <div class="expand">
            <?php if ($hasDescription) : ?>
                <span class="desc">
                <?php echo $row['description']; ?>
                </span>
            <?php endif; ?>  
            <?php if (count($row['tags']) > 0) : ?>                
            <ul class="tags">
                <?php foreach($row['tags'] as $tag) : ?>
                <li><a href="/tags.php?tag=<?=$tag?>"><?=$tag?></a></li>
                <? endforeach; ?>
            </ul>            
            <?php endif; ?>
        </div>


        <span class="meta">
            <span title="<?php echo date('r', strtotime($row['date'])); ?>"><?php echo approximate_time(date('U') - strtotime($row['date'])) ?> ago</a> by 
            <a class="uname" href="/<?php echo $row['user'] ?>"><?php echo $row['user'] ?></a>
        </span>        

        <?php if ($user['user'] == $row['user']): ?>
            <a href="/edit.php?id=<?php echo $row['clump_id'];?>" class="ui edit">&#x2710;</a>
            <a href="#" title="Delete" onClick="return deleteClump(<?php echo $row['clump_id']; ?>, this.parentNode);" class="ui delete">&times;</a>
        <?php endif; ?>              

    </li>

<?php endfor; ?>
</ul>


<hr />

<?php include 'footer.html' ?>
