$(document).ready(function()
{

    // Projects menu.
    $('ul#projectsmenu li.sel').css({'border-bottom':0,'margin':'.2em .5em'});

    $('ul#projectsmenu li').has('a[href]').hide();

    $('ul#projectsmenu li').click(function()
    {
        $('ul#projectsmenu li').has('a[href]').toggle();

        if ($('ul#projectsmenu li').has('a[href]').is(':hidden'))
        {
            $('ul#projectsmenu li.sel').css({'border-bottom':0,'margin':'.2em .5em'});
        }
        else
        {
            $('ul#projectsmenu li.sel')
            .css({'border-bottom':'1px solid rgba(196,196,196,.7)','margin':0});
        }
    });


    // Scroll-up icon.
    $('<a href="#" class="scrollup"></a>').prependTo('body').hide().click(function(e)
    {
        $("html, body").animate({ scrollTop: 0 }, 500);
        e.preventDefault();
    });


    $(window).scroll(function()
    {
        if($(this).scrollTop() > 100)
        {
            $('a.scrollup').fadeIn();
        }
        else
        {
            $('a.scrollup').fadeOut();
        }
    });

    setInterval(function(){
        $.ajax({
            "type":"GET",
            "url":"time",
            "success":function(data) {
                var timestring = data.split('|');
                $('#projectsmenu .tm').text(timestring[1]);
            }
        });
    }, 60000);

    $('#projectsmenu .tm').click(function(){
        if ($(this).hasClass('expand-time'))
        {
            $(this).removeClass('expand-time');
            $('body, html').css('overflow','auto');
        }
        else
        {
            $(this).addClass('expand-time');
            $('body, html').css('overflow','hidden');
        }

    });


    function hideshow()
    {
        $('.hide').hide();
        $('<span class="show" title="Show"><img src="img/menu2.png" width="30"></span>')
            .appendTo('a.mainarticle').addClass('moveright');
    }
    hideshow();

    // Show option - menu icon.
    $('body').on('click', '.mainarticle span.show', function(e)
    {
        $('.article' + $(this).parent().parent().parent().parent().prev().prev().prev()
            .find('a').attr('id').substr(2) + 'option').toggle(); // .articlexxxoption
        e.preventDefault();
    });

    // Show article: click on a main article or reply to a main article.
    $('body').on('click', '.mainarticle, .subbarticle', function(e)
    {
        //alert('');
        var articleid = $(this).attr('title2');
        var classsel  = '#' + $(this).attr('class') + articleid; // Main article or reply?
        if ($(this).attr('class') == 'subbarticle')
        {
            // If reply is clicked, focus main article.
            var mainarticleid = $(this).parent().parent().parent().find('a').attr('title2');
            var mainarticle   = '#mainarticle' + mainarticleid;
        }
        else
        {
            var mainarticle = classsel;
        }

        var thisarticledivvisible = $(this).parent().find('#articlediv').is(":visible");

        var nextelement = $(this).next().attr('id');

        $(this).parent().css('background', 'rgba(0, 0, 0, 1)');
        $that = $(this);
        $.ajax(
        {
            "type":"get",
            "url":"article/" + parseInt(articleid),
            "success": function(data)
            {
                if ($('#articlediv').is(":visible"))
                {
                    closeArticleDivs();
                    //$('#updatearticle').remove();
                    //alert('');
                }
                
                if (!thisarticledivvisible || nextelement != 'articlediv')
                {
                    $('.openedart').remove();
                    $that.clone()
                    .appendTo('body')
                    .removeAttr('id')
                    .removeClass('mainarticle')
                    .addClass('openedart')
                    ;
                    $('.openedart').find('span').remove();
                    //alert('');
                    //$('#updatearticle').remove();
                    $('<div id="articlediv">' + data + '</div>').insertAfter(classsel).show();
                }

                $('html, body').animate({scrollTop: $(mainarticle).offset().top}, 500);
            }
        });
    
        e.preventDefault();
    });

    $('body').on('click', '.openedart', function(e){
        $('html, body').animate({scrollTop: $('#mainarticle'+$(this).attr('title2')).offset().top}, 500);
    });


    // Close article view and edit forms.
    $('body').on('click','#closeeditarticle', function()
    {
        alert('Close article');
        if ($(this).parent().parent().parent().parent().parent().attr('class') == 'divharteditform')
        {
            var article = $(this).parent().parent().parent().parent().parent().parent().parent().find('a');
            if (article.attr('class') == 'subbarticle')
            {
                article = article.parent().parent().parent().find('a');
            }
        }
        else
        {
            var article = $(this).parent().parent().parent().parent().find('a');
            if (article.attr('class') == 'subbarticle')
            {
                article=article.parent().parent().parent().find('a');
            }
        }
        $('html, body').animate({scrollTop: article.offset().top}, 0);
        $("#articlediv").parent().find('a').first()
        .css({'background':'blue','transition':'background 4s ease-out'})
        ;
        closeArticleDivs();
        //alert('');
    });
    

    function closeArticleDivs()
    {
        $('#articlediv').remove();
        $('.openedart').remove();
        //$('#updatearticle').remove();
    }


    // Delete article.
    $('body').on('click','#deletearticle', function()
    {
        if ($(this).text()=='Confirm?')
        { // Reply.
            var articleid = $(this).attr('title');
            $.ajax({
                "type":"GET",
                "url":"article/delete/" + parseInt(articleid),
            });
            if ($(this).parent().parent().parent().parent().parent().parent().parent().find('a').attr('id').substr(0,3)=='sub') {
                $(this).parent().parent().parent().parent().parent().parent().parent().delay(2000).remove();
            } else {
                $(this).parent().parent().parent().parent().parent().parent().parent().delay(2000).remove();
            }
            $('<p style="position:fixed;top:0;left:50%;font-weight:bold;background:yellow;color:black;padding:.5em;">Article was deleted!</p>')
            .appendTo('body')
            .fadeIn().delay(3000).fadeOut();
        }
        $(this).text('Confirm?');
    });


    // Add new reply:
    $('body').on('click','a.reply',function(e)
    {
        //console.log('New reply');
        var articleid = $(this).attr('title2');
        $.ajax(
        {
            "type":"GET",
            "url":"article/reply/" + parseInt(articleid),
            "success":function(data)
            {
                var splitdata = data.split('|');
                
                var str = '<li><a id="subbarticle' + splitdata[0] 
                + '" class="subbarticle" href="#" title="View article" title2="'
                + splitdata[0] + '">View article</a><span id="rposter' + splitdata[0]
                + '" class="rposter">By [' + splitdata[1] + '] on [' 
                + splitdata[2] + ']</span></li>';

                if ($('#replylist' + articleid + ' li').length > 0)
                {
                    $(str).insertAfter('#replylist' + articleid + ' li:last'); // Replies exist already.
                }
                else
                {
                    $('#replylist' + articleid).append(str);
                }
            }
        });
        e.preventDefault();
    });


    // New article.
    $('.newarticle a').hover(function()
    {
        $(this).animate({paddingLeft:'+=15px'}, 200);
    }, function()
    {
        $(this).animate({paddingLeft: '-=15px'}, 200);
    });


    // Add new article:
    $('body').on('click','.newarticle', function(e)
    {
        //console.log('New article');
        var stopicid = $(this).attr('title2');
        var groupid = $(this).parent().prev().prev().find('.hilite').attr('class');
        var groupid_split = groupid.split(' ');
        var groupid1_split = groupid_split[0].split('-');
        groupid = groupid1_split[1];
        //console.log(groupid);
        var test     = $(this); // So we can add new article before new article link
        $.ajax({
            "type":"GET",
            "url":"article/new/subtopic/" + parseInt(stopicid) + "/" + parseInt(groupid),
            "success":function(articleid)
            {
                //console.log("New article on Subtopic:" + stopicid + " with ID: " + articleid);
                var splitdata = articleid.split('|');
                $('<li class="article_li"><a id="mainarticle' + splitdata[0] 
                + '" class="mainarticle" title2="'
                + splitdata[0] 
                + '" href="#">[New article]</a><span class="aposter" id="aposter'
                + splitdata[0] + '">By ['
                + splitdata[1] + '] on ['
                + splitdata[2] + ']</span></li>')
                .appendTo(test.prev()).show();
            }
        });
        e.preventDefault();
    });


    // Nav button to navigate around
    $('a.btnNav').click(function(e)
    {
        if ($('.divnav').length == 0)
        {
            var $that = $(this);
            $.ajax({
                "type":"POST",
                "url":"nav",
                "success":function(data)
                {
                    var splitdata = data.split('=||=');
                    data = splitdata[0] + splitdata[1];
                    $('<div class="divnav">' + data + '</div>').insertAfter($that);
                    $('.nav-subtopics').hide();
                    if ($('.nav-subtopics').length == 0)
                    {
                        $('#showsubtopics').attr('checked','checked');
                    }
                }
            });
        }
        else
        {
            $('.divnav').remove();
        }
        e.preventDefault();
    });


    // 20/08/15 - As we click in our Search box, open up DIV and display
    $('form.searchform input[type=text]').on('click', function()
    {
        var $that = $(this);
        $.ajax({
            "type":"POST",
            "url":"nav",
            "data":"q=" + encodeURIComponent($that.val()),
            "success":function(data){
                var splitdata = data.split('=||=');
                data = splitdata[1];
                $('.divnav div.tbl').html(data);
                // 21/08/15 - shorten text and allow read more ..
                $('.divnav ul.content li span').hide() ;
            }
        });

    });


    $('.divnav ul.content li').on('click', function()
    {
        $(this).find('span').toggle();
    });


    // Show suptopics when the checkbox is clicked:
    $('.divnav #showsubtopics').on('click', function()
    {
        $('.nav-subtopics').toggle();
    });


    // Hide topic link:
    $('div.topicrow h2 span.hidetopic').click(function()
    {
        var $that = $(this);
        $.ajax(
            {
            "type":"GET",
            "url":"hidetopic",
            "data":"topicid=" + $that.attr('title'),

            "success":function(data)
            {
                $that.parent().parent().fadeOut() ;
                alert(data);
            }
        });

    });


    // Show hidden topics when the checkbox is clicked:
    $('.divnav #hiddentopics').on('click', function()
    {
        $('.topichidden').toggle();
    });


    // Pin or unpin content from nav:
    $('#articlediv #pintonav').on('click', function()
    {
        var $that = $(this);
    
        $.ajax(
        {
            "type":"GET",
            "url":"pincontent",
            "data":"contentid=" + $that.attr('value') + "&checked=" + $that.attr('checked'),

            "success":function(data)
            {
                alert(data);
            }
        });

    });


    // Topic 'dots' tooltip.
    $( "div.container-fluid div span, div.container-fluid div.subtopic-container div span" ).tooltip({
        tooltipClass: "jquerytooltip",
        position: { "my": "center top-54" },
        show: { effect: "none" },
        hide: { effect: "none" }
    });


    $( "div.container-fluid div.subtopic-container div span" ).tooltip(
    {
        tooltipClass: "jquerytooltip2",
        position: { "my": "center top-50" },
        show:     { effect: "none" },
        hide:     { effect: "none" }
    });


    $('div.container-fluid div div div:first-child span, div.container-fluid div.subtopic-container div span').click(function(e)
    {
        $('html, body').animate(
        {
            scrollTop: $('#' + $(this).attr('title2')).offset().top
        }, 0);
        e.preventDefault();
    });


    $('div.subtopic-container + .sgroups + p span').click(function()
    {
        $(this).parent().find('span').removeClass('hilite');
        $(this).addClass('hilite');
        var stopicid = $(this).parent().prev().prev().find('a').attr('id').substring(2);
        var $group = $(this).parent().prev().find('li.hilite').attr('class').substring(6);
        var $this = $(this);

        $.ajax(
        {
            "type":"GET",
            "url":"article/subtopic/" + stopicid,
            "data":"orderby=" + $this.attr('class').substring($this.attr('class'), $this.attr('class').indexOf(' ')) + 
            "&group=" + $group.substring(0, $group.indexOf(' ')),
            "success":function(data)
            {
                $($this).parent().next().html(data);
                $('.hide').hide();
                $('<span class="show" title="Show"><img src="img/menu2.png" width="30"></span>')
                .appendTo($($this.parent()
                .next()
                .find('ul li a.mainarticle')))
                .addClass('moveright');
            }
        });

    });


    $('div.subtopic-container + .sgroups li').click(function()
    {
        $(this).parent().find('li').removeClass('hilite');
        $(this).addClass('hilite');
        var stopicid = $(this).parent().prev().find('a').attr('id').substring(2);
        var $order = $(this).parent().next().find('span.hilite').attr('class');
        var $group = $(this).attr('class').substring(6);
        var $this = $(this);

        $.ajax(
        {
            "type":"GET",
            "url":"article/subtopic/" + stopicid,
            "data":"orderby=" 
            + $order.substring($order, $order.indexOf(' '))
            + "&group=" 
            + $group.substring(0, $group.indexOf(' ')),
            "success":function(data)
            {
                $($this).parent().next().next().html(data);
                $('.hide').hide();
                $('<span class="show" title="Show"><img src="img/menu2.png" width="30"></span>')
                .appendTo($($this.parent()
                .next()
                .find('ul li a.mainarticle')))
                .addClass('moveright');
            }
        });

    });


});