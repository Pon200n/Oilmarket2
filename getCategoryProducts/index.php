<?php
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');

// require __DIR__ . '/../vendor/autoload.php';
// Dotenv\Dotenv::createUnsafeImmutable(__DIR__ . '/../')->load();

require __DIR__ . '/../index.php';

$host =  $_ENV['HOST'];
$dbName =  $_ENV['DB_NAME'];
$userName =  $_ENV['DB_USER'];
$DBpassword =  $_ENV['DB_PASSWORD'];


$categoryID = $_GET['categoryID'];
$brandID = $_GET['brandID'];

$ArrValues = $_GET['ArrValues'];
$sortByPrice = $_GET['sortByPrice'];
$ArrValues2 = explode(",",$ArrValues);
// *
// $sort = $_GET['sort'];
$page = $_GET['page'];
$limit = $_GET['limit'];

$offset = $page*$limit - $limit;

try {
    $pdo =new PDO("mysql:host=$host;dbname=$dbName;", $userName, $DBpassword);
} catch (PDOException $exception) {
    echo("Error: {$exception -> getMessage()}");
}
$result = [
    'products' =>'',
    'category' =>'',
    'brand' =>'',
    'countProd' =>'',
    'ArrValues2' =>'',
    'FiltratedProd' =>'',
    // 'limit' =>$limit,
];

$dirs = array("ASC","DESC");
$key  = array_search($sortByPrice,$dirs);
$dir = $dirs[$key];

