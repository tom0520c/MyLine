<?php
  	$json_str = file_get_contents('php://input'); //接收request的body
  	$json_obj = json_decode($json_str); //轉成json格式
  
  	$myfile = fopen("log.txt", "w+") or die("Unable to open file!"); //設定一個log.txt來印訊息
  	//fwrite($myfile, "\xEF\xBB\xBF".$json_str); //在字串前面加上\xEF\xBB\xBF轉成utf8格式
  
  	$sender_userid = $json_obj->events[0]->source->userId; //取得訊息發送者的id
  	$sender_txt = $json_obj->events[0]->message->text; //取得訊息內容
  	$sender_replyToken = $json_obj->events[0]->replyToken; //取得訊息的replyToken
	  
	$response = array (
		"replyToken" => $sender_replyToken,
		"messages" => array (
			array (
				"type" => "text",
				"text" => "請試用quick replies功能",
				"quickReply" => array (
					"items" => array (
						array (
							"type" => "action",
							"imageUrl" => "https://sporzfy.com/chtuser1/apple.png",
							"action" => array (
								"type" => "message",
								"label"=> "Apple",
								"text" => "這是一個Apple"
							)
						),
						array (
                            "type" => "action",
                            "imageUrl" => "https://sporzfy.com/chtuser1/placeholder.png",
                            "action" => array (
                                "type" => "location",
                                "label"=> "請選擇位置"
                            )
                        ),
                        array (
                            "type" => "action",
                            "imageUrl" => "https://sporzfy.com/chtuser1/camera.png",
                            "action" => array (
                                "type" => "camera",
                                "label"=> "啟動相機"
                            )
                        ),
                        array (
                            "type" => "action",
                            "imageUrl" => "https://sporzfy.com/chtuser1/picture.png",
                            "action" => array (
                                "type" => "cameraRoll",
                                "label"=> "啟動相簿"
                            )
						)
					)
				)	
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
