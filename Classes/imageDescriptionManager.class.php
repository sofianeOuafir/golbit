
<?php
class imageDescriptionManager
{
private $_db;



//recuperation d'une liste d'image
public function getList()
	{
		$req = $this->_db->query('SELECT * from image_description');
		while($donnees = $req->fetch(PDO::FETCH_ASSOC))
		{
			$images[] = new imageDescription($donnees);
		}
		
		return $images;
	}


//recuperation d'une imageDescription
public function get($id)
	{
		$req = $this->_db->prepare('SELECT * FROM image_description WHERE ID_IMAGE = ?');
		$req->execute(array($id));
		$donnees = $req->fetch(PDO::FETCH_ASSOC);
		$image = new imageDescription($donnees);
		return $image;
	}
	
public function getNextId()
{
	$req = $this->_db->query('SELECT MAX(ID_IMAGE) as maxId FROM image_description');
	$donnees = $req->fetch(PDO::FETCH_ASSOC);
	$nextId = $donnees['maxId'] + 1;
	
	return $nextId;

}

public function add($id,$source, $alt, $id_utilisateur)
{
	$req = $this->_db->prepare('INSERT INTO image_description (ID_IMAGE,SOURCE, ALT,ID_UTILISATEUR) VALUES (?,?,?,?)');
	$req->execute(array($id,$source,$alt, $id_utilisateur));
	
}

public function deleteImageInutile()
{
	$req = $this->_db->query('DELETE FROM `image_description` WHERE ID_IMAGE NOT IN (SELECT DISTINCT ID_IMAGE_1 FROM article)');
}

public function Delete($id)
{
	$req = $this->_db->prepare('DELETE FROM `image_description` WHERE ID_IMAGE = ?');
	$req->execute(array($id));
}

public function selectImageUtile()
{
	$req = $this->_db->query('SELECT DISTINCT  image_description.ID_IMAGE, image_description.SOURCE, image_description.ALT FROM image_description inner join article on image_description.ID_IMAGE = article.ID_IMAGE_1');
	while($donnees = $req->fetch(PDO::FETCH_ASSOC))
	{
		$images[] = new imageDescription($donnees);
	}

	if(isset($images))
	{
		foreach($images as $image)
		{
			$arrayImageUtile[] = $image->SOURCE();
		}
		
		return $arrayImageUtile;
	}
	
	return 0;
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
?>
