<?php
class Reporte extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model("reporte_model", "reporte", TRUE);
	}
	
	public function index() {
	}
	
	public function por_sede() {
		$this->load->view("header");
		$this->load->view("reportes/sede");
		$this->load->view("footer");
	}
}
?>