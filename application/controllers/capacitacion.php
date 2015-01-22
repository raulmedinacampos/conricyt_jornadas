<?php
class Capacitacion extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('capacitacion_model', 'capacitacion', TRUE);
	}
	
	public function index() {
	}
	
	public function acceder() {
		$this->load->helper('form');
		
		$correo = addslashes($this->input->post('correo_moodle'));
		
		$usuario = $this->capacitacion->getDataByMail($correo);
		
		$ruta_login = base_url('evaluacion/login/index.php');
		
		echo '<script type="text/javascript" src="'.base_url('scripts/jquery-1.11.0.min.js').'"></script>';
		
		$attr = array(
			'id'	=>	'form1',
			'name'	=>	'form1'
		);
		echo form_open($ruta_login, $attr);
		
		$attr = array(
				'id'	=>	'username',
				'name'	=>	'username',
				'type'	=>	'hidden',
				'value'	=>	$usuario->username
		);
		echo form_input($attr);
		
		$attr = array(
				'id'	=>	'password',
				'name'	=>	'password',
				'type'	=>	'hidden',
				'value'	=>	$usuario->password
		);
		echo form_input($attr);
		echo form_close();
		
		echo '<script type="text/javascript">';
		echo '$(function() {$("#form1").submit();});';
		echo '</script>';
		
		echo utf8_decode('<h4>Estas accediendo a la evaluación de la Capacitación ...</h4>');
	}
}
?>