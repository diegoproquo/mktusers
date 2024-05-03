<?php

class SitesModel extends CI_Model
{
    public $id;
    public $site_id;
    public $nombre;
    public $deleted_at;

    public function __construct()
    {
        $this->load->database();
    }
        
    public function getSites()
    {
        return $this->db->where("DELETED_AT IS NULL")->get("tbl_sites")->result();
    }

    public function getSitePorSite_Id($site_id)
    {
        return $this->db->get_where("tbl_sites", array("SITE_ID" => $site_id))->row();
    }

    
    public function nuevoSite ($id, $site_id, $nombre, $deleted_at)
    {

        $this->id = $id;
        $this->site_id = $site_id;
        $this->nombre = $nombre;
        $this->deleted_at = $deleted_at;

        $this->db->insert('tbl_sites', $this);
        return $this->db->insert_id();
    }


    public function eliminar($id, $deleted_at)
    {
        $data = array(
            'deleted_at' => $deleted_at
        );
    
        $this->db->where('id', $id);
        return $this->db->update('tbl_sites', $data);
    }
    
    public function comprobarSite($site_id)
    {
        $site = $this->db->get_where("tbl_sites", array("SITE_ID" => $site_id))->row();
        if($site != null) return true;
        else return false;
    }

    public function ActualizarNombre($id, $nombre)
    {
        $this->nombre = $nombre;
        $this->id = $id;
        unset($this->site_id);
        unset($this->deleted_at);
    
        return $this->db->update('tbl_sites', $this, array("ID" => $id));
    }

}
