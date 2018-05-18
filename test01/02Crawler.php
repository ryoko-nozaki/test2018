<?php
// phpQueryをロードする
require_once("phpQuery-onefile.php");

// データ取得の処理を関数化
function getData($url){

	// htmlを取得する
	$original = $url;
	$html = file_get_contents($original);

	// dom解析用のオブジェクトを生成する
	$doc = phpQuery::newDocument($html);

	// aタグを取得
	$tag = $doc->find("a");
	$data = [];
	$url = [];
	$text = [];
	$title = [];
	foreach($tag as $val) 
	{
		$a = pq($val);
		$href = $a->attr("href");
		if(strstr($href,'https://no1s.biz/') !== false)
		{
			$text[] = $a->text();
			if(strpos($href, '/', -1) === false)
			{
				$url[] = $href.'/';
			}
			else
			{
				$url[] = $href;
			}
		}
	}
	$url = array_unique($url);
	foreach( $url as $key => $value )
	{
		$data['title'][] = $text[$key];
		$data['url'][] = $value;
	}
	return $data;
}

// 最初のページ内検索
function crawler1($base_data)
{
	foreach($base_data['url'] as $base_value)
	{
		$list[] = getData($base_value);
	}
	foreach($list as $list_value)
	{
		$diff_list = array_diff($list_value['url'], $base_data['url']);
		if(!$diff_list) continue;
		foreach($diff_list as $key => $val)
		{
			$base_data['url'][] = $val;
			$base_data['title'][] = $list_value['title'][$key];
		}
	}
	return $base_data;
}

// 二番目以降
function crawler2($base_data, $data)
{
	$diff = array_diff($data['url'], $base_data['url']);
	foreach($diff as $diff_value)
	{
		$list[] = getData($diff_value);
	}
	foreach($list as $list_value)
	{
		$diff_list = array_diff($list_value['url'], $base_data['url']);
		if(!$diff_list) continue;
		foreach($diff_list as $key => $val)
		{
			$base_data['url'][] = $val;
			$base_data['title'][] = $list_value['title'][$key];
		}
	}
	return $base_data;
}

// 開始ログ
echo "start... \r\n";
 
// データ取得処理を呼び出す。
$base_data = getData('https://no1s.biz/');

$data = crawler1($base_data);

$diff = array_diff($data['url'], $base_data['url']);
$count = count($diff);

// 差分がなくなるまで繰り返し
while($count > 0){
	$box = $data;

	$data = crawler2($base_data, $data);
	$diff = array_diff($data['url'], $base_data['url']);

	$base_data = $box;
	$count = count($diff);
}

// 出力
foreach($data['url'] as $k => $v)
{
	echo $v.' '.$data['title'][$k].PHP_EOL;
}

// 終了ログ
echo "...end \r\n";
exit;
