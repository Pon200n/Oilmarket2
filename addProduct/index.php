<?php
header("Access-Control-Allow-Origin: *");
require __DIR__ . '/../vendor/autoload.php';
Dotenv\Dotenv::createUnsafeImmutable(__DIR__ . '/../')->load();

$host =  $_ENV['HOST'];
$dbName =  $_ENV['DB_NAME'];
$userName =  $_ENV['DB_USER'];
$password =  $_ENV['DB_PASSWORD'];

$postData = file_get_contents('php://input');
$data = json_decode($postData, true);
var_dump ($postData);
// var_dump ($data);
// $id  = ($data["id"]);
// $name = ($data["name"]);
// $category = ($data["category"]);
// $baseOilType = ($data["baseOilType"]);



foreach ($data as $key => $value){
    
    ${$key}=$value;
}
// echo $id;

// echo $name;
// echo $category;
// echo $baseOilType;
// echo $price;
// echo $manufact;
// echo $countryManufact;
echo $prodCharsValues;
$decCV = json_decode($prodCharsValues);
$decCV_count = count($decCV);




// date_default_timezone_set('Asia/Krasnoyarsk');
// // $dateID = date("Y-m-d H:i:s");
// // echo $dateID;

// $micro_date = microtime();
// $date_array = explode(" ",$micro_date);
// $date = date("Y-m-d H:i:s",$date_array[1]);
// echo "Date: $date:" . $date_array[0]."<br>";

date_default_timezone_set('Asia/Krasnoyarsk');
$micro_date = microtime();
$date_array = explode(" ",$micro_date);
$date = date("Y-m-d H:i:s");
$dateID = "{$date}{$date_array[0]}"; 
// $dateID = $date.$date_array[0]; 
// $dateID = $date.$micro_date; 
// $dateID = "2024"; 

// $dateID = $date_array[0]; 

// echo $dateID;

// $dateID = date("Y-m-d H:i:s");



try {
    $pdo =new PDO("mysql:host=$host;dbname=$dbName;", $userName, $password);
} catch (PDOException $exception) {
    echo("Error: {$exception -> getMessage()}");
}

$addProduct = "INSERT INTO `products` (`name`, `category`,`img`, `price`,  `priceTotal`, `manufact`, `countryManufact`, `baseOilType`, `engineType`, `SAE`, `API`, `ILSAC`, `tolerances`, `volume`, `reccomend`, `discr`, `dateID`) VALUES ( :name, :category, :img, :price, :priceTotal, :manufact, :countryManufact, :baseOilType, :engineType, :SAE, :API, :ILSAC, :tolerances, :volume, :reccomend, :discr, :dateID)";
$stmt = $pdo -> prepare($addProduct);

$stmt ->execute([
    ':name'=> $name,
    ':category'=> $category,
    ':img'=> $img,
    ':price'=> $price,
    ':priceTotal'=> $price,
    ':manufact'=> $manufact,
    ':countryManufact'=> $countryManufact,
    ':baseOilType'=> $baseOilType,
    ':engineType'=> $engineType,
    ':SAE'=> $SAE,
    ':API'=> $API,
    ':ILSAC'=> $ILSAC,
    ':tolerances'=> $tolerances,
    ':volume'=> $volume,
    ':reccomend'=> $reccomend,
    ':discr'=> $discr,
    ':dateID'=> $dateID,
    ]);

$getProductIDSQL = "SELECT `products`.`id` FROM `products` WHERE `products`.`dateID` = ? ";
$stmt2 = $pdo -> prepare($getProductIDSQL);
// $stmt2 ->execute([
//     ':dateID'=> $dateID,
// ]);
$stmt2->execute(array($dateID));
$prodID = $stmt2 ->fetchColumn();
echo $prodID;


for($count=0;$count<$decCV_count;++$count){
    $value_id = $decCV[$count]->value;
    $char_id = $decCV[$count]->char_id;
    // echo 'value:';
    // echo($value_id);
    // echo ' char:';
    // echo($char_id);
    // echo '<br/>';
$addcviSQL = "INSERT INTO `product_char_values`(`char_id`, `value_id`, `product_id`) VALUES (:char_id,:value_id,:prodID)";
$stmt3 = $pdo ->prepare($addcviSQL);
$stmt3 ->execute([
    ':value_id'=> $value_id,
    ':char_id'=> $char_id,
    ':prodID'=> $prodID,
]);

}
// $addcviSQL = "INSERT INTO `product_char_values`(`char_id`, `value_id`, `product_id`) VALUES ('[value-1]','[value-2]','[value-3]')";




// $result->free();
