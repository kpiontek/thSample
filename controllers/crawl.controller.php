<?php
require('./models/crawl.model.php');

class crawl extends system
{
	private $model;

	public function __construct()
	{
    parent::__construct();
		$this->model = new crawl_model($this->db);
	}
	
	public function main($website_id) 
	{
		$feeds = $this->model->get_feeds($website_id);
		
		switch($website_id) {
			case 1:
				$this->themeforest($feeds);
			break;
			case 2:
				$this->themify($feeds);
			break;
		}
	}

	private function themeforest($feeds) 
	{
		include('./includes/simple_html_dom.php');

		foreach($feeds as $feed) {
			echo '<pre>'; print_r($feed); echo '</pre>';

			$html = file_get_html($feed['feed_url']);

			foreach($html->find('.item-list', 0)->children() as $entry) {
				$title = trim($entry->find('h3', 0)->plaintext);
				
				$url = 'http://themeforest.net'.$entry->find('h3', 0)->find('a', 0)->href.'?ref=theme-hub';
				$website_id = $feed['website_id'];
				$category_id = $feed['category_id'];
				$thumbnail = $entry->find('.item-thumbnail__image', 0)->find('img', 0)->src;

				$tags = get_meta_tags($url);
				$description = htmlentities(trim($tags['description']));
				
				$price = filter_var($entry->find('.price', 0)->plaintext, FILTER_SANITIZE_NUMBER_INT);

				echo $title;
				echo '<br>'.$url;
				echo '<br>'.$thumbnail;
				echo '<br>'.$description;
				echo '<br>'.$price;
				
				$theme_id = $this->model->add_theme($title, $website_id, $category_id, $url, $description, $price);
				
				if (is_numeric($theme_id)) {
					copy($thumbnail, '/var/www/themehub/assets/img/thumbnails/theme'.$theme_id.'.jpg');
					echo 'theme added successfully';
				}
				else
					echo 'error';


				echo '<hr>';
					
			}
		}
	}

	private function themify($feeds) 
	{
		include('./includes/simple_html_dom.php');

		foreach($feeds as $feed) {
			echo '<pre>'; print_r($feed); echo '</pre>';

			$html = file_get_html($feed['feed_url']);

			foreach($html->find('.theme-post') as $entry) {
				$title = $entry->find('h3', 0)->plaintext;
				$url = $entry->find('h3', 0)->find('a', 0)->href;
				$website_id = $feed['website_id'];
				$category_id = $feed['category_id'];
				$thumbnail = $entry->find('.theme-image', 0)->find('img', 0)->src;
				$demo = $entry->find('.theme-title', 0)->find('a.tag-button', 0)->href;
				$description = $entry->find('.theme-excerpt', 0)->find('p', 0)->plaintext;
				
				//$moreHtml = file_get_html($url);

				//$price = filter_var($moreHtml->find('.buy-options', 0)->find('li', 0)->find('strong', 0)->plaintext, FILTER_SANITIZE_NUMBER_INT);

				$price = 49.00;

				echo $title;
				echo '<br>'.$url;
				echo '<br>'.$thumbnail;
				echo '<br>'.$demo;
				echo '<br>'.$price;
				echo '<br>'.$description;
				echo '<hr>';
				
				$theme_id = $this->model->add_theme($title, $website_id, $category_id, $url, $description, $price);
				
				if (is_numeric($theme_id)) {
					copy($thumbnail, '/var/www/themehub/assets/img/thumbnails/theme'.$theme_id.'.jpg');
					echo 'theme added successfully';
				}
				else
					echo 'error';
					
			}
		}
	}
}
