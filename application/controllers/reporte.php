<?php
class Reporte extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model("reporte_model", "reporte", TRUE);
		$this->load->helper("form");
	}
	
	public function por_sede() {
		$arregloDatos = array();
		$sedes = $this->reporte->listarSedes();
		$sedes = $sedes->result();
		$data['total'] = 0;
		
		foreach($sedes as $sede) {
			$datos = new stdClass();
			$datos->id = $sede->id_evento;
			$datos->sede = $sede->evento;
			$datos->registrados = $this->reporte->obtenerRegistradosPorSede($sede->id_evento);
			$data['total'] += $datos->registrados;
			$arregloDatos[] = $datos;
		}
		
		$data['datos'] = $arregloDatos;
		
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
	
	public function por_editorial() {
		$id = $this->uri->segment(3);
		settype($id, "int");
		
		$data['registros'] = $this->reporte->consultarRegistradosPorEditorial($id);
		$data['sede'] = $this->reporte->consultarSedePorId($id);
		
		$this->load->view("header");
		$this->load->view("reportes/editorial", $data);
		$this->load->view("footer");
	}
	
	public function por_perfil() {
		$id = $this->uri->segment(3);
		settype($id, "int");
	
		$data['registros'] = $this->reporte->consultarRegistradosPorPerfil($id);
		$data['sede'] = $this->reporte->consultarSedePorId($id);
	
		$this->load->view("header");
		$this->load->view("reportes/perfil", $data);
		$this->load->view("footer");
	}
	
	public function por_usuario() {
		$id = $this->uri->segment(3);
		settype($id, "int");
	
		$registros = $this->reporte->consultarRegistradosPorSede($id);
		$datos_arr = array();
	
		foreach($registros->result() as $registro) {
			$capacitaciones = "";
			$recursos = "";
			$capacitaciones = $this->reporte->consultarCapacitacionesPorUsuario($registro->id_usuario);
				
			if($capacitaciones) {
				foreach($capacitaciones->result() as $val) {
					$recursos .= $val->recurso.", ";
				}
			}
				
			$recursos = trim(trim($recursos), ",");
				
			$dato = new stdClass();
			$dato->nombre = $registro->nombre;
			$dato->ap_paterno = $registro->ap_paterno;
			$dato->ap_materno = $registro->ap_materno;
			$dato->correo = $registro->correo;
			$dato->institucion = $registro->institucion;
			$dato->capacitaciones = $recursos;
				
			$datos_arr[] = $dato;
		}
	
		$this->load->library('excel');
		$fila = 1;
	
		// Contenido de las celdas
		$this->excel->setActiveSheetIndex(0);
	
		// Encabezados
		$this->excel->getActiveSheet()->getStyle('A'.$fila.':F'.$fila)->getFont()->setBold(true);
		$this->excel->getActiveSheet()->getStyle('A'.$fila.':F'.$fila)->getFont()->getColor()->setRGB('FFFFFF');
		$this->excel->getActiveSheet()->getStyle('A'.$fila.':F'.$fila)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('5B9BD5');
		$this->excel->getActiveSheet()->getStyle('A'.$fila.':F'.$fila)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	
		$this->excel->getActiveSheet()
		->setCellValue('A'.$fila, utf8_encode('Nombre'))
		->setCellValue('B'.$fila, utf8_encode('Apellido paterno'))
		->setCellValue('C'.$fila, utf8_encode('Apellido materno'))
		->setCellValue('D'.$fila, utf8_encode('Correo'))
		->setCellValue('E'.$fila, utf8_encode('Institución'))
		->setCellValue('F'.$fila, utf8_encode('Capacitaciones'));
		$fila++;
	
		// Datos
		for($i=0; $i<sizeof($datos_arr); $i++) {
			$row = $datos_arr[$i];
			$this->excel->getActiveSheet()
			->setCellValue('A'.$fila, $row->nombre)
			->setCellValue('B'.$fila, $row->ap_paterno)
			->setCellValue('C'.$fila, $row->ap_materno)
			->setCellValue('D'.$fila, $row->correo)
			->setCellValue('E'.$fila, $row->institucion)
			->setCellValue('F'.$fila, $row->capacitaciones);
	
			$setColor = ($fila > 1 && $fila % 2 == 1) ? true : false;
	
			if($setColor) {
				$this->excel->getActiveSheet()->getStyle('A'.$fila.':F'.$fila)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('DEE1E2');
			}
	
			$fila++;
		}
	
		// Ancho de las columnas
		$this->excel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
		$this->excel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
		$this->excel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
		$this->excel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
		$this->excel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
		$this->excel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
	
		// Nombre de la hoja
		$this->excel->getActiveSheet()->setTitle(utf8_encode('Registros'));
	
		// Headers para salida de archivo xlsx
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="Registros.xlsx"');
		header('Cache-Control: max-age=0');
		// Header para IE9
		header('Cache-Control: max-age=1');
	
		$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
		$objWriter->save("php://output");
		exit();
	}
}
?>