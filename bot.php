<?php
$files = glob(__DIR__ . '/*.php');

foreach ($files as $file) {
    require_once $file;   
}

// parameters
$hubVerifyToken = 'Is238Group5Chatbot';
$accessToken =   "EAAKiQ7FkXIgBAACmoZBPAaZBvPxj7cblkIw9xEoOmXc6z41OSMSbMQsZAAcZB5nvucMrZAfzpc2cWPl6sgLuPpy4mPY7J1iZB4kDdq1Y9e7MOLrTSumhp1DhWU9Iwz6atAD5jA8TA5vTLZBT2dZAdnp8bXvA3B01NZCL4slzis3kMOLzZBoMW6gqN4"; 
// check token at setup
if ($_REQUEST['hub_verify_token'] === $hubVerifyToken) {
  echo $_REQUEST['hub_challenge'];
  exit;
}
// execute bot command
$input = json_decode(file_get_contents('php://input'), true);
$senderId = $input['entry'][0]['messaging'][0]['sender']['id'];
$messageText = $input['entry'][0]['messaging'][0]['message']['text'];
$command = trim(explode(" ", $messageText)[0]);
$parameter =  trim(substr($messageText, strlen($command)));
// get userDetails
$userDetails = json_decode(file_get_contents('https://graph.facebook.com/v2.6/'.$senderId.'?fields=first_name,last_name,profile_pic,locale,timezone,gender&access_token='.$accessToken), true);
$user = new User($userDetails);
// create sender
$sender = new Sender($senderId, $accessToken);
// resolve bot command
$botCommand = BotCommandFactory::create($command, $sender, $user);
$botCommand->execute($parameter);