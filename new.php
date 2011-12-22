<?php

include 'init.php';

$params = array();
$params['title'] = isset($_GET['title']) ? $_GET['title'] : null;
$params['url']  = isset($_GET['url'])  ? $_GET['url']  : null;


try {

    ?>
    <!DOCTYPE html><head>
	<?php include 'head.html'; ?>

    <link rel="stylesheet" type="text/css" href="/lib/tag-it/css/jquery-ui.css" />
    <link rel="stylesheet" type="text/css" href="/lib/tag-it/css/jquery.tagit.css" />
    <link rel="stylesheet" type="text/css" href="/lib/tag-it/css/clmpr.tagit.css" />

    <script src="/lib/tag-it/js/jquery-ui.min.js" type="text/javascript" charset="utf-8"></script>
    <script src="/lib/tag-it/js/tag-it.js" type="text/javascript" charset="utf-8"></script>

    <script>
    $(document).ready(function() {

        // user tags array
        var userTags = [];

        $("#tag-input").tagit({
            availableTags : userTags,
            animate : false,
            tabIndex : 3
        });
        $('.tagit input')[0].focus();

    });
    </script>

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

    		<label>tags <a href="#" class="ui tag-help" onClick="$('#tag-help').toggle();return false;">how to tag &raquo;</a></label>
            <ul id="tag-help" style="display:none">
                <li><span class="bull">&bull;</span>combine "multiple words" with quotes</li>
                <li><span class="bull">&bull;</span>separate tags by space, comma, enter</li>
            </ul>
    		<input type="text" id="tag-input" name="tags" tabindex="3" value="">

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
