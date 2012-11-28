<?php

ini_set('memory_limit', '512m');
// Num     Name            Type    Len     Decimal
// 1.      OFFICE          C       40      0
// 2.      ZIP3A           C       3       0
// 3.      ZIPCODE         C       5       0
// 4.      CITY            C       6       0
// 5.      AREA            C       8       0
// 6.      AREA1           C       8       0
// 7.      ROAD            C       25      0
// 8.      SCOOP           C       36      0
// 9.      EVEN            N       1       0
// 10.     CMP_LABLE       C       1       0
// 11.     LANE            N       4       0
// 12.     LANE1           N       4       0
// 13.     ALLEY           N       4       0
// 14.     ALLEY1          N       4       0
// 15.     NO_BGN          N       4       0
// 16.     NO_BGN1         N       4       0
// 17.     NO_END          N       4       0
// 18.     NO_END1         N       4       0
// 19.     FLOOR           N       2       0
// 20.     FLOOR1          N       2       0
// 21.     ROAD_NO         C       6       0
// 22.     ROAD1           C       22      0
// 23.     EROAD           C       45      0
// 24.     RMK             C       12      0
// 25.     RMK1            C       1       0
// 26.     ZIP3RMK         L       1       0
// 27.     ECITY           C       15      0
// 28.     EAREA           C       25      0
// 29.     UWORD           C       4       0
// 30.     ISN             L       1       0
$columns = array(
    'OFFICE',
    'ZIP3A',
    'ZIPCODE',
    'CITY',
    'AREA',
    'AREA1',
    'ROAD',
    'SCOOP',
    'EVEN',
    'CMP_LABLE',
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
    'ROAD_NO',
    'ROAD1',
    'EROAD',
    'RMK',
    'RMK1',
    'ZIP3RMK',
    'ECITY',
    'EAREA',
    'UWORD',
    'ISN',
);
$output = trim(`dbfdump.pl --fs=split_line RALL1.DBF | piconv -f big5 -t utf-8`);
$csv_fp = fopen('rall.csv', 'w');
$json_sample_fp = fopen('rall.sample.json', 'w');
$count = 0;

$ret = [];
fwrite($csv_fp, '#');
fputcsv($csv_fp, $columns);
foreach (explode("\n", trim($output)) as $line) {
    $terms = explode('split_line', trim($line));

    fputcsv($csv_fp, $terms);
    $ret[] = array_combine($columns, $terms);
}
file_put_contents('rall.json', json_encode($ret, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
file_put_contents('rall.sample.json', json_encode(array_slice($ret, 0, 10), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
