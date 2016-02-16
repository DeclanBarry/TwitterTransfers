<?php

ini_set( 'display_errors', 1 );
require_once('TwitterAPIExchange.php');

$settings = array (
    'oauth_access_token' => "355327178-bA4KvCRFXsGxsZ5gHOqxZCXG9oR9aQT8jKDKfRKU",
    'oauth_access_token_secret' => "0Kwv3lfICVGWzgcYYQKVkrv6DLLXIQXYlaTu1SWEnrXJf",
    'consumer_key' => "E2NPo5XNIkKBPcRdIKRbKKSa7",
    'consumer_secret' => "huBivtvF2p6oFdAzx27GQih7BMUMIdyMRGguiUb2uMY52C2j5U"
);
$twitterApi = new TwitterAPIExchange( $settings );

$hashtag = 'john stones chelsea since:2015-11-29 until:2015-11-30';
$total = 0;

function getTweets( $hashtag, $page, $total, $twitterApi )
{
    global $total, $hashtag;
	
	$url = 'https://api.twitter.com/1.1/search/tweets.json?';
	$json = $twitterApi->setGetfield( '?q=' . urlencode( $hashtag ) . '&page=' . $page )->buildOauth( $url, 'get' )->performRequest();

    $json_decode = json_decode( $json ); 
	
	if( count( $json_decode->errors ) )
	{
		print $json_decode->errors[0]->message . '<br>';
	}
	else
	{
		$total += count( $json_decode->results );    
		if( $json_decode->next_page )
		{
			$temp = explode( "&", $json_decode->next_page );        
			$p = explode( "=", $temp[0] );                
			$total = getTweets( $hashtag, $p[1], $total, $twitterApi );
		}
	}
	
	return $total;
}

$total = getTweets( $hashtag, 1, $total, $twitterApi ); 

print 'Total Tweets: ' . $total;