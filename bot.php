<?php

$addMessage = function($text) {
	// Connecting, selecting database
	$host = "ec2-54-235-248-197.compute-1.amazonaws.com";
	$dbname = "d1tttof3ndli1u";
	$user = "pxdvqzkqrblqjo";
	$password = "ca489debf088e6027e2d3aa2f513337ba7f5d6ef68e686d9fa52652cdeac883f";
	$dbconn = pg_connect("host=".$host." dbname=".$dbname." user=".$user." password=".$password)
	    or die('Could not connect: ' . pg_last_error());
	// Performing SQL query
	$query = "SELECT max(line_message_id) AS max_id FROM smstv.line_message";
	$result = pg_query($query) or die('Query failed: ' . pg_last_error());
	
	$maxId = 0;
	if ($row = pg_fetch_array($result)) {
	    $maxId = $row['max_id'];
	}
	$maxId = $maxId + 1;
	
	$query = "INSERT INTO smstv.line_message (line_message_id, message, sender) VALUES ($maxId, '$text', 'Sender $maxId')";
	$result = pg_query($query) or die('Query failed: ' . pg_last_error());
	
	pg_free_result($result);
	// Closing connection
	pg_close($dbconn);
	
	return "ข้อความตอบกลับ"."$maxId";
};

//////////////////////

$access_token = 'nLZ7DXTw9R1AYkIgkm7Ixu7E/Ftp32vHBIKGOw+VwaLjKQgSO5AIxGsLWJ2sWnbWXkhKh0ihNgJJkAvhwTze5swUMZFX8Mm3Tx+i21Btn6XAWICF7V1cjjg/fWzvQGWPNemz44dmY8VTJ9cm8PU2owdB04t89/1O/w1cDnyilFU=';

// Get POST body content
$content = file_get_contents('php://input');
// Parse JSON
$events = json_decode($content, true);
// Validate parsed JSON data
if (!is_null($events['events'])) {
	// Loop through each event
	foreach ($events['events'] as $event) {
		// Reply only when message sent is in 'text' format
		if ($event['type'] == 'message' && $event['message']['type'] == 'text') {
			// Get text sent
			//$text = $event['message']['text']; 
			
			//$text = "text = " . var_export($event, true);
			$text = "text1 = " . $event['message']['text'] . ", userId = " . $event['source']['userId'];
			
			// Get profile
			
			$httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient($access_token);
			$bot = new LINE\LINEBot($httpClient, ['channelSecret' => 'a711c9a806b76224695eaac12b3d9c69']);
			$response = $bot->getProfile('Uee518041b8409b808d28e07ae8cf8b39');
			if ($response->isSucceeded()) {
			    $profile = $response->getJSONDecodedBody();
			    //echo $profile['displayName'];
			    //echo $profile['pictureUrl'];
			    //echo $profile['statusMessage'];
				$text = $text . ", displayName = " . $profile['displayName'];
			}
			$text = $text . " text2.";
			/*
			{
			    "displayName":"LINE taro",
			    "userId":"Uxxxxxxxxxxxxxx...",
			    "pictureUrl":"http://obs.line-apps.com/...",
			    "statusMessage":"Hello, LINE!"
			}
			*/
			////////////////
			
			// Get replyToken
			$replyToken = $event['replyToken'];

			// Build message to reply back
			$messages = [
				'type' => 'text',
				'text' => $text
			];

			// Make a POST Request to Messaging API to reply to sender
			$url = 'https://api.line.me/v2/bot/message/reply';
			$data = [
				'replyToken' => $replyToken,
				'messages' => [$messages],
			];
			$post = json_encode($data);
			$headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);

			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			$result = curl_exec($ch);
			curl_close($ch);

			echo $result . "\r\n";
		}
	}
}
//echo "OK2";


