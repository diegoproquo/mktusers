<?php

class ConexionesModel extends CI_Model
{
    public $id;
    public $email;
    public $nombre;
    public $apellidos;
    public $ap_mac;
    public $client_mac;
    public $site_id;
    public $ssid;
    public $fabricante;
    public $created_at;

    public function __construct()
    {
        $this->load->database();
    }

    public function nuevaConexion($id, $email, $nombre, $apellidos, $ap_mac, $client_mac, $site_id, $ssid, $fabricante, $created_at)
    {
        $this->id = $id;
        $this->email = $email;
        $this->nombre = $nombre;
        $this->apellidos = $apellidos;
        $this->ap_mac = $ap_mac;
        $this->client_mac = $client_mac;
        $this->site_id = $site_id;
        $this->ssid = $ssid;
        $this->fabricante = $fabricante;
        $this->created_at = $created_at;

        $this->db->insert('tbl_conexiones', $this);
        return $this->db->insert_id();
    }


    public function getConexionesHoy($fecha, $site_id)
    {

        // Consulta SQL
        $sql = "SELECT DATE_FORMAT(CREATED_AT, '%H') AS hora, COUNT(*) AS cantidad 
        FROM tbl_conexiones 
        WHERE DATE(CREATED_AT) = '$fecha' AND SITE_ID = '$site_id' 
        GROUP BY DATE_FORMAT(CREATED_AT, '%H')";

        // Ejecutar la consulta SQL
        $query = $this->db->query($sql);

        $horas = [];
        for ($i = 0; $i <= date('H'); $i++) {
            $horas[$i] = 0; // Inicializar el valor para esta hora en cero
        }

        // Llenar el array con los valores obtenidos de la base de datos
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $hora = intval($row->hora); // Convertir a entero
                $horas[$hora] = intval($row->cantidad); // Convertir a entero y asignar al array
            }
        }

        return $horas;
    }

    
    public function getConexionesDia($fecha, $site_id)
    {
        // Consulta SQL para obtener las conexiones del día especificado
        $sql = "SELECT DATE_FORMAT(CREATED_AT, '%H') AS hora, COUNT(*) AS cantidad 
                FROM tbl_conexiones 
                WHERE DATE(CREATED_AT) = ? AND SITE_ID = ?
                GROUP BY DATE_FORMAT(CREATED_AT, '%H')";
    
        // Ejecutar la consulta SQL con los parámetros proporcionados
        $query = $this->db->query($sql, array($fecha, $site_id));
    
        // Inicializar un array para almacenar las conexiones de cada hora
        $horas = [];
        for ($i = 0; $i < 24; $i++) {
            $horas[$i] = 0; // Inicializar el valor para esta hora en cero
        }
    
        // Llenar el array con los valores obtenidos de la base de datos
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $hora = intval($row->hora); // Convertir a entero
                $horas[$hora] = intval($row->cantidad); // Convertir a entero y asignar al array
            }
        }
    
        return $horas;
    }
    
    
    public function getConexionesUltimos7dias($site_id)
    {
    
        // Obtener la fecha de hace 7 días
        $fecha_inicio = date('Y-m-d', strtotime('-7 days'));
        $fecha_fin = date('Y-m-d', strtotime('-1 days'));
    
        // Consulta SQL para obtener las conexiones por día en los últimos 7 días
        $sql = "SELECT 
                    DATE(CREATED_AT) AS fecha,
                    COUNT(*) AS cantidad 
                FROM tbl_conexiones 
                WHERE DATE(CREATED_AT) >= '$fecha_inicio' AND DATE(CREATED_AT) <= '$fecha_fin' AND SITE_ID = '$site_id'
                GROUP BY DATE(CREATED_AT)";
    
        // Ejecutar la consulta SQL
        $query = $this->db->query($sql);
        $resultados = $query->result();
    
        // Crear un array con los resultados de los últimos 7 días
        $conexiones_ultimos_7_dias = array_fill(0, 7, 0); // Inicializar el array con 7 elementos, todos a 0
    
        // Iterar sobre los resultados y actualizar el array de conexiones
        foreach ($resultados as $resultado) {
            $fecha_consulta = strtotime($resultado->fecha);
            $indice = ($fecha_consulta - strtotime($fecha_inicio)) / (60 * 60 * 24); // Calcular el índice del resultado en el array
            $conexiones_ultimos_7_dias[$indice] = $resultado->cantidad; // Actualizar el valor en el array
        }
    
        return $conexiones_ultimos_7_dias;
    }
    


    public function getConexionesPorDia($fecha)
    {
        $sql = "SELECT * FROM tbl_conexiones WHERE DATE(CREATED_AT) = '$fecha'";
        $query = $this->db->query($sql);
        return $query->result_array();
    }


    public function autorizar($id)
    {
        $data = array(
            'autorizado' => 1
        );

        return $this->db->update('tbl_conexiones', $data, array("id" => $id));
    }

    public function getConexiones($site_id, $fecha_inicio, $fecha_fin)
    {

        $fecha_inicio = $fecha_inicio . " 00:00:00";
        $fecha_fin = $fecha_fin . " 23:59:00";

        $this->db->order_by('CREATED_AT', 'desc');
        $this->db->where('CREATED_AT >=', $fecha_inicio);
        $this->db->where('CREATED_AT <=', $fecha_fin);
        $this->db->where('SITE_ID', $site_id);
        
        return $this->db->get("tbl_conexiones")->result();
    }
    
    
    public function eliminar($id)
    {
        return $this->db->delete("tbl_conexiones", array("ID" => $id));
    }

    public function uno($site)
    {
        return $this->db->get_where("tbl_conexiones", array("SITE_ID" => $site))->row();
    }
}
