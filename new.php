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
    		<input type="text" name="title" tabindex="1" value="<?=htmlentities($params['title'])?>">
            </p>

            <p>
    		<label>url</label>
    		<input type="text" name="url" tabindex="2" value="<?=$params['url']?>">
            </p>

    		<label>tags <a href="#" class="tag-help" onClick="$('#tag-help').toggle();return false;">how to tag &raquo;</a></label>
            <ul id="tag-help" style="display:none">
                <li><span class="bull">&bull;</span>tag characters: [a-z 0-9 + # - .]</li>
                <li><span class="bull">&bull;</span>combine multiple words into single-words with dashes</li>
                <li><span class="bull">&bull;</span>delimit tags by space, semicolon, or comma</li>
            </ul>
    		<input type="text" name="tags" tabindex="3" value="">

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
