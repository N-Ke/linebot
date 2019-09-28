<?php
    // Composerでインストールしたライブラリを一括読み込み
    require_once __DIR__ . '/vendor/autoload.php';

    // アクセストークンを使いCurlHTTPClientをインスタンス化
    $httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient('QWMGeklEwfRuDHfBXvyPUkhHQmfwNBSmzlAWSRvJyJ7DAUyubIp9VtIClhljmx1MFBIK7/d8Cy0/m5tfiMOOy25S8Xc6IFH18smRC26ZuBXS5vb+PaMg7YuF0V76fDHm80Lx0pqsAzMkMo5a6KJXnQdB04t89/1O/w1cDnyilFU=
');

    //CurlHTTPClientとシークレットを使いLINEBotをインスタンス化
    $bot = new \LINE\LINEBot($httpClient, ['channelSecret' => '794d2ee18b69ac9a07f3c1f6aaceb3b7']);

    // LINE Messaging APIがリクエストに付与した署名を取得
    $signature = $_SERVER["HTTP_" . \LINE\LINEBot\Constant\HTTPHeader::LINE_SIGNATURE];

    //署名をチェックし、正当であればリクエストをパースし配列へ、不正であれば例外処理
    $events = $bot->parseEventRequest(file_get_contents('php://input'), $signature);

    foreach ($events as $event) {
        // メッセージを返信
        $response = $bot->replyMessage(
            $event->getReplyToken(), new \LINE\LINEBot\MessageBuilder\TextMessageBuilder($event->getText())  
        );
    }
