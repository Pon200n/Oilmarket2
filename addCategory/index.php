<?php 
header("Access-Control-Allow-Origin: *");
// require __DIR__ . '/../vendor/autoload.php';
// Dotenv\Dotenv::createUnsafeImmutable(__DIR__ . '/../')->load();

require __DIR__ . '/../index.php';


$host =  $_ENV['HOST'];
$dbName =  $_ENV['DB_NAME'];
$userName =  $_ENV['DB_USER'];
$password =  $_ENV['DB_PASSWORD'];



$category = $_GET["category"];

try {
    $pdo =new PDO("mysql:host=$host;dbname=$dbName;", $userName, $password);
} catch (PDOException $exception) {
    echo("Error: {$exception -> getMessage()}");
}

$findCategorySQL = "SELECT `category_name` FROM `categories` WHERE category_name = ?";
$stmt1 = $pdo -> prepare($findCategorySQL);
$stmt1 -> execute([$category]);
$findCat = $stmt1 -> fetch(PDO::FETCH_ASSOC);
// echo json_encode($findCat['category_name']);

$result = [
    'status' => '',
    'array' => '',
    'msg' => ''
];

if(is_array($findCat)){
    $cat = $findCat['category_name'];
    $result['status']='error';
    $result['msg']='Категория  '.$cat.'  уже существует.';
    echo json_encode($result);    

    // echo json_encode('Категория '.$cat.'уже существует.');
} else {
    // $findCat = false;
    $addCategorySQL = "INSERT INTO `categories` VALUES (NULL, ?)";
    $stmt2 = $pdo -> prepare($addCategorySQL);
    $stmt2 -> execute([$category]);

    $getCategorySQL = "SELECT * FROM `categories`";
    $stmt3 = $pdo->query($getCategorySQL);

    $i = -1;
    while($row = $stmt3->fetch())
    {
        $i++;
        $arr[$i] = $row;
    }

    $result['status']='ok';
    $result['array']=$arr;
    $result['msg']='Категория '.$category.' добавлена';

    echo json_encode($result);    
    // echo json_encode('Категория '.$category.' добавлена');    
}

// if(is_array(!$cat)){
//     $addCategorySQL = "INSERT INTO `categories` VALUES (NULL, ?)";
//     $stmt2 = $pdo -> prepare($addCategorySQL);
//     $stmt2 -> execute([$category]);
//     echo json_encode($category);
// } else {
//     echo json_encode("такая категория уже существует");

// }

// $addCategorySQL = "INSERT INTO `categories` VALUES (NULL, ?)";
// $stmt2 = $pdo -> prepare($addCategorySQL);
// $stmt2 -> execute([$category]);
// echo json_encode($category);