<?php
header("Access-Control-Allow-Origin: *");

require __DIR__ . "/../JWTHandler.php";
require __DIR__ . '/../vendor/autoload.php';

$jwt = new JwtHandler();
Dotenv\Dotenv::createUnsafeImmutable(__DIR__ . '/../')->load();


$host =  $_ENV['HOST'];
$dbName =  $_ENV['DB_NAME'];
$userName =  $_ENV['DB_USER'];
$DBpassword =  $_ENV['DB_PASSWORD'];

$postData = file_get_contents('php://input');
$data = json_decode($postData, true);
// var_dump ($postData);


foreach ($data as $key => $value){
    
    ${$key}=$value;
}

// echo $nikName;
// echo $lastName;
// echo $firstName;
// echo $patronymic;
// echo $phone;
// echo $eMail;

$password = password_hash($password, PASSWORD_DEFAULT);
// echo $password;
// *mysqli
// $mysql = new mysqli("localHost", "root", "", "Oil_example");
// $mysql->query("SET NAMES 'utf8'");
// * PDO
try {
    $pdo =new PDO("mysql:host=$host;dbname=$dbName;", $userName, $DBpassword);
} catch (PDOException $exception) {
    echo("Error: {$exception -> getMessage()}");
}
// *mysqli
// $addUser = "INSERT INTO `users` (`id`, `nikName`, `lastName`,`firstName`, `patronymic`, `phone`, `eMail`, `password`) VALUES (NULL, '$nikName', '$lastName', '$firstName', '$patronymic', '$phone','$eMail','$password')";
// $mysql->query($addUser);

// *проверка наличия пользователя с таким же именем
$CheckUserSQL = "SELECT `nikName` FROM `users` WHERE nikName=?";
$stmt0 = $pdo -> prepare($CheckUserSQL);
$stmt0 -> execute(array($nikName));
$ChUser = $stmt0 -> fetch(PDO::FETCH_ASSOC);

if (is_array($ChUser)){
$CheckUser = $ChUser['nikName'];
// echo json_encode($CheckUser);
} else {
    $CheckUser = false;
}

$result = [
    'status' => '',
    'result' => '',
    'msg' => ''
];

if($CheckUser){
    $result['status']='error';
    $result['msg']='Пользователь с таким именем зарегестрирован.';
    echo json_encode($result);
    // echo json_encode('Пользователь с таким именем зарегестрирован.');
} else {
// //* PDO
$addUser = "INSERT INTO `users` (`nikName`, `lastName`,`firstName`, `patronymic`, `phone`, `eMail`, `password`) VALUES (:nikName, :lastName, :firstName, :patronymic, :phone, :eMail, :password)";



$stmt = $pdo -> prepare($addUser);

$stmt ->execute([
    ':nikName'=> $nikName,
    ':lastName'=> $lastName,
    ':firstName'=> $firstName,
    ':patronymic'=> $patronymic,
    ':phone'=> $phone,
    ':eMail'=> $eMail,
    ':password'=> $password,
    ]);

//*1 Получаем id из таблицы с пользователями mysqli
// $UserId = "SELECT `id` FROM `users` WHERE nikName='$nikName'";
// $resUserId= $mysql->query($UserId);
// $row = mysqli_fetch_array($resUserId, MYSQLI_ASSOC);
// $Uid=$row['id'];

//* PDO Получаем id из таблицы с пользователями
// $UserId = "SELECT `id` FROM `users` WHERE nikName=:nikName";
// $stmt2 = $pdo -> prepare($UserId);
// $stmt2 -> execute([
//         ':nikName'=> $nikName
//         ]);
// *получить ID юзера по nikName PDO:: (работает)
$UserId_SQL = "SELECT `id` FROM `users` WHERE nikName=?";
$stmt2 = $pdo -> prepare($UserId_SQL);
$stmt2 -> execute(array($nikName));
$userId = $stmt2 -> fetch(PDO::FETCH_ASSOC);
$Uid = $userId['id'];


// //*2 создаем корзину пользователя PDO:: (работает)
$addUserBasket_SQL = "INSERT INTO `basket` (`id`, `user_id`)VALUES (NULL, ?)";
$stmt3 = $pdo -> prepare($addUserBasket_SQL);
$stmt3 -> execute(array($Uid));

//*2 создаем корзину пользователя mysqli
// $addUserBasket = "INSERT INTO `basket` (`id`, `user_id`)VALUES (NULL, '$Uid')";
// $mysql->query($addUserBasket);
//* создаем список заказов пользователя(order_list)
// здесь код для списка закзов
// 


//Payload can be anything you want to store in the token
$payload = array($nikName,$lastName,$firstName,$patronymic);

$token = $jwt->encode("http://oilmarket1/php-jwt/", $payload);

$result['status']='ok';
$result['msg']='Пользователь успешно зарегестрирован. Авторизуйтесь.';
echo json_encode($result);
// echo json_encode('пользователь зарегестрирован');
// echo "$token";

// $result->free();
}
?>

