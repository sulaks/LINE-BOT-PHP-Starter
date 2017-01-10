<?php

$addMessage = function() {
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
	
	pg_free_result($result);
	// Closing connection
	pg_close($dbconn);
	
	return "ข้อความตอบกลับ"."$maxId";
};
/*
$addMessage = function() {
	return "ข้อความตอบกลับ";
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
	//return $maxId;
	
	$query = "INSERT INTO smstv.line_message (line_message_id, message, sender) VALUES ('4', 'new text', 'Sender4')";
	$result = pg_query($query) or die('Query failed: ' . pg_last_error());
	
	pg_free_result($result);
	// Closing connection
	pg_close($dbconn);
		
};
*/
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
			$text = $event['message']['text'] . $addMessage(); 
			
			//$addMessage($text);
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


