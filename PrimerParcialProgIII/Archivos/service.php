<?php
require_once "./Archivos/File.php";
class Service extends FileManager{
    public $name;
    public $idService;
    public $type;
    public $price;
    public $delay;

    public function __construct($id,$name,$type,$price,$delay){
        $this->idService = $id;
        $this->name = $name;
        $this->type = $type;
        $this->price = $price;
        $this->delay = $delay;
    }

    public function __get($name){
        return $this->$name;
    }

    public function __set($name,$value){
        $this->$name = $value;
    }

    public static function readServiceJsonSerialize($option,$file){
        $list = parent::readJsonSerialize($option,$file);
        $auxList = array();
        foreach($list as $service){
            if(count((array)$service) == 5){
                $newService = new Service($service->idService,$service->name,$service->type,$service->price,$service->delay);
                array_push($auxList,$newService);
            }
        }
        return $auxList;
    }

    public static function registerServicio($option,$file,$name,$type,$price,$delay){
        if($name != null && $type !=null && $price !=null && $delay != null){
            $list = Service::readServiceJsonSerialize($option,$file);   
            $newService = new Service(0,$name,$type,$price,$delay);
            $newService->idService = Service::autoincrementId($list);
            array_push($list,$newService);
            parent::saveJsonSerialize($option,$file,$list);
            echo "Se registro un servicio";           
        }else{
            echo "No se puede obtener datos nulos";
        }
        return true;
    }

    public static function autoincrementId($list){
        $auxId=0;
        foreach($list as $aux){
            if($aux->idService > $auxId){
                $auxId = $aux->idService;
            }
        }
        return $auxId+1;
    }
}
?>
