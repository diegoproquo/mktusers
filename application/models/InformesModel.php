<?php

class InformesModel extends CI_Model
{
    public $id;
    public $site_id;
    public $email;
    public $periodicidad;
    public $ultimo_envio;

    public function __construct()
    {
        $this->load->database();
    }

    public function inicializarEmail($id, $site_id)
    {
        $this->id = $id;
        $this->site_id = $site_id;
        $this->periodicidad = "0";
        $this->db->insert('tbl_informes', $this);
        return $this->db->insert_id();
    }

    public function getEmailPorSite($site_id){
        return $this->db->get_where("tbl_informes", array("SITE_ID" => $site_id))->row();
    }

    public function getEmails(){
        return $this->db->get("tbl_informes")->result();
    }

    public function getEmailsPeriodicidad($periodicidad){
        return $this->db->get_where("tbl_informes", array("PERIODICIDAD" => $periodicidad))->result();
    }

    public function actualizarEnvio($id, $fecha){

        $this->id = $id;    
        $this->ultimo_envio = $fecha;    
        unset($this->site_id);
        unset($this->email);
        unset($this->periodicidad);
        return $this->db->update('tbl_informes', $this, array("ID" => $id));
    }

    public function guardarCambios($id, $site_id, $email, $periodicidad){

        $this->id = $id;
        $this->site_id = $site_id;
        $this->email = $email;
        $this->periodicidad = $periodicidad;
    
        return $this->db->update('tbl_informes', $this, array("ID" => $id));
    }




}