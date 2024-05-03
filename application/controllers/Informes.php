<?php
defined('BASEPATH') or exit('No direct script access allowed');

use UniFi_API\Client;
use Dompdf\Dompdf;

require_once(APPPATH . 'libraries/jpgraph/src/jpgraph.php');
require_once(APPPATH . 'libraries/jpgraph/src/jpgraph_bar.php');

class Informes extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();

		$this->load->library('session');
		$this->load->helper('url');
		$this->load->model('SitesModel');
		$this->load->model('InformesModel');
		$this->load->library('email');

		if (!$this->session->userdata('logged_in')) {
			redirect(base_url() . "Login");
			return;
		}
	}


	public function newEditar()
	{

		$site = $this->input->get('site');
		if (!$this->SitesModel->comprobarSite($site)) redirect(base_url() . "Login");
		if (!$this->session->userdata('adm') && $this->session->userdata('site_id') != $site) redirect(base_url() . "Login");
		else $site = $this->SitesModel->getSitePorSite_Id($site);

		$data['email'] = $this->InformesModel->getEmailPorSite($site->SITE_ID);
		$data['adm'] = $this->session->userdata('adm');

		$this->load->view('plantillas/header', array('site_id' => $site->SITE_ID, 'site_nombre' => $site->NOMBRE));
		$this->load->view('informes/newEditar', $data);
		$this->load->view('plantillas/footer');
	}

	public function guardarEmail()
	{
		$datos = $this->input->post('datos');

		$this->InformesModel->guardarCambios($datos['id'], $datos['site_id'], $datos['email'], $datos['periodicidad']);

		echo json_encode(array(true));
	}

	public function send_emails()
	{
		//Prueba de script de envio de correos. Actualmente está configurado el envio mensual
		$this->load->model('ConexionesModel');
		$this->load->model('InformesModel');
		$this->load->library('email');


		$emails = $this->InformesModel->getEmailsPeriodicidad(2);

		foreach ($emails as $item) {

			// Obtenemos  las conexiones
			$fecha_hoy = date('Y-m-d 00:00:00'); // Obtenemos la fecha de hoy a las 00:00:00
			$fecha_1_mes_atras = date('Y-m-d 00:00:00', strtotime('-1 month', strtotime($fecha_hoy))); // Obtenemos la fecha de hace 7 días a las 00:00:00
			$fecha_ayer = date('Y-m-d 23:59:59', strtotime('-1 day', strtotime($fecha_hoy))); // Obtenemos la fecha de ayer a las 23:59:59
			$conexiones = $this->ConexionesModel->getConexiones($item->SITE_ID, $fecha_1_mes_atras, $fecha_ayer);

			// Obtener la fecha del mes pasado
			$fecha_mes_pasado = strtotime('first day of last month');
			// Array de nombres de meses en español
			$meses = array(
				'enero', 'febrero', 'marzo', 'abril',
				'mayo', 'junio', 'julio', 'agosto',
				'septiembre', 'octubre', 'noviembre', 'diciembre'
			);
			// Obtener el nombre del mes pasado en español
			$nombre_mes_pasado = $meses[date("n", $fecha_mes_pasado) - 1];
			// Nombre del archivo CSV con el mes pasado y el ID del sitio
			$nombre_archivo_csv = 'Conexiones_' . $item->SITE_ID . '_' . $nombre_mes_pasado . '.csv';

			// Ruta completa del archivo CSV
			$ruta_archivo_csv = FCPATH . $nombre_archivo_csv;

			// Encabezados del archivo CSV
			$encabezados = array('EMAIL', 'NOMBRE', 'APELLIDOS', 'SSID', 'FABRICANTE', 'FECHA_CONEXION');

			// Abrir archivo CSV para escritura
			$file = fopen($ruta_archivo_csv, 'w');

			// Escribir encabezados al archivo CSV
			fputcsv($file, $encabezados);

			// Recorrer las conexiones y escribir cada registro al archivo CSV
			foreach ($conexiones as $conexion) {
				// Obtener los datos de cada conexión
				if ($conexion->FABRICANTE == '{"errors":{"detail":"Not Found"}}') $conexion->FABRICANTE = "-";
				$datos_conexion = array(
					$conexion->EMAIL,
					$conexion->NOMBRE,
					$conexion->APELLIDOS,
					$conexion->SSID,
					$conexion->FABRICANTE,
					$conexion->CREATED_AT
				);

				// Escribir los datos al archivo CSV
				fputcsv($file, $datos_conexion);
			}

			// Cerrar el archivo CSV
			fclose($file);

			// Limpiar la instancia de email
			$this->email->clear(TRUE);

			// Adjuntar archivos al correo electrónico
			$this->email->attach($ruta_archivo_csv);
			//$this->email->attach($ruta_archivo_pdf);

			$this->email->from('diego@proquo.es', 'Proquo Wifi');
			$this->email->to($item->EMAIL);
			$this->email->subject('Informe ' . $nombre_mes_pasado . '');
			$this->email->message("Buenos días, <br/> Estos son los datos de los registros del mes de $nombre_mes_pasado en su portal cautivo. <br/> ¡Un saludo!");


			// Enviar correo electrónico
			if (!$this->email->send()) {
				// Manejar el error
				echo json_encode(false);
			} else {
				$this->InformesModel->actualizarEnvio($item->ID, $fecha_hoy);
			}

			// Eliminar los archivos después de enviar el correo
			unlink($ruta_archivo_csv);
		}

		echo json_encode(true);
	}


	public function generarPDFsemanal()
	{


		// Cargamos modelos y librerias necesarias
		$this->load->model('ConexionesModel');
		$this->load->model('InformesModel');
		$this->load->model('SitesModel');
		$this->load->library('email');
		require_once 'vendor/autoload.php';

		/*CODIGO REUTILIZABLE EN EL BUCLE FOREACH*/

		//Ajustes Unifi
		$controllerurl = 'https://hermes01.tecnologiasencilla.com:8443';
		$user = 'admin';
		$password = 'Tecn0sencilla';
		$version = '8.1.113';

		$fecha_hoy = '2024-03-01';
		$fecha_ayer = date('Y-m-d', strtotime('-1 day', strtotime($fecha_hoy)));
		$primer_dia_mes_pasado = date('Y-m-01', strtotime('-1 month', strtotime($fecha_hoy)));

		$encabezados = array('EMAIL', 'NOMBRE', 'APELLIDOS', 'SSID', 'FABRICANTE', 'FECHA_CONEXION');


		// Obtener la fecha del mes pasado
		$fecha_mes_pasado = strtotime('first day of last month');
		// Array de nombres de meses en español
		$meses = array(
			'enero', 'febrero', 'marzo', 'abril',
			'mayo', 'junio', 'julio', 'agosto',
			'septiembre', 'octubre', 'noviembre', 'diciembre'
		);
		// Obtener el nombre del mes pasado en español
		$nombre_mes_pasado = $meses[date("n", $fecha_mes_pasado) - 1];


		// Obtenemos los sites con la informacion del email que tengan periodicidad mensual
		$emails = $this->InformesModel->getEmailsPeriodicidad(2);

		// Iteramos esos datos
		foreach ($emails as $item) {

			$site = $this->SitesModel->getSitePorSite_Id($item->SITE_ID);
			$nombreSite = $site->NOMBRE;
			$nombreSiteSinEspacios = str_replace(' ', '', $nombreSite);

			// Obtenemos  las conexiones
			$conexiones = $this->ConexionesModel->getConexiones($item->SITE_ID, $primer_dia_mes_pasado, $fecha_ayer); // Obtenemos los datos de las conexiones del mes


			/* INICIO CSV */

			// Nombre del archivo CSV
			$nombre_archivo_csv = 'Conexiones_' . $nombreSiteSinEspacios . 'mes' . $nombre_mes_pasado . '.csv';

			// Ruta completa del archivo CSV
			$ruta_archivo_csv = FCPATH . '/scripts/' . $nombre_archivo_csv;


			// Abrir archivo CSV para escritura
			$file = fopen($ruta_archivo_csv, 'w');

			// Escribir encabezados al archivo CSV
			fputcsv($file, $encabezados);

			// Recorrer las conexiones y escribir cada registro al archivo CSV
			foreach ($conexiones as $conexion) {
				// Quitar error de mac privada
				if ($conexion->FABRICANTE == '{"errors":{"detail":"Not Found"}}') $conexion->FABRICANTE = "-";
				$datos_conexion = array(
					$conexion->EMAIL,
					$conexion->NOMBRE,
					$conexion->APELLIDOS,
					$conexion->SSID,
					$conexion->FABRICANTE,
					$conexion->CREATED_AT
				);

				// Escribir los datos al archivo CSV
				fputcsv($file, $datos_conexion);
			}

			// Cerrar el archivo CSV
			fclose($file);

			/* FIN CSV */






			/*INICIO GRAFICO*/

			$primer_dia_mes_pasado = date('Y-m-01', strtotime('-1 month'));
			$ultimo_dia_mes_pasado = date('Y-m-t', strtotime($primer_dia_mes_pasado));
			$num_dias_mes_pasado = date('j', strtotime($ultimo_dia_mes_pasado));
			$registros_por_dia = array_fill(0, $num_dias_mes_pasado, 0); // Creamos un array con numero de elementos igual a los dias del mes, inicializados en 0

			$registrosTotalesPortal = 0;

			// Iteramos sobre las conexiones y contamos cuántas hay por día
			foreach ($conexiones as $conexion) {
				$dia = date('d', strtotime($conexion->{'CREATED_AT'}));
				$registros_por_dia[$dia - 1]++;
				$registrosTotalesPortal += 1;
			}

			//Datos grafico
			$data1y = $registros_por_dia;

			for ($i = 0; $i < $num_dias_mes_pasado; $i++) {
				$lables_eje_x[$i] = $i +1;
			}

			$unifi_connection = new Client(
				$user,
				$password,
				$controllerurl,
				$item->SITE_ID,
				$version
			);

			$unifi_connection->login();

			// No podemos obtener las stats diarias mas alla de una semana, asi que obtenemos las stats totales del mes
			$timestamp_ayer = strtotime($fecha_ayer) * 1000;
			$timestamp_primer_dia_mes_pasado = strtotime($primer_dia_mes_pasado) * 1000;
			$conexionesUnifi = $unifi_connection->stat_monthly_site($timestamp_primer_dia_mes_pasado, $timestamp_ayer);

			$conexionesTotales = $conexionesUnifi[0]->{'wlan-num_sta'};
			$bytes = $conexionesUnifi[0]->{'wlan_bytes'};

			if (!isset($conexionesTotales)) $conexionesTotales = 0;
			if (!isset($bytes)) $bytes = 0;
			$megabytes = $bytes / 1024 / 1024;
			if ($megabytes >= 1024) {
				$megabytes = $megabytes / 1024;
				$trafico = number_format($megabytes, 1) . "GB";
			} else $trafico = intval($megabytes) . "MB";


			// Create the graph. These two calls are always required
			$graph = new Graph(1000, 400, 'auto');
			$graph->SetScale("textlin");

			$theme_class = new UniversalTheme;
			$graph->SetTheme($theme_class);


			$graph->SetBox(false);

			$graph->ygrid->SetFill(false);

			$graph->xaxis->SetTickLabels($lables_eje_x);

			$graph->yaxis->HideLine(false);
			$graph->yaxis->HideTicks(false, false);

			// Create the bar plots
			$b1plot = new BarPlot($data1y);

			// Create the grouped bar plot
			$gbplot = new GroupBarPlot(array($b1plot));
			// ...and add it to the graPH
			$graph->Add($gbplot);


			$b1plot->SetColor("white");
			$b1plot->SetFillColor("#ee82ee");
			$b1plot->value->Show();
			$b1plot->legend = 'Registros portal';

			$graph->SetMargin(60, 40, 80, 40);
			$graph->legend->Pos(0.2, 0.01, 'right', 'top');
			$graph->legend->SetColumns(1);

			ob_start();
			$graph->Stroke();
			$imageData = ob_get_clean();

			$rutaImagenTemporal = FCPATH . '/scripts/' . "image.jpg";
			file_put_contents($rutaImagenTemporal, $imageData);

			/*FIN GRAFICO*/




			/*INCIO PDF*/

			$contenido_pdf = '<!doctype html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <title>Informe ' . $nombreSite . ' ' . $nombre_mes_pasado . ' </title>

           <link rel="stylesheet" type="text/css" href="https://elmejorwifi.com/public/css/pdf.css" />

        </head>
        <body>

        <div class="information">
            <table width="100%">
                <tr>
                    <td align="left" style="width: 33%;">
                        <h3>Proquo Tecnologia Sencilla</h3>
                        <pre>
Calle Berriobide 36, oficina 020
31013 Ansoain
Navarra
948346126
                            </pre>
                    </td>
                    <td align="center">
                        <img src="https://elmejorwifi.com/assets/img/ProQuo_whitetrans_big.png" alt="Logo" width="200" class="logo"/>
                    </td>
                    <td align="right" style="width: 33%;">

                    </td>
                </tr>
            </table>
        </div>

        <br/>

            <div class="invoice">
                <h3 style="margin-left:40px;">Informe ' . $nombreSite . '. .' . $nombre_mes_pasado . ' </h3>
                <p class="dato"> Conexiones totales: ' . $conexionesTotales . ' </p>
                <p class="dato"> Registros en el portal cautivo: ' . $registrosTotalesPortal . ' </p>
                <p class="dato"> Tráfico acumulado: ' . $trafico . '</p>
                <div style="text-align:center; margin-top:50px;">
                <img src="https://elmejorwifi.com/scripts/image.jpg" />
                </div>
            </div>


            <div class="information footer">
                <span>Proquo Tecnologia Sencilla</span>
            </div>

        </body>
        </html>';

			// Creación del objeto Dompdf
			$dompdf = new Dompdf();
			// Cargar contenido HTML en Dompdf
			$dompdf->loadHtml($contenido_pdf);
			// Hay que poner el isremoteEnabled true para que acceda a archivos externos, como .css o imagenes
			$options = $dompdf->getOptions();
			$options->set('isRemoteEnabled', true);
			$dompdf->setOptions($options);
			// Renderizar PDF
			$dompdf->render();
			// Obtener contenido del PDF generado
			$output = $dompdf->output();
			// Ruta del archivo PDF
			$ruta_archivo_pdf = FCPATH . '/scripts/' . 'Informe_' . $nombreSiteSinEspacios . '_' . $nombre_mes_pasado . '.pdf';
			// Escribir contenido del PDF en un archivo
			file_put_contents($ruta_archivo_pdf, $output);

			/*FIN PDF*/


			/* INICIO ENVIO MAIL */
			// Limpiar la instancia de email
			$this->email->clear(TRUE);

			// Adjuntar archivos al correo electrónico
			$this->email->attach($ruta_archivo_csv);
			$this->email->attach($ruta_archivo_pdf);

			$this->email->from('proquo@tecnologiasencilla.com', 'Proquo Wifi');
			$this->email->to($item->EMAIL);
			$this->email->subject('Informe ' . $nombre_mes_pasado . '');
			$this->email->message("Buenos días, <br/> Estos son los datos de los registros de ".$nombre_mes_pasado." pasada en su portal cautivo. <br/> ¡Un saludo!");

			/*
			// Enviar correo electrónico
			if (!$this->email->send()) {
				echo 'Error al enviar el correo.';
				$error =  $this->email->print_debugger();
			} else {
				// Si se ha enviado actualizamos el campo en BD
				$this->InformesModel->actualizarEnvio($item->ID, $fecha_hoy);
			}
*/
			/* FIN ENVIO MAIL */

			// Eliminar los archivos después de enviar el correo
			unlink($ruta_archivo_csv);
			//unlink($ruta_archivo_pdf);
			unlink($rutaImagenTemporal);
		}


		echo json_encode(base_url() . 'scripts/Informe_' . $nombreSiteSinEspacios . '_' . $nombre_mes_pasado . '.pdf');
	}
}
