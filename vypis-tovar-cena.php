<!doctype html public "-//W3C//DTD HTML 4.0 //EN">
<html>
<head>
</head>
<body>
<p align = "left">
<?php
echo "<table border='0' cellpadding='0' cellspacing='0' style='border-collapse: collapse' bordercolor='#111111' width='700'>";
$nazov =$_POST["nazov"];
$vyrobca =$_POST["vyrobca"];
$cena=$_POST["cena"];

include ("config.php");   
$var = mysqli_connect("$localhost","$user","$password","$db") or die ("connect error");
$sql = "select id,nazov,vyrobca,popis,kod,cena,farba from tovar where cena LIKE '$cena%' AND nazov LIKE '%$nazov%' AND vyrobca LIKE '$vyrobca%'";
$result = mysqli_query($var, $sql) or exit ("chybny dotaz");
//zÃ¡hlavie tabulky
echo "<tr>
    <td width='100'bgcolor='#FFaaCC' height='32'><b>Kod </b></td>
    <td width='100'bgcolor='#FFFFCC' height='32'><b> cena-EUR</b></td>
    <td width='100'bgcolor='#FFaaCC' height='32'><b> nazov</b></td>
    <td width='100'bgcolor='#FFFFCC' height='32'><b>vyrobca</b></td>
    <td width='150'bgcolor='#FFaaCC' height='32'><b>popis</b></td>
    <td width='50' bgcolor='#FFFFCC' height='32'><b>farba</b></td>
    <td width='50' color='ff0000' bgcolor='#FFaaCC' height='32'><b>vymaz</b></td>
  </tr>    
  <tr>
   <td width='100'bgcolor='#0000ff' height='5'></td>
    <td width='100'bgcolor='#0000ff' height='5'></td> 
    <td width='100'bgcolor='#0000ff' height='5'></td>
    <td width='100'bgcolor='#0000ff' height='5'></td>
    <td width='150'bgcolor='#0000ff' height='5'></td> 
    <td width='50'bgcolor='#0000ff' height='5'></td>
    <td width='50'bgcolor='#0000ff' height='5'></td>
  </tr>"; 
//nacÃ­tanie hodnÃ´t do pola
$i="0";
while($row = mysqli_fetch_assoc($result))
		{ 
		$i=$i+1;
			$id = $row['id'];
      $nazov = $row['nazov'];
			$vyrobca = $row['vyrobca'];
			$popis = $row['popis'];
			$farba = $row['farba'];
      $cena = $row['cena'];
      $kod = $row['kod'];
//výpis hodnôt
echo "<tr>
    <td width='100'bgcolor='#FFaaCC' height='32'> ".$kod."</b></td>
    <td width='100'bgcolor='#FFFFCC' height='32'> ".$cena." </b></td>
    <td width='100'bgcolor='#FFaaCC' height='32'> ".$nazov."</b></td>
    <td width='100'bgcolor='#FFFFCC' height='32'> ".$vyrobca."</b></td>
    <td width='150'bgcolor='#FFaaCC' height='32'> ".$popis." </b></td>
    <td width='50' bgcolor='#FFFFCC' height='32'> ".$farba."</b></td>
    <td width='50' color='00ff00' bgcolor='#FFaaCC' height='32'><b><a href='zmazanietov.php?k=".$id."'> x</b></a></td>
   </tr>    
  <tr>
    <td width='100'bgcolor='#000000' height='2'></td>
    <td width='100'bgcolor='#000000' height='2'></td> 
    <td width='100'bgcolor='#000000' height='2'></td>
    <td width='100'bgcolor='#000000' height='2'></td>
    <td width='150'bgcolor='#000000' height='2'></td> 
    <td width='50' bgcolor='#000000' height='2'></td>
    <td width='50' bgcolor='#000000' height='2'></td>
    </tr>";
  }
   echo "</table>";
echo "Pocet vyhovujucich zaznamov je: "; echo $i;
if ($i=="0"):
endif; 
?>

</body>
</html>
