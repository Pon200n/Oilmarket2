<?php
header("Access-Control-Allow-Origin: *");
require __DIR__ . '/../vendor/autoload.php';
Dotenv\Dotenv::createUnsafeImmutable(__DIR__ . '/../')->load();

$host =  $_ENV['HOST'];
$dbName =  $_ENV['DB_NAME'];
$userName =  $_ENV['DB_USER'];
$password =  $_ENV['DB_PASSWORD'];

$brand = $_GET["brand"];

try {
    $pdo =new PDO("mysql:host=$host;dbname=$dbName;", $userName, $password);
} catch (PDOException $exception) {
    echo("Error: {$exception -> getMessage()}");
}

$findBrandSQL = "SELECT `brand_name` FROM `brands` WHERE brand_name = ?";
$stmt1 = $pdo -> prepare($findBrandSQL);
$stmt1 -> execute([$brand]);
$findBr = $stmt1 -> fetch(PDO::FETCH_ASSOC);
// echo json_encode($findCat['category_name']);

$result = [
    'status' => '',
    'array' => '',
    'msg' => ''
];

// echo json_encode($brand);
if(is_array($findBr)){
    $br = $findBr['brand_name'];
    $result['status']='error';
    $result['msg']='Бренд  '.$br.'  уже существует.';
    echo json_encode($result);    

    // echo json_encode('Категория '.$cat.'уже существует.');
} else {
    // $findCat = false;
    $addCategorySQL = "INSERT INTO `brands` VALUES (NULL, ?)";
    $stmt2 = $pdo -> prepare($addCategorySQL);
    $stmt2 -> execute([$brand]);

    $getBrandSQL = "SELECT * FROM `brands`";
    $stmt3 = $pdo->query($getBrandSQL);

    $i = -1;
    while($row = $stmt3->fetch())
    {
        $i++;
        $arr[$i] = $row;
    }

    $result['status']='ok';
    $result['array']=$arr;
    $result['msg']='Бренд '.$brand.' добавлен.';

    echo json_encode($result);    
    // echo json_encode('Категория '.$category.' добавлена');    
}