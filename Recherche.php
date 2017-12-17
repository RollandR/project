<?php

$sql = "SELECT "
	. "id, "
	. "texte, "
	. "titre, "
	. "DATE_FORMAT(date, '%d/%m/%Y') as date_fr"
	. "FROM articles "
	. "WHERE (titre LIKE :recherche OR texte LIKE :recherche) "
	. "AND publie=1 "
	. "ORDER BY date DESC "
	. "LIMIT :debut, :message_par_page";
$sth = $bdd->prepare($sql);
$sth->bindValue(':recherche', '%' . $recherche . '%', PDO::PARAM_STR);
