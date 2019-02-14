<?php
    $json_str = file_get_contents('php://input'); //接收request的body
    $json_obj = json_decode($json_str); //轉成json格式
  
    $myfile = fopen("replyMsglog.txt", "w+") or die("Unable to open file!"); //設定一個log.txt來印訊息
    //fwrite($myfile, "\xEF\xBB\xBF".$json_str); //在字串前面加上\xEF\xBB\xBF轉成utf8格式
  
    $sender_userid = $json_obj->events[0]->source->userId; //取得訊息發送者的id
    $sender_txt = $json_obj->events[0]->message->text; //取得訊息內容
    $sender_replyToken = $json_obj->events[0]->replyToken; //取得訊息的replyToken
  
    $response = array (
        "replyToken" => $sender_replyToken,
        "messages" => array (
            array (
                "type" => "text",
                "text" => "Hello. You say". $sender_txt
            )
        )
    );
  
    fwrite($myfile, "\xEF\xBB\xBF".json_encode($response)); //在字串前面加上\xEF\xBB\xBF轉成utf8格式
    $header[] = "Content-Type: application/json";
    $header[] = "Authorization: Bearer gd1gyH+Pc5TROu9ku5u/5tDvFnffsU8nXU69zXuhTgE0dIS5nVGmx9Js8PwijeUqgFuwWXzyJ14/N5FUmp/UXsmSJbUsxMGA6AW1gozlf6cbEgSGLiC02BEaRa5wUSqE7df8FOANP1WjPW8Mh/TgtwdB04t89/1O/w1cDnyilFU=";
    $ch = curl_init("https://api.line.me/v2/bot/message/reply");
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($response));                                                                  
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);                                                                                                   
    $result = curl_exec($ch);
    curl_close($ch);
?>
