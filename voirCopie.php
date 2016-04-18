<html>
    <head>
        <meta charset="UTF-8">
        <link href="style.css" rel="stylesheet" media="all" type="text/css"> 
        <meta http-equiv="Content-Type" content="text/html; charset=ANSI" />
    </head>

    <body>
        <div id="contenu">
            <div id="eleve">
                <?php
                require_once('connexion.php');
                $connexion = mysqli_connect($hote, $login, $pass, $base);


                if (isset($_GET['codeCorrection'])) {
                    $codeCorrection = $_GET['codeCorrection'];
                    $interro = $_GET['idInterro'];
                }
                if (isset($_POST['codeCorrection'])) {
                    $codeCorrection = $_POST['codeCorrection'];
                    $req = "select max(idInterro) total from interro_interro i, interro_classe c, interro_etudiant where refClasse=idClasse and codeCorrection='" . $codeCorrection . "' and i.mail_membres =c.mail_membres";
                    $result = mysqli_query($connexion, $req) or die('Erreur SQL !<br>' . $req . '<br>' . mysqli_error());
                    $num_rows = mysqli_num_rows($result);
                    while ($ligne = mysqli_fetch_array($result)) {
                        $interro = $ligne["total"];
                    }
                }

                if (!isset($codeCorrection)) {
                    //formulaire connection
                    ?>
                    <br/>	<br/>
                    <form method="POST">

                        code Copie<input type="text" name="codeCorrection"/>
                        <br/>
                        <input type="submit"/>
                    </form>
                    <?php
                } else {
                    //verif connection
                    $req = "select * from interro_etudiant  where codeCorrection='" . $codeCorrection . "'";
                    $result = mysqli_query($connexion, $req) or die('Erreur SQL !<br>' . $req . '<br>' . mysqli_error());
                    $mysqli_result = mysqli_query($connexion, $req);

                    $num_rows = mysqli_num_rows($result);
                    if ($num_rows > 0) {
                        while ($ligne = mysqli_fetch_array($result)) {
                            //recup refEtudiant
                            $refEtudiant = $ligne["idEtudiant"];

                            //calcul note
                            $req = "select sum(points) total, sum(bareme) bareme, dateInterro,nomEtudiant from interro_reponse, interro_etudiant, interro_interro where idinterro=refInterro and idinterro='" . $interro . "' and idEtudiant=refEtudiant and refEtudiant=" . $refEtudiant . " GROUP BY idinterro";
                            $result = mysqli_query($connexion, $req) or die('Erreur SQL !<br>' . $req . '<br>' . mysqli_error());
                            $num_rows = mysqli_num_rows($result);
                            while ($ligne = mysqli_fetch_array($result)) {
                                echo "<h1>" . $ligne["nomEtudiant"] . "</h1><br/><h2>Interro du " . $ligne["dateInterro"] . " - Note " . $ligne["total"] . "/" . $ligne["bareme"] . "</h2><br/> ";
                            }


                            $req = "select * from interro_reponse where refInterro= '" . $interro . "' and refEtudiant=" . $refEtudiant;
                            $result = mysqli_query($connexion, $req) or die('Erreur SQL !<br>' . $req . '<br>' . mysqli_error());
                            $num_rows = mysqli_num_rows($result);
                            ?>



                            <input type="hidden" name="refEtudiant" value="<?php echo $refEtudiant; ?>"/>
                            <table width="1000">			
                                <?php
                                while ($ligne = mysqli_fetch_array($result)) {
                                    ?>
                                    <tr><td><b>
                                                <?php
                                                echo "Question " . ($ligne["idQuestion"] + 1);
                                                ?>
                                            </B>
                                        </td>
                                    </tr>
                                    <tr><td>
                                            <?php echo $ligne["reponse"] ?> </br>
                                            Observation : <?php echo $ligne["observation"] ?>
                                        </TD>
                                        <td width=100>
                                            <?php echo $ligne["points"] . "/" . $ligne["bareme"]; ?> points
                                    <tr><td>&nbsp;</TD></TR>
                                    </TD>
                                    </TR>
                                    <?php
                                }
                                ?>
                            </table>





                            <?php
                        }
                    } else {

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
