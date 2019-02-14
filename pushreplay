<?php
    $json_str = file_get_contents('php://input'); //接收request的body
    $json_obj = json_decode($json_str); //轉成json格式
  
    $myfile = fopen("log.txt", "w+") or die("Unable to open file!"); //設定一個log.txt來印訊息
    fwrite($myfile, "\xEF\xBB\xBF".$json_str); //在字串前面加上\xEF\xBB\xBF轉成utf8格式
  
    $sender_userid = $json_obj->events[0]->source->userId; //取得訊息發送者的id
    $sender_type = $json_obj->events[0]->message->type; //取得訊息類型

    if($sender_type == "text"){
        $sender_txt = $json_obj->events[0]->message->text; //取得訊息內容
        if($sender_txt == "按鈕") {
            $response = array (
                "to" => $sender_userid,
                "messages" => array (
                    array (
                        "type" => "template",
                        "altText" => "BUTTON",
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
                                    "type" => "message",
                                    "label" => "Return",
                                    "text" => "This is text"
                                ),
                                array (
                                    "type" => "uri",
                                    "label" => "外站",
                                    "uri" => "https://google.com.tw/"
                                ),
                                array (
                                    "type" => "datetimepicker",
                                    "label" => "Select date",
                                    "data" => "storeId=12345",
                                    "mode" => "datetime",
                                    "initial" => "2017-12-25t00:00",
                                    "max" => "2018-01-24t23:59",
                                    "min" => "2017-12-25t00:00"
                                )
                            )
                        )
                    )
                )
            );
        } elseif($sender_txt == "是否") {
            $response = array (
                "to" => $sender_userid,
                "messages" => array (
                    array (
                        "type" => "template",
                        "altText" => "BUTTON",
                        "template" => array (
                            "type" => "confirm",
                            "text" => "是否！？",
                            "actions" => array (
                                array (
                                    "type" => "message",
                                    "label" => "是",
                                    "text" => "yes"
                                ),
                                array (
                                    "type" => "message",
                                    "label" => "否",
                                    "text" => "no"
                                )
                            )
                        )
                    )
                )
            );
        } elseif($sender_txt == "flex"){
            $msg_json = '{
                "type": "bubble",
                "hero": {
                  "type": "image",
                  "url": "https://scdn.line-apps.com/n/channel_devcenter/img/fx/01_1_cafe.png",
                  "size": "full",
                  "aspectRatio": "20:13",
                  "aspectMode": "cover",
                  "action": {
                    "type": "uri",
                    "uri": "http://linecorp.com/"
                  }
                },
                "body": {
                  "type": "box",
                  "layout": "vertical",
                  "contents": [
                    {
                      "type": "text",
                      "text": "Brown Cafe",
                      "weight": "bold",
                      "size": "xl"
                    },
                    {
                      "type": "box",
                      "layout": "baseline",
                      "margin": "md",
                      "contents": [
                        {
                          "type": "icon",
                          "size": "sm",
                          "url": "https://scdn.line-apps.com/n/channel_devcenter/img/fx/review_gold_star_28.png"
                        },
                        {
                          "type": "icon",
                          "size": "sm",
                          "url": "https://scdn.line-apps.com/n/channel_devcenter/img/fx/review_gold_star_28.png"
                        },
                        {
                          "type": "icon",
                          "size": "sm",
                          "url": "https://scdn.line-apps.com/n/channel_devcenter/img/fx/review_gold_star_28.png"
                        },
                        {
                          "type": "icon",
                          "size": "sm",
                          "url": "https://scdn.line-apps.com/n/channel_devcenter/img/fx/review_gold_star_28.png"
                        },
                        {
                          "type": "icon",
                          "size": "sm",
                          "url": "https://scdn.line-apps.com/n/channel_devcenter/img/fx/review_gray_star_28.png"
                        },
                        {
                          "type": "text",
                          "text": "4.0",
                          "size": "sm",
                          "color": "#999999",
                          "margin": "md",
                          "flex": 0
                        }
                      ]
                    },
                    {
                      "type": "box",
                      "layout": "vertical",
                      "margin": "lg",
                      "spacing": "sm",
                      "contents": [
                        {
                          "type": "box",
                          "layout": "baseline",
                          "spacing": "sm",
                          "contents": [
                            {
                              "type": "text",
                              "text": "Place",
                              "color": "#aaaaaa",
                              "size": "sm",
                              "flex": 1
                            },
                            {
                              "type": "text",
                              "text": "Miraina Tower, 4-1-6 Shinjuku, Tokyo",
                              "wrap": true,
                              "color": "#666666",
                              "size": "sm",
                              "flex": 5
                            }
                          ]
                        },
                        {
                          "type": "box",
                          "layout": "baseline",
                          "spacing": "sm",
                          "contents": [
                            {
                              "type": "text",
                              "text": "Time",
                              "color": "#aaaaaa",
                              "size": "sm",
                              "flex": 1
                            },
                            {
                              "type": "text",
                              "text": "10:00 - 23:00",
                              "wrap": true,
                              "color": "#666666",
                              "size": "sm",
                              "flex": 5
                            }
                          ]
                        }
                      ]
                    }
                  ]
                },
                "footer": {
                  "type": "box",
                  "layout": "vertical",
                  "spacing": "sm",
                  "contents": [
                    {
                      "type": "button",
                      "style": "link",
                      "height": "sm",
                      "action": {
                        "type": "uri",
                        "label": "CALL",
                        "uri": "https://linecorp.com"
                      }
                    },
                    {
                      "type": "button",
                      "style": "link",
                      "height": "sm",
                      "action": {
                        "type": "uri",
                        "label": "WEBSITE",
                        "uri": "https://linecorp.com"
                      }
                    },
                    {
                      "type": "spacer",
                      "size": "sm"
                    }
                  ],
                  "flex": 0
                }
              }';
            $response = array (
                "to" => $sender_userid,
                "messages" => array (
                    array (
                        "type" => "flex",
                        "altText" => "This is a Flex Message",
                        "contents" => json_decode($msg_json)
                    )
                )
            );
        } elseif($sender_txt == "快速回覆"){
            $response = array (
                "to" => $sender_userid,
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
        } else {
            $response = array (
                "to" => $sender_userid,
                "messages" => array (
                    array (
                        "type" => "text",
                        "text" => "Hello. You said: ". $sender_txt
                    )
                )
            );
        } 
    }
    elseif($sender_type == "sticker"){
        $sticker_id = $json_obj->events[0]->message->stickerId;
        $package_id = $json_obj->events[0]->message->packageId;
        $response = array (
            "to" => $sender_userid,
            "messages" => array (
                array (
                    "type" => "text",
                    "text" => "Hello. You post: stickerId=". $sticker_id. ", packageId=". $package_id
                )
            )
        );  //"type":"sticker","id":"9352799347319","stickerId":"21069261","packageId":"9601"
    }
