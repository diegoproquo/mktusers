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
    
    // Sincroniza los usuarios almacenados en el mikrotik con la BD. De esta forma, manda el Mikrotik permitiendo seguir almacenando los datos correspondientes de cada usuario en BD
    public function sincronizarUsuarios($usuarios)
    {
        foreach ($usuarios as $item) {
            $id_mkt = $item['.id']; 
            $nombre_usuario = $item['Usuario'];

            // Verificar si el usuario ya existe en la base de datos
            $this->db->where('id_mkt', $id_mkt);
            $query = $this->db->get('tbl_usuarios_mkt'); 
            $usuario_existente = $query->row();
    
            if ($usuario_existente) {
                // Si el usuario existe, actualizamos el nombre (unico campo que se puede editar en BD)
                $data = array(
                    'nombre' => $nombre_usuario,
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
            $id_mkt = $user['.id'];
            $this->db->where('id_mkt', $id_mkt);
            $this->db->delete('tbl_usuarios_mkt');
        }
    }
    
    public function getUsuariosPorID_MKT($usuarios) {
        // Crear un array de IDs de usuarios
        $ids = [];
        foreach ($usuarios as $item) {
            $ids[] = $item['.id'];
        }
    
        // Verificar si el array de IDs no está vacío
        if (!empty($ids)) {
            // Filtrar los usuarios que coincidan con los IDs
            $this->db->where_in('tbl_usuarios_mkt.ID_MKT', $ids);
        }
    
        // Seleccionar los campos de ambas tablas y asignar alias al campo NOMBRE y COLOR de tbl_tags
        $this->db->select('tbl_usuarios_mkt.*, tbl_tags.NOMBRE as NOMBRE_TAG, tbl_tags.COLOR');
    
        // Realizar la INNER JOIN con tbl_tags
        $this->db->join('tbl_tags', 'tbl_usuarios_mkt.ID_TAG = tbl_tags.ID');
    
        // Ejecutar la consulta y obtener los resultados
        return $this->db->get('tbl_usuarios_mkt')->result();
    }
    
    public function getUsuarioPorNomnre($nombre){

        $this->db->where('NOMBRE', $nombre);
        $query = $this->db->get('tbl_usuarios_mkt');
        return $query->row();
    }
    
    
    public function actualizarTag($nombre, $idtag){

        $data = array(
            'ID_TAG' => $idtag
        );
    
        $this->db->where('NOMBRE', $nombre);
        return $this->db->update('tbl_usuarios_mkt', $data);
    }

    public function actualizarTagMasivo($nombres, $idtag) {

        $usuarios = array_column($nombres, 'Usuario');

        $data = array(
            'ID_TAG' => $idtag
        );
    
        $this->db->where_in('NOMBRE', $usuarios);
        return $this->db->update('tbl_usuarios_mkt', $data);
    }
    
    


}