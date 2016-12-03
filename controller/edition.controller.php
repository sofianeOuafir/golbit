<?php
require '../functions.php';
session_start();
spl_autoload_register('chargerClasse');
$bdd = connexionBdd();
$articleManager = new articleManager($bdd);
$imageDescriptionManager = new imageDescriptionManager($bdd);
$article = new article($_POST);

$article->setINTRODUCTION(substr($article->INTRODUCTION(), 0, 200).'...');

if($article->PUBLIER() == "on") {
$article->setPUBLIER(1);
}
else {
$article->setPUBLIER(0);
}

if(isset($_FILES['image']))
{
	if($_FILES['image']['error'] > 0)
	{
		//erreur lors du chargement
		$erreur = 0;
	}
	else
	{
		$extention_upload = strtolower(substr(strrchr($_FILES['image']['name'],'.'),1));
		$extentions_autorisees = array('jpg','png','jpeg','gif');
		if(in_array($extention_upload,$extentions_autorisees))
		{
				$idImageDescription = $imageDescriptionManager->getNextId();
				$nom = 'image/'.$idImageDescription.'-'.$extention_upload;
				$resultat = move_uploaded_file($_FILES['image']['tmp_name'],'../'.$nom);
				if($resultat)
				{						
					
					$imageDescriptionManager->add($idImageDescription,$nom,$nom,$_SESSION['UTILISATEUR']->ID_UTILISATEUR());
					$article->setID_ARTICLE($articleManager->getNextID());
					$article->setID_UTILISATEUR($_SESSION['UTILISATEUR']->ID_UTILISATEUR());
					$article->setID_IMAGE_1($idImageDescription);
					$article->setDATE_REALISATION(date('Y-m-d H:i:s'));	   
					$articleManager->add($article);
				}
		}
		else
		{
			$erreur = 2;
			// "format de fichier non accepté";
		}
	}
}


?>