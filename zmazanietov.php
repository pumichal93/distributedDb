
<?php
include "query-functions.php";
$k = $_GET["k"];

$where = [
    'where' => [
        '0' => ['kod', $k]
    ]
];
removeRowQuery("tovar", $where);
header('Refresh: 3; url=index.php?menu=8');
?>


