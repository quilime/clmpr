<?php

include '../init.php';

$dbh = get_db_connection();
$dbh->beginTransaction();

$q = $dbh->prepare("SELECT *, clumps.id as clump_id
                    FROM clumps
                    JOIN users
                        ON users.id = clumps.user_id
                    ORDER BY date DESC");
$q->execute();

$clumps = array();
for($i = 0; $row = $q->fetch(); $i++ ):
	$clumps[] = $row;
endfor;

foreach($clumps as $clump) :
	#update tags
	$tags = str_replace(" ", ',', $clump['tags']);
    # update clump
	$update = $dbh->prepare("UPDATE `clumps` SET `tags` = '".$tags."' WHERE `id` = '".$clump['clump_id']."'");
	$update->execute();
endforeach;

exit;

$dbh = null;
$q = null;
