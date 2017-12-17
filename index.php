<?php
session_start();
require_once('libs/Smarty.class.php');
require_once 'fonctions.inc.php';
require_once 'config/Init.conf.php';
require_once 'config/bdd.conf.php';
require_once 'config/connexion.inc.php';
include './includes/header.inc.php';


$nb_articles_par_pages = 3;
$page_courante = isset($_GET['page']) ? $_GET['page'] : 1;
$index = pagination($page_courante, $nb_articles_par_pages);
$nb_total_articles_publie = nb_total_article_publie($bdd);
$recherche = isset($_GET['recherche']) ? $_GET['recherche'] : '';
if (!empty($recherche)) {
    $nb_pages = 1;
} else {
    $nb_pages = ceil($nb_total_articles_publie / $nb_articles_par_pages);
}
if (!empty($recherche)) {
    $select = "SELECT id, "
            . "titre, "
            . "texte, "
            . "DATE_FORMAT(date, '%d/%m/%Y') as date_fr "
            . "FROM articles "
            . "WHERE (titre LIKE :recherche OR texte LIKE :recherche)"
            . "AND publie = :publie "
            . "LIMIT :index, :nb_articles_par_pages;";
} else {
    $select = "SELECT id, "
            . "titre, "
            . "texte, "
            . "DATE_FORMAT(date, '%d/%m/%Y') as date_fr "
            . "FROM articles "
            . "WHERE publie = :publie "
            . "LIMIT :index, :nb_articles_par_pages;";
}
//Afficher la requete
//echo $select;
// Preparer la requete SQL
$sth = $bdd->prepare($select);
// Securiser les donnees, attribuer la valeur a publie et definir son type
if (!empty($recherche)) {
    $sth->bindValue(':recherche', '%' . $recherche . '%', PDO::PARAM_BOOL);
    $sth->bindValue(':publie', 1, PDO::PARAM_BOOL);
    $sth->bindValue(':index', $index, PDO::PARAM_INT);
    $sth->bindValue(':nb_articles_par_pages', $nb_articles_par_pages, PDO::PARAM_INT);
} else {
    $sth->bindValue(':publie', 1, PDO::PARAM_BOOL);
    $sth->bindValue(':index', $index, PDO::PARAM_INT);
    $sth->bindValue(':nb_articles_par_pages', $nb_articles_par_pages, PDO::PARAM_INT);
}
// Condition si la requete s'effectue correctement
if ($sth->execute() == TRUE) {
    // Attribuer les valeurs recupere dans la requete dans un tableau
    $tab_articles = $sth->fetchAll(PDO::FETCH_ASSOC);
} else {
    // Afficher le message
    echo 'Une erreur est survenue...';
    $notification = "Aucun article ne correspond a votre recherche...";
    $_SESSION['notification_result'] = FALSE;
}
?>
<!-- Page Content -->
<div class="container">
    <div class="row">
        <div class="col-lg-12 text-center">
            <h1 class="mt-5">Mon blog</h1>	 
            <ul class="list-unstyled">

            </ul>
        </div>
    </div>
    <div class="row">
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
        </div>

    <?php } ?>                     


    <!-- On lance une boucle -->
    <?php
    //On affecte le tableau à la variable value jusqu'a qu'il soit vide
    foreach ($tab_articles as $value) {
        ?> 
        <!-- On implémente du html -->
        <div class="row"> 



            <div class="col-md-12">
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
                        </br>
                        <a href="article.php?action=modifier&id_article=<?= $value['id'] ?>" class="btn btn-warning"> Modifier </a>

                        <a href="#" class="btn btn-danger"> Supprimer </a>

                        <a href="Commentaire.php?action=commentaire&id_article=<?= $value['id'] ?>" class="btn btn-success"> Commentaire </a>

                    </div>
                </div>

            </div>
            <div class="col-md-1">

            </div> 
        </div>








        <?php
    }
    ?> 
</div>
</br>
</br>

<div class="row"> 

    <div class="col-md-4">
    </div>



    <nav aria-label="Page navigation example">
        <ul class="pagination">
            <?php
            for ($i = 1; $i <= $nb_pages; $i++) {
                $is_active = $page_courante == $i ? 'active' : '';
                ?>
                <li class="page-item <?php echo $is_active; ?>">
                    <a class="page-link" href="?page=<?php echo $i; ?>&recherche=<?php echo $_GET['recherche']; ?>"><?php echo $i; ?></a>
                </li>
                <?php
            }
            ?>
        </ul>
    </nav>


</div> 


<!-- Bootstrap core JavaScript -->
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/popper/popper.min.js"></script>
<script src="./js/bootstrap.min.js"></script>


<?php
include './includes/footer.inc.php';
?>
