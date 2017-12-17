<?php

require_once 'config/Init.conf.php';
require_once('libs/Smarty.class.php');
require_once 'fonctions.inc.php';
require_once 'config/connexion.inc.php';

$prenom = 'Remi';


$smarty = new Smarty();

$smarty->setTemplateDir('templates');
$smarty->setCompileDir('templates_c');
//$smarty->setConfigDir('/web/www.example.com/guestbook/configs/');
//$smarty->setCacheDir('/web/www.example.com/guestbook/cache/');

$smarty->assign('name', $prenom);

//** un-comment the following line to show the debug console
//$smarty->debugging = true;
include './includes/header.inc.php';
$smarty->display('smarty.tpl');
include './includes/footer.inc.php';
?>