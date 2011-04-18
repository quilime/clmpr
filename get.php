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
                $q = $dbh->prepare(" SELECT *, clumps.id as clump_id FROM clumps JOIN users ON users.id = clumps.user_id WHERE user_id = ? ORDER BY date DESC ");
                $q->execute( array( $user['id'] ));
            }
        }
        else
        {
            $q = $dbh->prepare("SELECT *, clumps.id as clump_id FROM clumps JOIN users ON users.id = clumps.user_id ORDER BY date DESC");
            $q->execute();
        }
    }
    catch(PDOException $e)
    {
        echo $e->getMessage();
        exit;
    }


    switch ($format) {
        case 'rss' :
            include 'rss.php';
            exit;
    }

?>

<script>

    function deleteClump( id, elem ) {
        if (confirm("delete clump?")) {
            $.post('delete.php', { clump_id : id }, function(result) {
                $(elem).hide();
            }, 'json');
            return false;
        }
    }

</script>

<ul>
<?php for($i = 0; $row = $q->fetch(); $i++ ): ?>
    <li>
    <?php echo date("Y-m-d", strtotime($row['date'])) ?>
    <a class="uname" href="/get.php?user=<?php echo $row['user'] ?>"><?php echo $row['user'] ?></a>
    <a href="<?php echo $row['location'] ?>"><?php echo $row['title'] ?></a>
    <span class="tags"><?php echo $row['tags'] ?></span>
    <?php if ($user = get_user()): ?>
    <?php if ($user['user'] == $row['user']): ?>
    <a href="#" onClick="return deleteClump(<?php echo $row['clump_id']; ?>, this.parentNode);" class="delete">x</a>
    <?php endif; ?>
    <?php endif; ?>
    </li>
<?php endfor; ?>
</ul>
