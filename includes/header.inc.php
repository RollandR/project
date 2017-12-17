
<!DOCTYPE html>
<html lang="fr">

    <head>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>Bare - Start Bootstrap Template</title>

        <!-- Bootstrap core CSS -->
        <link href="./css/bootstrap.min.css" rel="stylesheet">

        <!-- Custom styles for this template -->
        <style>
            body {
                padding-top: 54px;
            }
            @media (min-width: 992px) {
                body {
                    padding-top: 56px;
                }
            }

        </style>

    </head>

    <body   style="background-image: url('img/téléchargement.png');">

        <!-- Navigation -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
            <div class="container">
                <a class="navbar-brand" href="#">Mon super blog !!!</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarResponsive">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item active">
                            <a class="nav-link" href="index.php">Home
                                <span class="sr-only">(current)</span>
                            </a>
                        </li>
                        <?php if ($is_connect == TRUE) {
                            ?><li class="nav-item">
                                <a class="nav-link" href="article.php">Ajouter un article</a>
                            </li>
                            <?php
                        } else {
                            "";
                        }
                        ?>
                        <?php if ($is_connect == TRUE) {
                            ?>
                            <li class="nav-item">
                                <a class="nav-link" href="inscription.php">Inscription</a>
                            </li>
                            <?php
                        } else {
                            "";
                        }
                        ?>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Contact</a>
                        </li>


                        <?php
                        if ($is_connect == TRUE) {
                            ?>
                            <li class="nav-item">
                                <a class="nav-link" href="Deconnexion.php">Déconnexion</a>
                            </li>
                        <?php } else {
                            ?>
                            <li class="nav-item">
                                <a class="nav-link" href="Connexion.php">Connexion</a>
                            </li>




                        <?php }
                        ?>
                        <form action="index.php" role="search" method="get" enctype="multipart/form-data" id="recherche" >
                            <input class="form-control mr-sm-2" type="text" name="recherche" id="inputrecherche" placeholder="Search">
                            <button class="btn btn-default" type="submit"> Search  </button>
                        </form>

                    </ul>
                </div>
            </div>
        </nav>


