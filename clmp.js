// clmp.js
(function()
{
    var l = 0; $(window).width() / 2;
    var t = 0; $(window).height() / 2;

    var c = $([
        '<div style="font-family:monospace !important;font-size:11px !important;background:#fff;border:5px solid #000;color:#000;padding:30px;position:fixed;left:' +  l + 'px;top:' + t + 'px">'
    ,   '<form id="clmpr">'
    ,   'title: <input name="title" value="' + $('title').text() + '" /><br/>'
    ,   'desc: &nbsp;<textarea name="desc"></textarea><br/>'
    ,   'tags: &nbsp;<input name="tags" /><br/><br/>'
    ,   '<input name="submit" type="button" value="save">'
    ,   '</form>'
    ,   '</div>'
    ].join(''));

    $('body').append(c);

    
    var params = {
        'title': "test",
        'referer': location.href,
        'tags': "tags",
        'desc': "desc"
    };
    var urlb = [];
    urlb.push('http://clmpr/save.php');
    urlb.push('?');
    for (var n in params) {
        urlb.push(encodeURIComponent(n));
        urlb.push('=');
        urlb.push(encodeURIComponent(params[n]));
        urlb.push('&');
    }
    
    // this may be caught by a popup blocker
    if (true) {
        window.open(urlb.join(''),
                'saved ' + new Date().getTime(),
                'status=no,resizable=no,scrollbars=no,personalbar=no,directories=no,location=no,toolbar=no,menubar=no,' +
                'width=300,height=50,left=0,top=0');
    }

}
)();

/*

javascript:var i,s,ss=['http://kathack.com/js/kh.js','http://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js'];for(i=0;i!=ss.length;i++){s=document.createElement('script');s.src=ss[i];document.body.appendChild(s);}void(0);
javascript:void((function(){var%20e=document.createElement('script');e.setAttribute('type','text/javascript');e.setAttribute('charset','UTF-8');e.setAttribute('src','http://ffffound.com/bookmarklet.js');document.body.appendChild(e)})());

*/