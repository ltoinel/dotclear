
// On document ready ...
$( document ).ready( function() {

    $.ajax({
        url: 'https://www.geeek.org/counter/index.php',
        type: "GET",
        dataType: "json",
        success: function (data) {
          $("#social .list-group-item.rss .count").html(data.rss + " Lecteurs");
          $("#social .list-group-item.facebook .count").html(data.facebook + " Abonnés");
          $("#social .list-group-item.twitter .count").html(data.twitter + " Followers");
          $("#social .list-group-item.google .count").html(data.googleplus + " Membres");
        }
      });
  
	// Load the gravatars
    $( '.gravatar' ).async_gravatars();
	
    // Load the modal images
    var lb_settings = {
      loader_img : 'https://www.geeek.org/?pf=lightbox/img/loader.gif',
      prev_img   : 'https://www.geeek.org/?pf=lightbox/img/prev.png',
      next_img   : 'https://www.geeek.org/?pf=lightbox/img/next.png',
      close_img  : 'https://www.geeek.org/?pf=lightbox/img/close.png',
      blank_img  : 'https://www.geeek.org/?pf=lightbox/img/blank.gif'
    };

    $( '.entry-content' ).each(function() {
      $(this).find("a[href$=\".jpg\"],a[href$=\".jpeg\"],a[href$=\".png\"],a[href$=\".gif\"]").modalImages(lb_settings);
    })

    //yash
    var yash_path = "/?pf=yash/syntaxhighlighter/js/";

    function shGetPath()
    {
        var args = arguments, result = [];
        for(var i = 0; i < args.length; i++)
                result.push(args[i].replace('@', yash_path));
        return result;
    };
    SyntaxHighlighter.autoloader.apply(null, shGetPath(
      'applescript            @shBrushAppleScript.js',
      'actionscript3 as3      @shBrushAS3.js',
      'bash shell             @shBrushBash.js',
      'coldfusion cf          @shBrushColdFusion.js',
      'cpp c                  @shBrushCpp.js',
      'c# c-sharp csharp      @shBrushCSharp.js',
      'css                    @shBrushCss.js',
      'delphi pascal          @shBrushDelphi.js',
      'diff patch pas         @shBrushDiff.js',
      'erl erlang             @shBrushErlang.js',
      'groovy                 @shBrushGroovy.js',
      'java                   @shBrushJava.js',
      'jfx javafx             @shBrushJavaFX.js',
      'js jscript javascript  @shBrushJScript.js',
      'perl pl                @shBrushPerl.js',
      'php                    @shBrushPhp.js',
      'text plain             @shBrushPlain.js',
      'ps powershell              @shBrushPowerShell.js',
      'py python              @shBrushPython.js',
      'ruby rails ror rb      @shBrushRuby.js',
      'sass scss              @shBrushSass.js',
      'scala                  @shBrushScala.js',
      'sql                    @shBrushSql.js',
      'vb vbnet               @shBrushVb.js',
      'xml xhtml xslt html    @shBrushXml.js'
   ));
   SyntaxHighlighter.all();


    // Initialize tooltips
    $("[data-rel~='tooltip']").tooltip();
  
    // Initialize load buttons
    function initShareButton(element,url){
	
        //Twitter
        if (typeof (twttr) != 'undefined') {
                twttr.widgets.load();
        } else {
		$.getScript("https://platform.twitter.com/widgets.js")
        }

        //Google+
        if (typeof (gapi) != 'undefined') {
                gapi.plusone.render(element.find(".g-plusone").get(0),{
        		'href':url
		});
        } else {
		$.getScript("https://apis.google.com/js/plusone.js?publisherid=107703659110168677069")
        }

        //Facebook
        if (typeof (FB) != 'undefined') {
         	FB.XFBML.parse(element.get(0));	
        } else {

		$.getScript( "https://connect.facebook.net/en_US/all.js#xfbml=1" )
  			.done(function( script, textStatus ) {
    				FB.init({ appId: '111114779937', status: true, cookie: true, xfbml: true });	
  			});
		
        }
    }
   
    // Popover 
    $(".share-button").popover({
	html:true,
	placement:"bottom",
	trigger:"click",
	delay: { show: 100, hide: 100 },
	content:    function(){
        var url = $(this).attr("data-url");
        var text = $(this).attr("data-text");
        return '<div class="twitter"><a href="https://twitter.com/share" class="twitter-share-button" data-url="'+url+'" data-text="'+text+'" data-via="ltoinel" data-lang="fr" data-dnt="true">Tweeter</a></div><div class="fb-like" data-href="'+url+'" data-send="false" data-layout="button_count" data-action="like" data-show-faces="false"></div><div class="g-plusone" data-size="medium" data-href="'+url+'"></div>';
    }
    });

    $(".share-button").on('shown.bs.popover', function () {
       initShareButton($(this).parent(),$(this).attr("data-url"));
    });

    // Social event tracking	
    $("[data-rel~='social']").click(function(){
	if (ga !== undefined){
		ga('send', 'event', 'social', $(this).attr("id"));
	};
    });
   
    // Tab initialize
    $('#annexe').tab();

});

