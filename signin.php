<?php

    require_once 'init.php';

    $params = array();
    $params['user'] = isset($_POST['user']) ? $_POST['user'] : null;
    $params['pass'] = isset($_POST['pass']) ? $_POST['pass'] : null;
    $params['logout'] = isset($_POST['logout']) ? true : false;

    try {

        if ($_SERVER['REQUEST_METHOD'] == 'POST' ) {

            if ( $params['logout'] ) {
                $_SESSION['user'] = null;
                echo json_encode(array('mssg' => 'logged out'));
                exit;
            }

            $dbh = get_db_connection();
            $dbh->beginTransaction();

            $sql = "SELECT * FROM `clmpr`.`users` WHERE `user` = ? AND `pass` = PASSWORD(?)";
            $q = $dbh->prepare($sql);
            $q->execute( array( $params['user'], $params['pass'] ));

            if ($q->rowCount() == 1) {
                $res = $q->fetch();
                $_SESSION['user'] = array( 'user' => $res['user'], 'id' => $res['id'] );
                echo json_encode(array('success'=>true, 'mssg' => 'welcome, ' . $params['user']));
            } else {
                $_SESSION['user'] = null;
                echo json_encode(array('error'=>true, 'mssg' => 'invalid login'));
            }

            $dbh = null;
            exit;
        }

    }
    catch(PDOException $e)
    {
        echo json_encode(array('success' => true, 'mssg' => $e->getMessage() ));
    }

?>
<script>

    function register()
    {
        var user = $('#nuser').val();
        var pass = $('#npass').val();
        $('#register').text("creating user...");
        $.post('signup.php', { user : user, pass : pass }, function(result) {
            $('#register').html(result.mssg);
        }, 'json');
        return false;
    }


    $('#signin_form').submit(function() {
      alert('Handler for .submit() called.');
      return false;
    });
    function onSignIn()
    {
        var user = $('#user').val();
        var pass = $('#pass').val();
        $('#signin').text("signing in...");
        $.post('signin.php', { user : user, pass : pass }, function(result) {
            $('#signin').html(result.mssg);
        }, 'json');
    }


    function onLogout()
    {
        $.post('signin.php', { logout : 1 }, function(result) {
            $('#signin').html(result.mssg);
            window.location.reload();
        }, 'json');
        return false;
    }


</script>

<p>



    <div id="signin">
    <?php if ($user = get_user()) { ?>

        hi, <?php echo $user['user']; ?><br/>
        <a href="#" onClick="return onLogout();">logout</a>

    <? } else { ?>

        sign in
        <form id="signin_form" action="javascript:onSignIn();" style="display:inline-block;">
            <input type="text" id="user" name="u">&nbsp;&nbsp;
            <input type="password" id="pass" name="p">
            <input type="submit" value="sign in" />
        </form>

    <? } ?>
    </div>

    <br/>
    <br/>

    <a id="register_link" href="#" onClick="$('#register').toggle(); return false;">register</a>

    <div id="register" style="display:none;">
        <form>
            <input id="nuser" type="text" value="user" onFocus="this.select();">
            <input id="npass" type="password" value="pass" onFocus="this.select();">
            <input type="submit" value="register" onClick="return register();">
        </form>
    </div>

</p>
