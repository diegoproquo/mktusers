<?php

class TagsModel extends CI_Model
{
    public $id;
    public $nombre;
    public $color;
    public $deleted_at;

    public function __construct()
    {
        $this->load->database();
    }

    public function nuevoTag ($id, $nombre, $color)
    {

        $this->id = $id;
        $this->nombre = $nombre;
        $this->color = $color;
        $this->deleted_at = null;

        $this->db->insert('tbl_tags', $this);
        return $this->db->insert_id();
    }

    public function guardarCambios($id, $nombre, $color)
    {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->color = $color;
    
        return $this->db->update('tbl_tags', $this, array("ID" => $id));
    }
    
    public function getTags()
    {
        $sql = "SELECT t.*, 
                        COUNT(u.ID_TAG) AS USUARIOS
                 FROM tbl_tags t
                 LEFT JOIN tbl_usuarios_mkt u ON t.ID = u.ID_TAG
                 WHERE t.DELETED_AT IS NULL 
                 GROUP BY t.ID;";
    
        return $this->db->query($sql)->result();
    }
    
    
    public function eliminar($id)
    {
        $data = array(
            'deleted_at' => date("Y-m-d H:i:s")
        );
    
        $this->db->where('id', $id);
        return $this->db->update('tbl_tags', $data);
    }
    
    public function uno($id)
    {
        return $this->db->get_where("tbl_tags", array("ID" => $id))->row();
    }

    public function obtenerTagPorNombre($nombre){
        return $this->db->get_where("tbl_tags", array("NOMBRE" => $nombre))->row();
    }



}