<?php

require_once 'init.php';

$url = isset($_GET['_url']) ? trim($_GET['_url'], '/') : '/';

list($endpoint) = explode('/', $url);

$pathinfo = pathinfo($_SERVER['REQUEST_URI']);
$format = isset($pathinfo['extension']) ? $pathinfo['extension'] : null;

$endpoint = str_replace('.'.$format, '', $endpoint);

switch($endpoint)
{
    case 'about' :
        include 'head.html';
        include 'header.html';
        echo '<hr />';

        ?>

        <p>
        <b>c</b>itation, <b>l</b>ogging and <b>m</b>ulti-<b>p</b>urpose a<b>r</b>chive
        <br />
        <br />
        </p>

        <p>

        use clmpr (<b>/klum-per/</b>) to save bookmarks.


<p>

<strong>to use: </strong>
<?php
    $js = file_get_contents('bookmarklet.js');
    $js = str_replace('{BASE_URL}', BASE_URL, $js);
?>
drag this link [<a href="javascript:<?=$js?>">+</a>] to your bookmark bar
<br />
<br />
</p>     

<p>
project keywords: <i>
bookmarks, 
delicious-clone.
webservice,
database,
mindmap,
citation,
semantic
</i>

<br /><br />
</p>




<p>
clmpr is open source. fork the project on <a href="http://github.com/quilime/clmpr">github</a>
</p>

        <?

        //echo '&copy; 2011 <a href="http://quilime.com">gabriel dunne</a>';
        echo '<hr />';
        include 'footer.html';
        exit;

    default :
        if ($endpoint != '') {
            $dbh = get_db_connection();
            $dbh->beginTransaction();
            $user = get_users($dbh, array( 'user' => $endpoint ));
            $dbh = null;
            if ( isset($user['user']) ) {
                $get = function( $user ) {
                    $_GET['user'] = $user;
                };
                $get( $user['user'] );
            }
        }
}

include 'get.php';
