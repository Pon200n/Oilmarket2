<?php

// $arr = [
    
// ];


$json = '[{"value":1, "char":11},{"value":2, "char":22},{"value":3, "char":33}]';
$dec_json = json_decode($json);

// $json = '{"foo":2}';
// echo(gettype($dec_json));
// var_dump($dec_json);
// var_dump(count($dec_json));
// var_dump($dec_json[0]);
// var_dump($dec_json[0]->foo);
// var_dump($dec_json[0]->value);
// var_dump($dec_json[1]->param);
$dec_json_count = count($dec_json);
// print_r($dec_json_count);
for($i=0;$i<$dec_json_count;++$i){
    // var_dump($dec_json[$i]->foo);
    // var_dump($dec_json[$i]->value);
    echo 'value:';
    print_r($dec_json[$i]->value);
    echo ' char:';
    print_r($dec_json[$i]->char);
    echo '<br/>';
};

$ARR=[];
$arr = [2,3,17];
$ArrValues2 = [2,3,17];
$ArrValues2Length = count($ArrValues2);

$arrLength = count($arr);
for($i=0;$i<$arrLength;++$i){
	$ARR[]='value_id = '.$arr[$i];
}
echo json_encode($ARR);
var_dump(implode(" or ", $ARR));

$f = 'value_id';
$field = "`".str_replace("`","``",$f)."`";
echo $field;

$arr22 = array(1,2,3);
$in  = str_repeat('?,', count($arr22) - 1) . '?';
echo $in;

for($it=0;$it<$ArrValues2Length;++$it){
    // $ii.$it = $ArrValues2[$it];
    // $stmt ->bindValue($it+1,$ii.$it, PDO::PARAM_INT);
echo $it;
}

$GET = "DESC";
$dirs = array("ASC","DESC");
$key  = array_search($GET,$dirs);
$dir = $dirs[$key];
echo ' $key= '.$key.' ';
echo ' $dir= '.$dir.' ';


// $Ar123 = [];
// if(($Ar123 != ['']) || ($Ar123 != []) ){
//     echo '$Ar123 not empty';
// } elseif(($Ar123 = ['']) || ($Ar123 = [])) {
//     echo '$Ar123 empty';
// }
$Ar123 = [1];
if($Ar123 === [] || $Ar123 === [""]){
    echo "<br>";
    echo '$Ar123 empty---';
    echo "<br>";
    var_dump ($Ar123);
} else {
    echo "<br>";
    echo '$Ar123 not empty';
    echo "<br>";
    var_dump ($Ar123);
    echo "<br>";


}

$numbers = [""];
print_r(empty($numbers));
// var_dump($numbers);
print_r(count($numbers) === 0);
// print_r(count($numbers));
if(empty($numbers) or $numbers === ['']){
    echo "<br>";
    echo "numbers empty";
    echo "<br>";

} else {
    echo "<br>";
    echo "numbers not empty";
    echo "<br>";
}