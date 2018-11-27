<?php
include "query-functions.php"; 
include "config.php";
// prepare data to db insert
$insert_data = [
	'nazov' => $_POST["nazov"],
	'vyrobca' => $_POST["vyrobca"],
	'popis' => $_POST["popis"],
	'farba' => $_POST["farba"],
	'cena' => $_POST["cena"],
	'kod' => $_POST["kod"],
	'node_id' => $name
];

addNewRow('tovar', $insert_data);
header('Refresh: 3; url=index.php?menu=8');
?>


