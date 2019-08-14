<?php
namespace App\helpers;

use App\crawler\simple_html_dom;
use App\crawler\simple_html_dom_helpers;

class GetNews extends simple_html_dom implements CrawlerInterface{

    private $html;
    private $htmlElementData;
    private $url;
    private $hyperLinks;
    private $filePath;
    private $mediaLinks;
    public  function __construct($url)
        {
            $this->html = new simple_html_dom($url);
            $this->html2 = new simple_html_dom_helpers();
            $this->url = $url;
            $this->htmlElementData = [];
            $this->filePath = ($_SERVER['DOCUMENT_ROOT'] == "") ? $_SERVER['DOCUMENT_ROOT'].'/html': __DIR__.'\html';
            $this->hyperLinks = [];
            $this->mediaLinks = [];
        }

   public function fetch(){
       $header=array(
            'User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.2.12) Gecko/20101026 Firefox/3.6.12',
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            'Accept-Language: en-us,en;q=0.5',
            'Accept-Encoding: gzip,deflate',
            'Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7',
            'Keep-Alive: 115',
           'Connection: keep-alive',
           );
       $agent= 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.0.3705; .NET CLR 1.1.4322)';


            $curl = curl_init();
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($curl, CURLOPT_HEADER, false);
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($curl, CURLOPT_URL, $this->url);
            curl_setopt($curl, CURLOPT_REFERER, $this->url);
            // curl_setopt($curl,CURLOPT_HTTPHEADER,$header);
            curl_setopt($curl, CURLOPT_USERAGENT, $agent);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
            $str = curl_exec($curl);
            curl_close($curl);
            return $str;

        }

//    public function crawl($tag, $tagToGet){
//        $str = $this->fetch();
//
//        $html_base = $this->html->load($str);
//        switch($tagToGet){
//            case "img":
//                foreach ($html_base->find($tag) as $element):
//                    $this->data[] = $element->src;
//                endforeach;
//                return $this->data;
//            break;
//            case "data-img":
//                foreach ($html_base->find($tag) as $element):
//                    $this->data[] = $element->{'data-src'};
//                endforeach;
//                return $this->data;
//                break;
//            case "special":
//                foreach ($html_base->find($tag) as $element):
//                    $this->data[] = $element->{$special};
//                endforeach;
//                return $this->data;
//                break;
//            case "text":
//                foreach ($html_base->find($tag) as $element):
//                    $this->data[] = $element->plaintext;
//                endforeach;
//                return $this->data;
//            break;
//            case "links":
//                foreach ($html_base->find($tag) as $element):
//                    $this->data[] = $element->href;
//                endforeach;
//                return $this->data;
//                break;
//            default:
//                foreach ($html_base->find($tag) as $element):
//                    $this->data[] = $element;
//                endforeach;
//                return $this->data;
//
//        }
//
//
//    }


    public function crawl($tag, $attr){
        $data = [];
        $str = $this->readFromFile();
        $html_base = $this->html->load($str);
        foreach ($html_base->find($tag) as $element):
            $data[] = $element->{$attr};
        endforeach;
        return $data;
    }

    public function writeToFile($data){
        file_put_contents(__DIR__.'\html'.'\test.html', $data);
    }

    public function readFromFile(){
       return file_get_contents(__DIR__.'\html'.'\test.html');
    }


    public function fetchData(){
        $str = $this->fetch();
        $write = $this->writeToFile($str);
        return $write;
    }



    public function crawlMediaData($tag, $attr){
        $data = [];
        $str = $this->fetch();
        $html_base = $this->html->load($str);

        foreach ($html_base->find($tag) as $element):
            $data[] = $element->{$attr};
        endforeach;
        return  $this->getMultimediaData($tag, $data);
    }



    /**
     * Retrieves data and processes data
     * @param $data
     */
    public function getData($data){
        $htmlElementData = [];
        for ($i = 0; $i < count($data); $i++):
           $htmlElementData[] = $data[$i];
           //$this->hyperLinks[] =  $href = $this->html2->str_get_html($this->data[$i]);
        endfor;
        return  $htmlElementData;

    }

    /**
     * Get multimedia Links
     *
     */
    public function getMultimediaData($tag, $data){
        foreach ($this->getData($data) as $link){
            $multimedia = new simple_html_dom($link);
            foreach($multimedia->find($tag) as $mediaLink){
                $this->mediaLinks[] =$mediaLink->href;
            }
        }

        return $this->mediaLinks;
    }


    /**
     * Gets download links from webpage data
     * @param $hyperlinks
     * @return string
     */
    public function getDownloadLinks($hyperlinks){
        try{
            preg_match_all('/<a[^>]+href=([\'"])(?<href>.+?)\1[^>]*>/i', $hyperlinks, $result);
            if (!empty($result)) {
                # Found a link.
                $result =  $result['href'][0];
            }
        }catch(\Exception $e){
            return $e->getMessage();
        }
        return $result;
    }

}