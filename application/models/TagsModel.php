<?php

class TagsModel extends CI_Model
{
    public $id;
    public $nombre;
    public $deleted_at;

    public function __construct()
    {
        $this->load->database();
    }

    public function nuevoTag ($id, $nombre)
    {

        $this->id = $id;
        $this->nombre = $nombre;
        $this->deleted_at = null;

        $this->db->insert('tbl_tags', $this);
        return $this->db->insert_id();
    }

    public function guardarCambios($id, $nombre)
    {
        $this->id = $id;
        $this->nombre = $nombre;
    
        return $this->db->update('tbl_tags', $this, array("ID" => $id));
    }
    
    public function getTags()
    {
        return $this->db->where("DELETED_AT IS NULL")->get("tbl_tags")->result();
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