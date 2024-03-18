<?php
header("Access-Control-Allow-Origin: *");


$name = mt_rand(0, 10000000);





// echo '<pre>';
// print_r ($_FILES);
// echo '<pre>';

// echo '<br>';
// echo '<pre>';
// print_r ($_FILES["file"]);
// echo '<pre>';
copy($_FILES['file']['tmp_name'], '../static/' . $name . $_FILES['file']['name']);
echo json_encode ($name . $_FILES['file']['name']);

// $postData = file_get_contents('php://input');
// $data = json_decode($postData, true);
// var_dump ($postData);