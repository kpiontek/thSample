<?php

class crawl_model
{
	public $database;

	public function __construct($database) 
	{
		$this->database = $database;
	}
	
	public function get_feeds($website_id)
	{
		$qtext = "SELECT * FROM feeds WHERE website_id = :website_id";
		
		$query = $this->database->prepare($qtext);
		$query->bindParam(':website_id', $website_id);
		$query->execute();
		
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}
	
	public function add_theme($title, $website_id, $category_id, $url, $description, $price)
	{
			$query = "INSERT IGNORE INTO themes (title, website_id, category_id, url, description, price) VALUES (?, ?, ?, ?, ?, ?)";
			$statement = $this->database->prepare($query);  
			$statement->execute(array($title, $website_id, $category_id, $url, $description, $price));
			$theme_id = $this->database->lastInsertId();
			return $theme_id;
	}
}
