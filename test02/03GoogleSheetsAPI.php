<?php
define("SHEET_ID", "11BCnspCt2Mut3nhc4WMY6CYTd0zF9C3eCzsk1AEpKLM"); // GoogleスプレッドシートのID
define("API_KEY", "XXXXXXXXXXXXXXXXXX"); // API KEY
define("SALES", "A1:E6"); // 取得したいセル

$url = 'https://sheets.googleapis.com/v4/spreadsheets/'.SHEET_ID.'/values/sales!'.SALES.'?key='.API_KEY;

$json = @file_get_contents($url);

// スプレッドシートが存在するか
if($json !== false){

	$json_decode = json_decode($json);

	if (array_key_exists("values",$json_decode)){
		$values = $json_decode->values;

		foreach ($values as $row) {
			foreach ($row as $v){
			    print "'{$v}',";
			}
			print "\n";
		}
	} else {
		print "no data.";
	}
} else {
	print "no sheet.";
}
exit;
