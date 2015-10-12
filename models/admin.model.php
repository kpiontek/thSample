<?php

class admin_model
{
	protected $database;

  public function __construct($database) 
  {
    $this->database = $database;
  }

  public function get_websites() {
    $qtext = "SELECT * FROM websites";
    
    $query = $this->database->prepare($qtext);
    $query->execute();
    
    return $query->fetchAll(PDO::FETCH_ASSOC);
  }
}
