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
 require_once('connexion.php');
$connexion = mysqli_connect($hote,$login,$pass,$base);



if (!isset($_POST['loginEtudiant'])){
	//formulaire connection
	?>
	<form method="POST">
	<h2>Informations de connexion</h2>
	login <input type="text" name="loginEtudiant" placeholder="ex : 1"/>
	<br/>
	code <input type="text" name="codeCorrection" placeholder="ex : G20r52"/>
	<br/>
	<input type="submit" value="Connecter"/>

	</form>
	<?php

}else {

	//verif connection
	$req = "select * from interro_etudiant,interro_classe where refClasse=idClasse and idEtudiant='".$_POST['loginEtudiant']."' and codeCorrection='".$_POST['codeCorrection']."'";
	$result = mysqli_query($connexion,$req) or die('Erreur SQL !<br>'.$req.'<br>'.mysqli_error()); 
	$mysqli_result = mysqli_query($connexion,$req);

	$num_rows = mysqli_num_rows($result);
	if ($num_rows>0){

		while ($ligne = mysqli_fetch_array($result)){
			//recup refEtudiant
			$refEtudiant = $ligne["idEtudiant"] ;
			$mail = $ligne["mail_membres"] ;
			
			//recup la derniere interro de la classe pour connaitre le nombre de questions
			$req = "select * from interro_interro where mail_membres = '".$mail ."' and idInterro= (select max(idInterro) from interro_interro where mail_membres = '".$mail."')";
			$result = mysqli_query($connexion,$req) or die('Erreur SQL !<br>'.$req.'<br>'.mysqli_error()); 
			$num_rows = mysqli_num_rows($result);
			while ($ligne = mysqli_fetch_array($result))
			{
			  $idInterro = $ligne["idInterro"] ;
			  // recup nbQuestions
			  $nbQuestions = $ligne["nbQuestions"] ;
			}
			
			$req2 = "select * from interro_reponse where refEtudiant=".$refEtudiant." and refInterro=".$idInterro;
			$result2 = mysqli_query($connexion,$req2) or die('Erreur SQL !<br>'.$req2.'<br>'.mysqli_error()); 
			$num_rows2 = mysqli_num_rows($result2);
			if ($num_rows2>0)
			{
				echo "<h2>Vous avez déjà rendu votre copie pour cette interro</h2>"; 
				$i=0;
				$passe="";
				?>	
				
				<form method="POST" action="index.php">
				
				<input type="hidden" name="refEtudiant" value="<?php echo $refEtudiant;?>"/>
				<input type="hidden" name="mail_membres" value="<?php echo $mail;?>"/>
				<input type="hidden" name="refaire" value="oui"/>
	
				<?php
				while ($ligne2 = mysqli_fetch_array($result2)){
					//S'il a le droit de continuer son interro, on lui remet ses réponses
					if ($ligne2['refaire']==1)
					{
							echo "Question ".($i+1);
							
							?>
							<TEXTAREA cols="60" rows="5" name="<?php echo'Q'.$i?>"><?php echo $ligne2['reponse']; ?></TEXTAREA>
							<br/><br/>
						
						<?php
						$i=$i+1;
						$passe='oui';
					}
				}
				if ($passe=='oui'){?>
					<br/><br/>	
					<input type="submit" value="rendre mon interro"/>
				<?php	
				}
				?>
			</form>
			<?php	
				
			}			
			else
			{
			?>
			<form method="POST" action="index.php">
			<h2>Faire mon interro</h2>
			<input type="hidden" name="refEtudiant" value="<?php echo $refEtudiant;?>"/>
			<input type="hidden" name="mail_membres" value="<?php echo $mail;?>"/>
			<?php
			for ($i=0;$i<$nbQuestions;$i++){
				echo "Question ".($i+1);
				?>
				<TEXTAREA cols="60" rows="5" name="<?php echo'Q'.$i?>"></TEXTAREA>
				<br/><br/>
				<?php
			}  
			?>
			<br/><br/>	
			<input type="submit" value="rendre mon interro"/>
			</form>
			<?php   
			}
		 }
	 }else{
 
	 //formulaire connection
	?>
<form method="POST">
<h2>Informations de connexion</h2>
	login <input type="text" name="loginEtudiant" placeholder="ex : 1"/>
	<br/>
	code <input type="text" name="codeCorrection" placeholder="ex : G20r52"/>
	<br/>
	<input type="submit" value="Connecter"/>
	</form>
	<?php
	 }
}

?>
<form method="POST" action="javascript:history.back()">
<input type="submit" value="retour"/>
</form>
</div>
</div>
</body>
</html>
