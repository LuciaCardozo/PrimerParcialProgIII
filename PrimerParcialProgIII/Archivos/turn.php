<?php
    require_once "./Archivos/File.php";
    require_once "./Archivos/service.php";
    require_once "./Archivos/vehicle.php";
    class Turn extends FileManager{
        public $date;

        public function __construct($date,$patentPlate,$brand,$model,$price,$typeOfService){
            $this->date = $date;
            $this->patentPlate = $patentPlate;
            $this->brand = $brand;
            $this->model = $model;
            $this->price = $price;
            $this->typeOfService = $typeOfService;
        }

        public function __get($name){
            return $this->$name;
        }

        public function __set($name,$value){
            $this->$name = $value;
        }

        public function readTurnJsonSerialize($option,$file){
            $list = parent::readJsonSerialize($option,$file);
            
            $auxList = array();
            foreach($list as $turn){
            if(count((array)$turn) == 6){
                $newTurn = new Turn($turn->date,$turn->patentPlate,$turn->brand,$turn->model,$turn->price,$turn->typeOfService);
                array_push($auxList,$newTurn);
            }
        }
        return $auxList;
        }

        public function registerTurn($option,$file,$fileVehicle,$fileService,$patentPlate,$date){
            if($patentPlate == null && $date == null){
                echo "No se permite valores nulos";
                return false;
            }
            else{
                $listVehicle = Vehicle::readVehicleJsonSerialize($option,$fileVehicle);
                $listService = Service::readServiceJsonSerialize($option,$fileService);
                $list = Turn::readTurnJsonSerialize($option,$file);
                $existTurn = Turn::existTurn($list,$patentPlate);
                $vehicle = Vehicle::findVehicle($listVehicle,$patentPlate);
                if($existTurn == false){
                    if($vehicle != false ){
                        foreach($listService as $service){
                            $newTurn = new Turn($date,$patentPlate,$vehicle->brand,$vehicle->model,$vehicle->price,$service->type);
                            array_push($list,$newTurn);
                           
                            parent::saveJsonSerialize($option,$file,$list);
                                echo "Se registro un turno";
                                return true;
                            }
                        }else{
                            echo "No existe patente";
                            return false;
                        }
                    }else{
                        echo "ya existe vehiculo";
                       
                        return false;
                    }  
                
            }
        }

        public function existTurn($list,$patentPlate){
            foreach($list as $turn){
                if($turn->patentPlate == $patentPlate){
                    return true;
                }
            }
            return false;
        }
    }
?>