<?php
class Carta_invitacion extends CI_Controller {
	function __construct() {
		parent::__construct();
		$this->load->model('registro_model', 'registro', TRUE);
		$this->load->helper('form');
	}
	
	public function index() {
		$data['sedes'] = $this->registro->getAuxSchedule();
		
		$this->load->view('header');
		$this->load->view('carta_invitacion', $data);
		$this->load->view('footer');
	}
	
	private function strtoupper_utf8($cadena) {
		$convertir_de = array(
			"a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u",
			"v", "w", "x", "y", "z", "à", "á", "â", "ã", "ä", "å", "æ", "ç", "è", "é", "ê", "ë","e", "ì", "í", "î", "ï",
			"ð", "ñ", "ò", "ó", "ô", "õ", "ö", "ø", "ù", "ú", "û", "ü", "ý", "?", "?", "?", "?", "?", "?", "?", "?",
			"?", "?", "?", "?", "?", "?", "?", "?", "?", "?", "?", "?", "?", "?", "?", "?", "?", "?", "?", "?", "?",
			"?", "?", "?", "?"
		);
		$convertir_a = array(
			"A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U",
			"V", "W", "X", "Y", "Z", "À", "Á", "Â", "Ã", "Ä", "Å", "Æ", "Ç", "È", "É", "Ê", "Ë","E", "Ì", "Í", "Î", "Ï",
			"Ð", "Ñ", "Ò", "Ó", "Ô", "Õ", "Ö", "Ø", "Ù", "Ú", "Û", "Ü", "Ý", "?", "?", "?", "?", "?", "?", "?", "?",
			"?", "?", "?", "?", "?", "?", "?", "?", "?", "?", "?", "?", "?", "?", "?", "?", "?", "?", "?", "?", "?",
			"?", "?", "?", "?"
		);
		return str_replace($convertir_de, $convertir_a, $cadena);
	}
	
	private function formatearFecha($fecha) {
		$meses = array("", "enero", "febrero", "marzo", "abril", "mayo", "junio", "julio", "agosto", "septiembre", "octubre", "noviembre", "diciembre");
		list($anio, $mes, $dia) = explode("-", $fecha);
		$str_fecha = "México D.F. a ".$dia." de ".$meses[(int)$mes]." de ".$anio;
		return $str_fecha;
	}
	
	private function crearInvitacion($data) {
		$fecha = $this->formatearFecha(date('Y-m-d'));
		$nombre = utf8_decode($data['nombre']);
		$remitente = $this->strtoupper_utf8(trim($nombre));
		$institucion = $this->strtoupper_utf8(utf8_decode(trim($data['institucion'])));
		$sede = $this->registro->getAuxScheduleByID($data['sede']);
		
		switch($sede->tipo) {
			case 1:
				$periodo = 'el día '.$sede->fecha;
				break;
			case 2:
				$periodo = 'los días '.$sede->fecha;
				break;
			case 3:
				$periodo = 'del '.$sede->fecha;
				break;
			default:
				$periodo = '';
				break;
		}
		
		if(strpos($sede->institucion, 'Universidad') !== false) {
			$artInstitucion = 'la';
		} else {
			$artInstitucion = 'el';
		}
		
		$header = '<p class="header"><img src="'.base_url().'images/header_pdf.jpg" /></p>';
		$footer = '<p class="footer"><strong>Oficina del Consorcio Nacional de Recursos de Información Científica y Tecnológica</strong><br />Av. Insurgentes Sur 1582, Col. Crédito Constructor, Del. Benito Juárez, C.P. 03940 México D.F. - Tel: 5322 7700 ext. 4020 a la 4026</p>';
		$html = '<div class="contenido">';
		$html .= '<p class="titulo2">Consorcio Nacional de Recursos de Información Científica y Tecnológica</p>';
		$html .= '<p class="fecha">'.$fecha.'</p>';
		$html .= '<p class="remitente">ESTIMADO(A) '.$remitente.'<br />'.$institucion.'<br />P r e s e n t e:</p>';
		$html .= '<p>El Consorcio Nacional de Recursos de Información Científica y Tecnológica tiene el agrado de invitarle cordialmente a las <em>Jornadas de Capacitación CONRICYT 2015</em>, cuyo objetivo central es fortalecer las habilidades informativas de los estudiantes, académicos, investigadores, bibliotecarios y referencistas, para ampliar, consolidar y facilitar el acceso a la información científica en formatos digitales.</p>';
		$html .= '<p>La Jornada se llevará a cabo '.$periodo.' en '.$artInstitucion.' '.utf8_decode($sede->institucion).', cuyo programa de actividades podrás consultarlo en la página http://jornadascapacitacion.conricyt.mx/jornadas</p>';
		$html .= '<p class="firma">A t e n t a m e n t e,</p>';
		$html .= '<blockquote><p><img src="'.base_url().'images/firma_pdf.jpg" /></p></blockquote>';
		$html .= '<p>Mtra. Margarita Ontiveros y Sánchez de la Barquera<br />Coordinadora General<br />Consorcio Nacional de Recursos de Información Científica y Tecnológica</p>';
		$html .= '</div>';
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, base_url('css/pdf.css'));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$stylesheet = curl_exec($ch);
		curl_close($ch);
			
		$this->load->library('pdf');
		$pdf = $this->pdf->load("c", "Letter", "", "", 20, 20, 45, 30, 10, 10);
		$pdf->SetHTMLHeader(utf8_encode($header));
		$pdf->SetHTMLFooter(utf8_encode($footer));
		$pdf->WriteHTML($stylesheet, 1);
		$pdf->WriteHTML(utf8_encode($html));
		//$pdf->Output();
		$contenido_pdf = $pdf->Output(utf8_encode('Invitación a Entrepares.pdf'), 'S');
		return $contenido_pdf;
	}
	
	private function actualizarContador() {
		$archivo = fopen("contador.txt", "r") or die();
		$valorActual = fread($archivo,filesize("contador.txt"));
		$valorActual++;
		fclose($archivo);
		
		$archivo = fopen("contador.txt", "w+") or die();
		fwrite($archivo, $valorActual);
		fclose($archivo);
	}
	
	public function generarCarta() {
		$data['nombre'] = $this->input->post('nombre');
		$data['institucion'] = $this->input->post('institucion');
		$data['sede'] = $this->input->post('sede');
		$this->actualizarContador();
		$this->crearInvitacion($data);
	}
	
	public function adjuntarCarta($data) {
		$this->actualizarContador();
		$this->crearInvitacion($data);
	}
}
?>