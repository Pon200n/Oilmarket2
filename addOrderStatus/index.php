<?php
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');

require __DIR__ . '/../vendor/autoload.php';
Dotenv\Dotenv::createUnsafeImmutable(__DIR__ . '/../')->load();

$host =  $_ENV['HOST'];
$dbName =  $_ENV['DB_NAME'];
$userName =  $_ENV['DB_USER'];
$password =  $_ENV['DB_PASSWORD'];

$statusName = $_GET['status'];
// echo json_encode($statusName);

try {
    $pdo =new PDO("mysql:host=$host;dbname=$dbName;", $userName, $password);
} catch (PDOException $exception) {
    echo("Error: {$exception -> getMessage()}");
}

$findStatusNameSQL = "SELECT `order_status_description` FROM `order_status` WHERE order_status_description = ?";
$stmt1 = $pdo -> prepare($findStatusNameSQL);
$stmt1 -> execute([$statusName]);
$findStatus = $stmt1 -> fetch(PDO::FETCH_ASSOC);
// echo json_encode($findStatus['order_status_description']);

$result = [
    'status' => '',
    'array' => '',
    'msg' => ''
];
if(is_array($findStatus)){
    $stat = $findStatus['order_status_description'];
    $result['status']='error';
    $result['msg']='Статус  '.$stat.'  уже существует.';
    echo json_encode($result);    

    // echo json_encode('Категория '.$cat.'уже существует.');
} else {
    // $findCat = false;
    // $addOrderStatusSQl = "INSERT INTO `order_status` SET  `order_status_description`=?";
    $addStatusSQl = "INSERT INTO `order_status` SET  `order_status_description`=?";
    $stmt0 = $pdo ->prepare($addStatusSQl);
    $stmt0 -> execute(array($statusName));

    $getAllStat = "SELECT * FROM `order_status`";   
    $stmt3 = $pdo->query($getAllStat);

    $i = -1;
    while($row = $stmt3->fetch())
    {
        $i++;
        $arr[$i] = $row;
    }

    $result['status']='ok';
    $result['array']=$arr;
    $result['msg']='Статус '.$statusName.' добавлен.';

    echo json_encode($result);    
    // echo json_encode('Категория '.$category.' добавлена');    
}
// $addStatusSQl = "INSERT INTO `order_status` SET  `order_status_description`=?";
// $stmt0 = $pdo ->prepare($addStatusSQl);
// $stmt0 -> execute(array($statusName));
// $addCategorySQL = "INSERT INTO `brands` VALUES (NULL, ?)";
// $stmt2 = $pdo -> prepare($addCategorySQL);
// $stmt2 -> execute([$statusName]);
// $getAllStat = "SELECT * FROM `order_status`";