if($brandID != 'none'){
    // if(count($ArrValues2)>0){
        if(($ArrValues2 != ['']  )){

        $result['ArrValues2'] = $ArrValues2;

        $in  = str_repeat('?,', count($ArrValues2) - 1) . '?';




        $getFiltrOFValueCatBrndProdSQl = "SELECT DISTINCT `products`.*,`categories`.`category_name`,`brands`.`brand_name` FROM `products` LEFT JOIN `categories` ON `products`.`category` = categories.id LEFT JOIN `brands` ON `products`.`manufact` = brands.id LEFT JOIN product_char_values ON products.id = product_char_values.product_id WHERE products.category = ? AND products.manufact= ? AND value_id IN ($in) ORDER BY products.price $dir LIMIT  ? OFFSET ? ;";
        // $getFiltrOFValueCatBrndProdSQl = "SELECT DISTINCT `products`.*,`categories`.`category_name`,`brands`.`brand_name` FROM `products` LEFT JOIN `categories` ON `products`.`category` = categories.id LEFT JOIN `brands` ON `products`.`manufact` = brands.id LEFT JOIN product_char_values ON products.id = product_char_values.product_id WHERE products.category = ? AND products.manufact= ? AND value_id IN ($in) LIMIT  ? OFFSET ?;";
        $stmt11 = $pdo -> prepare($getFiltrOFValueCatBrndProdSQl);
        $stmt11 ->bindValue(1,$categoryID, PDO::PARAM_INT);
        $stmt11 ->bindValue(2,$brandID, PDO::PARAM_INT);
        
        for($it3=0;$it3<count($ArrValues2);++$it3){
            $ii3 = $ArrValues2[$it3];
            $stmt11 ->bindValue($it3+3,$ii3, PDO::PARAM_INT);
        }
        $stmt11 ->bindValue(count($ArrValues2)+3,$limit, PDO::PARAM_INT);
        $stmt11 ->bindValue(count($ArrValues2)+4,$offset, PDO::PARAM_INT);
        $stmt11 ->execute();
        $FILTRproductsValCatBrnd =$stmt11 ->fetchAll(PDO::FETCH_ASSOC);
        $result['products'] = $FILTRproductsValCatBrnd;

        //*
        $CountOfFiltredValueCatBrndProdsSQL = "SELECT COUNT(DISTINCT(products.id)) as count FROM `products` LEFT JOIN `categories` ON `products`.`category` = categories.id LEFT JOIN `brands` ON `products`.`manufact` = brands.id LEFT JOIN product_char_values ON products.id = product_char_values.product_id WHERE products.category = ? AND products.manufact= ? AND value_id IN ($in);";
        $stmt12 = $pdo ->prepare($CountOfFiltredValueCatBrndProdsSQL);
        $stmt12 ->bindValue(1,$categoryID, PDO::PARAM_INT);
        $stmt12 ->bindValue(2,$brandID, PDO::PARAM_INT);
        for($it12=0;$it12<count($ArrValues2);++$it12){
            $ii12 = $ArrValues2[$it12];
            $stmt12 ->bindValue($it12+3,$ii12, PDO::PARAM_INT);
        }
        $stmt12 ->execute();
        $CountFILTRproductsByValCatBrnd =$stmt12 ->fetch(PDO::FETCH_ASSOC);

        $result['countProd']=$CountFILTRproductsByValCatBrnd['count']; 
        //*

    } else {
        $getCatBrndProdSQl = "SELECT `products`.*,`categories`.`category_name`,`brands`.`brand_name` FROM `products` LEFT JOIN `categories` ON `products`.`category` = categories.id LEFT JOIN `brands` ON `products`.`manufact` = brands.id WHERE products.category = :categoryID AND products.manufact=:brandID ORDER BY products.price $dir LIMIT  :limit_p OFFSET :offset_p;";
        // $getCatBrndProdSQl = "SELECT `products`.*,`categories`.`category_name`,`brands`.`brand_name` FROM `products` LEFT JOIN `categories` ON `products`.`category` = categories.id LEFT JOIN `brands` ON `products`.`manufact` = brands.id WHERE products.category = :categoryID AND products.manufact=:brandID LIMIT  :limit_p OFFSET :offset_p;";
        $stmt3 = $pdo -> prepare($getCatBrndProdSQl);
        $stmt3 ->bindValue('categoryID',$categoryID, PDO::PARAM_INT);
        $stmt3 ->bindValue('brandID',$brandID, PDO::PARAM_INT);
        $stmt3 ->bindValue('limit_p',$limit, PDO::PARAM_INT);
        $stmt3 ->bindValue('offset_p',$offset, PDO::PARAM_INT);
        $stmt3 ->execute();
    
        $productsCB = $stmt3 -> fetchAll(PDO::FETCH_ASSOC);
        $result['products'] = $productsCB;
    
        $countOfProductsSQL = 'SELECT COUNT(*) count FROM `products` WHERE products.category = ? AND products.manufact=?';
    
        $stmt5 = $pdo -> prepare($countOfProductsSQL);
        $stmt5 ->execute([$categoryID,$brandID]);
        $COUNT = $stmt5 -> fetch();
        
        $result['countProd']=$COUNT['count'];    
    
        $getbrandNameByID = "SELECT `brands`.`brand_name` FROM `brands` WHERE `brands`.`id`=?";
        $stmt4 = $pdo -> prepare($getbrandNameByID);
        $stmt4 ->execute([$brandID]);
        $brandName = $stmt4 -> fetch(PDO::FETCH_ASSOC);
        $result['brand'] = $brandName;
        $result['ArrValues2'] = $ArrValues2;
    }


} else {
    // if(count($ArrValues2)>1){
    // if(!empty($ArrValues2)){
    if($ArrValues2 != [''] ){
        $result['ArrValues2'] = $ArrValues2;
        // $result['ArrValues2'] = count($ArrValues2);

        $in  = str_repeat('?,', count($ArrValues2) - 1) . '?';

        $getFiltredValueProdsSQL = "SELECT DISTINCT products.*,`categories`.`category_name`,`brands`.`brand_name` FROM `products`LEFT JOIN product_char_values ON products.id = product_char_values.product_id LEFT JOIN `categories` ON `products`.`category` = categories.id LEFT JOIN `brands` ON `products`.`manufact` = brands.id WHERE products.category = ? AND value_id IN ($in) ORDER BY products.price $dir LIMIT  ? OFFSET ?;";
        // $getFiltredValueProdsSQL = "SELECT DISTINCT products.*,`categories`.`category_name`,`brands`.`brand_name` FROM `products`LEFT JOIN product_char_values ON products.id = product_char_values.product_id LEFT JOIN `categories` ON `products`.`category` = categories.id LEFT JOIN `brands` ON `products`.`manufact` = brands.id WHERE value_id IN ($in) ORDER BY products.price $dir LIMIT  ? OFFSET ?;";

        $stmt = $pdo ->prepare($getFiltredValueProdsSQL);

        $stmt ->bindValue(1,$categoryID, PDO::PARAM_INT);

        for($it=0;$it<count($ArrValues2);++$it){
            $ii = $ArrValues2[$it];
            $stmt ->bindValue($it+2,$ii, PDO::PARAM_INT);
        }
        $stmt ->bindValue(count($ArrValues2)+2,$limit, PDO::PARAM_INT);
        $stmt ->bindValue(count($ArrValues2)+3,$offset, PDO::PARAM_INT);
        // for($it=0;$it<count($ArrValues2);++$it){
        //     $ii = $ArrValues2[$it];
        //     $stmt ->bindValue($it+1,$ii, PDO::PARAM_INT);
        // }
        // $stmt ->bindValue(count($ArrValues2)+1,$limit, PDO::PARAM_INT);
        // $stmt ->bindValue(count($ArrValues2)+2,$offset, PDO::PARAM_INT);
        $stmt ->execute();
        $FILTRproducts =$stmt ->fetchAll(PDO::FETCH_ASSOC);
        // $result['FiltratedProd'] = $FILTRproducts;

        $CountOfFiltredValueProdsSQL = "SELECT COUNT(DISTINCT(products.id)) as count  FROM `products`LEFT JOIN product_char_values ON products.id = product_char_values.product_id WHERE value_id IN ($in);";
        $stmt2 = $pdo ->prepare($CountOfFiltredValueProdsSQL);
        for($it2=0;$it2<count($ArrValues2);++$it2){
            $ii2 = $ArrValues2[$it2];
            $stmt2 ->bindValue($it2+1,$ii2, PDO::PARAM_INT);
        }
        $stmt2 ->execute();
        $CountFILTRproducts =$stmt2 ->fetch(PDO::FETCH_ASSOC);

        $result['countProd']=$CountFILTRproducts['count']; 
        $result['products'] = $FILTRproducts;

    }
    else {
        $getCategoryProductsSQL = "SELECT `products`.*,`categories`.`category_name`,`brands`.`brand_name` FROM `products` LEFT JOIN `categories` ON `products`.`category` = categories.id LEFT JOIN `brands` ON `products`.`manufact` = brands.id WHERE `categories`.id = :categoryID  ORDER BY products.price $dir LIMIT  :limit_p OFFSET :offset_p";
        // $getCategoryProductsSQL = "SELECT `products`.*,`categories`.`category_name`,`brands`.`brand_name` FROM `products` LEFT JOIN `categories` ON `products`.`category` = categories.id LEFT JOIN `brands` ON `products`.`manufact` = brands.id WHERE `categories`.id = :categoryID  LIMIT  :limit_p OFFSET :offset_p";
        $stmt = $pdo -> prepare($getCategoryProductsSQL);
    
        $stmt ->bindValue('categoryID',$categoryID, PDO::PARAM_INT);
        $stmt ->bindValue('limit_p',$limit, PDO::PARAM_INT);
        $stmt ->bindValue('offset_p',$offset, PDO::PARAM_INT);
        $stmt ->execute();
    
        $products = $stmt -> fetchAll(PDO::FETCH_ASSOC);
        $result['products'] = $products;



        $countOfProductsSQL = 'SELECT COUNT(*) count FROM `products` WHERE products.category = ?';

    
        $stmt5 = $pdo -> prepare($countOfProductsSQL);
        $stmt5 ->execute([$categoryID]);
        $COUNT = $stmt5 -> fetch();
        
        $result['countProd']=$COUNT['count'];  
        $result['ArrValues2'] = $ArrValues2;
    }

}





$getCategoryNameByID = "SELECT `category_name` FROM `categories` WHERE `id`=?;";
$stmt2 = $pdo -> prepare($getCategoryNameByID);
$stmt2 ->execute([$categoryID]);
$categoryName = $stmt2 -> fetch(PDO::FETCH_ASSOC);
$result['category'] = $categoryName;
echo  json_encode ($result);

