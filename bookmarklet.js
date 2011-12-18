(function()
{
    var w = window,
    b = document,
    c = encodeURIComponent,
    d = w.open(
        'http://clmpr/put.php?'
        + 'url='  + c(b.location)
        + '&title=' + c(b.title)
    ,   'clmpr_popup'
    ,   'left=' + (( w.screenX || w.screenLeft ) + 10)
    +   ',top=' + (( w.screenY || w.screenTop) + 10 )
    +   ',height=420px, width=550px, resizable=1, alwaysRaised=1');
    w.setTimeout(function() {
        d.focus()
    } , 300)
}
)();