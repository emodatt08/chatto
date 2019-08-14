<?php
/**
 * Created by PhpStorm.
 * User: emodatt08
 * Date: 23/08/2018
 * Time: 3:45 PM
 */

require('vendor/autoload.php');
use App\Controller;

$queue = new Controller('vanguardTransaction','vanguard.transaction.exchange');
$messages = [
    "trans_id"=>"TF".date('ymd').uniqid(),
    "inst_id"=>"45",
    "branch"=>"014",
    "account_no"=>"03342113",
    "payer_id"=>"133",
    "currency"=>"GHS",
    "amount"=>"20.00",
    "payer_name"=>"James Sappor",
    "mobile"=>"233241763214",
    "email"=>"kollan@gmail.com",
    "alt1"=>"20160600010978",
    "alt3"=>"",
    "alt4"=>"cash",
    "alt5"=>"refuse collections"
];

//$queue->queue->publishMessages($messages);
echo $queue->storeTrans($messages);