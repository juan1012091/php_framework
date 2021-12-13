<?php
class Personal {
    //Los nombres de las propiedades deben llamarse igual que las columnas de la tabla
    public $id_Persona = -1;
    public $nombre = null;
    public $nombre_2 = null;
    public $apellido_pat = null;
    public $apellido_mat = null;
    public $tipo_persona = -1;
    public $activo = false;
    //Se hace el llenado de las propiedades en base al arreglo que se pasa en el constructor. Se usa en la capa DAL
    public function __construct(Array $properties=array()){
        foreach($properties as $key => $value){
            if(!mb_detect_encoding($value, 'utf-8', true)){
                $value = utf8_encode($value);//Si se detecta una codificacion diferente a UTF-8 se codifica a UTF-8 para evitar errores
            }
            $this->{$key} = $value;
        }
    }
} 