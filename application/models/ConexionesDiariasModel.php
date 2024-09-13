<?php

class ConexionesDiariasModel extends CI_Model
{
    public $id;
    public $fecha;
    public $conexiones;


    public function __construct()
    {
        $this->load->database();
    }

    public function registrarConexionDiaria() {
        // Obtener la fecha actual
        $fecha_actual = date('Y-m-d');
    
        // Verificar si ya existe un registro para la fecha actual
        $query = $this->db->get_where('tbl_conexiones_diarias', array('fecha' => $fecha_actual));
        $registro = $query->row();
    
        if ($registro) {
            // Si existe el registro, incrementar el número de conexiones
            $this->db->set('CONEXIONES', 'CONEXIONES+1', FALSE);
            $this->db->where('FECHA', $fecha_actual);
            $this->db->update('tbl_conexiones_diarias');
        } else {
            // Si no existe, crear un nuevo registro con 1 conexión
            $data = array(
                'FECHA' => $fecha_actual,
                'CONEXIONES' => 1
            );
            $this->db->insert('tbl_conexiones_diarias', $data);
        }
    }
    

    public function getConexiones(){
        return $this->db->get("tbl_conexiones_diarias")->result();
    }  





}