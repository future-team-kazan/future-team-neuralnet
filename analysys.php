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
$reg = 'ru-bl';

//Получение данных для формирования обучающей выборки
$csv = array_map('str_getcsv', file('data/perinat.oborud.csv'));

foreach ($csv as $csv_temp) {
	$output_data = explode(";", $csv_temp[0]);
	if ($output_data[1] == $reg) {
		$X1 = $output_data;
	}
}

$csv = array_map('str_getcsv', file('data/perinat.medikam.csv'));

foreach ($csv as $csv_temp) {
	$output_data = explode(";", $csv_temp[0]);
	if ($output_data[1] == $reg) {
		$X2 = $output_data;
	}
}

$csv = array_map('str_getcsv', file('data/perinat.kadri.csv'));

foreach ($csv as $csv_temp) {
	$output_data = explode(";", $csv_temp[0]);
	if ($output_data[1] == $reg) {
		$X3 = $output_data;
	}
}

$csv = array_map('str_getcsv', file('data/perinat.koika.csv'));

foreach ($csv as $csv_temp) {
	$output_data = explode(";", $csv_temp[0]);
	if ($output_data[1] == $reg) {
		$X4 = $output_data;
	}
}

$csv = array_map('str_getcsv', file('data/perinat.transport.csv'));

foreach ($csv as $csv_temp) {
	$output_data = explode(";", $csv_temp[0]);
	if ($output_data[1] == $reg) {
		$X5 = $output_data;
	}
}

$csv = array_map('str_getcsv', file('data/uch.oborud.csv'));

foreach ($csv as $csv_temp) {
	$output_data = explode(";", $csv_temp[0]);
	if ($output_data[1] == $reg) {
		$Y1 = $output_data;
	}
}

$csv = array_map('str_getcsv', file('data/uch.medikam.csv'));

foreach ($csv as $csv_temp) {
	$output_data = explode(";", $csv_temp[0]);
	if ($output_data[1] == $reg) {
		$Y2 = $output_data;
	}
}

$csv = array_map('str_getcsv', file('data/uch.kadri.csv'));

foreach ($csv as $csv_temp) {
	$output_data = explode(";", $csv_temp[0]);
	if ($output_data[1] == $reg) {
		$Y3 = $output_data;
	}
}

$csv = array_map('str_getcsv', file('data/uch.transport.csv'));

foreach ($csv as $csv_temp) {
	$output_data = explode(";", $csv_temp[0]);
	if ($output_data[1] == $reg) {
		$Y4 = $output_data;
	}
}

$csv = array_map('str_getcsv', file('data/fert.csv'));

foreach ($csv as $csv_temp) {
	$output_data = explode(";", $csv_temp[0]);
	if ($output_data[1] == $reg) {
		$Z1 = $output_data;
	}
}

$csv = array_map('str_getcsv', file('data/girls.csv'));

foreach ($csv as $csv_temp) {
	$output_data = explode(";", $csv_temp[0]);
	if ($output_data[1] == $reg) {
		$Z2 = $output_data;
	}
}

$csv = array_map('str_getcsv', file('data/birth.csv'));

foreach ($csv as $csv_temp) {
	$output_data = explode(";", $csv_temp[0]);
	if ($output_data[1] == $reg) {
		$Z3 = $output_data;
	}
}

$csv = array_map('str_getcsv', file('data/kill.csv'));

foreach ($csv as $csv_temp) {
	$output_data = explode(";", $csv_temp[0]);
	if ($output_data[1] == $reg) {
		$kill = $output_data;
	}
}


//Получаем список дат
$labels = array();
$count_date = 0;
foreach ($X1 as $X1_temp) {
	array_push($labels, 1990+$count_date);
	$count_date++;
}

//Формируем обучающую выборку
$count = count($labels);
$edu = array();

//Вычисляем шаг аргумента функции	
$step = $labels[$count-1] - $labels[$count-2];

$future_count = 5;
$future = array();
for ($i = 0; $i < $future_count; $i++) {
	$future_temp = array ($labels[$count-3]+$step*($i+1),$X1[$count-2]+$XX1, $X2[$count-2]+$XX2, $X3[$count-2]+$XX3, $X4[$count-2]+$XX4, $X5[$count-2]+$XX5, $Y1[$count-2]+$YY1, $Y2[$count-2]+$YY2, $Y3[$count-2]+$YY3, $Y4[$count-2]+$YY4, $Z1[$count-2]+$ZZ1, $Z2[$count-2]+$ZZ2, $Z3[$count-2]+$ZZ3);
	array_push($future, $future_temp);
}

//Записываем данные для прогноза в файл
file_put_contents('predict.csv', '');
$fp1 = fopen('predict.csv', 'w');
	foreach ($future as $fields) {
		fputcsv($fp1, $fields, ";");
}

fclose($fp1);

//Формируем обучающую выборку
$data_neural = array();

//Формируем заголовок
$csv_data_header = array("X1","X2","X3","X4","X5","Y1","Y2","Y3","Y4","Z1","Z2","Z3","OUTPUT");
	
array_push($data_neural, $csv_data_header);

