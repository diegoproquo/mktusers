<?php

class ConexionesModel extends CI_Model
{
    public $id;
    public $fecha;
    public $conexiones;


    public function __construct()
    {
        $this->load->database();
    }

    public function registrarConexionDiaria()
    {
        // Obtener la fecha actual
        $fecha_actual = date('Y-m-d');

        // Verificar si ya existe un registro para la fecha actual
        $query = $this->db->get_where('tbl_conexiones', array('fecha' => $fecha_actual));
        $registro = $query->row();

        if ($registro) {
            // Si existe el registro, incrementar el número de conexiones
            $this->db->set('CONEXIONES', 'CONEXIONES+1', FALSE);
            $this->db->where('FECHA', $fecha_actual);
            $this->db->update('tbl_conexiones');
        } else {
            // Si no existe, crear un nuevo registro con 1 conexión
            $data = array(
                'FECHA' => $fecha_actual,
                'CONEXIONES' => 1
            );
            $this->db->insert('tbl_conexiones', $data);
        }
    }


    // Devuelve un array con los registros de las conexiones de los ultimos 7 dias agrupados por fecha ascendente
    public function getConexiones7Dias($fecha)
    {
        $conexiones = array_fill(0, 7, 0);
        $fecha_base = strtotime($fecha);
        
        // Consulta para obtener las conexiones sumadas por fecha
        $this->db->select("DATE_FORMAT(FECHA, '%d/%m') as fecha_formateada, CONEXIONES as total_conexiones");
        $this->db->order_by('FECHA', 'DESC');
        $this->db->limit(7);
    
        $query = $this->db->get("tbl_conexiones");
        $resultados = $query->result();

        // Crear un mapa de fechas para actualizar las conexiones
        $fecha_map = [];
        for ($i = 0; $i < 7; $i++) {
            $fecha_map[date('d/m', strtotime("-$i day", $fecha_base))] = $i;
        }
    
        // Rellenamos los valores del array con los resultados de la consulta
        foreach ($resultados as $row) {
            $key = $fecha_map[$row->fecha_formateada] ?? null;
            if ($key !== null) {
                $conexiones[$key] = $row->total_conexiones;
            }
        }
    
        // Invertimos el array para que las fechas más recientes estén al final (opcional)
        return array_reverse($conexiones);
    }
}
