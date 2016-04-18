<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ANSI" />
<link href="style.css" rel="stylesheet" media="all" type="text/css"> 
<meta http-equiv="Content-Type" content="text/html; charset=ANSI" />
</head>

<body>
<div id="contenu">
<div id="eleve">
<?php
$connexion = mysqli_connect($hote,$login,$pass,$base);

			
$req = "select sum(points) total, nomEtudiant from interro_reponse,interro_etudiant,interro_interro where idinterro=refInterro and idinterro=(select max(idInterro) from interro_interro) and idEtudiant=refEtudiant and idEtudiant<>0 group by nomEtudiant order by nomEtudiant";
$result = mysqli_query($connexion,$req) or die('Erreur SQL !<br>'.$req.'<br>'.mysqli_error()); 
$num_rows = mysqli_num_rows($result);

echo "<table border=1>";
$total=0;
$cpt=0;
while ($ligne = mysqli_fetch_array($result)){	
	?>
	<tr><td>
	<?php echo $ligne["nomEtudiant"]."</TD><td>".$ligne["total"];?>
	</td></tr>
	<?php
	$total=$total+$ligne["total"];
	$cpt=$cpt+1;	
} 
?>
</table>
<BR/>
Moyenne : <?php
	echo ($total/$cpt);
?>

<br/><br/>
<a href="index.php" >accueil </a><br/>
<a href="admin.php" >admin </a><br/>
</div>
</div>
</body>
</html>
