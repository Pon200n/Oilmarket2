<?php
require __DIR__ . "/../JWTHandler.php";
$jwt = new JwtHandler();
header("Access-Control-Allow-Origin: *");
require __DIR__ . '/../vendor/autoload.php';

Dotenv\Dotenv::createUnsafeImmutable(__DIR__ . '/../')->load();

$host =  $_ENV['HOST'];
$dbName =  $_ENV['DB_NAME'];
$userName =  $_ENV['DB_USER'];
$DBpassword =  $_ENV['DB_PASSWORD'];

$postData = file_get_contents('php://input');
$data = json_decode($postData, true);
// var_dump ($postData);

$nikFromClient=($data['nikName']);
$passwordFromClient=($data['password']);
// echo ($nikFromClient);
// echo ($passwordFromClient);

// *mysqli подключение
// $mysql = new mysqli("localHost", "root", "", "Oil_example");
// $mysql->query("SET NAMES 'utf8'");

// *PDO подключение
try {
    $pdo =new PDO("mysql:host=$host;dbname=$dbName;", $userName, $DBpassword);
} catch (PDOException $exception) {
    echo("Error: {$exception -> getMessage()}");
}

//* получение данных пользователя mysqli
// $getUserByNikName = "SELECT * FROM `users` WHERE nikName='$nikFromClient'";
// $UserResult = $mysql->query($getUserByNikName);
// $row = mysqli_fetch_array($UserResult, MYSQLI_ASSOC);

//* получение данных пользователя PDO::
$getUserByNikName_SQL = "SELECT * FROM `users` WHERE nikName=?";
$stmt = $pdo -> prepare($getUserByNikName_SQL);
$stmt -> execute([$nikFromClient]);
$row = $stmt ->fetch(PDO:: FETCH_ASSOC);


if(!empty($row)){
$hash = $row['password'];
$id = $row['id'];
$role = $row['role'];
$nikName = $row['nikName'];
};
// if (!empty($nikName)){
//     $hash = $row['password'];
// };
// if (!empty($nikName)){
//     $id = $row['id'];
// };
// if (!empty($nikName)){
//     $role = $row['role'];
// };
// if (!empty($nikName)){
//     $nikName = $row['nikName'];
// };
// $bn = boolval($nikName);
// echo json_encode($bn);


if (!empty($nikName)){
    $result = [
        'status' => '',
        'result' => '',
        'msg' => ''
    ];
    // $error = array();

    if (password_verify($passwordFromClient, $hash)) {
        // echo json_encode('Password is valid!');
        
            //Payload can be anything you want to store in the token
    // $payload = array($id,$role,$nikName);
    $payload = array("id"=>$id,"role"=>$role,"nik"=>$nikName);
    
    $token = $jwt->encode("http://oilmarket1/php-jwt/", $payload);
    
    $result['status'] = 'ok';
    $result['result'] = $token;
    echo  json_encode ($result);
    // echo json_encode("$token");
    } else {
        // echo json_encode('Invalid password.');
        
        $result['status'] = 'invPass';
        $result['msg'] = 'Неверный пароль.';
        echo  json_encode ($result);

        // throw new Exception('Неверный пароль.');
        
        
    }
}
else{
    // echo json_encode('Пользователь с таким именем не найден');
    
    $result['status'] = 'invUsser';
    $result['msg'] = 'Пользователь не найден.';
    echo  json_encode ($result);

    // throw new Exception('Пользователь с таким именем не найден.');

    

};


