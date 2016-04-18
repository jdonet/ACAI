<?php

require('../connexion.php');
$connexion = mysqli_connect($hote, $login, $pass, $base);

/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}
mysqli_query("set names 'utf8'");
if (!isset($_SERVER['PHP_AUTH_USER'])) {
    header('WWW-Authenticate: Basic realm="Connexion"');
    header('HTTP/1.0 401 Unauthorized');
    exit;
} else {
    //verif pass
    $req = "SELECT * FROM interro_membres WHERE membre_mail = '" . $_SERVER['PHP_AUTH_USER'] . "' and membre_mdp = '" . $_SERVER['PHP_AUTH_PW'] . "'";
    if ($result = mysqli_query($connexion, $req)) {
        echo "Connecté en tant que " . $_SERVER["PHP_AUTH_USER"];
    }


    //Si la requête a un résultat (c'est-à-dire si l'id existe dans la table membres)
    if (!mysqli_num_rows($result) > 0) {
        echo "<p>Authentification incorrecte </p>";
        header('WWW-Authenticate: Basic realm="Authentification incorrecte"');
        header('HTTP/1.0 401 Unauthorized');
        echo 'Authentification incorrecte';
    }
}
?>
