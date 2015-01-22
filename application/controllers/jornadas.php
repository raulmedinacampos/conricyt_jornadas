<?php
class Jornadas extends CI_Controller {
	function __construct() {
		parent::__construct();
		$this->load->helper('form');
	}
	
	public function index() {
		$this->load->view('header');
		$this->load->view('jornadas');
		$this->load->view('footer');
	}
}
?>