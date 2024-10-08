<?php

class UsuariosWebModel extends CI_Model
{
    public $id;
    public $usuario;
    public $password;
    public $rol; //0=usuario 1=admin
    public $last_login;
    public $deleted_at;

    public function __construct()
    {
        $this->load->database();
    }

    public function nuevoUsuario ($id, $usuario, $password, $rol, $deleted_at)
    {

        $this->id = $id;
        $this->usuario = $usuario;
        $this->rol = $rol;
        $this->deleted_at = null;

        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $this->password = $hashed_password;

        $this->db->insert('tbl_usuarios_web', $this);
        return $this->db->insert_id();
    }

    public function guardarCambios($id, $usuario, $password, $rol, $deleted_at)
    {
        $this->id = $id;
        $this->usuario = $usuario;
        $this->rol = $rol;
        $this->deleted_at = $deleted_at;
        unset($this->last_login);
    
        if (password_needs_rehash($password, PASSWORD_BCRYPT)) {
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            $this->password = $hashed_password;
        } else {
            $this->password = $password;
        }
    
        return $this->db->update('tbl_usuarios_web', $this, array("ID" => $id));
    }
    
    public function actualizarLastLogin($id)
    {

        unset($this->usuario);
        unset($this->usuario);
        unset($this->password);
        unset($this->rol);
        unset($this->deleted_at);
    
        $data = array(
            'last_login' => date("Y-m-d H:i:s")
        );
    
        $this->db->where('id', $id);
        return $this->db->update('tbl_usuarios_web', $data);

    }

    public function getUsuarios()
    {
        return $this->db->where("DELETED_AT IS NULL")->get("tbl_usuarios_web")->result();
    }
    

    public function eliminar($id)
    {
        $data = array(
            'deleted_at' => date("Y-m-d H:i:s")
        );
    
        $this->db->where('id', $id);
        return $this->db->update('tbl_usuarios_web', $data);
    }
    
    public function uno($id)
    {
        return $this->db->get_where("tbl_usuarios_web", array("ID" => $id))->row();
    }

    public function obtenerUsuarioPorNombre($usuario){
        return $this->db->get_where("tbl_usuarios_web", array("USUARIO" => $usuario))->row();
    }



}