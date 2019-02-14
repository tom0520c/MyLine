<?php
    $json_str = file_get_contents('php://input'); //接收request的body
    $json_obj = json_decode($json_str); //轉成json格式
  
    $myfile = fopen("log.txt", "w+") or die("Unable to open file!"); //設定一個log.txt來印訊息
    fwrite($myfile, "\xEF\xBB\xBF".$json_str); //在字串前面加上\xEF\xBB\xBF轉成utf8格式
    
    $sender_userid = $json_obj->events[0]->source->userId; //取得訊息發送者的id
    $sender_txt = $json_obj->events[0]->message->text; //取得訊息內容
    $sender_replyToken = $json_obj->events[0]->replyToken; //取得訊息的replyToken
    $imageId = $json_obj->events[0]->message->id; //取得圖片訊息編號
    $url = 'https://api.line.me/v2/bot/message/'.$imageId.'/content'; //取得圖片網址
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Authorization: Bearer jMq9LqPNycxu5cId9Yb5/NfVph/Jh4hOZlbHUNhqS15E1i7PbPbgISad/Pz7gcC7/hYVP0qFVBF+5YUHratPPdCeeB5/bbNhJBTZFPgZ3QgzVGptElg/BWZFRgU1hab5DZKIdjx/m8lCGjmdEwDVugdB04t89/1O/w1cDnyilFU='
    ));

    $json_content = curl_exec($ch);
    curl_close($ch);
    $imagefile = fopen($imageId.".jpeg", "w+") or die("Unable to open file!"); //取得圖片
    fwrite($imagefile, $json_content); 
    fclose($imagefile); //將圖片存在自己server上
			
    $header[] = "Content-Type: application/json";
    $post_data = array (
        "requests" => array (
            array (
                "image" => array (
                    "source" => array (
                        "imageUri" => "https://159.65.4.103/cht20190214/cmyLine/".$imageId.".jpeg"
                    )
                ),
                "features" => array (
                    array (
                        "type" => "TEXT_DETECTION",
                        "maxResults" => 1
                    )
                )
            )
        )
    );
    fwrite($myfile, "\xEF\xBB\xBF".json_encode($post_data));
    $ch = curl_init('https://vision.googleapis.com/v1/images:annotate?key=AIzaSyBJH3w6aTjoIBYhCh8GiI5byZ0Z-Q88cfg');                                                                      
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));                                                                  
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);                                                                                                   
    $result = json_decode(curl_exec($ch));

    $result_ary = explode("\n",$result -> responses[0] -> fullTextAnnotation -> text);
    //fwrite($myfile, "\xEF\xBB\xBF".json_encode($result -> responses[0] -> fullTextAnnotation -> text));

    $ans_txt = "這張發票沒用了，你又製造了一張垃圾";
    foreach ($result_ary as $val) {
        if($val == "MB-76164441"){
          $ans_txt = "恭喜您中獎啦，快分紅!!";
        }
    }
    //fwrite($myfile, "aaaaa");
    $response = array (
        "replyToken" => $sender_replyToken,
        "messages" => array (
            array (
                "type" => "text",
                "text" => $ans_txt
            //"text" => $result -> responses[0] -> fullTextAnnotation -> text
            )
        )
    );
  
    fwrite($myfile, "\xEF\xBB\xBF".json_encode($response)); //在字串前面加上\xEF\xBB\xBF轉成utf8格式
    $header[] = "Content-Type: application/json";
    $header[] = "Authorization: Bearer jMq9LqPNycxu5cId9Yb5/NfVph/Jh4hOZlbHUNhqS15E1i7PbPbgISad/Pz7gcC7/hYVP0qFVBF+5YUHratPPdCeeB5/bbNhJBTZFPgZ3QgzVGptElg/BWZFRgU1hab5DZKIdjx/m8lCGjmdEwDVugdB04t89/1O/w1cDnyilFU=";
    $ch = curl_init("https://api.line.me/v2/bot/message/reply");
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($response));                                                                  
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);                                                                                                   
    $result = curl_exec($ch);
    curl_close($ch);
?>
