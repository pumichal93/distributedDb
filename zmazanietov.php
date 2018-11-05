
<?php
include ("config.php");
$k = $_GET["k"]; 

$var = mysqli_connect("$localhost","$user","$password","$db") or die ("connect error");
$sql = "delete from tovar where id = $k";

$res = mysqli_query($var,$sql) or die ("registration error");
echo "<font color=\"black\"><br><strong>Vymazanie prebehlo úspešne </strong>";
echo "";
header('Refresh: 3; url=index.php?menu=8');
?>


