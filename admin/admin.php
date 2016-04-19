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
                ?>
                <h1>Application interro en autocorrection - Interface prof</h1>

                <h2><a href="gestionInterro.php" >Gestion des interros </a></h2>

                <h2><a href="imprimerCodesEtudiant.php" >Gestion des classes et etudiants </a></h2>
                
                <?php // si on fait un hébergement local 
                if (substr($_SERVER[ 'SERVER_ADDR'], 0, 3) == "127" || substr($_SERVER[ 'SERVER_ADDR'], 0, 3) == "10" || substr($_SERVER[ 'SERVER_ADDR'], 0, 7) == "192.168" || substr($_SERVER[ 'SERVER_ADDR'], 0, 7) == "172.16"){ ?>

                <?php if (substr($_SERVER[ 'SERVER_ADDR'], 0, 3) == "127"){ ?>
                <h3>Adresse à communiquer aux élèves <a href="http://www.mon-ip.com/adresse-ip-locale.php" target="blank" >sur ce site  </a></h3>
                <?php } else { ?>
                <h3>URL à communiquer aux élèves : <a href="http://<?php echo $_SERVER[ 'SERVER_ADDR']; ?>" >http://<?php echo $_SERVER[ 'SERVER_ADDR']; ?>  </h3>
                <?php } ?>          
                <?php } ?>          
                <a href="../index.php" ><img src="../img/eleve.png" alt="Retour Accueil"/></a>


            </div>
        </div>
    </body>
</html>
