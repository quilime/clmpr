<?php

include 'init.php';

$params = array();
$params['title'] = isset($_GET['title']) ? $_GET['title'] : null;
$params['url']  = isset($_GET['url'])  ? $_GET['url']  : null;


try {

    ?>
    <!DOCTYPE html><head>
	<?php include 'head.html'; ?>
    </head><body>
    <?php

    if ($user = get_user()) {

    	?>

    	<form method="POST" action="put.php" class="new">

            <p>
    		<label>title</label>
    		<input type="text" name="title" tabindex="1" value="<?=htmlentities($params['title'])?>">
            </p>

            <p>
    		<label>url</label>
    		<input type="text" name="url" tabindex="2" value="<?=$params['url']?>">
            </p>

            <p>
    		<label>tags <span class="ui">(combine "multiple words" with quotes)</span></label>
    		<input type="text" id="tag-input" name="tags" tabindex="3" value="">
            </p>

            <p>
            <label>description</label>
            <input type="text" name="description" tabindex="4" value="">
            </p>

    		<br />

            <p>
    		<input type="submit" tabindex="5" value="save">
    		<a href="javascript:window.close();">cancel</a>
            </p>

    		<br />
    		<br />

    	</form>

    	<?php

    } else {
        include 'signin.php';
    }
}
catch(PDOException $e)
{
    echo $e->getMessage();
}

exit;
