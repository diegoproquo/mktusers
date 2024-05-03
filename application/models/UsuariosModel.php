<?php

class UsuariosModel extends CI_Model
{
    public $id;
    public $nombre;
    public $usuario;
    public $password;
    public $rol; //0=usuario 1=admin
    public $site_id;
    public $last_login;
    public $deleted_at;

    public function __construct()
    {
        $this->load->database();
    }

    public function nuevoUsuario ($id, $nombre, $usuario, $password, $rol, $site_id, $deleted_at)
    {

        $this->id = $id;
        $this->nombre = $nombre;
        $this->usuario = $usuario;
        $this->rol = $rol;
        $this->site_id = $site_id;
        $this->deleted_at = $deleted_at;
        $this->deleted_at = null;

        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $this->password = $hashed_password;

        $this->db->insert('tbl_usuarios', $this);
        return $this->db->insert_id();
    }

    public function guardarCambios($id, $nombre, $usuario, $password, $rol, $site_id, $deleted_at)
    {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->usuario = $usuario;
        $this->rol = $rol;
        $this->site_id = $site_id;
        $this->deleted_at = $deleted_at;
        unset($this->last_login);
    
        if (password_needs_rehash($password, PASSWORD_BCRYPT)) {
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            $this->password = $hashed_password;
        } else {
            $this->password = $password;
        }
    
        return $this->db->update('tbl_usuarios', $this, array("ID" => $id));
    }
    
    public function getUsuariosPorSiteId($site_id)
    {
        return $this->db->where("DELETED_AT IS NULL AND SITE_ID = '$site_id'")->get("tbl_usuarios")->result();
    }

    public function actualizarLastLogin($id)
    {

        unset($this->nombre);
        unset($this->usuario);
        unset($this->password);
        unset($this->rol);
        unset($this->site_id);
        unset($this->deleted_at);
    
        $data = array(
            'last_login' => date("Y-m-d H:i:s")
        );
    
        $this->db->where('id', $id);
        return $this->db->update('tbl_usuarios', $data);

    }

    public function todos()
    {
        return $this->db->where("DELETED_AT IS NULL")->get("tbl_usuarios")->result();
    }
    

    public function eliminar($id)
    {
        $data = array(
            'deleted_at' => date("Y-m-d H:i:s")
        );
    
        $this->db->where('id', $id);
        return $this->db->update('tbl_usuarios', $data);
    }
    
    public function uno($id)
    {
        return $this->db->get_where("tbl_usuarios", array("ID" => $id))->row();
    }

    public function obtenerUsuarioPorNombre($usuario){
        return $this->db->get_where("tbl_usuarios", array("USUARIO" => $usuario))->row();
    }
}
