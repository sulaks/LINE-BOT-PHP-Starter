<?php
$access_token = 'nLZ7DXTw9R1AYkIgkm7Ixu7E/Ftp32vHBIKGOw+VwaLjKQgSO5AIxGsLWJ2sWnbWXkhKh0ihNgJJkAvhwTze5swUMZFX8Mm3Tx+i21Btn6XAWICF7V1cjjg/fWzvQGWPNemz44dmY8VTJ9cm8PU2owdB04t89/1O/w1cDnyilFU=';

$url = 'https://api.line.me/v1/oauth/verify';

$headers = array('Authorization: Bearer ' . $access_token);

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
$result = curl_exec($ch);
curl_close($ch);

echo $result;
