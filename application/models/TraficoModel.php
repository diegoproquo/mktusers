<?php

class TraficoModel extends CI_Model
{
    public $id;
    public $fecha;
    public $bytes_descarga;
    public $bytes_carga;

    public function __construct()
    {
        $this->load->database();
    }

    public function registrarTraficoSesion($bytes_descarga, $bytes_carga)
    {
        $this->load->model('ObtenerUltimoIdModel');

        // Obtener la fecha actual
        $fecha_actual = date('Y-m-d');

        // Verificar si ya existe un registro para la fecha actual
        $query = $this->db->get_where('tbl_trafico', array('fecha' => $fecha_actual));
        $registro = $query->row();

        if ($registro) {
            // Si existe el registro, añadimos los bytes a los existentes
            $this->db->set('BYTES_DESCARGA', 'BYTES_DESCARGA + ' . (int)$bytes_descarga, FALSE);
            $this->db->set('BYTES_CARGA', 'BYTES_CARGA + ' . (int)$bytes_carga, FALSE);
            $this->db->where('FECHA', $fecha_actual);
            $this->db->update('tbl_trafico');
        } else {
            // Si no existe, crear un nuevo registro con trafico
            $id = $this->ObtenerUltimoIdModel->obtenerUltimoId('tbl_trafico');
            $data = array(
                'ID' => $id,
                'FECHA' => $fecha_actual,
                'BYTES_DESCARGA' => (int)$bytes_descarga,
                'BYTES_CARGA' => (int)$bytes_carga
            );
            $this->db->insert('tbl_trafico', $data);
        }
    }

    // Devuelve un array con el trafico de carga y descarga de los ultimos 7 dias agrupados por fecha ascendente
    public function getTrafico7dias($fecha)
    {
        $traficoDescarga = array_fill(0, 7, 0);
        $traficoCarga = array_fill(0, 7, 0);
        $fecha_base = strtotime($fecha);

        // Consulta para obtener el trafico
        $this->db->select("DATE_FORMAT(FECHA, '%d/%m') as fecha_formateada, BYTES_DESCARGA, BYTES_CARGA");
        $this->db->order_by('FECHA', 'DESC');
        $this->db->limit(7);

        $query = $this->db->get("tbl_trafico");
        $resultados = $query->result();

        // Crear un mapa de fechas para actualizar el trafico
        $fecha_map = [];
        for ($i = 0; $i < 7; $i++) {
            $fecha_map[date('d/m', strtotime("-$i day", $fecha_base))] = $i;
        }

        // Rellenamos los valores del array con los resultados de la consulta
        foreach ($resultados as $item) {
            $key = $fecha_map[$item->fecha_formateada] ?? null;
            if ($key !== null) {

                // Conertimos Bytes a MB
                $bytes_out = round($item->BYTES_DESCARGA * 0.000001, 2);
                $traficoDescarga[$key] = $bytes_out;

                $bytes_in = round($item->BYTES_CARGA * 0.000001, 2);
                $traficoCarga[$key] = $bytes_in;
            }
        }

        // Invertimos el array para que las fechas más recientes estén al final
        $traficoDescarga = array_reverse($traficoDescarga);
        $traficoCarga = array_reverse($traficoCarga);


        return array($traficoDescarga, $traficoCarga);
    }
}
