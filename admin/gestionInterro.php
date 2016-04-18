<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ANSI" />
<link href="../style.css" rel="stylesheet" media="all" type="text/css"> 
<meta http-equiv="Content-Type" content="text/html; charset=ANSI" />
</head>

<body>
<div id="contenu">
<div id="prof">
<?php
 require_once('authentification.php');
 require_once('../connexion.php');
$connexion = mysqli_connect($hote,$login,$pass,$base);

//verifie si nouveaux baremes envoyes (copie bareme)
if (isset($_POST['baremeQ0']) && $_POST['baremeQ0']<>""){
	//recup la derniere interro pour connaitre le nombre de questions
	$req = "select * from interro_interro where idInterro= (select max(idInterro) from interro_interro where mail_membres = '".$_SERVER["PHP_AUTH_USER"]."')";
	$result = mysqli_query($connexion,$req) or die('Erreur SQL !<br>'.$req.'<br>'.mysqli_error()); 
	$num_rows = mysqli_num_rows($result);
	while ($ligne = mysqli_fetch_array($result))
	{
	  // recup nbQuestions
	  $nbQuestions = $ligne["nbQuestions"] ;
	  $idInterro = $ligne["idInterro"] ;
	}

	for ($i=0;$i<$nbQuestions;$i++){
		//insertion reponses
		$req = "insert into interro_reponse values('',".$idInterro.",".$i.
	",'0','','',".$_POST['baremeQ'.$i].",'','0')";
		mysqli_query($connexion,$req) or die('Erreur SQL !<br>'.$req.'<br>'.mysqli_error()); 
	}  
	echo "Interro enregistree";	
}  
//suppression interro
if (isset($_GET['suppInterro'])){
	//effacer réponses
	$req = "delete from interro_reponse where refInterro='".$_GET['suppInterro']."'";
	$result = mysqli_query($connexion,$req) or die('Erreur SQL !<br>'.$req.'<br>'.mysqli_error()); 
	$mysqli_result = mysqli_query($connexion,$req);
	//effacer interro
	$req = "delete from interro_interro where idInterro='".$_GET['suppInterro']."'";
	$result = mysqli_query($connexion,$req) or die('Erreur SQL !<br>'.$req.'<br>'.mysqli_error()); 
	$mysqli_result = mysqli_query($connexion,$req);
}

//suppression copie
if (isset($_GET['supprimer'])){
	//effacer réponses
	$req = "delete from interro_reponse where refInterro='".$_GET['idInterro']."' and refEtudiant='".$_GET['idEtudiant']."'";
	$result = mysqli_query($connexion,$req) or die('Erreur SQL !<br>'.$req.'<br>'.mysqli_error()); 
	$mysqli_result = mysqli_query($connexion,$req);
}
//Refaire copie
if (isset($_GET['refaire'])){
		/*$req = "select * from interro_reponse where refInterro=".$_GET['idInterro']." and refEtudiant=".$_GET['idEtudiant'];
		$result = mysqli_query($connexion,$req) or die('Erreur SQL !<br>'.$req.'<br>'.mysqli_error()); 
		while ($ligne = mysqli_fetch_array($result)){
			
		}
*/
$req2 = "update interro_reponse set refaire=1 where refInterro='".$_GET['idInterro']."' and refEtudiant='".$_GET['idEtudiant']."'";
			$result2 = mysqli_query($connexion,$req2) or die('Erreur SQL !<br>'.$req.'<br>'.mysqli_error()); 
}




