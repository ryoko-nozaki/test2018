<?php
define('API_KEY', 'xxx');
define('API_SECRET', 'xxx');
define('ACCESS_TOKEN', 'xxx');
define('ACCESS_SECRET', 'xxx');
		
//「twitteroauth」読み込み
require_once '../vendor/autoload.php';

//トークンを記述
//$connection = new \Abraham\TwitterOAuth\TwitterOAuth("Consumer Key (API Key)","Consumer Secret (API Secret)","Access Token","Access Token Secret");
$connection = new \Abraham\TwitterOAuth\TwitterOAuth(API_KEY,API_SECRET,ACCESS_TOKEN,ACCESS_SECRET);

//ツイート取得
/*
 * q：検索キーワード
 * result_type：取得ツイートの種類[popular,recent,mixed]
 * count：取得ツイート数
 */
$tweets_params = array('q' => 'JustinBieber','result_type'=>'recent','count' => '10');
$tweets = $connection->get('search/tweets', $tweets_params)->statuses;

foreach ($tweets as $value) {
	foreach($value->extended_entities->media as $value_media){
		if($value_media->type == 'photo'){
			$media_id = $value_media->id;		//画像ID
			$media_url = $value_media->media_url;		//画像URL
			echo $media_id.PHP_EOL.$media_url.PHP_EOL;
// 			$data = file_get_contents($media_url);
// 			file_put_contents('./download/dl.jpg',$data);PHP_EOL
		}
	}
}