<?php

class PortalModel extends CI_Model
{
    public $site_id;
    public $titulo;
    public $usar_titulo;
    public $texto;
    public $usar_texto;
    public $boton_texto;
    public $color;
    public $color_secundario;
    public $color_terciario;
    public $tamano_logo;
    public $radio_esquinas;
    public $usar_imagen;
    public $url_imagen;
    public $dir_imagen;
    public $url_imagen_fondo;
    public $dir_imagen_fondo;
    public $opacidad_fondo;
    public $redireccion;
    public $terminos;
    public $url_terminos;
    public $dir_terminos;
    public $limite_sesion;
    public $limite_sesion_minutos;
    public $registro_email;
    public $registro_nombre;
    public $registro_apellidos;

    public function __construct()
    {
        $this->load->database();
    }

    public function inicializarPortal($site_id)
    {
        $this->site_id = $site_id;
        $this->color_terciario = "#ffffff";
        $this->color = 'rgba(255, 255, 255, 0.48)';
        $this->color_secundario = 'rgba(255, 255, 255, 0.48)';
        $this->color_terciario = "#4068aa";
        $this->tamano_logo = 75;
        $this->radio_esquinas = 60;
        $this->usar_imagen = 0;
        $this->opacidad_fondo = 8;
        $this->redireccion = "";
        $this->terminos = 0;
        $this->limite_sesion = 0;
        $this->limite_sesion_minutos = 1;
        $this->registro_email = 0;
        $this->registro_nombre = 0;
        $this->registro_apellidos = 0;
        $this->usar_titulo = 0;
        $this->usar_texto = 0;

        $this->db->insert('tbl_portal', $this);
        return $this->db->insert_id();
    }

    //Actualiza el registro al completo sin tener en cuenta urls
    public function guardarCambios($site_id, $titulo, $usar_titulo, $texto, $usar_texto, $boton_texto, $color, $color_secundario, $color_terciario, $tamano_logo, $radio_esquinas, $usar_imagen, $opacidad_fondo, $redireccion, $terminos, $limite_sesion, $limite_sesion_minutos, $registro_email, $registro_nombre, $registro_apellidos)
    {
        $this->site_id = $site_id;
        $this->titulo = $titulo;
        $this->usar_titulo = $usar_titulo;
        $this->texto = $texto;
        $this->usar_texto = $usar_texto;
        $this->boton_texto = $boton_texto;
        $this->color = $color;
        $this->color_secundario = $color_secundario;
        $this->color_terciario = $color_terciario;
        $this->tamano_logo = $tamano_logo;
        $this->radio_esquinas = $radio_esquinas;
        $this->usar_imagen = $usar_imagen;
        $this->opacidad_fondo = $opacidad_fondo;
        $this->redireccion = $redireccion;
        $this->terminos = $terminos;
        $this->limite_sesion = $limite_sesion;
        $this->limite_sesion_minutos = $limite_sesion_minutos;
        $this->registro_email = $registro_email;
        $this->registro_nombre = $registro_nombre;
        $this->registro_apellidos = $registro_apellidos;

        unset($this->url_imagen);
        unset($this->dir_imagen);
        unset($this->url_imagen_fondo);
        unset($this->dir_imagen_fondo);
        unset($this->url_terminos);
        unset($this->dir_terminos);

        return $this->db->update('tbl_portal', $this, array("SITE_ID" => $site_id));
    }

    //Actualiza los campos de url_imagen y dir_imagen
    public function guardarImagenUrl($site_id, $url_imagen, $dir_imagen)
    {
        $this->site_id = $site_id;
        $this->url_imagen = $url_imagen;
        $this->dir_imagen = $dir_imagen;

        unset($this->titulo);
        unset($this->usar_titulo);
        unset($this->texto);
        unset($this->usar_texto);
        unset($this->boton_texto);
        unset($this->color);
        unset($this->color_secundario);
        unset($this->color_terciario);
        unset($this->tamano_logo);
        unset($this->radio_Esquinas);
        unset($this->usar_imagen);
        unset($this->dir_terminos);
        unset($this->url_imagen_fondo);
        unset($this->opacidad_fondo);
        unset($this->redireccion);
        unset($this->terminos);
        unset($this->limite_sesion);
        unset($this->limite_sesion_minutos);
        unset($this->registro_email);
        unset($this->registro_nombre);
        unset($this->registro_apellidos);
        unset($this->url_terminos);
        unset($this->dir_terminos);

        return $this->db->update('tbl_portal', $this, array("SITE_ID" => $site_id));
    }

        //Actualiza los campos de url_imagen y dir_imagen
        public function guardarImagenFondoUrl($site_id, $url_imagen_fondo, $dir_imagen_fondo)
        {
            $this->site_id = $site_id;
            $this->url_imagen_fondo = $url_imagen_fondo;
            $this->dir_imagen_fondo = $dir_imagen_fondo;
    
            unset($this->titulo);
            unset($this->usar_titulo);
            unset($this->texto);
            unset($this->usar_texto);
            unset($this->boton_texto);
            unset($this->color);
            unset($this->color_secundario);
            unset($this->color_terciario);
            unset($this->tamano_logo);
            unset($this->radio_Esquinas);
            unset($this->usar_imagen);
            unset($this->opacidad_fondo);
            unset($this->redireccion);
            unset($this->terminos);
            unset($this->limite_sesion);
            unset($this->limite_sesion_minutos);
            unset($this->registro_email);
            unset($this->registro_nombre);
            unset($this->registro_apellidos);
            unset($this->url_terminos);
            unset($this->dir_terminos);
    
            return $this->db->update('tbl_portal', $this, array("SITE_ID" => $site_id));
        }


    //Actualiza los campos de url_terminos y dir_terminos
    public function guardarUrlTerminos($site_id, $url_terminos, $dir_terminos)
    {
        $this->site_id = $site_id;
        $this->url_terminos = $url_terminos;
        $this->dir_terminos = $dir_terminos;

        unset($this->titulo);
        unset($this->usar_titulo);
        unset($this->texto);
        unset($this->usar_texto);
        unset($this->boton_texto);
        unset($this->color);
        unset($this->color_secundario);
        unset($this->color_terciario);
        unset($this->tamano_logo);
        unset($this->radio_Esquinas);
        unset($this->usar_imagen);
        unset($this->opacidad_fondo);
        unset($this->redireccion);
        unset($this->terminos);
        unset($this->limite_sesion);
        unset($this->limite_sesion_minutos);
        unset($this->registro_email);
        unset($this->registro_nombre);
        unset($this->registro_apellidos);
        unset($this->url_imagen);
        unset($this->dir_imagen);

        return $this->db->update('tbl_portal', $this, array("SITE_ID" => $site_id));
    }

    public function getConfiguracionPortal($site_id){
        $this->db->select('LIMITE_SESION, LIMITE_SESION_MINUTOS, REDIRECCION ');
        $this->db->from('tbl_portal');
        $this->db->where('SITE_ID', $site_id);
        $query = $this->db->get();
        return $query->result_array();
    }



    public function todos()
    {
        return $this->db->get("tbl_portal")->result();
    }

    public function uno($site_id)
    {
        return $this->db->get_where("tbl_portal", array("SITE_ID" => $site_id))->row();
    }
}
