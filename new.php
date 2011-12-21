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

            <p>
    		<label>title</label>
    		<input type="text" name="title" value="<?=htmlentities($params['title'])?>">
            </p>

            <p>
    		<label>url</label>
    		<input type="text" name="url" value="<?=$params['url']?>">    		
            </p>
    		
            <p>
    		<label>tags <span style="font-weight:normal">(space " " or comma "," delimited)</span></label>
    		<input type="text" name="tags" value="">    		    		
            </p>

            <p>
            <label>description</label>
            <input type="text" name="description" value="">                        
            </p>            

    		<br />

            <p>
    		<input type="submit" value="save">
    		<a href="javascript:window.close();">cancel</a>
            </p>

    		<br />
    		<br />

    	</form>

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
