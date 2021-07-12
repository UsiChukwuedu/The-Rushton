<?php

$graph_url = 'https://graph.instagram.com/';

$app_id = get_field('app_id', 'option');
$app_secret = get_field('app_secret', 'option');
$user_id = get_field('user_id', 'option');
$access_token = get_field('access_token', 'option');
$limit = 11;

/*
    Run three checks to determine if we should check the instagram Api

    1. Check if the instagram_cache table exists. If it doesn't the script hasn't been
        run and we need to create the two instagram tables
    2. Check if the instagram_cache table is empty. This means there isn't anything
        in there and there should be something because this account as images right?
        maybe ill change this later but they should have media if they want this to be linked up
    3. Check if its been 15 minutes since the last time the instagram api was checked Its been a
        while and maybe they posted a new image so we should check that
*/

$table_exists_query = "SELECT table_name FROM information_schema.tables WHERE table_schema = '"
    .DB_NAME."' AND table_name = 'instagram_info'";
$database_query = "SELECT last_update from instagram_info where id = 0";
$empty_query = "SELECT count(*) as entries FROM instagram_cache";

global $wpdb;
$results = $wpdb->get_results($table_exists_query);

//we are running this query first because if the table is not there none of the other queries will even work

if($results === array()) {
    //we do not have tables we need to make them
    $info_table = "CREATE TABLE instagram_info (id SMALLINT primary key, last_update DATETIME)";
    $cache_table = "CREATE TABLE instagram_cache (id VARCHAR(255) primary key, permalink TEXT, img_url TEXT, timestamp DATETIME)";

    $setup_query = "INSERT INTO instagram_info values (0, NULL)";

    global $wpdb;
    $wpdb->query($info_table);
    $wpdb->query($cache_table);
    $wpdb->query($setup_query);
}


$results = $wpdb->get_results($database_query);
$count_result = $wpdb->get_results($empty_query);

$last_update = NULL;

foreach($results as $result) {
    $last_update = $result->last_update;
}


if(($count_result[0]->entries <= 0) || (time() - $last_update > 15 * 60 || $last_update == NULL)) {
    //okay so lets just do this when we have everything already if we have the variables and the access_token we can just go
    //do the media query grab a bunch of stuff and then we are goign to grab carousels and images

    $request_url = $graph_url.$user_id."/media?fields=permalink,media_url,media_type,id,timestamp&access_token=".$access_token;
    $rq = curl_init();

    curl_setopt($rq, CURLOPT_URL, $request_url);
    curl_setopt($rq, CURLOPT_RETURNTRANSFER, true);

    $results = json_decode(curl_exec($rq));

    curl_close($rq);

    $objects = array();

    $insert_query = "REPLACE INTO instagram_cache VALUES ";

    $i = 0;
    
    if(is_array($results->data))
    foreach($results->data as $post) {
        switch($post->media_type) {
            case 'IMAGE': 
                $insert_query .= "('". $post->id . "', '"
                    . $post->permalink. "', '"
                    . $post->media_url. "', '"
                    . $post->timestamp ."')"
                    . ($i+1 === count($results->data) ? "" : ", ");
            break;
            case 'CAROUSEL_ALBUM':
                $insert_query .= "('" . $post->id . "', '"
                    . $post->permalink. "', '"
                    . $post->media_url. "', '"
                    . $post->timestamp ."')"
                    . ($i+1 === count($results->data) ? "" : ",");
            break;
        }
        $i++;
    }

    $insert_query.= ";";

    global $wpdb;
    $results = $wpdb->query($insert_query);

    $last_update_query = "UPDATE instagram_info SET last_update = now() where id=0";

    //refresh our token
    $refresh_url = 'https://graph.instagram.com/refresh_access_token?grant_type=ig_refresh_token&access_token='. $access_token;

    $refreshq = curl_init();

    curl_setopt($refreshq, CURLOPT_URL, $request_url);
    curl_setopt($refreshq, CURLOPT_RETURNTRANSFER, true);
    curl_exec($refreshq);
    curl_close($refreshq);

    $wpdb->query($last_update_query);
}

// grab the last 5 entries I could remove everything that isn't 5 but why do that when we could keep the data
$select_query = "SELECT * FROM instagram_cache order by timestamp desc limit $limit";
$results = $wpdb->get_results($select_query);

$instagram_posts = array();

foreach($results as $instapost) {
    array_push($instagram_posts, $instapost);
}

//its time to clean up now
wp_reset_query();

unset($app_id);
unset($app_secret);
unset($user_id);
unset($access_token);

unset($table_exists_query);
unset($database_query);
unset($empty_query);

unset($count_result);
unset($last_update);
unset($select_query);
unset($results);

?>