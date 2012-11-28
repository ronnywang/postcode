<?php

// Num     Name            Type    Len     Decimal
// 1.      ZIPCODE         C       3       0
// 2.      CITY            C       6       0
// 3.      AREA            C       8       0
// 4.      ROAD            C       20      0
// 5.      CMP_LABLE       C       1       0
// 6.      EVEN            N       1       0
// 7.      LANE            N       4       0
// 8.      LANE1           N       4       0
// 9.      ALLEY           N       4       0
// 10.     ALLEY1          N       4       0
// 11.     NO_BGN          N       4       0
// 12.     NO_BGN1         N       4       0
// 13.     NO_END          N       4       0
// 14.     NO_END1         N       4       0
// 15.     FLOOR           N       2       0
// 16.     FLOOR1          N       2       0
// 17.     SCOOP           C       30      0
// 18.     ROAD_NO         C       6       0
// 19.     EXP             C       30      0
// 20.     RMK             C       10      0
$columns = array(
    'ZIPCODE',
    'CITY',
    'AREA',
    'ROAD',
    'CMP_LABLE',
    'EVEN',
    'LANE',
    'LANE1',
    'ALLEY',
    'ALLEY1',
    'NO_BGN',
    'NO_BGN1',
    'NO_END',
    'NO_END1',
    'FLOOR',
    'FLOOR1',
    'SCOOP',
    'ROAD_NO',
    'EXP',
    'RMK',
    );
$output = trim(`dbfdump.pl ZIP3.DBF | iconv -f big5 -t utf-8`);
$csv_fp = fopen('zip3.csv', 'w');
$json_fp = fopen('zip3.json', 'w');

$ret = [];
fwrite($csv_fp, '#');
fputcsv($csv_fp, $columns);
foreach (explode("\n", trim($output)) as $line) {
    $terms = explode(':', trim($line));

    fputcsv($csv_fp, $terms);
    $ret[] = array_combine($columns, $terms);
}
fwrite($json_fp, json_encode($ret, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
