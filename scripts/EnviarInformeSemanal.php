<?php

/*SCRIPT DE INFORMES SEMANALES
 La lógica del script esa configurada para que la tarea programada se ejecute los lunes a las 9:00
 En caso de querer cambiar esa condicion, es necesario modificar la lógica de las fechas, pues ahora mismo obtiene los datos
 de las conexiones al portalo cautivo desde el lunes pasado a las 00:00:00 y "ayer" (domingo) a las 23:59:59.

 El script recoge de base de datos los sites que desean recibir un informe semanal. Despues obtiene los datos de conexiones del periodo 
 de tiempo en ese site, para finalmente configurar un archivo csv y enviarlo por correo al destinatario especificado.

 La configuración del servidor de corero electrónico se hace en el archivo system/libraries/Email
 */

// Instancia de CodeIgniter.
require_once('/var/www/vhosts/elmejorwifi.com/httpdocs/unifi_manager/index.php');
//Instancia del autoload.php para cargar librerias carpeta vendor
require_once('/var/www/vhosts/elmejorwifi.com/httpdocs/unifi_manager/vendor/autoload.php');

use UniFi_API\Client;
use Dompdf\Dompdf;

require_once(APPPATH . 'libraries/jpgraph/src/jpgraph.php');
require_once(APPPATH . 'libraries/jpgraph/src/jpgraph_bar.php');

// Instancia de CodeIgniter
$CI = &get_instance();

// Cargamos modelos y librerias necesarias
$CI->load->model('ConexionesModel');
$CI->load->model('InformesModel');
$CI->load->model('SitesModel');
$CI->load->library('email');


/*CODIGO REUTILIZABLE EN EL BUCLE FOREACH*/

//Ajustes Unifi
$controllerurl = 'https://hermes01.tecnologiasencilla.com:8443';
$user = 'admin';
$password = 'Tecn0sencilla';
$version = '8.1.113';

$fecha_hoy = date('Y-m-d 00:00:00'); // Obtenemos la fecha de hoy a las 00:00:00
$fecha_7_dias_atras = date('Y-m-d 00:00:00', strtotime('-7 days', strtotime($fecha_hoy))); // Obtenemos la fecha de hace 7 días a las 00:00:00
$fecha_ayer = date('Y-m-d 23:59:59', strtotime('-1 day', strtotime($fecha_hoy))); // Obtenemos la fecha de ayer a las 23:59:59

$fecha_semana_pasada = strtotime('-7 days');
$fecha_semana_pasada_formateada = date("d-m-Y", $fecha_semana_pasada);

$encabezados = array('EMAIL', 'NOMBRE', 'APELLIDOS', 'SSID', 'FABRICANTE', 'FECHA_CONEXION');


    
// Obtenemos los sites con la informacion del email que tengan periodicidad semanal
$emails = $CI->InformesModel->getEmailsPeriodicidad(1);

