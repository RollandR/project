
<?php
require_once 'bdd.conf.php';


$is_connect = FALSE;
//On regarde si le sid est différent de vide
if (!empty($_COOKIE['sid'])) {
    //On séléctionne sid dans la base utilisateur
    $verif_sid = "SELECT sid, "
            . "nom, "
            . "prenom "
            . "FROM utilisateurs "
            . "WHERE sid = :sid ";

// On prépare $verif_sid
    $sth = $bdd->prepare($verif_sid);
    $sth->bindValue(':sid', $_COOKIE['sid'], PDO::PARAM_STR);
    //Si tout s'éxécute correctement alors ...
    if ($sth->execute() == TRUE) {
        //On compte le nombre de paramètre identique
        $count = $sth->rowCount();
        //Si rowCount est supérrieur à 1 on connecte l'utilisateur
        if ($count > 0) {
            $tab_result = $sth->fetch(PDO::FETCH_ASSOC);

            $is_connect = TRUE;
            ?>
            <div class="alert alert-info" role="alert">
                <?php echo "Vous êtes connecté en tant que " . $tab_result['nom'] . ' ' . $tab_result['prenom'] . ' !'; ?>
            </div>  
            <?php
        }
    }
};
?>
