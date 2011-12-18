<?php

require_once 'init.php';

$params = array();
$params['user'] = isset($_GET['user']) ? $_GET['user'] : null;

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

?>

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

        <?php if ($hasDescription) : ?>
        <a href="#" class="more" onClick="$(this.parentNode).addClass('expand');">+</a>
        <a href="#" class="less" onClick="$(this.parentNode).removeClass('expand');">-</a>
        <?php else : ?>
        &nbsp; 
        <?php endif; ?>

            
        <span class="url">
            <a href="<?php echo $row['url'] ?>">
                <?php echo $row['title'] ? $row['title'] : "&lt;title&gt;" ?>
            </a>
        </span>

        <span class="meta">
            <?php echo date("Y-m-d", strtotime($row['date'])) ?> by 
            <a class="uname" href="/?user=<?php echo $row['user'] ?>"><?php echo $row['user'] ?></a>
        </span>

        <?php 
        if ($user = get_user()):
            if ($user['user'] == $row['user']): ?>
                <!-- &nbsp; 
                <a href="" class="edit">&#x270F;</a> -->
                <a href="#" title="Delete" onClick="return deleteClump(<?php echo $row['clump_id']; ?>, this.parentNode);" class="delete">&times;</a>
        <?php   
            endif;
        endif; 
        ?>        

        <div class="expand">
    
            <?php if ($hasDescription) : ?>
                <p class="desc">
                <?php echo $row['description']; ?>
                </p>
            <?php endif; ?>      

            <ul class="tags">
                <?php foreach($row['tags'] as $tag) : ?>
                <li><a href="/tags.php?tag=<?=$tag?>"><?=$tag?></a></li>
                <? endforeach; ?>
            </ul>

        </div>

    </li>

<?php endfor; ?>
</ul>
