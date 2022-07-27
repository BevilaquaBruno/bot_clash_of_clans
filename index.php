<?php

use Discord\Discord;
use Discord\Parts\Channel\Message;
use Discord\WebSockets\Intents;
use Discord\WebSockets\Event;

require("vendor/autoload.php");

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

$discord = new Discord([
    'token' => $_ENV['TOKEN'],
    'loadAllMembers' => true, // Enable this option
    'intents' => Intents::getDefaultIntents() | Intents::GUILD_MEMBERS // Enable the `GUILD_MEMBERS` intent
]);

$discord->on('ready', function(Discord $discord) {

  $discord->on(Event::MESSAGE_CREATE, function (Message $message, Discord $discord) {
      if($message->author->bot) return false; // se é um bot não faz nada
      $message_content = explode(" ", $message->content);

      if($message_content[0] !== $_ENV['PREFIX']) return false;
      array_shift($message_content); // remove o comando do array

      $message->channel->sendMessage("abc");
  });

});

$discord->run();