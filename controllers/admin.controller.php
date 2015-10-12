<?php
require('./models/admin.model.php');

class admin extends system
{
	private $model;

	public function __construct()
	{
    parent::__construct();
		$this->model = new admin_model($this->db);
	}
	
	public function main() 
	{
		echo 'nothing yet';
	}

	public function add($type = null) {
		switch($type) {
			case 'feed':
				$this->addFeed();
			break;
		}
	}

	private function addFeed() {
		if (isset($_POST['submit'])) {
			echo 'do it';
		}
		$this->data['websites'] = $this->model->get_websites();

		$this->loadView('admin/addFeed');
	}
}
