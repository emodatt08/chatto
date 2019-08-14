<?php
/**
 * Created by PhpStorm.
 * User: emodatt08
 * Date: 17/08/2018
 * Time: 5:02 PM
 */

namespace App;

use App\helpers\GetNews;
use App\helpers\GetRssNews;
use App\helpers\GetSpecialNewsFeeds;
use App\helpers\Helpers;

class Controller extends GetNews
{
    public $store;

    use Helpers;

    function __construct()
    {
        $this->store = new \App\Database();


    }


    /**
     * this processes crawled data
     * @param $data
     * @return mixed
     */

    public function getCrawledData()
    {

            $query = "SELECT * FROM posts_urls LEFT JOIN websites ON websites.website_id = posts_urls.website_id WHERE posts_urls.url_status = '1' AND is_rss = '0'";
                $urls =  $this->store->runQuery($query, "", array());

                foreach($urls as $url):
                    //$this->dd($url);
                $newsUrl = new GetNews($url["post_url"]);
                    //$fetch = $newsUrl->fetchData();
                     //if($this->calTimeDiff($url["url_crawled_at"]) > 5):
                    if(true):
                                        //var_dump($this->fromJson($url["elements"]), $this->fromJson($url["element_attribute"])); die;
                //var_dump($this->fromJson($url[0][0]["elements"]), $this->fromJson($url[0][0]["element_attribute"])); die;

                            $elements = $this->fromJson($url["elements"]);
                            $element_attr =  $this->fromJson($url["element_attribute"]);
                        //$this->dd($elements,$element_attr );
                           
                            $data['title'] = $newsUrl->crawl($elements['post_title_element'], $element_attr['post_title_type']);
                            //$this->dd($data['title']);
                            $data['desc'] = ($elements['post_description_element'] == "") ? "":$newsUrl->crawl($elements['post_description_element'], $element_attr['post_description_type']);
                            //$this->dd($data['desc']);
                            $data['post_url'] = $newsUrl->crawl($elements['post_url_element'], $element_attr['post_url_type']);
                             //$this->dd($data['post_url']);
                        //$this->dd($elements['post_image_element'], $element_attr['post_image_type']);
                            $data['image'] = $newsUrl->crawl($elements['post_image_element'], $element_attr['post_image_type']);
                            $this->dd($data['post_url'],$data['image']);
                            $data['media'] = ($elements['post_media_element'] == "") ? "":  $newsUrl->getMultimediaData($elements['post_media_element'], $element_attr['post_media_type']);
                            $data['count_id'] = $url['count_id'];
                            $data['cate_id'] = $url['cate_id'];

                            $this->setCrawledTime($url["url_id"]);
                            for($x = 0; $x < count($data[$url['count_type']]); $x++){
                                $this->insertIntoTable($data,$x);
                            }

                            print_r($data);
                     endif;
               endforeach;

    }


    
    public function getSpecialCrawledData()
    {

            $query = "SELECT * FROM posts_urls LEFT JOIN websites ON websites.website_id = posts_urls.website_id WHERE posts_urls.url_status = '1' AND posts_urls.is_special = '1'";
                $urls =  $this->store->runQuery($query, "", array());

                foreach($urls as $url):
                    //$this->dd($url);
                $newsUrl = new GetSpecialNewsFeeds($url["post_url"]);
                    //$fetch = $newsUrl->fetchData();
                     //if($this->calTimeDiff($url["url_crawled_at"]) > 5):
                    if(true):
                                        //var_dump($this->fromJson($url["elements"]), $this->fromJson($url["element_attribute"])); die;
                //var_dump($this->fromJson($url[0][0]["elements"]), $this->fromJson($url[0][0]["element_attribute"])); die;

                            $elements = $this->fromJson($url["elements"]);
                            $element_attr =  $this->fromJson($url["element_attribute"]);
                        //$this->dd($elements,$element_attr );
                           
                            $data['title'] = $newsUrl->crawl($elements['post_title_element'], $element_attr['post_title_type']);
                            //$this->dd($data['title']);
                            $data['desc'] = ($elements['post_description_element'] == "") ? "":$newsUrl->crawl($elements['post_description_element'], $element_attr['post_description_type']);
                            //$this->dd($data['desc']);
                            $data['post_url'] = $newsUrl->crawl($elements['post_url_element'], $element_attr['post_url_type']);
                             //$this->dd($data['post_url']);
                        //$this->dd($elements['post_image_element'], $element_attr['post_image_type']);
                            $data['image'] = $newsUrl->crawl($elements['post_image_element'], $element_attr['post_image_type']);
                            //$this->dd($data['image']);
                            $data['media'] = ($elements['post_media_element'] == "") ? "":  $newsUrl->getMultimediaData($elements['post_media_element'], $element_attr['post_media_type']);
                            $data['count_id'] = $url['count_id'];
                            $data['cate_id'] = $url['cate_id'];

                            $this->setCrawledTime($url["url_id"]);
                            for($x = 0; $x < count($data[$url['count_type']]); $x++){
                                $this->insertIntoTable($data,$x);
                            }

                            print_r($data);
                     endif;
               endforeach;

    }
    public function getRssData(){
        $query = "SELECT * FROM posts_urls LEFT JOIN websites ON websites.website_id = posts_urls.website_id WHERE posts_urls.url_status = '1' AND is_rss = '1'";
                $urls =  $this->store->runQuery($query, "", array());
                //$this->dd($urls);
              foreach($urls as $url):
                    //$this->dd($this->calTimeDiff($url["url_crawled_at"]));
                $newsUrl = new GetRssNews();
                    $newsUrl->fetchData($url['post_url']);

                     //if($this->calTimeDiff($url["url_crawled_at"]) > 5):
                if(true):
                    $elements = $this->fromJson($url["elements"]);
                    $element_attr =  $this->fromJson($url["element_attribute"]);
                    //$this->dd($elements,$element_attr );
                    //$fetch = $newsUrl->fetchData($url['post_url']);
                    $rss['title'] = $newsUrl->getRssFeeds($elements['post_title_element'], $element_attr['post_title_type']);
                     //$this->dd($rss['title']);
                    $rss['desc'] = $newsUrl->getRssFeeds($elements['post_description_element'], $element_attr['post_description_type']);
                    //$this->dd($rss['desc']);
                   $rss['post_url'] = $newsUrl->getRssFeeds($elements['post_url_element'], $element_attr['post_url_type']);
                   // $this->dd($rss['post_url']);
                    $rss['image'] = $newsUrl->getRssFeeds($elements['post_image_element'], $element_attr['post_image_type']);
                    //$this->dd($elements['post_image_element'], $element_attr['post_image_type'],$rss['image']);
                        //$this->dd($rss['image']);                                    
                            $rss['count_id'] = $url['count_id'];
                            $rss['cate_id'] = $url['cate_id'];
                            $rss['crawlType'] = "rss";
                            $this->setCrawledTime($url["url_id"]);
                            for($x = 0; $x < count($rss[$url['count_type']]); $x++){
                                $this->insertIntoTable($rss,$x);
                            }

                            //print_r($rss);
                    endif;
                    
                endforeach;

    }

