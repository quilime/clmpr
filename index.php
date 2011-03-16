<?php
    require_once 'init.php';
?>
<!DOCTYPE html>
<head>

<title>clmpr</title>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js"></script>

</head>

<body>

<b>C</b>itation, <b>L</b>ogging and <b>M</b>ulti-<b>P</b>urpose a<b>R</b>chive

<?php include 'signin.php'; ?>

<p>
bookmarklet:
<a href="javascript:(function()
{
    var w = window,
    b = document,
    c = encodeURIComponent,
    d = w.open(
        'http://clmpr.com/put.php?'
        + 'location='  + c(b.location)
        + '&title=' + c(b.title)
    ,   'clmpr_popup'
    ,   'left=' + (( w.screenX || w.screenLeft ) + 10)
    +   ',top=' + (( w.screenY || w.screenTop) + 10 )
    +   ',height=420px,width=550px,resizable=1,alwaysRaised=1');
    w.setTimeout(function() {
        d.focus()
    } , 300)
}
)();">clmpr</a>