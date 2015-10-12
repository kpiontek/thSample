<?php
require('./models/themes.model.php');

class themes extends system
{
	private $model;

	public function __construct()
	{
    parent::__construct();
		$this->model = new themes_model($this->db);
	}

	public function main() 
	{
		$this->data['themes'] = $this->get_themes();
		$this->data['page_title'] = 'Latest Themes';
		$this->loadView('themeList');
	}
	
	public function view($theme_id = null) 
	{
		$this->data['theme'] = $this->model->get_theme($theme_id);
		$this->loadView('view_theme');
	}
	
	public function get_themes() 
	{
		$themes = $this->model->get_themes();
		return $themes;
	}
	
	public function popular($data = null) 
	{
		$themes = $this->model->get_themes('GROUP BY themes.theme_id ORDER BY votes DESC');
		$this->data['themes'] = $themes;
		$this->data['page_title'] = 'Popular Themes';
		$this->loadView('themeList');
	}
	
	public function category($category_id) 
	{
		$themes = $this->model->get_themes(' WHERE themes.category_id = :category_id OR categories.parent_id = :category_id GROUP BY themes.theme_id', array('category_id' => $category_id));
		$this->data['themes'] = $themes;
		
		$category = $this->model->get_category($category_id);
		$this->data['page_title'] = $category['title'].' Themes';
		
		//if (isset($themes[0]['category.parent']))
		//	$data['page_title'] = $themes[0]['category.parent'].' > '.$data['page_title'];
		$this->loadView('themeList');
	}
	
	public function updateVotes($theme_id, $action) 
	{		
		if ($action == 'arrowUp')
			$value = 1;
		else
			$value = -1;
		$this->model->updateVotes($theme_id, $value);
	}
}
