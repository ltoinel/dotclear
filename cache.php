<?php

/**
 * Deliver a page 
 */
function deliverPage($page, $cache=false){

      // Check the Etag & the If modified since ...
      if (($cache) && ((isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && trim($_SERVER['HTTP_IF_MODIFIED_SINCE']) == $page['upddt'])
             || (isset($_SERVER['HTTP_IF_NONE_MATCH']) && $_SERVER['HTTP_IF_NONE_MATCH'] == "W/".$page['etag']))){

              header("HTTP/1.1 304 Not Modified");
	      header('Last-Modified: '.$page['upddt']);
              header('ETag: '.$page['etag']);
	      header('Vary: Accept-Encoding');

              exit;
      }


      $content = $page['content'];

      #header('Content-Length: '.$length);
      header('Content-Type: '.$page['content_type'].'; charset=UTF-8');
      header('Last-Modified: '.$page['upddt']);
      header('ETag: '.$page['etag']);

      if ($cache){
      	header('Cache-Control: public, max-age=60, must-revalidate');
      } else{
	header('Cache-control: no-cache');
      }

      echo($content);
      exit();
}

/**
 * Serve and cache a document 
 */
function serveAndCacheDocument($tpl,$content_type='text/html',$http_cache=true,$http_etag=true)
{
	$_ctx =& $GLOBALS['_ctx'];
	$core =& $GLOBALS['core'];
	
	if ($_ctx->nb_entry_per_page === null) {
		$_ctx->nb_entry_per_page = $core->blog->settings->system->nb_post_per_page;
	}
	if ($_ctx->nb_entry_first_page === null) {
		$_ctx->nb_entry_first_page = $_ctx->nb_entry_per_page;
	}	

	$tpl_file = $core->tpl->getFilePath($tpl);
	
	if (!$tpl_file) {
		throw new Exception('Unable to find template ');
	}
	
	$result = new ArrayObject;
	
	$_ctx->current_tpl = $tpl;
	$_ctx->content_type = $content_type;

	$core->callBehavior('urlHandlerBeforeGetData',$_ctx);

	// Get Content
	$content =  $core->tpl->getData($_ctx->current_tpl);

	// Get Uri
	$uri = urldecode(http::getSelfURI());
	$uri = explode('?',$uri);
	$uri = explode('#',$uri[0]);
	$uri = $uri[0];
	$key = md5($uri);

	// Gzip content
	$result['content'] = $content;
	$result['content_length'] = strlen($content);
	$result['content_type'] = $_ctx->content_type; 
	$result['tpl'] = $_ctx->current_tpl;
	$result['etag'] = '"'.crc32($content).'"';
	$result['uri'] = $uri;

	$result['upddt'] = gmdate("D, d M Y H:i:s")." GMT";

	// Get last update date  
	//if ($_ctx->posts != NULL && $_ctx->posts->post_upddt != NULL){
	//	// Post update date
	//		$result['upddt'] = gmdate("D, d M Y H:i:s",strtotime($_ctx->posts->post_upddt))." GMT";
	//} else {
	//	// Blog update date
	//	$result['upddt'] = gmdate("D, d M Y H:i:s",$core->blog->upddt)." GMT";
	//}
		
	global $mc;
	$cache = false;
	// We store only content with no get and no post data
	if (elligibleForCache()){
		$mc->set($key,serialize($result),false,604800);
		$cache = true;
	} else {
	   // post data we remove to post from the cache
	   if (count($_POST) != 0){
		$mc->delete($key,0);
	   }
	}

	deliverPage($result, $cache);
}
	
/**
 * Analyze the 404 error 
 */
