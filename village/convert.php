<?php

// Num     Name            Type    Len     Decimal
// 1.      ZIPCODE         C       5       0
// 2.      CITY            C       6       0
// 3.      AREA            C       8       0
// 4.      AREA1           C       8       0
// 5.      VILLAGE         C       10      0
// 6.      VILLAGE1        C       10      0
// 7.      RMK             L       1       0
// 8.      RMKC            L       1       0
// 9.      EVILLAGE        C       20      0
$columns = array(
    'ZIPCODE',
    'CITY',
    'AREA',
    'AREA1',
    'VILLAGE',
    'VILLAGE1',
    'RMK',
    'RMKC',
    'EVILLAGE',
);
$output = trim(`dbfdump.pl village.DBF | piconv -f big5 -t utf-8`);
$csv_fp = fopen('village.csv', 'w');
$json_fp = fopen('village.json', 'w');

$json = new StdClass;
$json->link = 'https://github.com/ronnywang/postcode';
$json->data_time = '2012-09-11';
$json->data_source = 'http://www.post.gov.tw/post/internet/down/index.html?ID=190108';
$json->description = '村里文字中英對照表';
$json->data = [];

fwrite($csv_fp, '# link=https://github.com/ronnywang/postcode' . PHP_EOL);
fwrite($csv_fp, '# data_time=2012-09-11' . PHP_EOL);
fwrite($csv_fp, '# data_source=http://www.post.gov.tw/post/internet/down/index.html?ID=190108' . PHP_EOL);
fwrite($csv_fp, '# description=村里文字中英對照表' . PHP_EOL);
fwrite($csv_fp, '#');
fputcsv($csv_fp, $columns);
foreach (explode("\n", trim($output)) as $line) {
    $terms = explode(':', trim($line));

    fputcsv($csv_fp, $terms);
    $json->data[] = array_combine($columns, $terms);
}
fwrite($json_fp, json_encode($json, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
$json->data = array_slice($json->data, 0, 10);
file_put_contents('village.sample.json', json_encode($json, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
