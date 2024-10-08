<?php

class ConexionesModel extends CI_Model
{
    public $id;
    public $fecha;
    public $conexiones;
    public $id_tag;


    public function __construct()
    {
        $this->load->database();
    }

    public function registrarConexionDiaria($usuario)
    {

        $this->load->model('ConexionesModel');
        $this->load->model('ObtenerUltimoIdModel');

        // Obtener la fecha actual
        $fecha_actual = date('Y-m-d');

        $usuarioMKT = $this->UsuariosMktModel->getUsuarioPorNomnre($usuario);

        // Verificar si ya existe un registro para la fecha actual
        $query = $this->db->get_where('tbl_conexiones', array('FECHA' => $fecha_actual, 'ID_TAG' => $usuarioMKT->ID_TAG));
        $registro = $query->row();

        if ($registro) {
            // Si existe el registro, incrementar el número de conexiones
            $this->db->set('CONEXIONES', 'CONEXIONES+1', FALSE);
            $this->db->where('FECHA', $fecha_actual);
            $this->db->where('ID_TAG', $usuarioMKT->ID_TAG);
            $this->db->update('tbl_conexiones');
        } else {
            // Si no existe, crear un nuevo registro con 1 conexión
            $id = $this->ObtenerUltimoIdModel->obtenerUltimoId('tbl_conexiones');
            $data = array(
                'ID' => $id,
                'FECHA' => $fecha_actual,
                'CONEXIONES' => 1,
                'ID_TAG' => $usuarioMKT->ID_TAG,
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
        $this->db->select("DATE_FORMAT(FECHA, '%d/%m') as fecha_formateada, SUM(CONEXIONES) as total_conexiones");
        $this->db->group_by("FECHA");  // Agrupar por fecha formateada
        $this->db->order_by('FECHA', 'DESC');  // Ordenar por la fecha original
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

    // Devuelve las conexiones agrupadas por tag de un dia en concreto
    public function getConexionesPorTagDia($fecha)
    {
        // Convertimos la fecha a un formato adecuado para la consulta
        $fecha_base = date('Y-m-d', strtotime($fecha));

        // Consulta para obtener las conexiones sumadas por ID_TAG y unir con tbl_tags (si no tiene tag, lo añadimos en "Otros" (se habra guardado como 0 en tbl_conexiones))
        $this->db->select("
            c.ID_TAG, 
            CASE 
                WHEN c.ID_TAG = 0 THEN 'Otros' 
                ELSE t.NOMBRE 
            END as NOMBRE, 
            CASE 
                WHEN c.ID_TAG = 0 THEN '#808080'  /* Color gris */ 
                ELSE t.COLOR 
            END as COLOR, 
            SUM(c.CONEXIONES) as CONEXIONES
        ");
        $this->db->from("tbl_conexiones c"); // Usamos un alias para tbl_conexiones
        $this->db->join("tbl_tags t", "c.ID_TAG = t.ID", "left"); // Usamos LEFT JOIN por si no hay coincidencia en tbl_tags
        $this->db->where('DATE(c.FECHA)', $fecha_base); // Usamos DATE() para comparar solo la parte de la fecha
        $this->db->group_by("c.ID_TAG"); // Agrupamos por ID_TAG
        $this->db->order_by('c.ID_TAG'); // Ordenamos por ID_TAG

        $query = $this->db->get();
        $resultados = $query->result();

        return $resultados; // Devolvemos los resultados
    }
}
