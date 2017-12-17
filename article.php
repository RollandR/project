<?php
//On lance une session pour garder les variables en chargeant la page
session_start();


//Ici on s'assure que le fichier n'est appellé qu'un fois
require_once './config/bdd.conf.php';
require_once './config/init.conf.php';
require_once 'config/connexion.inc.php';



if ($is_connect == TRUE) {


//Ici on rappatri les variables file et post
    if (isset($_POST['submit'])) {
        print_r($_POST);
        print_r($_FILES);

        if ($_FILES['image']['error'] == 0) {




            //Date du jour pour charger les fichier dans la BDD
            $date_du_jour = date("Y-m-d");

            //On initialise la variable notif
            $notification = 'Aucune modification a afficher';

            $_SESSION['notification_result'] = FALSE;
            // verifier que les champs sont pas vide ou pas. Vide = erreur ; remplis = entrer en base de donnees
            if (!empty($_POST['titre']) AND ! empty($_POST['texte'])) {
                $publie = isset($_POST['publie']) ? $_POST['publie'] : 0;

                //On test si la page est en modification ou en ajout pour adapter la requête
                if ($_POST['action'] == 'modifier') {

                    $update = "UPDATE articles "
                            . "SET titre= :titre, "
                            . "texte = :texte, "
                            . "publie = :publie "
                            . "WHERE id = :id_article";

                    $sth = $bdd->prepare($update);

                    $sth->bindValue(':titre', $_POST['titre'], PDO::PARAM_STR);
                    $sth->bindValue(':texte', $_POST['texte'], PDO::PARAM_STR);
                    $sth->bindValue(':publie', $publie, PDO::PARAM_BOOL);
                    $sth->bindValue(':id_article', $_POST['id'], PDO::PARAM_INT);
                    if ($sth->execute() == TRUE) {
                        $id_article = $_POST['id'];

                        $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);

                        move_uploaded_file($_FILES['image']['tmp_name'], 'img/' . $id_article . '.' . $extension);


                        $notification = 'Felicitation, votre article est modifié...';
                        $_SESSION['notification_result'] = TRUE;
                        $_SESSION['notification'] = $notification;
                        header('Location: index.php');
                        exit();
                    }
                } else {

                    $insert = "INSERT INTO articles (titre, texte, date, publie) "
                            . "VALUES (:titre, :texte, :date, :publie)";

                    //On prépare les requêtes
                    $sth = $bdd->prepare($insert);
                    $sth->bindValue(':titre', $_POST['titre'], PDO::PARAM_STR);
                    $sth->bindValue(':texte', $_POST['texte'], PDO::PARAM_STR);
                    $sth->bindValue(':date', $date_du_jour, PDO::PARAM_STR);
                    $sth->bindValue(':publie', $publie, PDO::PARAM_BOOL);
                }






                if ($sth->execute() == TRUE) {
                    $id_article = $bdd->lastInsertId();

                    $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                    /*
                      //Tableau contenant les extensions à comaprer
                      $tab_extensions=array
                      ( 'jpg',
                      'png',
                      'jpeg'
                      );

                      $result_extension_image = in_array($extension, $tab_extension);
                      if ($result_extension_image==TRUE) */
                    move_uploaded_file($_FILES['image']['tmp_name'], 'img/' . $id_article . '.' . $extension);
                    echo $extension;
                    $notification = 'Felicitation, votre article est inséré...';
                    $_SESSION['notification_result'] = TRUE;
                } else {
                    $notification = 'Une erreur est survenue lors de l\'insertion de l\'article...';
                    $_SESSION['notification_result'] = FALSE;
                }
            } else {
                $notification = 'Veuillez renseigner les champs obligatiores...';
            }
        } else {
            $notification = 'Il y a eu un soucis dans le taitement de votre image...';
        }


        echo $notification;
        $_SESSION['notification'] = $notification;
        header('Location: article.php');
        exit();
    } else {

        include './includes/header.inc.php';
        ?>
        <!-- Page Content -->
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <?php
                    //echo $_GET['action'];
                    //   exit();

                    if (($_GET['action']) == modifier) {
                        ?>  
                        <h1 class="mt-5">Modifier un article</h1>
                        <?php
                    } else {
                        ?>

                        <h1 class="mt-5">Ajouter un article</h1>
                        <?php
                    }
                    ?>

                    <!--- test php -->

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
                        <?php
                    }
                    if ($_GET['action'] == 'modifier') {

                        $select = "SELECT id, "
                                . "titre, "
                                . "texte,"
                                . "publie "
                                . "FROM articles "
                                . "WHERE id = :id_article;";

                        $sth = $bdd->prepare($select);
                        $sth->bindValue(':id_article', $_GET['id_article'], PDO::PARAM_INT);
                        if ($sth->execute() == TRUE) {
                            $tab_articles = $sth->fetchAll(PDO::FETCH_ASSOC);
                        } else {
                            echo 'Une erreur est survenue...';
                        }
                        foreach ($tab_articles as $value) {
                            ?>

                            <div class="col-lg-6">
                                <form action="article.php" method="post" enctype="multipart/form-data" id="form_article">
                                    <input type="hidden" name="id" value="<?php echo $_GET['id_article']; ?>">
                                    <input type="hidden" name="action" value="<?php echo $_GET['action']; ?>">
                                    <div class="form-row">
                                        <div class="form-group col-md-12">
                                            <label for="inputTitre" name="titre" class="col-form-label">Titre</label>
                                            <input type="text" class="form-control" id="inputTitre" name="titre" value="<?php echo $value['titre']; ?>" placeholder="" required>
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label for="inputTexte" name="texte" class="col-form-label">Zone texte</label>
                                            <textarea class="form-control" id="texte" name="texte"  placeholder=""  rows="3" required><?php echo $value['texte']; ?></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <img class="card-img-top" src="img/<?php echo $value['id'] ?>.jpg" alt="Card image cap">
                                        <label for="inputImg" name="image" class="col-md-12">Image</label>
                                        <input type="file" class="form-control" id="inputAddress" name="image" placeholder="Image" required>
                                    </div>
                                    <div class="form-group">
                                        <div class="form-check">
                                            <label class="form-check-label">
                                                <input class="form-check-input" name="publie" type="checkbox" value="1" <?php if ($value['publie'] == 1) { ?> checked <?php } ?>> Publier l'article</input>
                                            </label>
                                        </div>
                                    </div>
                                    <button type="submit" name="submit" class="btn btn-primary">Modifier</button>
                                </form>
                            </div>
                        </div>
                    </div> 
                </div>
                <?php
            }
        } else {
            ?>                     


            <div class="col-lg-6">
                <form action="article.php" method="post" enctype="multipart/form-data" id="form_article">
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="inputTitre" name="titre" class="col-form-label">Titre</label>
                            <input type="text" class="form-control" id="inputTitre" name="titre"  placeholder="Titre" required>
                        </div>
                        <div class="form-group col-md-12">
                            <label for="inputTexte" name="texte" class="col-form-label">Zone texte</label>
                            <textarea class="form-control" id="texte" name="texte"  rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputImg" name="image" class="col-md-12">Image</label>
                        <input type="file" class="form-control" id="inputAddress" name="image" placeholder="Image" required>
                    </div>
                    <div class="form-group">
                        <div class="form-check">
                            <label class="form-check-label">
                                <input class="form-check-input" name="publie" type="checkbox" value="1" required> Publier l'article</input>
                            </label>
                        </div>
                    </div>
                    <button type="submit" name="submit" class="btn btn-primary">Soumettre le formulaire</button>
                </form>
            </div>
            </div>
            </div> 
            </div>



            <?php
        }
        include './includes/footer.inc.php';
    }
} else {
    echo "Vous devez vous connecter";
}
?>
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