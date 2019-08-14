<?php
namespace App\helpers;
/**
 * Created by PhpStorm.
 * User: emodatt08
 * Date: 17/08/2018
 * Time: 5:04 PM
 */
interface CrawlerInterface
{


    /**
     * Function to crawl 
     * @return mixed
    */
   public function crawl($tag,$tagToGet);
}