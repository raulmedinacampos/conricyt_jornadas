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
			$datos->id = $sede->id_evento;
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
	
	public function por_institucion() {
		$id = $this->uri->segment(3);
		settype($id, "int");
		
		$arregloDatos = array();
		$instituciones = $this->reporte->listarInstitucionesPorSede($id);
		
		if($instituciones) {
			$instituciones = $instituciones->result();
			
			foreach($instituciones as $institucion) {
				$datos = new stdClass();
				$datos->institucion = $institucion->institucion;
				$datos->registrados = $this->reporte->obtenerRegistradosPorInstitucionSede($institucion->institucion, $id);
				$arregloDatos[] = $datos;
			}
		}
		
		$data['datos'] = $arregloDatos;		
		$data['sede'] = $this->reporte->consultarSedePorId($id);
		$data['total'] = $this->reporte->obtenerTotalUsuariosPorSede($id);
		
		$this->load->view("header");
		$this->load->view("reportes/institucion", $data);
		$this->load->view("footer");
	}
}
?>