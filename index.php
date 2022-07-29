<?php

use Discord\Discord;
use Discord\Parts\Channel\Message;
use Discord\WebSockets\Intents;
use Discord\WebSockets\Event;

require("vendor/autoload.php");

// get env variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

$discord = new Discord([
    'token' => $_ENV['DISC_TOKEN'],
    'loadAllMembers' => true, // Enable this option
    'intents' => Intents::getDefaultIntents() | Intents::GUILD_MEMBERS // Enable the `GUILD_MEMBERS` intent
]);

function makeRequest(String $url){
    // Guzzlle CLient
    $GuzzleClient = new GuzzleHttp\Client();
    try {
        $res = $GuzzleClient->request('GET', $_ENV['BASE_URL'] . $url, [
            'headers' => ['Authorization' => 'Bearer ' . $_ENV['COC_API_TOKEN']
            ]
        ]);
        return json_decode($res->getBody());
    } catch (\GuzzleHttp\Exception\GuzzleException $e) {
        return($e->getMessage());
    }

}

$discord->on('ready', function(Discord $discord) {

  $discord->on(Event::MESSAGE_CREATE, function (Message $message, Discord $discord) {
      if($message->author->bot) return false; // se é um bot não faz nada
      $message_content = explode(" ", $message->content);

      if($message_content[0] !== $_ENV['PREFIX']) return false;
      array_shift($message_content); // remove o comando do array

      $response = makeRequest('clans/%'.$_ENV['CLAN_TAG'].'/members');
      print_r($response);
      $message->channel->sendMessage(json_encode($response));
  });

});

$discord->run();