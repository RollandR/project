<?php
//On démarre la session
session_start();

//On lit une fois ces fichiers
require_once 'config/Init.conf.php';
require_once 'config/bdd.conf.php';
require_once 'config/connexion.inc.php';
require_once '/fonctions.inc.php';

//On inclu le header (menu navigation)
include './includes/header.inc.php';

//On prends les valeurs dans les champs
if (isset($_POST['submit2'])) {

    //On initialise la variable notif
    $notification = 'Aucune modification a afficher';
    $_SESSION['notification_result'] = FALSE;

    //On regarde si les champs sont vide
    if (!empty($_POST['nom']) AND ! empty($_POST['prenom']) AND ! empty($_POST['email']) AND ! empty($_POST['mdp'])) {


        $insert = "INSERT INTO utilisateurs (nom, prenom, email, mdp)"
                . "VALUES (:nom, :prenom, :email, :mdp)";

        $sth = $bdd->prepare($insert);
        $sth->bindValue(':nom', $_POST['nom'], PDO::PARAM_STR);
        $sth->bindValue(':prenom', $_POST['prenom'], PDO::PARAM_STR);
        $sth->bindValue(':email', $_POST['email'], PDO::PARAM_STR);
        $sth->bindValue(':mdp', cryptPassword($_POST['mdp']), PDO::PARAM_STR);
    }
    if ($sth->execute() == TRUE) {
        $notification = 'Felicitation, vous êtes inscrit';
        $_SESSION['notification'] = $notification;
        $_SESSION['notification_result'] = TRUE;
    }
    $notification = 'Veuillez renseigner les champs obligatiores...';
    $_SESSION['notification'] = $notification;
    $_SESSION['notification_result'] = FALSE;
} else {




    echo $notification;
    $_SESSION['notification'] = $notification;
    ?>
    <?php
    if (isset($_SESSION['notification'])) {
        $notification_result = $_SESSION['notification_result'] == TRUE ? 'alert-success' : 'alert-danger';
        ?>
        <div class="alert <?= $notification_result ?> alert-dismissible fade show" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <strong>
                <?php
                echo $_SESSION['notification'];
                unset($_SESSION['notification']);
                unset($_SESSION['notification_result']);
                ?> 
            </strong> 
        </div>

    <?php } ?> 
    <div class="col-lg-6">
        <form action="inscription.php" method="post" enctype="multipart/form-data" id="form_article">
            <div class="form-row">
                <div class="form-group col-md-12">
                    <label for="inputnom" name="nom" class="col-form-label">Nom</label>
                    <input type="text" class="form-control" id="inputnom" name="nom"  placeholder="Nom" required>
                </div>
                <div class="form-group col-md-12">
                    <label for="inputprenom" name="prenom" class="col-form-label">Prénom</label>
                    <input type="text" class="form-control" id="inputprenom" name="prenom"  placeholder="Prénom" required>
                </div>
                <div class="form-group col-md-12">
                    <label for="inputemail" name="email" class="col-form-label">Email</label>
                    <input type="email" class="form-control" id="inputemail" name="email"  placeholder="E-mail" required>
                </div>
                <div class="form-group col-md-12">
                    <label for="inputmdp" name="mdp" class="col-form-label">Mot de passe</label>
                    <input type="text" class="form-control" id="inputpass" name="mdp"  placeholder="Password" required>
                </div>


            </div>
            <button type="submit" name="submit2" class="btn btn-primary">Soumettre l'inscription</button>
        </form>
    </div>


    <!-- Bootstrap core JavaScript -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/popper/popper.min.js"></script>
    <script src="./js/bootstrap.min.js"></script>
    <script src="./jss/dist/jquery.validate.min.js"></script>

    <script>
        $(document).ready(function () {
            $("#form_article").validate();
        });
    </script>





    <?php
    include './includes/footer.inc.php';
}
?>