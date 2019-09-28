<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: X-Requested-With, Content-type');

//Получение данных из Front-end для прогнозирования нейрофункции
$post = json_decode(file_get_contents('php://input'), true);
$XX1 = $post['X1'];
$XX2 = $post['X2'];
$XX3 = $post['X3'];
$XX4 = $post['X4'];
$XX5 = $post['X4'];

$YY1 = $post['Y1'];
$YY2 = $post['Y2'];
$YY3 = $post['Y3'];
$YY4 = $post['Y3'];

$ZZ1 = $post['Z1'];
$ZZ2 = $post['Z2'];
$ZZ3 = $post['Z3'];

$reg = $post['REG'];

//Получение данных для формирования обучающей выборки
$X1_t = file_get_contents('http://iminister.site/rest/statistic.php');
$X1 = json_decode($X1_t, JSON_UNESCAPED_UNICODE);
print_r $X1['2018'];

$X2_t = file_get_contents('http://iminister.site/rest/statistic.php');
$X2 = json_decode($X2_t, JSON_UNESCAPED_UNICODE);

$X3_t = file_get_contents('http://iminister.site/rest/statistic.php');
$X3 = json_decode($X3_t, JSON_UNESCAPED_UNICODE);

$X4_t = file_get_contents('http://iminister.site/rest/statistic.php');
$X4 = json_decode($X4_t, JSON_UNESCAPED_UNICODE);

$X5_t = file_get_contents('http://iminister.site/rest/statistic.php');
$X5 = json_decode($X5_t, JSON_UNESCAPED_UNICODE);



$Y1_t = file_get_contents('http://iminister.site/rest/statistic.php');
$Y1 = json_decode($Y1_t, JSON_UNESCAPED_UNICODE);

$Y2_t = file_get_contents('http://iminister.site/rest/statistic.php');
$Y2 = json_decode($Y2_t, JSON_UNESCAPED_UNICODE);

$Y3_t = file_get_contents('http://iminister.site/rest/statistic.php');
$Y3 = json_decode($Y3_t, JSON_UNESCAPED_UNICODE);

$Y4_t = file_get_contents('http://iminister.site/rest/statistic.php');
$Y4 = json_decode($Y4_t, JSON_UNESCAPED_UNICODE);


$Z1_t = file_get_contents('./data.json');
$Z1 = json_decode($Z1_t, JSON_UNESCAPED_UNICODE);

$Z2_t = file_get_contents('./data.json');
$Z2 = json_decode($Z2_t, JSON_UNESCAPED_UNICODE);

$Z3_t = file_get_contents('./data.json');
$Z3 = json_decode($Z3_t, JSON_UNESCAPED_UNICODE);





?>