// Iteramos esos datos
foreach ($emails as $item) {

    $site = $CI->SitesModel->getSitePorSite_Id($item->SITE_ID);
    $nombreSite = $site->NOMBRE;
    $nombreSiteSinEspacios = str_replace(' ', '', $nombreSite);

    // Obtenemos  las conexiones
    $conexiones = $CI->ConexionesModel->getConexiones($item->SITE_ID, $fecha_7_dias_atras, $fecha_ayer); // Obtenemos los datos de las conexiones


    /* INICIO CSV */

    // Nombre del archivo CSV
    $nombre_archivo_csv = 'Conexiones_' . $nombreSiteSinEspacios . '_semana_' . $fecha_semana_pasada_formateada . '.csv';

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

    $registros_por_dia = array_fill(0, 7, 0); // Creamos un array con 7 elementos, inicializados en 0
    $registrosTotalesPortal = 0;
    // Iteramos sobre las conexiones y contamos cuántas hay por día
    foreach ($conexiones as $conexion) {
        $fecha_creacion = date('Y-m-d', strtotime($conexion->{'CREATED_AT'}));
        $dias_transcurridos = (strtotime($fecha_creacion) - strtotime($fecha_7_dias_atras)) / (60 * 60 * 24); // Calculamos el número de días transcurridos
        $registros_por_dia[$dias_transcurridos]++;
        $registrosTotalesPortal += 1;
    }
    $data2y = array($registros_por_dia[0], $registros_por_dia[1], $registros_por_dia[2], $registros_por_dia[3], $registros_por_dia[4], $registros_por_dia[5], $registros_por_dia[6]);

    $unifi_connection = new Client(
        $user,
        $password,
        $controllerurl,
        $item->SITE_ID,
        $version
    );

    $unifi_connection->login();

    $timestamp_ayer = strtotime($fecha_ayer) * 1000;
    $timestamp_hace_7_dias = strtotime($fecha_7_dias_atras) * 1000;
    $conexionesUnifi = $unifi_connection->stat_daily_site($timestamp_hace_7_dias, $timestamp_ayer);

    $conexionesDiarias = [0, 0, 0, 0, 0, 0, 0];
    $conexionesMaximas = 0;
    $conexionesTotales = 0;
    $bytes = 0;
    $count = 0;
    foreach ($conexionesUnifi as $unifi) {
        $conexionesDiarias[$count] = $unifi->{'wlan-num_sta'};
        if ($unifi->{'wlan-num_sta'} > $conexionesMaximas) $conexionesMaximas = $unifi->{'wlan-num_sta'};
        $conexionesTotales = $conexionesTotales + $unifi->{'wlan-num_sta'};
        $bytes = $bytes + $unifi->{'wlan_bytes'};
        $count++;
    }

    //Datos de trafico para las cards de arriba
    if (!isset($conexionesTotales)) $conexionesTotales = 0;
    if (!isset($bytes)) $bytes = 0;
    $megabytes = $bytes / 1024 / 1024;
    if ($megabytes >= 1024) {
        $megabytes = $megabytes / 1024;
        $trafico = number_format($megabytes, 1) . "GB";
    } else $trafico = intval($megabytes) . "MB";

    $data1y = $conexionesDiarias;
    
    // Create the graph. These two calls are always required
    $graph = new Graph(700, 400, 'auto');
    $graph->SetScale("textlin");

    $theme_class = new UniversalTheme;
    $graph->SetTheme($theme_class);


    $graph->SetBox(false);

    $graph->ygrid->SetFill(false);

    //Creamos las labels para el eje X
    $labels_dias_semana = array('Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo');
    $labels_numeros_dia = array();
    // Creamos un array con los labels que representan el día de la semana y el número del día
    $fecha_actual = strtotime($fecha_7_dias_atras);
    while ($fecha_actual <= strtotime($fecha_ayer)) {
        $dia_semana = date('N', $fecha_actual); // Obtenemos el número del día de la semana (1 para lunes, 2 para martes, etc.)
        $numero_dia = date('j', $fecha_actual); // Obtenemos el número del día del mes
        $labels_numeros_dia[] = $labels_dias_semana[$dia_semana - 1] . ' ' . $numero_dia; // Concatenamos el día de la semana con el número del día
        $fecha_actual = strtotime('+1 day', $fecha_actual);
    }
    $graph->xaxis->SetTickLabels($labels_numeros_dia);

    $graph->yaxis->HideLine(false);
    $graph->yaxis->HideTicks(false, false);

    // Create the bar plots
    $b1plot = new BarPlot($data1y);
    $b2plot = new BarPlot($data2y);

    // Create the grouped bar plot
    $gbplot = new GroupBarPlot(array($b1plot, $b2plot));
    // ...and add it to the graPH
    $graph->Add($gbplot);


    $b1plot->SetColor("white");
    $b1plot->SetFillColor("#0275d8");
    $b1plot->value->Show();
    $b1plot->legend = 'Conexiones Wifi';

    $b2plot->SetColor("white");
    $b2plot->SetFillColor("#ee82ee");
    $b2plot->value->Show();
    $b2plot->legend = 'Registros portal';

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

    $fecha_semana_pasada = strtotime('-7 days');
    $fecha_semana_pasada_formateada = date("d-m-Y", $fecha_semana_pasada);


    $contenido_pdf = '<!doctype html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <title>Informe ' . $nombreSite . ' semana ' . $fecha_semana_pasada_formateada . ' </title>

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
                <h3 style="margin-left:40px;">Informe ' . $nombreSite . ' semana ' . $fecha_semana_pasada_formateada . ' </h3>
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
    $ruta_archivo_pdf = FCPATH . '/scripts/' . 'Informe_' . $nombreSiteSinEspacios . '_semana_' . $fecha_semana_pasada_formateada . '.pdf';
    // Escribir contenido del PDF en un archivo
    file_put_contents($ruta_archivo_pdf, $output);

    /*FIN PDF*/


    /* INICIO ENVIO MAIL */
    // Limpiar la instancia de email
    $CI->email->clear(TRUE);

    // Adjuntar archivos al correo electrónico
    $CI->email->attach($ruta_archivo_csv);
    $CI->email->attach($ruta_archivo_pdf);

    $CI->email->from('proquo@tecnologiasencilla.com', 'Proquo Wifi');
    $CI->email->to($item->EMAIL);
    $CI->email->subject('Informe semana ' . $fecha_semana_pasada_formateada . '');
    $CI->email->message("Buenos días, <br/> Estos son los datos de los registros de la semana pasada en su portal cautivo. <br/> ¡Un saludo!");


    // Enviar correo electrónico
    if (!$CI->email->send()) {
        echo 'Error al enviar el correo.';
        $error =  $CI->email->print_debugger();
    } else {
        // Si se ha enviado actualizamos el campo en BD
        $CI->InformesModel->actualizarEnvio($item->ID, $fecha_hoy);
    }

    /* FIN ENVIO MAIL */

    // Eliminar los archivos después de enviar el correo
    unlink($ruta_archivo_csv);
    unlink($ruta_archivo_pdf);
    unlink($rutaImagenTemporal);
}

return true;
