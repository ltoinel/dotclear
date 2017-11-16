<?php
header('Access-Control-Allow-Origin: *'); 
header('Content-type: application/json');

$social_counter_settings = array(       
        
   // RSS
   'rss_db_host' => 'localhost',
   'rss_db_name' => 'x',
   'rss_db_user' => 'x',
   'rss_db_pass' => 'x',
 
   //twitter       
   'twitter_user' => 'x',
   'oauth_access_token' => "x",
   'oauth_access_token_secret' => "x",
   'consumer_key' => "x",
   'consumer_secret' => "x",

   //facebook      
   'facebook_id' => 'geeek.org',
   'facebook_app_id' => 'x',
   'facebook_app_secret' => 'x',
             
   //Google API Key for Google related services
   'google_api_key' => 'x',
   'googleplus_id' => 'x',      
             
   //others
    'cache_file_name' => 'counter.json'
);

/**
 * RSS follower count
 */
function rss_follower_count(){
	global $social_counter_settings;
  	$settings = $social_counter_settings;

        // On se connecte à la base de données
        $bdd = new PDO('mysql:host='.$settings['rss_db_host'].';dbname='.$settings['rss_db_name'], $settings['rss_db_user'], $settings['rss_db_pass'], array(
                PDO::ATTR_PERSISTENT => true
        ));

        $req = $bdd->prepare('SELECT count(distinct(reader_id)) as readerCount FROM `stats` where read_time > (NOW() - INTERVAL 90 DAY)');
        $req->execute();
        $rows = $req->fetchAll(PDO::FETCH_ASSOC);
        return $rows[0]['readerCount'];
};


/**
* Facebook Like Count
*/
function facebook_follower_count() {        
  global $social_counter_settings;
  $count = -1;
  $settings = $social_counter_settings;

  $json_url ='https://graph.facebook.com/v2.8/'.$settings['facebook_id'].'?access_token='.$settings['facebook_app_id'].'|'.$settings['facebook_app_secret'].'&fields=fan_count';
  $json = file_get_contents($json_url);
  $json_output = json_decode($json);
  //Extract the likes count from the JSON object
  if($json_output->fan_count){
    $count = $json_output->fan_count;
  }

  return $count;
}

/**
* Twitter Follower Count
*/
require_once('TwitterAPIExchange.php'); 
 
//to get follower count
function twitter_follower_count(){
    global $social_counter_settings;
    $count = -1;
    $settings = $social_counter_settings;
    $apiUrl = "https://api.twitter.com/1.1/users/show.json";
    $requestMethod = 'GET';
    $getField = '?screen_name=' .  $settings['twitter_user']; 
    $twitter = new TwitterAPIExchange($settings);
    $response = $twitter->setGetfield($getField)->buildOauth($apiUrl, $requestMethod)->performRequest(); 
    $followers = json_decode($response);
    $count = $followers->followers_count;
    return $count ;
}

/**
* Google+ Follower Count
*/
function googleplus_follower_count() {      
    global $social_counter_settings;
    $settings = $social_counter_settings;
    $count = -1;                
    $gUrl = "https://www.googleapis.com/plus/v1/people/".$settings['googleplus_id']."?key=".$settings['google_api_key'];            
     
    $response = file_get_contents($gUrl);           
    $fb = json_decode($response);
    if ( isset( $fb->circledByCount)) {              
                $count = intval($fb->circledByCount);                    
    }                   
    return $count ;
}

/**
 * Caching Function
 */
/* gets the contents of a file if it exists, otherwise grabs and caches */
function get_content($content,$forcewrite=false, $hours = 24) {
    //vars
    global $social_counter_settings;
    $file = $social_counter_settings['cache_file_name'];
    $current_time = time(); 
    $expire_time = $hours * 60 * 60; 
 
    if(file_exists($file) && ($current_time - $expire_time < filemtime($file)) && $forcewrite===false) {
        //echo 'returning from cached file';
        return json_decode(file_get_contents($file),true);
    }
    else {          
        file_put_contents($file,json_encode($content));     
        return $content;
    }
}


function process_counts(){
    global $social_counter;
    $social_counter = get_content($social_counter);

    $isdirty=0;

    foreach ( $social_counter as $key => $value ) {
        if ($value < 0){
          $isdirty++;
          $method = $key . '_follower_count';
          $social_counter[$key] = $method();
        }
     }
     if ($isdirty>0){
        //cache modified counts
        $social_counter = get_content($social_counter,true);
     }
     return $social_counter;
}

$social_counter = array(
   'rss' => -1,
   'twitter' => -1,
   'facebook' => -1,
   'googleplus' => -1,
);

echo json_encode(process_counts());

