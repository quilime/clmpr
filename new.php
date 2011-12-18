<?php

include 'init.php';

$params = array();
$params['title'] = isset($_GET['title']) ? $_GET['title'] : null;
$params['url']  = isset($_GET['url'])  ? $_GET['url']  : null;


try {

	include 'head.html';

    if ($user = get_user()) {

    	?>

    	<form method="POST" action="put.php" class="new">

    		<label>title</label>
    		<input type="text" name="title" value="<?=htmlentities($params['title'])?>">

    		<br /><br />

    		<label>url</label>
    		<input type="text" name="url" value="<?=$params['url']?>">    		

    		<br /><br />

    		<label>tags (space delimited)</label>
    		<input type="text" name="tags" value="">    		    		

    		<br /><br /><br />

    		<input type="submit" value="save">
    		<a href="javascript:window.close();">cancel</a>

    		<br />
    		<br />

    	</form>

    	<!-- <hr /> -->

    	<?php // include 'footer.html'; ?>

    	<script>

    		window.onload = function() {
    			document.forms[0].tags.focus();
    		}

	    	</script>    	



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
