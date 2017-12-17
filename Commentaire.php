<?php
session_start();
require_once('libs/Smarty.class.php');
require_once 'fonctions.inc.php';
require_once 'config/Init.conf.php';
require_once 'config/bdd.conf.php';
require_once 'config/connexion.inc.php';
include './includes/header.inc.php';

if (isset($_POST['submitcom'])) {



    //On regarde si les champs sont vide
//    if (!empty($_POST['pseudo']) AND ! empty($_POST['mail']) AND ! empty($_POST['Commentaire']) ) {


    $insert = "INSERT INTO commentaire (pseudo, mail, texte, id_art)"
            . "VALUES (:pseudo, :mail, :texte, :id_art)";

    $sth = $bdd->prepare($insert);
    $sth->bindValue(':pseudo', $_POST['pseudo'], PDO::PARAM_STR);
    $sth->bindValue(':mail', $_POST['mail'], PDO::PARAM_STR);
    $sth->bindValue(':texte', $_POST['Commentaire'], PDO::PARAM_STR);
    $sth->bindValue(':id_art', $_GET["id_article"], PDO::PARAM_INT);
    // }
    if ($sth->execute() == TRUE) {
        echo "Votre commentaire est inséré";
    } else {
        echo "Une erreur est survenue";
    }
} else {








    $select = "SELECT id, "
            . "titre, "
            . "texte, "
            . "DATE_FORMAT(date, '%d/%m/%Y') as date_fr "
            . "FROM articles "
            . "WHERE id = :id ";
}
//Afficher la requete
echo $select;


// Preparer la requete SQL
$req = $bdd->prepare($select);
// Securiser les donnees, attribuer la valeur a publie et definir son type
$req->bindValue(':id', $_GET['id_article'], PDO::PARAM_INT);

if ($req->execute() == TRUE) {
    // Attribuer les valeurs recupere dans la requete dans un tableau
    $tab_articles = $req->fetchAll(PDO::FETCH_ASSOC);
} else {
    // Afficher le message
    echo 'Une erreur est survenue...';
}
?>
<br>
<br>
<br>
<br>

<?php
//On affecte le tableau à la variable value jusqu'a qu'il soit vide
foreach ($tab_articles as $value) {
    ?>


    <div class="container">

        <div class="col-lg-3 col-lg-offset-1">
            <div class="card-warning border border-dark" style="width: 20rem;">
                <img class="card-img-top" style="height: 12rem;" src="img/<?php echo $value['id'] ?>.jpg" alt="Card image cap">
                <div class="card-body">

                    <h2 style="color:#FFFFFF" class="card-title">  <!-- Ici on ajout une variable PHP qui est le titre de l'article dans la carte -->
    <?php
    echo $value['titre'] . '<br/>';
    ?></h2>

                    <p class="card-text">     <h5 style="color:#FFFFFF">   <?php
                    //Pareil pour le texte
                    echo $value['texte'];
    ?></p>
                    </h5>

                    <a href="#" class="btn btn-info"> Crée le <?php echo $value['date_fr'] ?></a>

                </div>
                <h6 style="color:#FFFFFF" class="card-title">
                    <div class="col-lg-6">
                        <form action="Commentaire.php?action=commentaire&id_article=<?= $_GET['id_article'] ?>" method="post" enctype="multipart/form-data" id="form_commentaire">
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label for="inputpseudo" name="pseudo" class="col-form-label">Pseudo</label>
                                    <input type="text" class="form-control" id="inputpseudo" name="pseudo"  placeholder="Pseudo" required>
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="inputmail" name="mail" class="col-form-label">Mail</label>
                                    <input type="text" class="form-control" id="inputmail" name="mail"  placeholder="Mail" required>
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="inputcommentaire" name="Commentaire" class="col-form-label">Commentaire</label>
                                    <input type="text-area" class="form-control" id="inputcommentaire" name="Commentaire"  placeholder="Commentaire" required>
                                </div>



                            </div>
                            <button type="submit" name="submitcom" class="btn btn-primary">Soumettre le commentaire</button>
                        </form>
                    </div>
                </h6>
            </div>
            <div class="col-md-12">

            </div> 
        </div>
    </div
    </div>
<?php }; ?>
<h4 style="color:#FFFFFF"> Commentaires :</h4>

<?php
// Récupération des commentaires
$req = $bdd->prepare('SELECT pseudo, texte, id, mail FROM commentaire WHERE id_art = :id_article ');

$req->bindValue(':id_article', $_GET['id_article'], PDO::PARAM_INT);
if ($req->execute() == TRUE) {

    // Attribuer les valeurs recupere dans la requete dans un tableau
    $tab_commentaire = $req->fetchAll(PDO::FETCH_ASSOC);
} else {
    // Afficher le message
    echo 'Une erreur est survenue...';
    $notification = "Aucun article ne correspond a votre recherche...";
    $_SESSION['notification_result'] = FALSE;
}

foreach ($tab_commentaire as $commentaire) {
    ?>


    <h6 style="color:#FFFFFF"> 
        Pseudo : <?php echo $commentaire['pseudo'] ?> <br>
        Mail : <?php echo $commentaire['mail'] ?> <br>
        Texte : <?php echo $commentaire['texte'] ?> <br>









    </h6>







    <?php
} // Fin de la boucle des commentaires  */
?>