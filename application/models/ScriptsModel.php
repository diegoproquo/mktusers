<?php

class ScriptsModel extends CI_Model
{
    public $id;
    public $script;

    public function __construct()
    {
        $this->load->database();
    }

    public function guardarScript($id, $script)
    {

        $this->id = $id;
        $this->script = $script;

        return $this->db->replace('tbl_scripts', $this);
    }

    public function getScripts()
    {
        $sql = "SELECT * FROM tbl_scripts";

        return $this->db->query($sql)->result();
    }
}
