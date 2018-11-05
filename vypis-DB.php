<?php
include ("config.php");
echo "<table border='0' cellpadding='0' cellspacing='0' style='border-collapse: collapse' bordercolor='#111111' width='700'>";
$var = mysqli_connect("$localhost","$user","$password","$db") or die ("connect error");
//mysql_select_db ("zaklad") or die ("nepodarilo sa otvorit databazu");

// z DB z tabulky osoba vyberieme meno,priezvisko,titul,rc
$sql = "select meno,priezvisko,titul,rc from osoba";
$result = mysqli_query($var, $sql) or exit ("chybny dotaz");
//načítanie hodnôt do pola
while($row = mysqli_fetch_assoc($result))
		{ 
			$meno = $row['meno'];
			$priezvisko = $row['priezvisko'];
			$titul = $row['titul'];
      $rc = $row['rc'];

//výpis hodnôt
echo "<tr>
    <td width='49'bgcolor='#FFffCC' height='32'></td>
    <td width='184'bgcolor='#FFffCC' height='32'><b> ".$titul."</b></td>
    <td width='240'bgcolor='#FFffCC' height='32'></td>
    <td width='247'bgcolor='#FFffCC' height='32'></td>
    <td width='6'bgcolor='#FFffCC' height='32'></td>
  </tr>
  <tr>
    <td width='49'bgcolor='#FFFFff' height='52'></td>
    <td width='184'colspan='2'bgcolor='#FFFFff' height='52'>meno<b> ".$meno."</b></td>
    <td width='240'bgcolor='#FFFFff' height='52'>priezvisko <b>".$priezvisko." </b></td> 
    <td width='247'bgcolor='#FFFFff' height='52'>rodne cislo <b>".$rc."</b></td>
    <td width='6'colspan='2'bgcolor='#FFFFff' height='52'></td>
  </tr>";
  }
echo "</table>";  
?>

