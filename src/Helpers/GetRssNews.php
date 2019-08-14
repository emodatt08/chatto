<?php
namespace App\helpers;

use DOMDocument;
use DOMXPath;
use App\crawler\simple_html_dom;
use App\crawler\simple_html_dom_helpers;

class GetRssNews extends simple_html_dom{

    private $rss;
    private $html;
    private $url;
    private $filePath;
    public  function __construct()
        {
           
            $this->filePath = ($_SERVER['DOCUMENT_ROOT'] == "") ? $_SERVER['DOCUMENT_ROOT'].'/html': __DIR__.'\html';

        }

   public function fetch($url){
       $agent= 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.0.3705; .NET CLR 1.1.4322)';

            $curl = curl_init();
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($curl, CURLOPT_HEADER, false);
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_REFERER, $url);
            curl_setopt($curl, CURLOPT_USERAGENT, $agent);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
            $str = curl_exec($curl);
            curl_close($curl);
            return $str;

        }



    public function writeToFile($data){
        file_put_contents(__DIR__.'\html'.'\test.xml', $data);
    }

    public function readFromFile(){
       return file_get_contents(__DIR__.'\html'.'\test.xml');
    }


    public function fetchData($url){
        $str = $this->fetch($url);
        $write = $this->writeToFile($str);
        return $write;
    }
 


    /**
     * Retrieves rss data and processes data
     * @param $data
     */

    public function getRssFeeds($toGet, $type){
        // $file = $this->readFromFile();
        $this->rss = simplexml_load_file(__DIR__.'\html'.'\test.xml', null, LIBXML_NOCDATA);
        $namespaces = $this->rss->getNamespaces(true);
        $data = [];
        switch($type){
            case "text":
                 foreach($this->rss->channel->item as $entry ){
                     $data[] = $entry->{$toGet};
                }
                break;
            case "cnnImage":
                  foreach($this->rss->channel->item as $entry ){
                    $media_content = $entry[0]->children($namespaces['media']);
                    $data[] = $media_content->group->content->attributes()->url;
                }
                break;
             case "bbcImage":
                  foreach($this->rss->channel->item as $entry ){
                    $media_content = $entry[0]->children($namespaces['media']);
                    $data[] = $media_content->thumbnail->attributes()->url;
                  // $data[] = $entry->{$toGet}->attributes()->thumbnail;
                } 
                break;  
                 case "ghanaWebImage":
                  foreach($this->rss->channel->item as $entry ){
                       $data[] = $entry->{$toGet}->attributes()->url;

                    // $media_content = $entry[0]->children($namespaces['media']);
                    // $data[] = $media_content->thumbnail->attributes()->url;
                } 
                break;  
                
                 case "cnbcImage":
                  foreach($this->rss->channel->item as $entry ){
                      $imgUrl = $entry->{$toGet}[0];
                     // print_r($this->crawl((string) $imgUrl, " img", "src")[4]); die();
                       $data[] = $this->crawl((string) $imgUrl, " img", "src")[3];
                } 
                break;   

               

            
                   case "buzzFeedImage":
                  foreach($this->rss->channel->item as $entry ){
                      $linkData = $entry->{$toGet};
                       //print_r($this->getImageLinks($linkData));die();
                      $data[] = $this->getImageLinks($linkData);
                } 
                break;  
                 case "huffPostImage":
                  foreach($this->rss->channel->item as $entry ){
                      //$media_content = $entry[0]->children($namespaces['enclosure']);
                    $data[] = $entry->enclosure->attributes()->url;
                } 
                break;  
                
                 case "washingtonPostImage":
                  foreach($this->rss->channel->item as $entry ){
                      $media_content = $entry[0]->children($namespaces['media']);
                    $data[] = $media_content->thumbnail->attributes()->url;
                } 
                break; 

                 case "theGuardianRssImage":
                  foreach($this->rss->channel->item as $entry ){
                      $media_content = $entry[0]->children($namespaces['media']);
                    $data[] = $media_content->attributes()->url;
                } 
                break; 

                 case "theWallStreetRssImage":
                  foreach($this->rss->channel->item as $entry ){
                      $media_content = $entry[0]->children($namespaces['media']);
                    $data[] = $media_content->attributes()->url;
                } 
                break; 

                 case "theVergeImage":
                  foreach($this->rss->channel->item as $entry ){
                      //$media_content = $entry[0]->children($namespaces['enclosure']);
                       $data[] = $this->getImageLinks($entry->content);
                } 
                break;  

                case "theVergeLink":
                  foreach($this->rss->channel->item as $entry ){
                      //$media_content = $entry[0]->children($namespaces['enclosure']);
                       $data[] =$entry->link->attributes()->href;
                } 
                break;  
        }
            return $data;
    }

    public function crawl($url, $tag, $attr){
        $data = [];
        //var_dump($url, $tag, $attr); die;
        $str = $this->fetch($url);
        $this->html = new simple_html_dom();
        $html_base = $this->html->load($str);
        foreach ($html_base->find($tag) as $element):
            if(is_null($attr)){
                 $data[] = $element;
            }else{
                $data[] = $element->{$attr};
            }
            
        endforeach;
        return $data;
    }

    private function getAllLinks($url, $class){
        $photos = [];
        $str = $this->fetch($url);
        $dom = new DOMDocument();
        $internalErrors = libxml_use_internal_errors(true);
            //Parse the HTML. The @ is used to suppress any parsing errors
            //that will be thrown if the $html string isn't valid XHTML.
           // @$dom->loadHTML($str);
            @$dom->loadHTML(htmlspecialchars($str));
            // $xp = new DOMXPath($dom);
            $imgs = $dom->getElementsByTagName('img');
            foreach($imgs as $img) {
                if($img->attributes->getNamedItem('class') && $img->attributes->getNamedItem('class')->nodeValue = 'kWidgetCentered vilynx_mainImg') {
                    $photos[] = $img->attributes->getNamedItem('src')->nodeValue;
                }
            }
            // $nodeLists = $xp->query("//img[@class='{$class}']");
            // $photos[] = $nodeLists->nodeValue;
            //Get all links. You could also use any other tag name here,
            //like 'img' or 'table', to extract other tags.
           // $links = $dom->getElementsByTagName('img');

            //Iterate over the extracted links and display their URLs
            // foreach ($links as $link){   
            //     //Extract and show the "href" attribute.
            //      echo $link->nodeValue;
            //      $data[] = $link->getAttribute('src');
            // }
            return $photos;
    }

     public function getImageLinks($link){
        preg_match_all('/<img[^>]+>/i',$link, $imgTags); 

            for ($i = 0; $i < count($imgTags[0]); $i++) {
             // get the source string
            preg_match('/src="([^"]+)/i',$imgTags[0][$i], $imgage);

             // remove opening 'src=' tag, can`t get the regex right
            $origImageSrc[] = str_ireplace( 'src="', '',  $imgage[0]);
            }
            // will output all your img src's within the html string
            return $origImageSrc[0];

    }


}