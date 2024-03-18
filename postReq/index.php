<?php
header("Access-Control-Allow-Origin: *");

$postData = file_get_contents('php://input');
$dataID = json_decode($postData, true);
// print_r ($postData);
// echo json_encode("fsfd");
// unlink('../static/test.html');

$mysql = new mysqli("localHost", "root", "", "Oil_example");
$mysql->query("SET NAMES 'utf8'");
$getImgName = "SELECT img FROM `products` WHERE id='$dataID'";
echo json_encode($getImgName);

// $test = "test.html";
// unlink('../static/'.$test);

// echo $dataID['rout'];