//"type":"image","id":"9352825640135","contentProvider":{"type":"line"}}
    elseif($sender_type == "image"){
        $image_id = $json_obj->events[0]->message->id;
        $response = array (
            "to" => $sender_userid,
            "messages" => array (
                array (
                    "type" => "text",
                    "text" => "Hello, you've post an image(". $image_id. ")!"
                )
            )
        );
    }
    else
    {
        $response = array (
            "to" => $sender_userid,
            "messages" => array (
                array (
                    "type" => "text",
                    "text" => "I dont understand what you have done!"
                )
            )
        );
    }
  
    //fwrite($myfile, "\xEF\xBB\xBF".json_encode($response)); //在字串前面加上\xEF\xBB\xBF轉成utf8格式
    $header[] = "Content-Type: application/json";
    $header[] = "Authorization: Bearer jMq9LqPNycxu5cId9Yb5/NfVph/Jh4hOZlbHUNhqS15E1i7PbPbgISad/Pz7gcC7/hYVP0qFVBF+5YUHratPPdCeeB5/bbNhJBTZFPgZ3QgzVGptElg/BWZFRgU1hab5DZKIdjx/m8lCGjmdEwDVugdB04t89/1O/w1cDnyilFU=";
    $ch = curl_init("https://api.line.me/v2/bot/message/push");
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($response));                                                                  
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);                                                                                                   
    $result = curl_exec($ch);
    curl_close($ch);
?>
