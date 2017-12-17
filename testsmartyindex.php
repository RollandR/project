<?php

session_start();
require_once('libs/Smarty.class.php');
require_once 'fonctions.inc.php';
require_once 'config/Init.conf.php';
require_once 'config/bdd.conf.php';
require_once 'config/connexion.inc.php';
include './includes/header.inc.php';

$smarty->assign('nombre_article',$nombre_article_page);
$smarty->assign('page_courante',$page_courante);
$smarty->assign('index',$index);
$smarty->assign('nb_publie',$nb_total_article_publie);
$smarty->assign('nb_page',$nb_de_page);
$smarty->assign('nb_page',$nb_de_page);
$nombre_article_page = 2;

$page_courante = isset($_GET['page']) ? $_GET['page'] : 1;

$index = pagination($page_courante, $nombre_article_page);

$nb_total_article_publie = nb_total_article_publie($bdd);

$nb_de_page = ceil($nb_total_article_publie / $nombre_article_page);

// Ici on va rajouter le module de recherche d'article 
//Si $GET recherche existe on execute la requête SQL suivante
if (isset($_GET['recherche'])) {
    $sql = "SELECT id, "
            . "texte, "
            . "titre, "
            . "DATE_FORMAT(date, '%d/%m/%Y') as date_fr "
            . "FROM articles "
            . "WHERE (titre LIKE :recherche OR texte LIKE :recherche) "
            . "AND publie= :publie "
            . "ORDER BY date DESC "
            . "LIMIT :debut, :message_par_page";
    $sth = $bdd->prepare($sql);
    $sth->bindValue(':recherche', '%' . ($_GET['recherche']) . '%', PDO::PARAM_STR);
    $sth->bindValue(':debut', $debut, PDO::PARAM_STR);
    $sth->bindValue(':recherche', $message_par_page, PDO::PARAM_STR);
    if ($sth->execute() == TRUE) {
        $tab_recherche = $sth->fetchAll(PDO::FETCH_ASSOC);
    }
}
//Sinon on execute celle-ci pour afficher les articles
else {



    $select = "SELECT id, "
            . "titre, "
            . "texte, "
            . "DATE_FORMAT(date, '%d/%m/%Y') as date_fr "
            . "FROM articles "
            . "WHERE publie = :publie;"
            . "LIMIT :index, :nombre_article_page;";
//echo $select;  
    /* @var $bdd PDO */
    $sth = $bdd->prepare($select);
    $sth->bindValue(':publie', 1, PDO::PARAM_BOOL);
    $sth->bindValue(':index', $index, PDO::PARAM_INT);
    $sth->bindValue(':nombre_article_page', $nombre_article_page, PDO::PARAM_INT);
    if ($sth->execute() == TRUE) {
        $tab_articles = $sth->fetchAll(PDO::FETCH_ASSOC);
    } else {
        echo 'Une erreur est survenue...';
    }
}



if (isset($_SESSION['notification'])) {
    $notification_result = $_SESSION['notification_result'] == TRUE ? 'alert-success' : 'alert-danger';



    echo $_SESSION['notification'];
    unset($_SESSION['notification']);
    unset($_SESSION['notification_result']);
}


// On lance une boucle pour stocker le tableau dans la variable value jusqu'à qu'il soit vide -->

foreach ($tab_articles as $value) {



    echo $value['titre'] . '<br/>';
}



for ($i = 1; $i <= $nb_de_page; $i++) {
    $is_active = $page_courante == $i ? 'active' : '';
}






include './includes/footer.inc.php';
?>
