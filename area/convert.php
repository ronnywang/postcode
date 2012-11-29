<?php

ini_set('memory_limit', '512m');
// Num     Name            Type    Len     Decimal
// 1.      ZIPCODE         C       3       0
// 2.      CITY            C       6       0
// 3.      AREA            C       8       0
// 4.      ECITYAREA       C       38      0
// 5.      TCITYAREA       C       38      0
// 6.      HCITYAREA       C       38      0
// 7.      PCITYAREA       C       38      0
$columns = array(
    'ZIPCODE',
    'CITY',
    'AREA',
    'ECITYAREA',
    'TCITYAREA',
    'HCITYAREA',
    'PCITYAREA',
);

// 1.      ZIPCODE         C       3       0
// 2.      CITYAREA        C       14      0
// 3.      CITY            C       6       0
// 4.      AREA            C       8       0
// 5.      ECITYAREA       C       40      0
// 6.      ECITY           C       20      0
// 7.      EAREA           C       22      0
// 8.      AREA1           C       8       0
// 9.      RMK             C       1       0
$cityarea_columns = array(
    'ZIPCODE',
    'CITYAREA',
    'CITY',
    'AREA',
    'ECITYAREA',
    'ECITY',
    'EAREA',
    'AREA1',
    'RMK',
);
$output = trim(`dbfdump.pl --fs=split_line AREA.DBF | piconv -f big5 -t utf-8`);
$csv_fp = fopen('area.csv', 'w');
$count = 0;

$output_cityarea = trim(`dbfdump.pl --fs=split_line CITYAREA.DBF | piconv -f big5`);
$cityarea_csv_fp = fopen('cityarea.csv', 'w');

$json = new StdClass;
$json->link = 'https://github.com/ronnywang/postcode';
$json->data_time = '2012-09-11';
$json->data_source = 'http://www.post.gov.tw/post/internet/down/index.html?ID=190108';
$json->description = '鄉鎮縣市及各種別稱中英對照';
$json->data = [];

fwrite($cityarea_csv_fp, '# link=https://github.com/ronnywang/postcode' . PHP_EOL);
fwrite($cityarea_csv_fp, '# data_time=2012-09-11' . PHP_EOL);
fwrite($cityarea_csv_fp, '# data_source=http://www.post.gov.tw/post/internet/down/index.html?ID=190108' . PHP_EOL);
fwrite($cityarea_csv_fp, '# description=鄉鎮縣市別稱中英對照' . PHP_EOL);
fwrite($cityarea_csv_fp, '#');
fputcsv($cityarea_csv_fp, $cityarea_columns);
$alias = [];
foreach (explode("\n", trim($output_cityarea)) as $line) {
    $terms = explode('split_line', trim($line));
    fputcsv($cityarea_csv_fp, $terms);

    $data = array_combine($cityarea_columns, $terms);
    unset($data['ZIPCODE']);
    $alias[$terms[0]][] = $data;
}

fwrite($csv_fp, '# link=https://github.com/ronnywang/postcode' . PHP_EOL);
fwrite($csv_fp, '# data_time=2012-09-11' . PHP_EOL);
fwrite($csv_fp, '# data_source=http://www.post.gov.tw/post/internet/down/index.html?ID=190108' . PHP_EOL);
fwrite($csv_fp, '# description=鄉鎮縣市中英對照' . PHP_EOL);
fwrite($csv_fp, '#');
fputcsv($csv_fp, $columns);
foreach (explode("\n", trim($output)) as $line) {
    $terms = explode('split_line', trim($line));

    fputcsv($csv_fp, $terms);
    $data_array = array_combine($columns, $terms);
    $data_array['ALIAS'] = $alias[$terms[0]];

    $json->data[] = $data_array;
}
file_put_contents('area.json', json_encode($json, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
$json->data = array_slice($json->data, 0, 10);
file_put_contents('area.sample.json', json_encode($json, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
