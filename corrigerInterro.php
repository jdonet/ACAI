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
require("connexion.php");
$connexion = mysqli_connect($hote,$login,$pass,$base);

if (!isset($_POST['codeCorrection'])){
	//formulaire connection
	?>
				<br/>	<br/>
	<form method="POST">

	code Copie<input type="text" name="codeCorrection"/>
	<br/>
	<input type="submit"/>
	</form>
	<?php
}else {
	//verif connection
	$req = "select * from interro_etudiant,interro_classe where refClasse=idClasse and codeCorrection='".$_POST['codeCorrection']."'";
	$result = mysqli_query($connexion,$req) or die('Erreur SQL !<br>'.$req.'<br>'.mysqli_error()); 
	$mysqli_result = mysqli_query($connexion,$req);

	$num_rows = mysqli_num_rows($result);
	if ($num_rows>0){
		while ($ligne = mysqli_fetch_array($result)){
			//recup refEtudiant
			$refEtudiant = $ligne["idEtudiant"] ;
			$mail = $ligne["mail_membres"] ;
			
			//recup des réponses de l'élève à la derniere interro de la classe pour connaitre le nombre de questions
			$req = "select * from interro_reponse where refInterro= (select max(idInterro) from interro_interro i, interro_classe c, interro_etudiant where c.mail_membres = i.mail_membres and refClasse=idClasse and idEtudiant=".$refEtudiant.") and refEtudiant=".$refEtudiant;
			$result = mysqli_query($connexion,$req) or die('Erreur SQL !<br>'.$req.'<br>'.mysqli_error()); 
			$num_rows = mysqli_num_rows($result);
			
			?>

			<form method="POST" action="index.php">
			<input type="hidden" name="refEtudiant" value="<?php echo $refEtudiant;?>"/>
			<input type="hidden" name="mail_membres" value="<?php echo $mail;?>"/>
				<?php
				while ($ligne = mysqli_fetch_array($result)){
					echo "<b>Question ".($ligne["idQuestion"]+1);
					?></b>
					<br/>
					<TEXTAREA cols="100" rows="10" name="reponse"><?php echo $ligne["reponse"]?></TEXTAREA>
					<!--<input type="text" size=1 name="<?php echo'noteQ'.$ligne["idQuestion"]?>" value="<?php echo $ligne["points"]?>"/> /<?php echo $ligne["bareme"];?> pts<br/>-->
					<br/>
					Note proposée <select name="<?php echo'noteQ'.$ligne["idQuestion"]?>">
						<?php $i=0;
						while($i<=$ligne["bareme"]){
							?>
							<option value="<?php echo $i;?>"><?php echo $i;?></option>
							<?php
							$i=$i+0.5;
						}?>
					</select><br/>
					<?php echo "Observation question ".($ligne["idQuestion"]+1);?><br/> 
					<input type="text" size=131 name="<?php echo'observation'.$ligne["idQuestion"]?>" value="<?php echo $ligne["observation"]?>"/>
					
					
					<br/><br/>	
					<?php
				}  
				?>
			<br/><br/>	
			<input type="submit" value=" valider correction"/>
			</form>
			<?php 
			  
		 }
		  
	}else{
 
		 //formulaire connection
		?>
		
			<br/>	<br/>
	<form method="POST">

	code Copie<input type="text" name="codeCorrection"/>
	<br/>
	<input type="submit"/>
	</form>
		<?php
	 }
}

 ?>

<form method="POST" action="javascript:history.back()">
<input type="submit" value="retour"/>
</form>
</div></div>
</body>
</html>
