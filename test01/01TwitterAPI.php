<?php
// Twitter API関連情報
define('CONSUMER_KEY', 'xxxx');
define('CONSUMER_SECRET', 'xxxx');
define('ACCESS_TOKEN', 'xxxx');
define('ACCESS_TOKEN_SECRET', 'xxxx');

//「twitteroauth」読み込み
require_once '../vendor/autoload.php';

// 開始ログ
echo "start... \r\n";

//トークンを記述
$connection = new \Abraham\TwitterOAuth\TwitterOAuth(CONSUMER_KEY,CONSUMER_SECRET,ACCESS_TOKEN,ACCESS_TOKEN_SECRET);

//ツイート取得
/*
 * q：検索キーワード
 * result_type：取得ツイートの種類[popular,recent,mixed]
 * count：取得ツイート数
 */
$tweets_params = array('q' => 'JustinBieber','result_type'=>'recent','count' => '100');
$tweets = $connection->get('search/tweets', $tweets_params)->statuses;

$count = 0;
$data = [];

foreach ($tweets as $value) {

	// $value->extended_entities->mediaが存在する場合のみ
	if(isset($value->extended_entities->media)){
		foreach($value->extended_entities->media as $value_media){
		
			// 画像の場合のみ
			if($value_media->type == 'photo'){
			
				// 画像URL
				$media_url = $value_media->media_url;
				// 画像名
				$name = substr($media_url,strripos ($media_url, '/')+1);
				
				// 重複する画像は除外
				if(!in_array($media_url, $data)){
				
					// 画像を出力
					$file = file_get_contents($media_url);
					file_put_contents('./download/'.$name, $file);
					$data[] = $media_url;

					// 10枚取得したら終了
					$count++;
					if( $count === 10) break 2;
				}
			}
		}
	}
}

// 終了ログ
echo "...end \r\n";
exit;
