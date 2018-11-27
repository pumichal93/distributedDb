 <!doctype html public "-//W3C//DTD HTML 4.0 //EN">
<html>
<head>
       <title>vyhladaj</title>
        <link href="style.css" rel=stylesheet type=text/css>
</head>
<body>
<p align = "left">

<?php
echo "<table border='0' cellpadding='0' cellspacing='0' style='border-collapse: collapse' bordercolor='#111111' width='700'>";
include ("config.php");   
$var = mysqli_connect("$host","$user","$password","$db") or die ("connect error");
$sql = "select id,nazov,vyrobca,popis,kod,cena,farba, node_id from tovar";
$result = mysqli_query($var, $sql) or exit ("chybny dotaz");
//načítanie hodnôt do pola
while($row = mysqli_fetch_assoc($result))
		{ 
			$id = $row['id'];
      $nazov = $row['nazov'];
			$vyrobca = $row['vyrobca'];
			$popis = $row['popis'];
			$farba = $row['farba'];
      $cena = $row['cena'];
      $kod = $row['kod'];
          $id = $row["node_id"];
//výpis hodnôt
echo "<tr>
    <td width='200'bgcolor='#ffffff' height='22'><b> ".$kod."</b></td>
    <td width='300'bgcolor='#ffffff' height='22'>Nazov<b> ".$nazov."</b></td> 
    <td width='100'bgcolor='#ffffff' height='22'>Cena: <b> ".$cena."</b></td>
    <td width='100'bgcolor='#ffffff' height='22'></td>
     </tr>
     <tr>
    <td width='200'bgcolor='#FFFFee' height='32'>Vyrobca<b> ".$vyrobca."</b></td>
    <td width='300'bgcolor='#FFFFee' height='32'>popis <b>".$popis." </b></td>
    <td width='100'bgcolor='#FFFFee' height='32'>farba <b>".$farba."</b></td>
    <td width='100' color='ff0000' bgcolor='#FFFFee' height='32'><b><a href='zmazanietov.php?k=".$kod."&id=".$id."'>x</b></a></td>
  </tr>   
  <tr>
   <td width='200'bgcolor='#000000' height='1'></td>
    <td width='300'bgcolor='#000000' height='1'></td> 
    <td width='100'bgcolor='#000000' height='1'></td>
    <td width='100'bgcolor='#000000' height='1'></td>
    </tr>";
  }
  echo "</table>";
?>

 </body>
</html>
