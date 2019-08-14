<?php
require('../vendor/autoload.php');
use App\Controllers\ChatController;

$messages = (new ChatController())->getMessages();
echo $messages;