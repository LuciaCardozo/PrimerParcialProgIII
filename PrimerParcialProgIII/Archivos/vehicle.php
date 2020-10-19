<?php
require_once "./Archivos/File.php";

class Vehicle extends FileManager{
    public $brand;
    public $model;
    public $patentPlate;
    public $price;

    public function __construct($brand,$model,$patentPlate,$price){
        $this->brand = $brand;
        $this->model = $model;
        $this->patentPlate = $patentPlate;
        $this->price = $price;
    }

    public function __get($name){
        return $this->$name;
    }

    public function __set($name,$value){
        $this->$name = $value;
    }

    public function __toString(){
        return $this->brand.' '.$this->model.' '.$this->patentPlate.' '.$this->price;
    }

    public static function readVehicleJsonSerialize($option,$file){
        $list = parent::readJsonSerialize($option,$file);
        $auxList = array();
        foreach($list as $vehicle){
            if(count((array)$vehicle) == 4){
                $newVehicle = new Vehicle($vehicle->brand,$vehicle->model,$vehicle->patentPlate,$vehicle->price);
                array_push($auxList,$newVehicle);
            }
        }
        return $auxList;
    }

    public static function registerVehicle($option,$file,$brand,$model,$patentPlate,$price){
        if($brand != null && $model !=null && $patentPlate !=null && $price != null){
            $list = Vehicle::readVehicleJsonSerialize($option,$file);
                if(Vehicle::existPatentPlate($list,$patentPlate)){
                    echo "ya existe la patente";
                    return false;
                }
                    $newVehicle = new Vehicle($brand,$model,$patentPlate,$price);
                    array_push($list,$newVehicle);
                    parent::saveJsonSerialize($option,$file,$list);
                    echo "Se registro un vehiculo";           
        }else{
            echo "No se puede obtener datos nulos";
        }
        return true;
    }

    public static function existPatentPlate($list,$patentPlate){
        if($list != null && $patentPlate != null){
            foreach($list as $vehicle){
                if($vehicle->patentPlate == $patentPlate){
                    return true;
                }
            }
        }
        return false;
    }

    public function findVehicle($list,$patente){
        $encotroVehiculo=null;
        $retorno=null;
        if($list != null && $patente != null){
            foreach($list as $vehicle){
                if($vehicle->patentPlate == $patente){
                    $encotroVehiculo=$vehicle;
                    $retorno = true;
                }
            }
            if($retorno){
                return $encotroVehiculo;
            }
        }
        return false;
    }
}
?>