if (isset($_POST['nbQuestions'])){

	//Insertion interro
	$req = "insert into interro_interro values ('','".$_POST['dateInterro']."', '".$_POST['nbQuestions']."','".$_SERVER["PHP_AUTH_USER"]."')";
	$result = mysqli_query($connexion,$req) or die('Erreur SQL !<br>'.$req.'<br>'.mysqli_error()); 
?>
<h2> Ajouter une interro</h2>
	De préférence, faites en sorte d'avoir un total sur 20<br/>	
	<form method="POST" action="gestionInterro.php">
		<?php
		for ($i=0;$i<$_POST['nbQuestions'];$i++){
			echo "bareme question ".($i+1);
			?>
			<input type="text" name="<?php echo'baremeQ'.$i?>"/>
			<br/><br/>
			<?php
		}   
		?>
	<br/><br/>	
	<input type="submit" value="saisir Interro"/>
	</form>
	<?php   
}else{
 /// Affichage de l'interro

	if (isset($_POST['idInterro'])){
		$req = "select dateInterro from interro_interro where idinterro=".$_POST['idInterro'];
		$result = mysqli_query($connexion,$req) or die('Erreur SQL !<br>'.$req.'<br>'.mysqli_error()); 
		while ($ligne = mysqli_fetch_array($result)){	
			$dateInterro= $ligne["dateInterro"];
		} 
		?>		
		<h2> Interro du <?php echo $dateInterro; ?></h2>
				
		<?php			
		$req = "select sum(points) total, idEtudiant,codeCorrection,refaire from interro_reponse,interro_etudiant,interro_interro where idinterro=refInterro and idinterro=".$_POST['idInterro']." and idEtudiant=refEtudiant and idEtudiant<>0 group by idEtudiant order by idEtudiant";
		$result = mysqli_query($connexion,$req) or die('Erreur SQL !<br>'.$req.'<br>'.mysqli_error()); 
		$num_rows = mysqli_num_rows($result);
		?>
		<table border=1>
			<tr>
				<td>id Etudiant</TD>
				<td>Note</td>
				<td>Copie</td>
				<td>Poursuivre son interro?</td>
				<td>Supprimer copie</td>
			</tr>
		<?php	
		$total=0;
		$cpt=0;
		while ($ligne = mysqli_fetch_array($result)){	
			?>
			<tr><td>
			<?php  echo $ligne["idEtudiant"]."</td><td>".$ligne["total"]."</td>
			<td><a href='../voirCopie.php?codeCorrection=".$ligne["codeCorrection"]."&idInterro=".$_POST["idInterro"]."'>voir</a>
			<td>";
			if ($ligne["refaire"]!=1){
				echo "<a href='gestionInterro.php?refaire=oui&idEtudiant=".$ligne["idEtudiant"]."&idInterro=".$_POST["idInterro"]."'>Refaire</a>";
			}
			echo "</td><td><a href='gestionInterro.php?supprimer=oui&idEtudiant=".$ligne["idEtudiant"]."&idInterro=".$_POST["idInterro"]."'>Supprimer</a>";?>
			</td></tr>
			<?php
			$total=$total+$ligne["total"];
			$cpt=$cpt+1;	
		} 
		?>
		</table>
		<br/>
		<h4>Moyenne : <?php
			if($cpt==0) $cpt=1;
			echo round($total/$cpt, 2); 
			
			?></h4>
			<form method="POST" action="gestionInterro.php">
				
			<input type="submit" value="Retour"/>
		</form>
		<h2> Supprimer interro</h2>
		<a href="#" onclick="if (confirm('Voulez-vous supprimer cette interro ?')) { document.location.href='gestionInterro.php?suppInterro=<?php echo $_POST['idInterro'];?>';}"><img width="80px;" src="../img/supp.png" alt="Supprimer interro"/></a>
<br/><br/>
		<?php
	}else{
	?>
	<h2>Selectionner une interro</h2>
		<form method="POST" action="gestionInterro.php">
			<select name="idInterro">
			<?php
			echo $req = "select * from interro_interro where mail_membres='".$_SERVER["PHP_AUTH_USER"]."'";
			$result = mysqli_query($connexion,$req) or die('Erreur SQL !<br>'.$req.'<br>'.mysqli_error()); 
			$num_rows = mysqli_num_rows($result);
			
			while ($ligne = mysqli_fetch_array($result)){
				echo "<option value='".$ligne["idInterro"]."'>".$ligne["dateInterro"]."</option>";
			 }
			?>
			
			</select>		
			<input type="submit" value="Voir cette interro"/>
		</form>

	<h2>Ajouter une nouvelle interro</h2>
		<form method="POST">
		Nombre de questions <input type="text" name="nbQuestions"/>
		<br/>
		Date de l'interro <input type="text" name="dateInterro" value="<?php echo  date("Y-m-d");?>"/>
		<br/>
		<input type="submit" value="Inserer"/>
		</form>
	<?php
	}
}
?>
Attention : Les élèves ne peuvent composer que sur votre DERNIERE interro créée (toutes classes confondues)

<br/>
<br/>
<br/>

<a href="../index.php" ><img src="../img/eleve.png" alt="Retour Accueil"/></a>&nbsp;&nbsp;&nbsp;&nbsp;
<a href="admin.php" ><img src="../img/prof.png" alt="Menu prof"/></a>

</div>
</div>
</body>
</html>
