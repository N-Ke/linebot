<?php
$url = 'http://webservice.recruit.co.jp/hotpepper/gourmet/v1/?key=1d7c45987a45cd65&lat=34.67&lng=135.52&range=5&order=4';
$json = $file_get_contents($url);
$json = mv_convert_encoding($json, 'UTF8', 'ASCII, JIS, UTF-8, EUC-JP, SJIS-WIN');
$data = json_decode($json, true);
?>
