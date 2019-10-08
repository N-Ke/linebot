<?php

//チャンネルアクセストークン
$channelAccessToken = 'QWMGeklEwfRuDHfBXvyPUkhHQmfwNBSmzlAWSRvJyJ7DAUyubIp9VtIClhljmx1MFBIK7/d8Cy0/m5tfiMOOy25S8Xc6IFH18smRC26ZuBXS5vb+PaMg7YuF0V76fDHm80Lx0pqsAzMkMo5a6KJXnQdB04t89/1O/w1cDnyilFU=';
$ChannelSecret = '794d2ee18b69ac9a07f3c1f6aaceb3b7';
//ユーザーからのメッセージ取得

$inputData = file_get_contents('php://input');

//受信したJSON文字列をデコードします
$jsonObj = json_decode($inputData);

$to   = $jsonObj->{"result"}[0]->{"content"}->{"from"};

$text =  $jsonObj->{"result"}[0]->{"content"}->{"text"};


//Webhook Eventのタイプを取得
$eventType = $jsonObj->{"events"}[0]->{"type"};

//メッセージイベントだった場合です
//テキスト、画像、スタンプなどの場合「message」になります
//他に、follow postback beacon などがあります
if ($eventType == 'message') {


	//メッセージタイプ取得
	//ここで、受信したメッセージがテキストか画像かなどを判別できます
	$messageType = $jsonObj->{"events"}[0]->{"message"}->{"type"};

	//ReplyToken取得
	//受信したイベントに対して返信を行うために必要になります
	$replyToken = $jsonObj->{"events"}[0]->{"replyToken"};

	//メッセージタイプがtextの場合の処理
	if ($messageType == 'text') {

		//メッセージテキスト取得
		//ここで、相手から送られてきたメッセージそのものを取得できます
		$messageText = $jsonObj->{"events"}[0]->{"message"}->{"text"};

		//返答準備1
		//単純にテキストで返す場合です
		//よくあるオウム返しでは、text に $messageText を入れればOKです
		$response_format_text = [
			"type" => "text",
			"text" => "正解です"
		];

		//返答準備2
		//先程取得したトークンとともに、返答する準備です
		$post_data = [
			"replyToken" => $replyToken,
			"messages" => [$response_format_text]
		];
	
	//上記以外のメッセージタイプ
	//画像やスタンプなどの場合です
	//位置情報の場合
	} elseif ($messageType == 'location') {

		$loco = $jsonObj->{"events"}[0]->{"message"}->{"location"};
$loca = '';
		foreach ($loco as $locoo) {
			$loca .= $locoo;
		}
if (empty($loco)) {
		$response_format_text = [
		"type" => "text",
		"text" => "aaaa"
		];
		$post_data = [
			"replyToken" => $replyToken,
			"messages" => [$response_format_text]
		];

} else {

		$response_format_text = [
		"type" => "text",
		"text" => [$loco]
		];
		$post_data = [
			"replyToken" => $replyToken,
			"messages" => [$response_format_text]
		];

	}
}

//後は、Reply message用のURLに対して HTTP requestを行うのみです
$ch = curl_init("https://api.line.me/v2/bot/message/reply");

curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json; charser=UTF-8',
    'Authorization: Bearer ' . $channelAccessToken
    ));

$result = curl_exec($ch);
curl_close($ch);

?>
