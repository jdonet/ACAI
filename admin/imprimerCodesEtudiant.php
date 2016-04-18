<html>
    <head>
        <meta charset="UTF-8">
        <link href="../style.css" rel="stylesheet" media="all" type="text/css"> 
        <meta http-equiv="Content-Type" content="text/html; charset=ANSI" />
    </head>

    <body>
        <div id="contenu">
            <div id="prof">
                <?php
                require_once('authentification.php');
                require_once('../connexion.php');
                $connexion = mysqli_connect($hote, $login, $pass, $base);




//reinit choix classe
                if (isset($_POST['choixClasse'])) {
                    unset($_POST['idClasse']);
                }

                if (isset($_GET['supp'])) {
                    $req = "delete from interro_etudiant where idEtudiant='" . $_GET['etudiant'] . "';";
                    $result = mysqli_query($connexion, $req) or die('Erreur SQL !<br>' . $req . '<br>' . mysqli_error());
                    $_POST['idClasse'] = $_GET['classe'];
                }
//supp classe
                if (isset($_POST['effacerClasse'])) {
                    $req = "delete from interro_classe where idClasse='" . $_POST['effacerClasse'] . "';";
                    $result = mysqli_query($connexion, $req) or die('Erreur SQL !<br>' . $req . '<br>' . mysqli_error());
                }

//ajout classe
                if (isset($_POST['AjoutClasse'])) {
                    $req = "insert into interro_classe(nomClasse,mail_membres) values ('" . $_POST['AjoutClasse'] . "','" . $_SERVER["PHP_AUTH_USER"] . "');";
                    $result = mysqli_query($connexion, $req) or die('Erreur SQL !<br>' . $req . '<br>' . mysqli_error());

                    $req = "select idClasse from interro_classe where nomClasse='" . $_POST['AjoutClasse'] . "';";
                    $result = mysqli_query($connexion, $req) or die('Erreur SQL !<br>' . $req . '<br>' . mysqli_error());
                    while ($ligne = mysqli_fetch_array($result)) {
                        $_POST['idClasse'] = $ligne['idClasse'];
                    }
                }

//ajout des eleves
                if (isset($_POST['nbEleves'])) {
                    //compter le nb etudiant dans la classe, pour ajouter a la suite
                    $req = "select * from interro_etudiant where refClasse='" . $_POST['idClasse'] . "'";
                    $result = mysqli_query($connexion, $req) or die('Erreur SQL !<br>' . $req . '<br>' . mysqli_error());
                    $num_rows = mysqli_num_rows($result);

                    for ($i = ($num_rows + 1); $i <= ($num_rows + $_POST['nbEleves']); $i++) {
                        $req = "insert into interro_etudiant(idEtudiant,codeCorrection,refClasse) values ('','" . md5(microtime(TRUE) * 100000) . "','" . $_POST['idClasse'] . "');";
                        $result = mysqli_query($connexion, $req) or die('Erreur SQL !<br>' . $req . '<br>' . mysqli_error());
                    }
                }

                if (!isset($_POST['idClasse'])) {
                    //choix classe - liste deroulante
                    ?>
                    <h2>Selectionner une classe</h2>
                    <form method="POST" action="imprimerCodesEtudiant.php">
                        <select name="idClasse">
                            <?php
                            $req = "select * from interro_classe where mail_membres='" . $_SERVER["PHP_AUTH_USER"] . "'";
                            $result = mysqli_query($connexion, $req) or die('Erreur SQL !<br>' . $req . '<br>' . mysqli_error());
                            $num_rows = mysqli_num_rows($result);

                            if ($num_rows > 0) {
                                while ($ligne = mysqli_fetch_array($result)) {
                                    echo "<option value='" . $ligne["idClasse"] . "'>" . $ligne["nomClasse"] . "</option>";
                                }
                            } else
                                echo "Merci de créer vos classes avant";
                            ?>

                        </select>
                        <br/>
                        <input type="submit" value="Voir les élèves de la classe"/>
                    </form>
                    <br/><br/><br/>
                    <h2>Ajouter une nouvelle classe</h2>
                    <form method="POST" action="imprimerCodesEtudiant.php">
                        Nom classe <input type="text" name="AjoutClasse"/>
                        <input type="submit" value="Creer classe"/>
                    </form>
                    <?php
                }else {
                    $req = "select * from interro_classe where idClasse='" . $_POST['idClasse'] . "';";
                    $result = mysqli_query($connexion, $req) or die('Erreur SQL !<br>' . $req . '<br>' . mysqli_error());
                    $num_rows = mysqli_num_rows($result);
                    while ($ligne = mysqli_fetch_array($result)) {
                        $classe = $ligne["nomClasse"];
                    }
                    echo "<h2>Eleves de la classe " . $classe . "</h2>";
                    //recup la derniere interro pour connaitre le nombre de questions
                    $req = "select * from interro_etudiant where refClasse='" . $_POST['idClasse'] . "' and idEtudiant<>0;";
                    $result = mysqli_query($connexion, $req) or die('Erreur SQL !<br>' . $req . '<br>' . mysqli_error());
                    $num_rows = mysqli_num_rows($result);
                    echo "<table border=1>";
                    echo "<tr><td>login</td><td>code</td><td>code</td></tr>";
                    while ($ligne = mysqli_fetch_array($result)) {
                        echo "<tr>";
                        echo "<td>" . $ligne["idEtudiant"] . "</td><td>" . $ligne["codeCorrection"] . "</td><td>" . $ligne["codeCorrection"] . "</td><td><a href='imprimerCodesEtudiant.php?supp=yes&etudiant=" . $ligne["idEtudiant"] . "&classe=" . $ligne["refClasse"] . "'>Suppression</a></td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                    ?>
                    <h3>Ajouter des eleves</h3>
                    <form method="POST" action="imprimerCodesEtudiant.php">
                        Nombre d'eleves a ajouter
                        <select name="nbEleves">
    <?php
    for ($i = 1; $i <= 50; $i++) {
        echo "<option value=" . $i . ">" . $i . "</option>";
    }
    ?>
                        </select>
                        <input type="hidden" name='idClasse' value="<?php echo $_POST['idClasse']; ?>"/>
                        <input type="submit" value="ajouter"/>
                    </form>
                    <h3>Supprimer classe</h3>
                    <form method="POST" action="imprimerCodesEtudiant.php" name="myform">
                        <input type="hidden" name='effacerClasse' value="<?php echo $_POST['idClasse']; ?>"/>
                        <a href="#" onclick="if (confirm('Voulez-vous supprimer cette classe ?')) {
                                document.myform.submit();
                            }"><img width="80px;" src="../img/supp.png" alt="Supprimer classe"/></a>	
                    </form>
                    <br/><br/>
                    <form method="POST" action="imprimerCodesEtudiant.php">
                        <input type="hidden" name='choixClasse' value="retour"/>
                        <input type="submit" value="Retour choix classe"/>
                    </form>

    <?php
}
?>
                <br/>
                <br/>
                <br/>

                <a href="../index.php" ><img src="../img/eleve.png" alt="Retour Accueil"/></a>&nbsp;&nbsp;&nbsp;&nbsp;
                <a href="admin.php" ><img src="../img/prof.png" alt="Menu prof"/></a>

            </div>
        </div>
    </body>
</html>
