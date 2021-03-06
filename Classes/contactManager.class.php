<?php


class contactManager
{
private $_db;



public function ControlAndSendEmail(contact $contact)
{

		// commentaire : function smtpmailer($to, $from, $from_name, $subject, $body)
		smtpmailer('sofiane.chronicle@gmail.com', 'sofiane.chronicle@gmail.com', 'llolo',$contact->OBJET(), $contact->MESSAGE()); 
}


//ajoute une demande de contact dans la base de données
public function add(contact $contact)
{
	$query = $this->_db->prepare('INSERT INTO `contact`(`MESSAGE`, `ID_UTILISATEUR`, `DATE`) VALUES (?,?,?)');
	$query->execute(array($contact->MESSAGE(),$contact->ID_UTILISATEUR(),$contact->DATE()));
}

public function GetContact($idArticle)
{
	$req = $this->_db->prepare('SELECT * from contact WHERE ID_ARTICLE = ? ORDER BY DATE DESC');
	$req->execute(array($idArticle));
	while($donnees = $req->fetch(PDO::FETCH_ASSOC))
	{
		$contact[] = new contact($donnees);
	}
	
	if(isset($contact))
	{
		return $contact;
	}
	
	return 0;

}

public function getNumberOfComment($idArticle)
{
	$req = $this->_db->prepare('SELECT count(*) as nb from contact WHERE ID_ARTICLE = ?');
	$req->execute(array($idArticle));
	$donnees = $req->fetch(PDO::FETCH_ASSOC);	
	return $donnees['nb'];

}

public function setDB($db)
{
	$this->_db = $db;
}

public function __construct($db)
{
	$this->setDB($db);
}

}