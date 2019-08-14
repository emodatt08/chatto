<?php
namespace App;
use PDO;
class Database
{
    public $db;
    public $loggerman;
	public $config;

    public function __construct(){
    	$this->config = parse_ini_file("config.ini");
        try {
            $this->db = new PDO('mysql:host='.$this->config['host'].';port='.$this->config['port'].';dbname=' . $this->config['db_name'] . ';charset=utf8', $this->config['username'], $this->config['password']);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->db->setAttribute(PDO::ATTR_EMULATE_PREPARES, FALSE);
        }catch (\PDOException $e){
            echo $e->getMessage();
        }
    }



    /**
     * Function to run all mysql queries
     * @param string $query
     * @param string $type
     * @param array $val
     * @param string $route
     * @param array $output
     * @return mixed
     */
    public function runQuery($query, $type, $val,$route='norm',$output=array()) {
        switch($route){
            case 'stdProc':
                try {
                    $stmt = $this->db->prepare($query);
                    if(!empty($output)):
                        foreach($output as $id=>$value):
                            $outparams[] = $value;
                        endforeach;
                        $outparams = rtrim(implode($outparams),',');
                        $stmt->execute($val);
                        $stmt->query("select $outparams;");
                    endif;

                } catch (\PDOException $e) {
                	echo 'stdProc <=> '.$e->getMessage();
                }
                break;
            default:
                try {
             
                    $stmt = $this->db->prepare($query);
                    $stmt->execute($val);
                } catch (\PDOException $e) {
                	echo "default <=> ".var_dump($val)." <=> ". $e->getMessage();
                }
                break;
        }
        $returnVal = $this->getQueryOpt($stmt, $type);
        return $returnVal;
    }


    /**
     * Function to run all mysql query responses
     * @param string $resource
     * @param string $type
     * @return mixed
     */
    public function getQueryOpt($resource,$type) {
        
        switch ($type) {

            case "AFF" :
            case "NUM" :
                $result = $resource->rowCount();
                break;

            case "RAW" :
                $result = $resource;
                break;               
            case "COMB":
            	$result = array($resource->fetchAll(PDO::FETCH_ASSOC),$resource->rowCount());
            	break;    
            case "INSID":
                $result = $resource->lastInsertId();
                break;
            default :
                $result = $resource->fetchAll(PDO::FETCH_ASSOC);
                break;
        }
        return $result;
    }

}       