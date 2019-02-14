<?php

	// $bot = new \\LINE\LINEBot(new CurlHTTPClient('your-channel-token'), \[ 'channelSecret' => 'your-channel-secret'\]);
 
//$res = $bot->getProfile('user-id');
//if ($res->isSucceeded()) {
// $profile = $res->getJSONDecodedBody();
// $displayName = $profile\['displayName'\];
// $statusMessage = $profile\['statusMessage'\];
// $pictureUrl = $profile\['pictureUrl'\];
//}



	$json_str = file_get_contents('php://input'); //接收request的body(可以接收除了Content-type為multipart/form-data的資料)
	$json_obj = json_decode($json_str); //轉成json格式
	
	$myfile = fopen("log.txt", "w+") or die("Unable to open file!"); //設定一個log.txt，用來印訊息
	fwrite($myfile, "\xEF\xBB\xBF".$json_str); //在字串前加上\xEF\xBB\xBF轉成utf8格式

	$sender_userid = $json_obj->events[0]->source->userId; //取得訊息的發送者id
	$sender_txt = $json_obj->events[0]->message->text;	//取得訊息內容
	$sender_txtType = $json_obj->events[0]->message->type;	//取得訊息種類
	$sender_replyToken = $json_obj->events[0]->replyToken; //取得訊息的replyToken
	$line_server_url = 'https://api.line.me/v2/bot/message/push';
	
	if($sender_txtType == "image"){
		$imageId = $json_obj->events[0]->message->id; //取得訊息編號
		$url = 'https://api.line.me/v2/bot/message/'.$imageId.'/content';
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Authorization: Bearer jMq9LqPNycxu5cId9Yb5/NfVph/Jh4hOZlbHUNhqS15E1i7PbPbgISad/Pz7gcC7/hYVP0qFVBF+5YUHratPPdCeeB5/bbNhJBTZFPgZ3QgzVGptElg/BWZFRgU1hab5DZKIdjx/m8lCGjmdEwDVugdB04t89/1O/w1cDnyilFU='
		));
		$json_content = curl_exec($ch);
		curl_close($ch);
		$imagefile = fopen($imageId.".jpeg", "w+") or die("Unable to open file!");
		fwrite($imagefile, $json_content); 
		fclose($imagefile); //將圖片存在server上
			
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
		$ans_txt = "這張發票沒用了，你又製造了一張垃圾";
		foreach ($result_ary as $val) {
			if($val == "AG-26272435"){
				$ans_txt = "恭喜您中獎啦，快分紅!!";
			}
		}
		$response = array (
			"to" => $sender_userid,
			"messages" => array (
				array (
					"type" => "text",
					// "text" =>$ans_txt
					 "text" => $result -> responses[0] -> fullTextAnnotation -> text
				)
			)
		);
	}else{
		switch ($sender_txt) {
			case "push":
				$response = array (
					"to" => $sender_userid,
					"messages" => array (
						array (
							"type" => "text",
							"text" => "Hello. This is push. You say ".$sender_userid
						)
					)
				);
				break;
			case "reply":
				$line_server_url = 'https://api.line.me/v2/bot/message/reply';
				$response = array (
					"replyToken" => $sender_replyToken,
					"messages" => array (
						array (
							"type" => "text",
							"text" => "Hello. This is reply. You say ".$sender_txt
						)
					)
				);
				break;
			case "image":
				$line_server_url = 'https://api.line.me/v2/bot/message/reply';
				$response = array (
					"replyToken" => $sender_replyToken,
					"messages" => array (
						array (
							"type" => "image",
							"originalContentUrl" => "https://www.w3schools.com/css/paris.jpg",
							"previewImageUrl" => "https://www.nasa.gov/sites/default/themes/NASAPortal/images/feed.png"
						)
					)
				);
				break;
			case "location":
				$line_server_url = 'https://api.line.me/v2/bot/message/reply';
				$response = array (
					"replyToken" => $sender_replyToken,
					"messages" => array (
						array (
							"type" => "location",
							"title" => "my location",
							"address" => "〒150-0002 東京都渋谷区渋谷２丁目２１−１",
							"latitude" => 35.65910807942215,
							"longitude" => 139.70372892916203
						)
					)
				);
				break;
			case "sticker":
				$line_server_url = 'https://api.line.me/v2/bot/message/reply';
				$response = array (
					"replyToken" => $sender_replyToken,
					"messages" => array (
						array (
							"type" => "sticker",
							"packageId" => "1",
							"stickerId" => "1"
						)
					)
				);
				break;
			case "button":
				$line_server_url = 'https://api.line.me/v2/bot/message/reply';
				$response = array (
					"replyToken" => $sender_replyToken,
					"messages" => array (
						array (
							"type" => "template",
							"altText" => "this is a buttons template",
							"template" => array (
								"type" => "buttons",
								"thumbnailImageUrl" => "https://www.w3schools.com/css/paris.jpg",
								"title" => "Menu",
								"text" => "Please select",
								"actions" => array (
									array (
										"type" => "postback",
										"label" => "Buy",
										"data" => "action=buy&itemid=123"
									),
									array (
										"type" => "postback",
										"label" => "Add to cart",
										"data" => "action=add&itemid=123"
									)
								)
							)
						)
					)
				);
				break;
			default:
				$sender_txt=rawurlencode($sender_txt); //因為使用get的方式呼叫luis api，所以需要轉碼
				$ch = curl_init('https://westus.api.cognitive.microsoft.com/luis/v2.0/apps/2a767935-e661-4c58-8d16-ad32fcbb5d95?subscription-key=2c842c8dba264856887b7d947d96fd05&staging=true&verbose=true&timezoneOffset=480&q='.$sender_txt);                                                                      
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");                                                                                                                          
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				$result_str = curl_exec($ch);
				fwrite($myfile, "\xEF\xBB\xBF".$result_str); //在字串前加上\xEF\xBB\xBF轉成utf8格式
				$result = json_decode($result_str);
				$ans_txt = $result -> topScoringIntent -> intent;
				$response = array (
					"to" => $sender_userid,
					"messages" => array (
						array (
							"type" => "text",
							"text" => $ans_txt
						)
					)
				);
			break;
		}
	}
	
	fclose($myfile);
		
	//回傳給line server
	$header[] = "Content-Type: application/json";
	$header[] = "Authorization: Bearer jMq9LqPNycxu5cId9Yb5/NfVph/Jh4hOZlbHUNhqS15E1i7PbPbgISad/Pz7gcC7/hYVP0qFVBF+5YUHratPPdCeeB5/bbNhJBTZFPgZ3QgzVGptElg/BWZFRgU1hab5DZKIdjx/m8lCGjmdEwDVugdB04t89/1O/w1cDnyilFU=";
	$ch = curl_init($line_server_url);                                                                      
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($response));                                                                  
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
	curl_setopt($ch, CURLOPT_HTTPHEADER, $header);                                                                                                   
	$result = curl_exec($ch);
	curl_close($ch);
?>
