<?php
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');

require __DIR__ . '/../index.php';

$host =  $_ENV['HOST'];
$dbName =  $_ENV['DB_NAME'];
$userName =  $_ENV['DB_USER'];
$password =  $_ENV['DB_PASSWORD'];

$sort = $_GET['sort'];
$page = $_GET['page'];
$limit = $_GET['limit'];

$offset = $page*$limit - $limit;

try {
    $pdo =new PDO("mysql:host=$host;dbname=$dbName;", $userName, $password);
} catch (PDOException $exception) {
    echo("Error: {$exception -> getMessage()}");
}

$result = [
    'count'=>'',
    'products'=>'',
];

$countOfProductsSQL = 'SELECT COUNT(*) count FROM `products`';
$stmt4 = $pdo ->query($countOfProductsSQL);
$COUNT=$stmt4->fetch();
$result['count']=$COUNT['count'];

if ($sort === "none"){
    $getProductsSQL = "SELECT `products`.*,`categories`.`category_name`,`brands`.`brand_name` FROM `products` LEFT JOIN `categories` ON `products`.`category` = categories.id LEFT JOIN `brands` ON `products`.`manufact` = brands.id LIMIT :limit_p OFFSET :offset_p";
    $stmt = $pdo ->prepare($getProductsSQL);
    $stmt ->bindValue('limit_p',$limit, PDO::PARAM_INT);
    $stmt ->bindValue('offset_p',$offset, PDO::PARAM_INT);
    $stmt ->execute();
    $products =$stmt ->fetchAll(PDO::FETCH_ASSOC);
    $result['products'] = $products;
    echo  json_encode ($result);
} else if($sort==="ASC"){
    $getProductsSQL = "SELECT `products`.*,`categories`.`category_name`,`brands`.`brand_name` FROM `products` LEFT JOIN `categories` ON `products`.`category` = categories.id LEFT JOIN `brands` ON `products`.`manufact` = brands.id ORDER BY `products`.`price` ASC LIMIT :limit_p OFFSET :offset_p";
    $stmt = $pdo ->prepare($getProductsSQL);
    $stmt ->bindValue('limit_p',$limit, PDO::PARAM_INT);
    $stmt ->bindValue('offset_p',$offset, PDO::PARAM_INT);
    $stmt ->execute();
    $products =$stmt ->fetchAll(PDO::FETCH_ASSOC);
    $result['products'] = $products;
    echo  json_encode ($result);
} else if($sort==="DESC"){
    // $getProductsSQL = "SELECT `products`.*,`categories`.`category_name`,`brands`.`brand_name` FROM `products` LEFT JOIN `categories` ON `products`.`category` = categories.id LEFT JOIN `brands` ON `products`.`manufact` = brands.id ORDER BY `products`.`price` DESC LIMIT $limit OFFSET $offset";
    $getProductsSQL = "SELECT `products`.*,`categories`.`category_name`,`brands`.`brand_name` FROM `products` LEFT JOIN `categories` ON `products`.`category` = categories.id LEFT JOIN `brands` ON `products`.`manufact` = brands.id ORDER BY `products`.`price` DESC LIMIT :limit_p OFFSET :offset_p";
    $stmt = $pdo ->prepare($getProductsSQL);
    $stmt ->bindValue('limit_p',$limit, PDO::PARAM_INT);
    $stmt ->bindValue('offset_p',$offset, PDO::PARAM_INT);
    $stmt ->execute();
    $products =$stmt ->fetchAll(PDO::FETCH_ASSOC);
    $result['products'] = $products;
    echo  json_encode ($result);
    // $stmt = $pdo ->query($getProductsSQL);
    // $i = -1;
    // while($row = $stmt->fetch())
    // {
    //     $i++;
    //     $arr[$i] = $row;
    // }
    // $result['products'] = $arr;
    // echo  json_encode ($result);
} 
?>