<!-- Page Content -->
<div class="container">
    <div class="row">
        <div class="col-lg-12 text-center">
            <h1 class="mt-5">Mon blog</h1>	 
            <ul class="list-unstyled">

            </ul>
        </div>
    </div>
    
      <div class="alert {php}$notification_result{/php} alert-dismissible fade show" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
                    </div>
 <!-- On implémente du html -->
        <div class="row"> 
            <div class="col">
                <div class="card" style="width: 20rem;">
                    <img class="card-img-top" src="img/{php} echo $value['id'] {/php}.jpg" alt="Card image cap">
                    <div class="card-body">

                        <h2 class="card-title">  <!-- Titre de la carte dans la boucle donc on ouvre le php -->
                            </h2>

                        <p class="card-text">        </p>
                        <a href="#" class="btn btn-info"> Crée le {php} echo $value['date_fr'] {/php}</a>
                        <a href="article.php?action=modifier&id_article={php} $value['id'] {/php}" class="btn btn-warning"> Modifié </a>
                        <a href="#" class="btn btn-danger"> Supprimer </a>

                    </div>
                </div>

            </div>
        </div>





      </ul>
    </nav>
</div>

  <li class="page-item {php} $is_active;{/php}">
                    <a class="page-link" href="?page=<?= $i ?>">{php} $i {/php}</a>
                </li>

<!-- Bootstrap core JavaScript -->
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/popper/popper.min.js"></script>
<script src="./js/bootstrap.min.js"></script>




</div>
<div class="col">
    <nav aria-label="Page navigation example">
        <ul class="pagination">