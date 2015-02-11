<?php
class Confirmacion extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('confirmacion_model', 'confirmacion', TRUE);
		$this->load->helper("form");
	}

	public function imss() {
		$condiciones = "";
		$nombre = addslashes(trim($this->input->post('nombre')));
		$ap_paterno = addslashes(trim($this->input->post('ap_paterno')));
		$ap_materno = addslashes(trim($this->input->post('ap_materno')));
		$correo = addslashes(trim($this->input->post('correo')));
		
		if($nombre) {
			if($condiciones) {
				$condiciones .= " AND ";
			}
			$condiciones .= "u.nombre LIKE '%$nombre%'";
		}
		
		if($ap_paterno) {
			if($condiciones) {
				$condiciones .= " AND ";
			}
			$condiciones .= "u.ap_paterno LIKE '%$ap_paterno%'";
		}
		
		if($ap_materno) {
			if($condiciones) {
				$condiciones .= " AND ";
			}
			$condiciones .= "u.ap_materno LIKE '%$ap_materno%'";
		}
		
		if($correo) {
			if($condiciones) {
				$condiciones .= " AND ";
			}
			$condiciones .= "u.correo LIKE '%$correo%'";
		}
		
		$data['nombre'] = $nombre;
		$data['ap_paterno'] = $ap_paterno;
		$data['ap_materno'] = $ap_materno;
		$data['correo'] = $correo;
		$data['pendientes'] = $this->confirmacion->listarUsuariosIMSS(2, $condiciones);
		$data['confirmados'] = $this->confirmacion->listarUsuariosIMSS(1);
		
		$this->load->view('header');
		$this->load->view('lista_confirmacion', $data);
		$this->load->view('footer');
	}
	
	public function confirmar() {
		$id_usuario = addslashes($this->input->post('id'));
		settype($id_usuario, "int");
		
		if($this->confirmacion->actualizarEstatus($id_usuario, 1)) {
			echo "1";
		} else {
			echo "0";
		}
	}
}
?>