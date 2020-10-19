<?php
require_once "./Archivos/File.php";

class User extends FileManager{
    public $email;
    public $password;
    public $type;

    public function __construct($email,$password,$type){
        $this->email = $email;
        $this->password  = $password;
        $this->type = $type;
    }

    public function __get($name){
        return $this->$name;
    }

    public function __set($name,$value){
        $this->$name = $value;
    }

    public function __toString(){
        return $this->email.'*'.$this->password.'*'.$this->type;
    }

    public static function readUserJsonSerialize($option,$file){
        $list = parent::readJsonSerialize($option,$file);
        $auxList = array();
        foreach($list as $user){
            if(count((array)$user) == 3){
                $newUser = new User($user->email,$user->password,$user->type);
                array_push($auxList,$newUser);
            }
        }
        return $auxList;
    }

    public static function existUser($list,$email,$clave){
        if($list == null && $email == null && $clave == null){
            return false;
        }
        foreach($list as $user){
            if($user->email == $email && $user->password == $clave){
                return true;
            }
        }
    }

    public static function is_valid_email($str){
        if(filter_var($str,FILTER_VALIDATE_EMAIL)){
            return true;
        }
        return false;
    }

    public static function registerUser($option,$file,$email,$password,$type){
        if($email != null && $password  !=null){
            $list = User::readUserJsonSerialize($option,$file);
            if(User::is_valid_email($email)){
                if(User::existUser($list,$email)){
                    echo "ya existe el usuario";
                    return false;
                }else if($type == 'user' || $type == 'admin'){
                    $newUser = new User($email,$password,$type);
                    array_push($list,$newUser);
                    parent::saveJsonSerialize($option,$file,$list);
                    echo "Se registro el usuario";
                }else{
                    echo "Tipo de usuario invalido";
                }
            }else{
                echo "Mail invalido";
            }
        }else{
            echo "No se puede obtener datos nulos";
        }
        return true;
    }

    public static function isTypeOf($list,$email){
        $type = "";
        if($list == null && $email == null){
            return false;
        }
        foreach($list as $user){
            if($user->email == $email){
                $type = $user->type;
                break;
            }
        }
        return $type;
    }
}
?>
