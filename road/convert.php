<?php

ini_set('memory_limit', '512m');
// Num     Name            Type    Len     Decimal
// 1.      ZIPCODE         C       3       0
// 2.      ROAD            C       25      0
// 3.      ROAD_NO         C       6       0
// 4.      EROAD           C       40      0
// 5.      CITY            C       6       0
// 6.      AREA            C       8       0
// 7.      RKEY            C       10      0
// 8.      ROAD1           C       2       0
// 9.      ROADA           C       2       0
// 10.     PHON            C       2       0
// 11.     ZIP3A           C       3       0
$columns = array(
    'ZIPCODE',
    'ROAD',
    'ROAD_NO',
    'EROAD',
    'CITY',
    'AREA',
    'RKEY',
    'ROAD1',
    'ROADA',
    'PHON',
    'ZIP3A',
);
$output = trim(`dbfdump.pl --fs=split_line ROAD1.DBF | piconv -f big5 -t utf-8`);
$csv_fp = fopen('road.csv', 'w');
$count = 0;

$json = new StdClass;
$json->link = 'https://github.com/ronnywang/postcode';
$json->data_time = '2012-09-11';
$json->data_source = 'http://www.post.gov.tw/post/internet/down/index.html?ID=190108';
$json->description = '路街中英對照';
$json->data = [];

fwrite($csv_fp, '# link=https://github.com/ronnywang/postcode' . PHP_EOL);
fwrite($csv_fp, '# data_time=2012-09-11' . PHP_EOL);
fwrite($csv_fp, '# data_source=http://www.post.gov.tw/post/internet/down/index.html?ID=190108' . PHP_EOL);
fwrite($csv_fp, '# description=路街中英對照' . PHP_EOL);
fwrite($csv_fp, '#');
fputcsv($csv_fp, $columns);
foreach (explode("\n", trim($output)) as $line) {
    $terms = explode('split_line', trim($line));

    fputcsv($csv_fp, $terms);
    $json->data[] = array_combine($columns, $terms);
}
file_put_contents('road.json', json_encode($json, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
$json->data = array_slice($json->data, 0, 10);
file_put_contents('road.sample.json', json_encode($json, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
