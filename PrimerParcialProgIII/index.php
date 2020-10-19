<?php
    require_once "./Archivos/user.php";
    require_once "./Archivos/vehicle.php";
    require_once "./Archivos/service.php";
    require_once "./Archivos/turn.php";
    require __DIR__ . '/vendor/autoload.php';
    use \Firebase\JWT\JWT;
    
    /*$clave = password_hash('MultitaskLu',PASSWORD_DEFAULT);
    echo $clave;
    var_dump(password_verify('MultitaskLu',$clave));*/
    $key = "primerparcial";
    try {
        $token = $_SERVER['HTTP_TOKEN'];
        $decodificado= JWT::decode($token,$key,array('HS256'));
    } catch (\Throwable $th) {
        echo "TOKEN INVALIDO</br>";

    }

    $method = $_SERVER['REQUEST_METHOD'];
    $path_info = $_SERVER['PATH_INFO'] ?? 0;

    switch($method){
        case 'POST':
            $email = $_POST['email'] ?? '';
            $clave = $_POST['clave'] ?? '';
            $tipo = $_POST['tipo'] ?? '';
            $marca = $_POST['marca'] ?? '';
            $modelo = $_POST['modelo'] ?? '';
            $patente = $_POST['patente'] ?? '';
            $precio = $_POST['precio'] ?? '';
            $nombre = $_POST['nombre'] ?? '';
            $demora = $_POST['demora'] ?? '';
            switch($path_info){
                case '/registro':
                    User::registerUser(1,"users.json",$email,$clave,$tipo);
                break;
                case '/login':
                    $list = User::readUserJsonSerialize(1,'users.json');
                    $existUser = User::existUser($list,$email,$clave);
                    $type = User::isTypeOf($list,$email);
                    if($existUser){
                        $time = time();
                        $payload = array(
                            "iat" => $time,
                            "email" => $email,
                            "tipo" => $type
                        );
                        $jwt = JWT::encode($payload,$key);
                        echo $jwt;
                    }else{
                        echo "No existe usuario";
                    }
                break;
                case '/vehiculo':
                    if($decodificado->tipo == 'admin' || $decodificado->tipo == 'user'){
                        Vehicle::registerVehicle(1,"vehiculos.json",$marca,$modelo,$patente,$precio);
                    }else{
                        echo "No tiene permiso";
                    }
                break;
                case '/servicio':
                    if($decodificado->tipo == 'admin' || $decodificado->tipo == 'user'){
                        Service::registerServicio(1,"tiposServicio.json",$nombre,$tipo,$precio,$demora);
                    }else{
                        echo "No tiene permiso";
                    }
                break;
                case '/turno':
                    if($decodificado->tipo == 'admin' || $decodificado->tipo == 'user'){
                        $fecha = $_POST['fecha'];
                        Turn::registerTurn(1,"turnos.json","vehiculos.json","tiposServicio.json",$patente,$fecha);
                    }else{
                        echo "no tiene permiso";
                    }
                break;
            }
        break;
        case 'GET':
            switch($path_info){
                case '/patente':
                    $patente = $_GET['patente'];
                    if($decodificado->tipo == 'admin' || $decodificado->tipo == 'user'){
                        $list = Vehicle::readVehicleJsonSerialize(1,"vehiculos.json");
                        $existVehicle = Vehicle::findVehicle($list,$patente);
                        if($existVehicle != null){
                            echo $existVehicle->__toString()."<br/>";
                        }
                    }else{
                        echo "No tiene permiso";
                    }
                break;
            }

        break;
    }

?>