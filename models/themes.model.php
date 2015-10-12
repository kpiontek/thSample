<?php

class themes_model extends system_model
{
	public $database;

	public function __construct($database) 
	{
		$this->database = $database;
	}
	
	public function get_themes($extra = null, $fields = array(), $start = 0, $end = 100)
	{
		$qtext = "SELECT themes.*, categories.title AS 'category.title', IFNULL(cat_parent.title,null) AS 'category.parent_title', 
		IFNULL(cat_parent.category_id,null) AS 'category.parent_id', IFNULL(SUM(votes.vote),0) AS 'votes' FROM themes 
		LEFT JOIN categories ON themes.category_id = categories.category_id 
		LEFT JOIN categories AS cat_parent ON cat_parent.category_id = categories.parent_id
		LEFT JOIN votes ON themes.theme_id = votes.theme_id
		";
		
		if ($extra == null)
			$qtext .= " GROUP BY themes.theme_id ORDER BY themes.theme_id DESC";
		else
			$qtext .= $extra;
			
		$qtext .= " LIMIT $start, $end";
		
		$query = $this->database->prepare($qtext);
		
		foreach($fields as $key => $value)
			$query->bindParam(':'.$key, $value);
			
		$query->execute();
		
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}
	
	public function get_theme($theme_id)
	{
		$qtext = "SELECT themes.*, categories.title AS 'category.title', IFNULL(SUM(votes.vote),0) AS 'votes' FROM themes 
		LEFT JOIN categories ON themes.category_id = categories.category_id 
		LEFT JOIN votes ON votes.theme_id = themes.theme_id
		WHERE themes.theme_id = :theme_id
		";
		
		$query = $this->database->prepare($qtext);
		$query->bindParam(':theme_id', $theme_id);
		$query->execute();
		
		return $query->fetch(PDO::FETCH_ASSOC);
	}
	
	public function updateVotes($theme_id, $value)
	{
		$ip = $ip = $_SERVER['REMOTE_ADDR'];
		$query = "INSERT INTO votes (theme_id, vote, ip) VALUES (?, ?, ?)";
		$statement = $this->database->prepare($query);  
		$statement->execute(array($theme_id, $value, $ip));
		return true;
		
	}
}
