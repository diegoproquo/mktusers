<?php
defined('BASEPATH') or exit('No direct script access allowed');

use UniFi_API\Client;

class Dashboard extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();

		$this->load->library('session');
		$this->load->helper('url');
		$this->load->helper('html');
		$this->load->model("SitesModel");
		$this->load->model("ConexionesModel");
		$this->load->model("PortalModel");

		// Si no esta logueado, al login
		if (!$this->session->userdata('logged_in')) {
			redirect(base_url() . "Login");
			return;
		}
	}

	public function index()
	{

		$site = $this->input->get('site'); //Obtenemos site_id de la cabecera url
		if (!$this->SitesModel->comprobarSite($site)) redirect(base_url() . "Login"); // Si no xiste el site, al login
		if (!$this->session->userdata('adm') && $this->session->userdata('site_id') != $site) redirect(base_url() . "Login"); //Si no eres admin y estas en un site que no es el tuyo, al login
		else $site = $this->SitesModel->getSitePorSite_Id($site); // SI todo es correcto, obtenemos datos del site

		$data['site_id'] = $site->SITE_ID;

		// Registros en el portal cautivo HOY
		$fecha_actual = date('Y-m-d');
		$conexionesHorasDB = $this->ConexionesModel->getConexionesHoy($fecha_actual, $site->SITE_ID);
		$data['conexionesHorasDB'] = $conexionesHorasDB;

		// Registros en el portal cautivo de los 7 ULTIMOS dias
		$conexiones7diasDB = $this->ConexionesModel->getConexionesUltimos7dias($site->SITE_ID);
		$data['conexiones7diasDB'] = $conexiones7diasDB;

		// Obtener datos de 24h para las cards
		$conexiones24h = $this->getConexionesTotales24h($site->SITE_ID);
		$data['conexiones24horas'] = $conexiones24h[0];
		$data['trafico24horas'] = $conexiones24h[1];

		// Obtener datos de conexiones por HORA para el gráfico de funcion
		$conexionesUnifiHoras = $this->getConexionesPorHoraHoy($site->SITE_ID);
		$data['dataHoras'] = $conexionesUnifiHoras[0];
		$data['labelsHoras'] = $conexionesUnifiHoras[1];

		// Obtener datos de conexiones de lo ULTIMOS 7 DIAS para el gráfico de barras y para las cards
		$conexionesDiaraiasUnifi = $this->getConexionesUltimos7Dias($site->SITE_ID);
		$data['dataDiarias'] = $conexionesDiaraiasUnifi[0];
		$data['labelsDiarias'] = $conexionesDiaraiasUnifi[1];
		$data['conexionesMaximasDiarias'] = $conexionesDiaraiasUnifi[2];
		$data['conexiones7dias'] = $conexionesDiaraiasUnifi[3];
		$data['trafico7dias'] = $conexionesDiaraiasUnifi[4];

		// Generamos los datos para la datatable
		$columns = $this->ObtenerColumnasDatatable($site->SITE_ID);
		$data['columns'] = $columns[0];
		$data['esconder'] = $columns[1];
		$fechaInicio = date('Y-m-d', strtotime('-1 month'));
		$data['fechaInicio'] = $fechaInicio;
		$data['fechaActual'] = $fecha_actual;
		$data['fechaHace7dias'] = date('Y-m-d', strtotime('-7 days'));
		$data['data'] = $this->MostrarRecargarDatos($site->SITE_ID, $fechaInicio, $fecha_actual);

		$this->load->view('plantillas/header', array('site_id' => $site->SITE_ID, 'site_nombre' => $site->NOMBRE));
		$this->load->view('dashboard/show', $data);
		$this->load->view('plantillas/footer');
	}

	public function getConexionesTotales24h($site)
	{
		$controllerurl = 'https://hermes01.tecnologiasencilla.com:8443';
		$user = 'admin';
		$password = 'Tecn0sencilla';
		$version = '8.1.113';

		require_once 'vendor/autoload.php';

		$unifi_connection = new Client(
			$user,
			$password,
			$controllerurl,
			$site,
			$version
		);

		$loginresults = $unifi_connection->login();

		if($loginresults === false){
			$this->session->set_flashdata('error', 'No es posible recuperar datos de Unifi. Espera unos minutos o contacta con el administrador.');
			redirect(base_url()."Login");
		}

		$fecha_actual = date('Y-m-d H:i:s');
		$timestamp_milisegundos_final  = strtotime($fecha_actual) * 1000;

		$fecha_hace_24_horas = date('Y-m-d H:i:s', strtotime('-24 hours', strtotime($fecha_actual)));
		$timestamp_milisegundos_inicial = strtotime($fecha_hace_24_horas) * 1000;

		$conexionesUnifi = $unifi_connection->stat_hourly_site($timestamp_milisegundos_inicial, $timestamp_milisegundos_final);

		if($conexionesUnifi === false){
			$this->session->set_flashdata('error', 'No es posible recuperar datos de Unifi. Espera unos minutos o contacta con el administrador.');
			redirect(base_url()."Login");
		}

		$bytes = 0;
		foreach ($conexionesUnifi as $item) {
			$conexionesTotales = $item->{'wlan-num_sta'};
			$bytes = $bytes + $item->{'wlan_bytes'};
		}

		//Datos del trafico para las cards de arriba
		if (!isset($conexionesTotales)) $conexionesTotales = 0;
		if (!isset($bytes)) $bytes = 0;
		$megabytes = $bytes / 1024 / 1024;
		if ($megabytes >= 1024) {
			$megabytes = $megabytes / 1024;
			$trafico = number_format($megabytes, 1) . "GB";
		} else $trafico = intval($megabytes) . "MB";

		return array($conexionesTotales, $trafico);
	}

	public function getConexionesPorHoraHoy($site)
	{

		$controllerurl = 'https://hermes01.tecnologiasencilla.com:8443';
		$user = 'admin';
		$password = 'Tecn0sencilla';
		$version = '8.1.113';

		require_once 'vendor/autoload.php';

		$unifi_connection = new Client(
			$user,
			$password,
			$controllerurl,
			$site,
			$version
		);

		$loginresults = $unifi_connection->login();

		if($loginresults === false){
			$this->session->set_flashdata('error', 'No es posible recuperar datos de Unifi. Espera unos minutos o contacta con el administrador.');
			redirect(base_url()."Login");
		}

		$fecha_actual = date('Y-m-d');
		$hora_inicial = $fecha_actual . " 00:00:00";
		$timestamp_milisegundos_inicial  = strtotime($hora_inicial) * 1000;

		$hora_final = $fecha_actual . " 23:59:59";
		$timestamp_milisegundos_final = strtotime($hora_final) * 1000;
		$conexionesUnifi = $unifi_connection->stat_hourly_site($timestamp_milisegundos_inicial, $timestamp_milisegundos_final);

		if($conexionesUnifi === false){
			$this->session->set_flashdata('error', 'No es posible recuperar datos de Unifi. Espera unos minutos o contacta con el administrador.');
			redirect(base_url()."Login");
		}

		// Generar etiquetas de hora en función de la longitud del array $conexionesUnifi
		$labelsHoras = [];
		$num_horas = count($conexionesUnifi);

		// Obtenemos a partir de los timestamps de las conexiones de Unifi, el indie del array mas alto al que corresponderan las conexiones
		$max_indice = $num_horas;
		foreach ($conexionesUnifi as $item) {
			$timestamp_segundos = $item->{'time'} / 1000;
			$hora = date('G', $timestamp_segundos);
			if($hora > $max_indice) $max_indice = $hora;
		}

		// Nos quedamos con las conesxiones inalambricas. Calculamos la posicion del array en funcion del timestamp de unifi
		$conexionesHoras = array_fill(0, $max_indice, 0);
		foreach ($conexionesUnifi as $item) {
			$timestamp_segundos = $item->{'time'} / 1000;
			$hora = date('G', $timestamp_segundos);
			$conexionesHoras[$hora] = $item->{'wlan-num_sta'};
		}

		// Generamos las etiquetas de las horas en funcion del indice mas alto
		for ($i = 0; $i < $max_indice; $i++) {
			$hora = str_pad($i, 2, '0', STR_PAD_LEFT); // Añade un cero inicial si el número es de un solo dígito
			$labelsHoras[] = $hora . ':00'; // Añade la hora al formato 'HH:00'
		}

		return array($conexionesHoras, $labelsHoras);
	}

	public function getConexionesUltimos7Dias($site)
	{
		$controllerurl = 'https://hermes01.tecnologiasencilla.com:8443';
		$user = 'admin';
		$password = 'Tecn0sencilla';
		$version = '8.1.113';

		require_once 'vendor/autoload.php';

		$unifi_connection = new Client(
			$user,
			$password,
			$controllerurl,
			$site,
			$version
		);

		$loginresults = $unifi_connection->login();

		if($loginresults === false){
			$this->session->set_flashdata('error', 'No es posible recuperar datos de Unifi. Espera unos minutos o contacta con el administrador.');
			redirect(base_url()."Login");
		}

		// Calculamos fechas para que unifi nos devuelva las fechas de los ultimos 7 dias. La hora no se tiene en cuenta en la llamada, se usa para el timestamp
		$fecha_ayer = date("Y-m-d", strtotime('-1 day')) . " 23:59:59";
		$fecha_hace_7_dias = date("Y-m-d", strtotime('-7 day')) . " 00:00:00";
		$timestamp_ayer = strtotime($fecha_ayer) * 1000;
		$timestamp_hace_7_dias = strtotime($fecha_hace_7_dias) * 1000;

		$conexionesUnifi = $unifi_connection->stat_daily_site($timestamp_hace_7_dias, $timestamp_ayer);

		if($conexionesUnifi === false){
			$this->session->set_flashdata('error', 'No es posible recuperar datos de Unifi. Espera unos minutos o contacta con el administrador.');
			redirect(base_url()."Login");
		}

		$conexionesDiarias = [];
		$conexionesMaximas = 0;
		$conexionesTotales = 0;
		$bytes = 0;
		$conexionesDiarias = array_fill(0, 24, 0);

		//Calculamos la posicion del array en funcion del timestamp de unifi
		$numero_dia_hace_7_dias = date('j', strtotime($fecha_hace_7_dias));
		foreach ($conexionesUnifi as $item) {
			$timestamp_segundos = $item->{'time'} / 1000;
			$numero_dia = date('j', $timestamp_segundos);
			$indiceArray = $numero_dia - $numero_dia_hace_7_dias;
			$conexionesDiarias[$indiceArray] = $item->{'wlan-num_sta'};
			if ($item->{'wlan-num_sta'} > $conexionesMaximas) $conexionesMaximas = $item->{'wlan-num_sta'};
			$conexionesTotales = $conexionesTotales + $item->{'wlan-num_sta'};
			$bytes = $bytes + $item->{'wlan_bytes'};
		}

		//Datos de trafico para las cards de arriba
		if (!isset($conexionesTotales)) $conexionesTotales = 0;
		if (!isset($bytes)) $bytes = 0;
		$megabytes = $bytes / 1024 / 1024;
		if ($megabytes >= 1024) {
			$megabytes = $megabytes / 1024;
			$trafico = number_format($megabytes, 1) . "GB";
		} else $trafico = intval($megabytes) . "MB";


		//Labels eje X
		$dias_semana = array('Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado');

		// Obtener el número del día de la semana actual
		$dia_actual = date('N');

		$dias_array = array();

		for ($i = 6; $i >= 0; $i--) {
			// Ajustar el índice del día de la semana para comenzar en martes
			$indice_dia = ($dia_actual - 1 - $i + 7) % 7;

			// Obtener el nombre del día de la semana
			$nombre_dia = $dias_semana[$indice_dia];

			// Obtener el número del día del mes
			$numero_dia_mes = date('j', strtotime("-$i days")) - 1;

			// Agregar el nombre del día y el número del día del mes al array
			$dias_array[] = "$nombre_dia $numero_dia_mes";
		}

		return array($conexionesDiarias, $dias_array, $conexionesMaximas, $conexionesTotales, $trafico);
	}

	// Obtiene los datos de conexion por horas de Unifi de un dia completo
	public function getConexionesPorHoraDia($site, $fecha)
	{
		$controllerurl = 'https://hermes01.tecnologiasencilla.com:8443';
		$user = 'admin';
		$password = 'Tecn0sencilla';
		$version = '8.1.113';

		require_once 'vendor/autoload.php';

		$unifi_connection = new Client(
			$user,
			$password,
			$controllerurl,
			$site,
			$version
		);

		$loginresults = $unifi_connection->login();

		if($loginresults === false){
			$this->session->set_flashdata('error', 'No es posible recuperar datos de Unifi. Espera unos minutos o contacta con el administrador.');
			redirect(base_url()."Login");
		}

		$hora_inicial = $fecha . " 00:00:00";
		$timestamp_milisegundos_inicial  = strtotime($hora_inicial) * 1000;

		$hora_final = $fecha . " 23:59:59";
		$timestamp_milisegundos_final = strtotime($hora_final) * 1000;
		$conexionesUnifi = $unifi_connection->stat_hourly_site($timestamp_milisegundos_inicial, $timestamp_milisegundos_final);

		if($conexionesUnifi === false){
			$this->session->set_flashdata('error', 'No es posible recuperar datos de Unifi. Espera unos minutos o contacta con el administrador.');
			redirect(base_url()."Login");
		}

		// Nos quedamos con las conesxiones inalambricas
		$conexionesHoras = array_fill(0, 24, 0);
		foreach ($conexionesUnifi as $item) {
			$timestamp_segundos = $item->{'time'} / 1000;
			$hora = date('G', $timestamp_segundos);
			$conexionesHoras[$hora] = $item->{'wlan-num_sta'};
		}

		// Generamos etiquetas para el eje x del grafico
		$labelsHoras = [];
		for ($i = 0; $i < 24; $i++) {
			if ($i < 10) {
				$hora = sprintf("%02d", $i); // Agrega un cero a la izquierda si es menor que 10
			} else {
				$hora = $i; // Mantén la hora sin cambios para las horas de dos dígitos
			}
			$labelsHoras[] = $hora . ':00'; // Añade la hora al formato 'HH:00'
		}

		return array($conexionesHoras, $labelsHoras);
	}

	public function ActualizarGraficoFuncion()
	{
		$datos = $this->input->post('datos');

		// Obtener la fecha del array
		$fecha = $datos["fechaGraficoFuncion"];

		// Obtener la acción del array
		$accion = $datos["accion"];

		// Aplicar la accion a la fecha
		$nueva_fecha = date("Y-m-d", strtotime($fecha . " " . $accion));

		// Obtenermos los registros de la BD que coincidan con la nueva fecha
		$conexionesHorasDB = $this->ConexionesModel->getConexionesDia($nueva_fecha, $datos['site_id']);

		$conexiones = $this->getConexionesPorHoraDia($datos['site_id'], $nueva_fecha);

		//Formateamos fecha para mostrarla correctamente en el titulo, aunque internamente trabajaremos con el formato YYYY-MM-DD
		$fecha_formateada = date("d-m-Y", strtotime($nueva_fecha));

		//Obtenemos el html del grafico
		$html_grafico = actualizarGraficoFuncion($conexiones[0], $conexionesHorasDB, $conexiones[1], "graficoHoras", "Conexiones por hora " . $fecha_formateada);

		echo json_encode(array(true, $html_grafico, $nueva_fecha));
	}

	public function ObtenerColumnasDatatable($site_id)
	{
		$portal = $this->PortalModel->uno($site_id);

		// Columnas por defecto
		$columns = array("ID", "EMAIL", "NOMBRE", "APELLIDOS", "FABRICANTE", "SSID", "FECHA CONEXION",);

		// Por defecto quitamos la columna ID
		unset($columns[array_search("ID", $columns)]);

		// Descomentar para hacer ELIMINAR las columnas en funcion de si las opciones estan activadas en la tabla tbl_portal

		// Verificar si se debe incluir la columna EMAIL
		/*if ($portal->REGISTRO_EMAIL != "1") {
			unset($columns[array_search("EMAIL", $columns)]); // Remover la columna EMAIL
		}

		// Verificar si se debe incluir la columna NOMBRE
		if ($portal->REGISTRO_NOMBRE != "1") {
			unset($columns[array_search("NOMBRE", $columns)]); // Remover la columna NOMBRE
		}

		// Verificar si se debe incluir la columna APELLIDOS
		if ($portal->REGISTRO_APELLIDOS != "1") {
			unset($columns[array_search("APELLIDOS", $columns)]); // Remover la columna APELLIDOS
		}*/


		// OCULTA las columnas que no estan seleccionadas en el portal
		$esconder = '';
		// Verificar si se debe incluir la columna EMAIL
		if ($portal->REGISTRO_EMAIL != "1") {
			$esconder .= '0,';
		}
		// Verificar si se debe incluir la columna NOMBRE
		if ($portal->REGISTRO_NOMBRE != "1") {
			$esconder .= '1,';
		}
		// Verificar si se debe incluir la columna APELLIDOS
		if ($portal->REGISTRO_APELLIDOS != "1") {
			$esconder .= '2,';
		}
		// Eliminar la última coma si existe
		$esconder = rtrim($esconder, ',');

		return array(array_values($columns), $esconder); // Reindexar el array y devolverlo
	}

	public function FiltrarTabla()
	{
		$datos = $this->input->post('datos');

		$dataRegistros = $this->MostrarRecargarDatos($datos['site_id'], $datos['fechaInicio'], $datos['fechaFin']);

		echo json_encode(array(true, $dataRegistros));
	}

	public function MostrarRecargarDatos($site_id, $fecha_inicio, $fecha_fin)
	{
		require_once 'vendor/autoload.php';
		$conexiones = $this->ConexionesModel->getConexiones($site_id, $fecha_inicio, $fecha_fin);

		foreach ($conexiones as $item) {
			$item->btnSeleccionar = '
			<div class="dropdown">
			<button class="dropbtn"><i class="fas fa-ellipsis-vertical"></i></button>
			<div class="dropdown-content">

			</div>
		  </div> ';

			if ($item->EMAIL == "" || $item->EMAIL == null) $item->EMAIL = "-";
			if ($item->NOMBRE == "" || $item->NOMBRE == null) $item->NOMBRE = "-";
			if ($item->APELLIDOS == "" || $item->APELLIDOS == null) $item->APELLIDOS = "-";
			if ($item->FABRICANTE == "" || $item->FABRICANTE == null || $item->FABRICANTE == '{"errors":{"detail":"Not Found"}}') $item->FABRICANTE = "-";

			$item->{'FECHA CONEXION'} = $item->CREATED_AT;
		}

		return $conexiones;
	}
}
