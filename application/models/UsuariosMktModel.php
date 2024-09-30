<?php

class UsuariosMktModel extends CI_Model
{
    public $id;
    public $id_mkt;
    public $id_tag;
    public $nombre;
    public $conexiones;
    public $fecha_alta;
    public $deleted_at;

    public function __construct()
    {
        $this->load->database();
    }

    public function insertarConexion($nombre)
    {

        unset($this->id);
        unset($this->id_tag);
        unset($this->id_mkt);
        unset($this->conexiones);
        unset($this->fecha_alta);
        unset($this->deleted_at);
    
        $this->db->set('conexiones', 'conexiones+1', FALSE);
        $this->db->where('nombre', $nombre);
        $this->db->update('tbl_usuarios_mkt');

    }
    
    public function sincronizarUsuarios($usuarios)
    {
        foreach ($usuarios as $item) {
            // Obtener el ID del MikroTik y el nombre del usuario
            $id_mkt = $item['.id'];  // ID del Mikrotik
            $nombre_usuario = $item['Usuario'];  // Nombre del usuario
    
            // Verificar si el usuario ya existe en la base de datos
            $this->db->where('id_mkt', $id_mkt);
            $query = $this->db->get('tbl_usuarios_mkt');  // Nombre de tu tabla en la base de datos
            $usuario_existente = $query->row();
    
            if ($usuario_existente) {
                // Si el usuario existe, actualizamos el nombre (unico campo que se puede editar)
                $data = array(
                    'nombre' => $nombre_usuario
                );
                $this->db->where('id_mkt', $id_mkt);
                $this->db->update('tbl_usuarios_mkt', $data);
            } else {
                // Si el usuario no existe, lo creamos
                $fecha_actual = date('Y-m-d H:i:s');
                $data = array(
                    'id_mkt' => $id_mkt,
                    'nombre' => $nombre_usuario,
                    'conexiones' => 0,
                    'fecha_Alta' => $fecha_actual,
                );
                $this->db->insert('tbl_usuarios_mkt', $data);
            }
        }
    }
    
    public function eliminarUsuarios($usuarios)
    {
        foreach ($usuarios as $user) {
            // Obtener el ID del MikroTik del usuario
            $id_mkt = $user['.id'];
            // Verificar si el usuario existe en la base de datos antes de eliminar
            $this->db->where('id_mkt', $id_mkt);
            $this->db->delete('tbl_usuarios_mkt');  // Reemplaza 'tbl_usuarios_mkt' por el nombre de tu tabla
        }
    }
    

    public function getUsuarios(){
        return $this->db->get("tbl_usuarios")->result();
    }

    public function getUsuarioPorId($site_id){
        return $this->db->get_where("tbl_usuarios", array("ID_MKT" => $site_id))->row();
    }
    





}