<?php
class Reporte extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model("reporte_model", "reporte", TRUE);
		$this->load->helper("form");
	}
	
	public function index() {
	}
	
	public function por_sede() {
		$arregloDatos = array();
		$sedes = $this->reporte->listarSedes();
		$sedes = $sedes->result();
		
		foreach($sedes as $sede) {
			$datos = new stdClass();
			$datos->sede = $sede->evento;
			$datos->registrados = $this->reporte->obtenerRegistradosPorSede($sede->id_evento);
			$arregloDatos[] = $datos;
		}
		
		$data['datos'] = $arregloDatos;
		$data['total'] = $this->reporte->obtenerTotalUsuarios();
		
		$this->load->view("header");
		$this->load->view("reportes/sede", $data);
		$this->load->view("footer");
	}
}
?>