
<?php
include "query-functions.php";
$code = $_GET["k"];
$node_id = $_GET["id"];

$where = [
    'where' => [
        '0' => ['kod', $code ],
        '1' => ['node_id', $node_id]
    ]
];
removeRowQuery("tovar", $where);
header('Refresh: 3; url=index.php?menu=8');
?>