    /**
     * Returns the insert columns
     * @return array
     */
    private function insertColumns(){
        return [
            'title',
            'body',
            'url',
            'image',
            'post_category_id',
            'post_country_id'
        ];
    }

    /**
     * Calculate time difference
     * @params $timerecorded
     * @return string
     */
    public function calTimeDiff($timeRecorded){
        $t1 = strtotime($timeRecorded);
        $t2 = strtotime(date('Y-m-d H:i:s'));
        $delta_T = ($t2 - $t1);
//        $day = round(($delta_T % 604800) / 86400);
//        $hours = round((($delta_T % 604800) % 86400) / 3600);
        $minutes = round(((($delta_T % 604800) % 86400) % 3600) / 60);
        //$sec = round((((($delta_T % 604800) % 86400) % 3600) % 60));
        return (int) $minutes;
    }

    /**
     * This prepares the params so they are stored by the [storeTrans] table
     * @param $data
     * @return array
     */
    public function prepareParams($data,$x){
        if(isset($data['crawlType']) && $data['crawlType'] == "rss"){   
       
             return [
                    $this->removeQuotes(strip_tags((string) $data['title'][$x][0])),
                    $this->removeQuotes(strip_tags((string) $data['desc'][$x][0])),
                    strip_tags((string) $data['post_url'][$x][0]),
                    
                    (array_key_exists('0',$data['image'][$x])) ? strip_tags((string) $data['image'][$x][0]):strip_tags((string) $data['image'][$x]),
                     $data['cate_id'],
                     $data['count_id']
            ];
        }else{
            return [
                    $this->removeSpecialChars(strip_tags($data['title'][$x])),
                    $this->removeSpecialChars(strip_tags($data['desc'][$x])),
                    $data['post_url'][$x],
                    $data['image'][$x],
                    $data['cate_id'],
                    $data['count_id']
            ];
        }
           
    }

    /**
     * Insert into posts_url table table
     * @param $data, $x
     */

private function insertIntoTable($data,$x){

      $columns = implode(',',$this->insertColumns());
      $query = "INSERT IGNORE INTO posts({$columns})VALUES ";
            $query = $query."(\"" .implode('","', $this->prepareParams($data, $x))."\")";
             //$this->dd($query);
            $store = $this->store->runQuery($query, "AFF", array());
}

    /**
     * Update crawled timestamp
     * @param $id
     */
private function setCrawledTime($id){
    $query = "UPDATE posts_urls SET url_crawled_at = ? WHERE url_id = ?";
    $update = $this->store->runQuery($query, "AFF", array(date('Y-m-d H:i:s'), $id));
    // var_dump("Update Crawled date = ",$update);
}

private function removeQuotes($string){
    $string = str_replace('"', "'   ", $string);
    return $string;
}

    /**
     * Removes special characters
     * @param $id
     */
private function removeSpecialChars($string){
    $string = str_replace(array('[\', \']'), '', $string);
    $string = preg_replace('/\[.*\]/U', '', $string);
    $string = preg_replace('/&(amp;)?#?[a-z0-9]+;/i', '', $string);
    $string = htmlentities($string, ENT_COMPAT, 'utf-8');
    $string = preg_replace('/&([a-z])(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig|quot|rsquo);/i', '\\1', $string );
    $string = preg_replace(array('/[^a-z0-9]/i', '/[-]+/') , ' ', $string);
    return strtolower(trim($string, ' '));
}

}