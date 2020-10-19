<?php

class FileManager{
    
    public static function save($file,$data){
        if($data != null && file_exists($file)){
            $fileOpen = fopen($file,'a+');
            fwrite($fileOpen, $data);
            fclose($fileOpen);
        }else{
            echo "Se produjo un error";
        }
    }

    public static function read($file){
        $list = array();
        if(file_exists($file)){
            $fileOpen = fopen($file, 'r');
            while(!feof($fileOpen)){
                $line = fgetc($fileOpen);
                $data = explode('*', $line);
                if(count($data) > 0){
                    array_push($list, $data);
                } 
            }
            fclose($fileOpen);
        }     
        return $list;
    }
    
    public static function readJson($file){
        $array = array();
        if(file_exists($file)){
            $fileOpen = fopen($file,'r');
            $size = filesize($file);
            if($size > 0){
                $fread = fread($fileOpen,$size);
            }
            else{
                $fread = "{}";
            }
            fclose($fileOpen);
            $array = json_decode($fread);
        }
        return $array;
    }
    public static function saveJson($file, $data){
        if($data != null){
            $fileOpen = fopen($file, 'w');
            $fwhite = fwrite($fileOpen, json_encode($data));
            fclose($fileOpen);
        }
    }
    public static function readSerialize($file){
        
        $array = array();
        if(file_exists($file)){
            $fileOpen = fopen($file, 'r');
            $size = filesize($file);
            if($size > 0){
                $fread = fread($fileOpen, $size);
            }else{
                $fread = "{}";
            }
            fclose($fileOpen);
            $array = unserialize($fread);
        }
        return $array;
    }
    public static function saveSerialize($file, $data){
        if($data != null){
            $fileOpen = fopen($file, 'w');
            $fwhite = fwrite($fileOpen, serialize($data));
            fclose($fileOpen);
        }
    }

    public static function readJsonSerialize($option,$file){
        $array = array();
        if(file_exists($file)){
            $fileOpen = fopen($file,'r');
            $size = filesize($file);
            if($size > 0){
                $fread = fread($fileOpen,$size);
            }
            else{
                $fread = "{}";
            }
            fclose($fileOpen);
            if($option == 1){
                $array = json_decode($fread);
            }else if($option == 2){
                $array = unserialize($fread);
            }
        }
        return $array;
    }

    public static function saveJsonSerialize($option,$file,$data){
        if($data != null){
            if($option == 1){
                $fileOpen = fopen($file, 'w');
                $fwhite = fwrite($fileOpen, json_encode($data));
                fclose($fileOpen);
            }
            else if($option == 2){
                $fileOpen = fopen($file, 'w');
                $fwhite = fwrite($fileOpen, serialize($data));
                fclose($fileOpen);
            }
        }

    }
}
?>
