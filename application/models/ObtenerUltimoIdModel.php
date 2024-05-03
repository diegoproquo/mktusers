<?php

class ObtenerUltimoIdModel extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
    }

    public function obtenerUltimoId($tabla)
    {
        // Construye la consulta SQL para obtener el ID más alto
        $this->db->select_max('id');
        $query = $this->db->get($tabla);

        // Verifica si se encontraron resultados
        if ($query->num_rows() > 0) {
            // Obtiene el ID más alto y suma 1
            $row = $query->row();
            $maxId = $row->id;
            return $maxId + 1;
        } else {
            // No se encontraron resultados, devuelve 1 como el siguiente ID
            return 1;
        }
    }
}
