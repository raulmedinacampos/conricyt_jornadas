<?php
	class Captcha extends CI_Controller {
		public function __construct() {
			parent::__construct();
			$this->load->library('session');
		}
		
		public function index() {
			$string = '';
			for ($i = 0; $i < 6; $i++) {
				$string .= chr(rand(97, 122));
			}
				
			$this->session->set_userdata('captcha_value', $string);
			echo $string;
		}
		
		public function getImage() {				
			$string = $this->uri->segment(3);
			$image = imagecreatetruecolor(148, 34); // dimensiones
			$fuente = "fonts/TCM.ttf";
			$color = imagecolorallocate($image, 30, 30, 30);
			$fondo = imagecolorallocate($image, 210, 210, 200);
			imagefilledrectangle($image, 0, 0, 399, 99, $fondo);
			imagettftext($image, 22, 0, 28, 26, $color, $fuente, $string);
			
			$this->output->set_header("Content-type: image/png");
			imagepng($image);
			imagedestroy($image);
		}
		
		public function testCaptcha() {
			$val = $this->input->post('captcha');
			if($val) {
				echo json_encode(strtolower($val) == strtolower($this->session->userdata('captcha_value')));
			} else {
				echo 0;
			}
		}
	}
?>