<?php
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');

require __DIR__ . '/../index.php';

$host =  $_ENV['HOST'];
$dbName =  $_ENV['DB_NAME'];
$userName =  $_ENV['DB_USER'];
$password =  $_ENV['DB_PASSWORD'];


$ArrValues = $_GET['ArrValues'];
$ArrValues2 = explode(",",$ArrValues);


try {
    $pdo =new PDO("mysql:host=$host;dbname=$dbName;", $userName, $password);
} catch (PDOException $exception) {
    echo("Error: {$exception -> getMessage()}");
}
//*
// $ArrValuesLength = count($ArrValues2);
// $ARR=[];
// for($i=0;$i<$ArrValuesLength;++$i){
//     $ARR[]='value_id='.$ArrValues2[$i];
// }
// $ARR2 = implode(' or ',$ARR);

// $getFiltredValueProdsSQL = "SELECT products.*, value_id FROM `products`LEFT JOIN product_char_values ON products.id = product_char_values.product_id where $ARR2";
// // $stmt1 = $pdo -> prepare($ARR2);
// $stmt1 = $pdo -> query($getFiltredValueProdsSQL);
// $prods = $stmt1 -> fetchAll(PDO::FETCH_ASSOC);
// echo json_encode($prods);

//*

// if(count($ArrValues2>0)){
//     $in  = str_repeat('?,', count($ArrValues2) - 1) . '?';

//     $getFiltredValueProdsSQL = "SELECT products.*, value_id FROM `products`LEFT JOIN product_char_values ON products.id = product_char_values.product_id WHERE value_id IN ($in);";
//     $stmt = $pdo ->prepare($getFiltredValueProdsSQL);
//     $stmt ->execute($ArrValues2);
//     $products =$stmt ->fetchAll(PDO::FETCH_ASSOC);
//     echo json_encode($products);    
// } else {

// }
//*
$in  = str_repeat('?,', count($ArrValues2) - 1) . '?';

$limit = 10;
$offset = 1;

// $getFiltredValueProdsSQL = "SELECT products.*, value_id FROM `products`LEFT JOIN product_char_values ON products.id = product_char_values.product_id WHERE value_id IN ($in) LIMIT  $limit_p OFFSET $offset_p;";
$getFiltredValueProdsSQL = "SELECT products.*, value_id FROM `products`LEFT JOIN product_char_values ON products.id = product_char_values.product_id WHERE value_id IN ($in) LIMIT  ? OFFSET ?;";
// $getFiltredValueProdsSQL = "SELECT products.*, value_id FROM `products`LEFT JOIN product_char_values ON products.id = product_char_values.product_id WHERE value_id IN ($in) ";
$stmt = $pdo ->prepare($getFiltredValueProdsSQL);

for($it=0;$it<count($ArrValues2);++$it){
    $ii = $ArrValues2[$it];
    $stmt ->bindValue($it+1,$ii, PDO::PARAM_INT);

}
$stmt ->bindValue(count($ArrValues2)+1,$limit, PDO::PARAM_INT);
$stmt ->bindValue(count($ArrValues2)+2,$offset, PDO::PARAM_INT);
$stmt ->execute();
$products =$stmt ->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($products);