for ($j = 0; $j < $count-2; $j++) {
	$data_neural_temp = array ($labels[$j],$X1[$j+2], $X2[$j+2], $X3[$j+2], $X4[$j+2], $X5[$j+2], $Y1[$j+2], $Y2[$j+2], $Y3[$j+2], $Y4[$j+2], $Z1[$j+2], $Z2[$j+2], $Z3[$j+2], $kill[$j+2]/10);
	array_push($data_neural, $data_neural_temp);
}

//Записываем обучающую выборку в файл
file_put_contents('data.csv', '');
$fp1 = fopen('data.csv', 'w');

foreach ($data_neural as $fields) {
	fputcsv($fp1, $fields, ";");
}
fclose($fp1);

//Формируем обучающую выборку для прозноза по базовой ситуации
$data_neural_1 = array();

//Формируем заголовок
$csv_data_header = array("INPUT", "OUTPUT");
	
array_push($data_neural_1, $csv_data_header);
$basic = array();
$new = array();

for ($j = 0; $j < $count-2; $j++) {
	$data_neural_temp_1 = array ($labels[$j],$kill[$j+2]/10);
	array_push($data_neural_1, $data_neural_temp_1);
	array_push($basic, $data_neural_temp_1);
	array_push($new, $data_neural_temp_1);
}

//Записываем обучающую выборку в файл
file_put_contents('data1.csv', '');
$fp1 = fopen('data1.csv', 'w');

foreach ($data_neural_1 as $fields) {
	fputcsv($fp1, $fields, ";");
}
fclose($fp1);

//Формируем выборку для прогноза базовой ситуации

$future_1 = array();
for ($i = 0; $i < $future_count; $i++) {
	$future_temp_1 = array ($labels[$count-3]+$step*($i+1));
	array_push($future_1, $future_temp_1);
}

//Записываем данные для прогноза в файл
file_put_contents('predict1.csv', '');
$fp1 = fopen('predict1.csv', 'w');
	foreach ($future_1 as $fields) {
		fputcsv($fp1, $fields, ";");
}

fclose($fp1);

unset($csv_data);


//Получение прогноза по базовой ситуации
//Разибраем не валидный json
$data_json = file_get_contents('http://46.173.218.174/brew/predict10.html');
$data_json = str_replace("\r\n", "", $data_json);
$data_json=str_replace('\n','',$data_json);
$data_json=str_replace('\n','',$data_json);
$data_json=str_replace('\n','',$data_json);
$data_json=str_replace('<\/div>','',$data_json);
$data_json=str_replace('{','',$data_json);
$data_json=str_replace('}','',$data_json);
$output_data1 = explode("[", $data_json);
$output_data1 = explode("]", $output_data1[1]);
$$output_data1[0]=str_replace('[','',$output_data1[0]);
$$output_data1[0]=str_replace(']','',$$output_data1[0]);
$output_data = explode(",", $$output_data1[0]);

$future_array_temp = array();
for ($z = 0; $z < $future_count; $z++) {
	$future_array_temp = array($labels[$count-3]+$step*($z+1), $output_data[$z]);
	array_push($basic, $future_array_temp);
}



//Получение прогноза по новой ситуации
//Разибраем не валидный json
$data_json = file_get_contents('http://46.173.218.174/brew/predict12.html');
$data_json = str_replace("\r\n", "", $data_json);
$data_json=str_replace('\n','',$data_json);
$data_json=str_replace('\n','',$data_json);
$data_json=str_replace('\n','',$data_json);
$data_json=str_replace('<\/div>','',$data_json);
$data_json=str_replace('{','',$data_json);
$data_json=str_replace('}','',$data_json);
$output_data1 = explode("[", $data_json);
$output_data1 = explode("]", $output_data1[1]);
$$output_data1[0]=str_replace('[','',$output_data1[0]);
$$output_data1[0]=str_replace(']','',$$output_data1[0]);
$output_data = explode(",", $$output_data1[0]);

$predict_new_temp = array();
for ($z = 0; $z < $future_count; $z++) {
	$predict_new_temp = array($labels[$count-3]+$step*($z+1), $output_data[$z]);
	array_push($new, $predict_new_temp);
}

for ($i = 0; $i < $future_count; $i++) {
	$future_temp_1 = array ($labels[$count-3]+$step*($i+1));
	array_push($labels, $labels[$count-3]+$step*($i+1));
}

$basic_value = array();
$new_value = array();
foreach ($basic as $basic_temp) {
	array_push($basic_value, (float)$basic_temp[1]);
}

foreach ($new as $new_temp) {
	array_push($new_value, (float)$new_temp[1]);
}

$datasets_temp = array();

//Формируем данные для вывода
$dataset = array(
	"label"=>'Basic',
	"data"=> $basic_value
);

$dataset_future = array(
	"label"=>'New',
	"data"=> $new_value
);

array_push($datasets_temp, $dataset);
array_push($datasets_temp, $dataset_future);

$chart = array(
"labels" => $labels,
"datasets" => $datasets_temp
);

$f = json_encode($chart, JSON_UNESCAPED_UNICODE);

echo $f;






?>