function analyze404($core, $_ctx){

	$uri = urldecode($_SERVER['REQUEST_URI']);
	$urit = explode('?',$uri);
	$uri = $urit[0];
	$uri_orig = $uri;

      // Gone URL for removed content
        if( strpos(file_get_contents("removed.txt"),$uri) !== false) {

		//header('Content-Type: text/html; charset=UTF-8');
                //http::head(410,'Gone');
                //$core->url->type = '410';
                //$_ctx->current_tpl = '410.html';
                //$_ctx->content_type = 'text/html';

                //echo $core->tpl->getData($_ctx->current_tpl);

                # --BEHAVIOR-- publicAfterDocument
                //$core->callBehavior('publicAfterDocument',$core);

	        header("HTTP/1.1 301 Moved Permanently");
                header("Location:https://www.geeek.org");

                exit;
        }


	if (startsWith($uri,"/index.php/") 
	 || startsWith($uri,"/share/")
         || startsWith($uri,"/cgi-bin/")){
	        header("HTTP/1.1 301 Moved Permanently");
                header("Location:https://www.geeek.org");
                exit();
	}

	// Deleted tag
	if (startsWith($uri,"/tag/")){
		$tagUri = substr($uri, 5);
		header("HTTP/1.1 301 Moved Permanently");
		header("Location:https://www.geeek.org/?q=".$tagUri);
		exit();
	}

	// Deleted category
	if (startsWith($uri,"/category/")){
                $tagUri = substr($uri, 10);
                header("HTTP/1.1 301 Moved Permanently");
                header("Location:https://www.geeek.org/?q=".$tagUri);
                exit();
        }

	// Suppression de l'extension	
	if (endsWith($uri,'.htm')){
		$uri = substr($uri,0,-4);
	
        }else if (endsWith($uri,'-.html')){
                $uri = substr($uri,0,-6);

	}else if (endsWith($uri,'.html')){
                $uri = substr($uri,0,-5);

	}else if (endsWith($uri,'/')){
		$uri = substr($uri,0,-1);
	}

	// Suppression du post dans les URL 
	if (startsWith($uri,"/post/")){
		$uri = substr($uri,5);
	}

	// Suppression des dates
	if (startsWith($uri,"/2005/") || startsWith($uri,"/2006/") ||  startsWith($uri,"/2007/") || startsWith($uri,"/2008/") || startsWith($uri,"/2009/") || startsWith($uri,"/2010/") || startsWith($uri,"/2011/")){
		$uri = substr($uri,11);
	}

	// Suppression du / initial
	if (startsWith($uri,"/")){
                $uri = substr($uri,1);
        }

	
	$uri = strtolower(text::str2URL($uri));

	// Suppression des parenthèses
	$uri = str_replace('[','',$uri);
	$uri = str_replace(']','',$uri);
	$uri = str_replace('-:-','-',$uri);
	$uri = str_replace(':-','-',$uri);

	// Supression de l'espace de fin
	if (endsWith($uri,'-')){
	    $uri = substr($uri,0,-1);
	}

	$uri = $uri . '.html';
	$params['post_url']=$uri;
	$rs = $core->blog->getPosts($params,true);		 

	// If the content exists or URL doesn't respect guidelines
	if ($rs->f(0) > 0 || '/'.$uri != $uri_orig){
		header("HTTP/1.1 301 Moved Permanently");
		header("Location:http://www.geeek.org/".$uri);
		exit();
	} else {

	} 
}


/**
 * Check if the page is elligible for the cache 
 */
function elligibleForCache(){

	$uri = urldecode($_SERVER['REQUEST_URI']);
	//global $logger;
	if (count($_POST) == 0 && (count($_GET) == 0 || isset($_GET['utm_source'])) && !startsWith($uri,'/subscribetocomments')){
		return true;
	}
	return false;
}

/** 
 * Check if a string starts with another string
 */
function startsWith($haystack, $needle){
	
    return strpos($haystack, $needle) === 0;
}

function endsWith($haystack, $needle)
{
    $length = strlen($needle);
    if ($length == 0) {
        return true;
    }

    $start  = $length * -1; //negative
    return (substr($haystack, $start) === $needle);
}

///////////////////// Cache ////////////////////////

require_once 'inc/libs/clearbricks/common/lib.http.php';
$uri = http::getSelfURI();

// Start a new Memcache
$mc = new Memcache();
if (!$mc->pconnect("localhost", "11211")) {
        throw new Exception('Unable to connect to memcached.');
}

// Check if the page is elligible for the cache
if (elligibleForCache()) {
	
        $uri = urldecode($uri);
        $uri = explode('?',$uri);
	$uri = explode('#',$uri[0]);
        $uri = md5($uri[0]);
 
	$page = @unserialize($mc->get($uri));

        if($page != null){
	     header("X-Cache: HIT");
             deliverPage($page,true);
        } else {
	     header("X-Cache: MISS");
	}
}



