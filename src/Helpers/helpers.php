<?php
/**
 * Created by PhpStorm.
 * User: emodatt08
 * Date: 20/08/2018
 * Time: 2:22 PM
 */

namespace App\helpers;


trait Helpers
{


    public function __construct() {
        if (!function_exists('dd')) {
            $this->dd();
        }
    }
    /**
     * Die dump function
     */
    function dd()
    {
        array_map(function($x) { var_dump($x); }, func_get_args());
        die;
    }

    /**
     * convert from json to array
     */
    function fromJson($object){
        return json_decode($object, true);
    }

    /**
     * Format response
     * @param $level
     * @param null $data
     * @return null|string
     */

    function responses($level, $data = null){
        $header = header("Content-Type:application/json");
        $response = null;
        switch($level){
            case "1":
                $response = json_encode(['responseCode' => '01', 'responseMsg' => 'Success', 'data' => $data]);
             break;
            case "2":
                $response = json_encode(['responseCode' => '03', 'responseMsg' => 'Failed', 'data' => $data]);
                break;
            default:
                $response = json_encode(['responseCode' => '05', 'responseMsg' => 'Something went wrong', 'data' => $data]);
        }
            $header;
            return $response;
    }
}