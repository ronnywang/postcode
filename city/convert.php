<?php

// Num     Name            Type    Len     Decimal
// 1.      CITY            C       6       0
// 2.      ZIPCODE         C       3       0
// 3.      NO              C       2       0
// 4.      ECITY           C       20      0
// 5.      ECITY1          C       10      0
$columns = array(
    'CITY',
    'ZIPCODE',
    'NO',
    'ECITY',
    'ECITY1',
);
$output = trim(`dbfdump.pl CITY.DBF | piconv -f big5 -t utf-8`);
$csv_fp = fopen('city.csv', 'w');
$json_fp = fopen('city.json', 'w');

$json = new StdClass;
$json->link = 'https://github.com/ronnywang/postcode';
$json->data_time = '2012-09-11';
$json->data_source = 'http://www.post.gov.tw/post/internet/down/index.html?ID=190108';
$json->description = '縣市中英對照表';
$json->data = [];

fwrite($csv_fp, '# link=https://github.com/ronnywang/postcode' . PHP_EOL);
fwrite($csv_fp, '# data_time=2012-09-11' . PHP_EOL);
fwrite($csv_fp, '# data_source=http://www.post.gov.tw/post/internet/down/index.html?ID=190108' . PHP_EOL);
fwrite($csv_fp, '# description=縣市中英對照表' . PHP_EOL);
fwrite($csv_fp, '#');
fputcsv($csv_fp, $columns);
foreach (explode("\n", trim($output)) as $line) {
    $terms = explode(':', trim($line));

    fputcsv($csv_fp, $terms);
    $json->data[] = array_combine($columns, $terms);
}
fwrite($json_fp, json_encode($json, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
