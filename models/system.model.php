<?php

class system_model
{
	function __construct()
	{
		$this->host = 'localhost';
		$this->database_name = '';
		$this->username = '';
		$this->password = '';
		$this->database = new PDO("mysql:host=$this->host;dbname=$this->database_name", $this->username, $this->password);
		$this->database->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );
	}

  public function get_category($category_id)
  {
    $qtext = "SELECT * FROM categories WHERE category_id = :category_id";
    
    $query = $this->database->prepare($qtext);
    $query->bindParam(':category_id', $category_id);
    $query->execute();
    
    return $query->fetch(PDO::FETCH_ASSOC);
  }

  public function get_categories() {
    $qtext = "SELECT * FROM categories";
    
    $query = $this->database->prepare($qtext);
    $query->execute();
    
    return $query->fetchAll(PDO::FETCH_GROUP|PDO::FETCH_UNIQUE);
  }
}
