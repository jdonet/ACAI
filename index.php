<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="Content-Type" content="text/html; charset=ANSI" />
        <link href="style.css" rel="stylesheet" media="all" type="text/css"> 
    </head>

    <body>
        <?php
        require("connexion.php");
        $connexion = mysqli_connect($hote, $login, $pass, $base);
        /* check connection */
        if (mysqli_connect_errno()) {
            printf("Connect failed: %s\n", mysqli_connect_error());
            exit();
        }


//verifie si nouvelles reponses envoyees pour inserer des reponses (nouvelle copie)
        if (isset($_POST['Q0']) && $_POST['Q0'] <> "") {

            //recup bareme, qui est la premiere reponse inseree pour la derniere interro
            $req = "select * from interro_reponse where refInterro= (select max(idInterro) from interro_interro where mail_membres='" . $_POST['mail_membres'] . "') and refEtudiant=0";
            $result = mysqli_query($connexion, $req) or die('Erreur SQL !<br>' . $req . '<br>' . mysqli_error());

            while ($ligne = mysqli_fetch_array($result)) {
                //Deuxième saisie - Update
                if (isset($_POST['refaire'])) {
                    $req2 = "update interro_reponse set reponse = '" . mysqli_real_escape_string($connexion, $_POST['Q' . $ligne["idQuestion"]]) . "' 
					where idQuestion='" . $ligne["idQuestion"] . "' 
					and refEtudiant=" . $_POST['refEtudiant'] . "
					and refInterro = " . $ligne["refInterro"];
                    mysqli_query($connexion, $req2) or die('Erreur SQL !<br>' . $req2 . '<br>' . mysqli_error());
                } else { //première saisie - insert
                    //insertion reponses
                    $req2 = "insert into interro_reponse values(''," . $ligne["refInterro"] . "," . $ligne["idQuestion"] .
                            "," . $_POST['refEtudiant'] . ",'" . mysqli_real_escape_string($connexion, $_POST['Q' . $ligne["idQuestion"]]) . "',''," . $ligne["bareme"] . ",'','0')";
                    mysqli_query($connexion, $req2) or die('Erreur SQL !<br>' . $req2 . '<br>' . mysqli_error());
                }
            }
            echo "Copie inseree ou mise à jour";
        }


//verifie si nouvelles corrections envoyees pour maj des points (correction copie)
        if (isset($_POST['noteQ0']) && $_POST['noteQ0'] <> "") {
            //recup bareme, qui est la premiere reponse inseree pour la derniere interro
            $req = "select * from interro_reponse where refInterro= (select max(idInterro) from interro_interro where mail_membres='" . $_POST['mail_membres'] . "') and refEtudiant=0";
            $result = mysqli_query($connexion, $req) or die('Erreur SQL !<br>' . $req . '<br>' . mysqli_error());

            while ($ligne = mysqli_fetch_array($result)) {
                //insertion reponses
                $req2 = "update interro_reponse set points=" . $_POST['noteQ' . $ligne["idQuestion"]] . ", observation='" . mysqli_real_escape_string($connexion, $_POST['observation' . $ligne["idQuestion"]]) . "' 
		where refInterro=" . $ligne["refInterro"] . " and idQuestion=" . $ligne["idQuestion"] .
                        " and refEtudiant=" . $_POST['refEtudiant'];
                mysqli_query($connexion, $req2) or die('Erreur SQL !<br>' . $req2 . '<br>' . mysqli_error());
            }
            echo "Copie corrigée";
        }
        ?>
        <h1>ACAI : Application de Correction Anonyme d' Interro </h1>


        <div id="contenu">
            <div id="eleve">
                <h2>Interface Eleve</h2>
                <img src="img/eleve.png"/>
                <h3><a href="faireInterro.php" >1- Faire mon interro </a></h3>
                <h3><a href="corrigerInterro.php" >2- Corriger une interro </a></h3>
                <h3><a href="voirCopie.php" >3- Voir ma copie </a><br/></h3>
            </div>
            <div id="prof">

                <h2>Interface Prof</h2>
                <img src="img/prof.png"/><br/>
                <h3><a href="./admin/admin.php" >Connexion</a></h3>
                <h3><a href="img/deroulement.png" target="blank">Comment ca marche ? </a></h3>
            </div>
        </div>
    </body>
</html>
