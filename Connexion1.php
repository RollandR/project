<?php
//On démarre la session
session_start();

//On lit une fois ces fichiers
require_once 'config/Init.conf.php';
require_once 'config/bdd.conf.php';
require_once 'config/connexion.inc.php';
require_once '/fonctions.inc.php';

//On inclu le header (menu navigation)
include 'includes/header.inc.php';

                 
                  if(isset($_SESSION['notification']))
                      {
                      $notification_result=$_SESSION['notification_result']==TRUE ? 'alert-success' : 'alert-danger';
                    ?>
                      <div class="alert <?=$notification_result?> alert-dismissible fade show" role="alert">
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                      </button>
                       <strong>
                       <?php  echo $_SESSION['notification']; 
                     unset($_SESSION['notification']);
                      unset($_SESSION['notification_result']);
                     ?> 
                   </strong> 
</div>
 <?php }?>                     
 <?php            

if (isset($_POST['submit'])) {
        print_r($_POST);
        
 $notification = 'Aucune modification a afficher';
     $_SESSION['notification_result']=FALSE;
  
    //On regarde si les champs sont 
    if (!empty($_POST['email']) AND ! empty($_POST['mdp']))
    {
        $select_user = "SELECT email, "
            . "mdp "
            . "FROM utilisateurs "
            . "WHERE email = :email "
            . "AND mdp = :mdp";
        
    
$sth = $bdd->prepare($select_user);
$sth->bindValue(':email', $_POST['email'], PDO::PARAM_STR);
$sth->bindValue(':mdp', cryptPassword($_POST['mdp']), PDO::PARAM_STR);
    }
if($sth->execute() == TRUE) {
   $count = $sth->rowCount();
   if($count > 0){
       $sid = sid($_POST['email']);
//Il envoie email dans la fonction sid ET MET à jour SID
       $update_sid = "UPDATE utilisateurs "
               . "SET sid = :sid "
               . "WHERE email = :email";
               
       $sth_update = $bdd->prepare($update_sid);
       $sth_update->bindValue(':sid', $sid, PDO::PARAM_STR);
       $sth_update->bindValue ('email', $_POST['email'], PDO::PARAM_STR);
       
       if($sth_update->execute() == TRUE) {
           setcookie('sid', $sid, time() + 86400);
          
         
    $notification = "Félicitation ! Vous êtes connecté !";
    $_SESSION['notification'] = $notification;
    $_SESSION['notification_result'] = TRUE; 
    header("Location: index.php");
    exit();
       }else {
           $notification = "Une erreur technique est survenue...";
    $_SESSION['notification_result'] = FALSE;
       }
       
   } else {
        $notification = "L'email ou le mot de passe sont invalide...";
    $_SESSION['notification_result'] = FALSE;
   }
} 
else {
    $notification = "Une erreur technique est survenue...";
    $_SESSION['notification_result'] = FALSE;
}

$notification = "Veuillez renseigner les champs obligatoires...";
$_SESSION['notification_result'] = FALSE;
 
$_SESSION['notification'] = $notification;
}




?>    
<div class="col-lg-6">
        <form action="Connexion.php" method="post" enctype="multipart/form-data" id="form_article">
            <div class="form-row">
                <div class="form-group col-md-12">
                    <label for="inputemail" name="email" class="col-form-label">E-Mail</label>
                    <input type="text" class="form-control" id="inputemail" name="email"  placeholder="Email">
                </div>
                <div class="form-group col-md-12">
                    <label for="inputmdp" name="mdp" class="col-form-label">Mot de passe</label>
                    <input type="text" class="form-control" id="inputmdp" name="mdp"  placeholder="MDP">
                </div>
                     <button type="submit" name="submit" class="btn btn-primary">Connexion</button>
            </div>
        </form>
</div>
</div>
    
    
    
    
    <!-- Bootstrap core JavaScript -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/popper/popper.min.js"></script>
    <script src="./js/bootstrap.min.js"></script>

     <?php
 
    include './includes/footer.inc.php';
  
    ?>