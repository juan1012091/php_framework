<?php
require_once("GenericDAL.php");
class PersonalDAL extends GenericDAL{
    public function __construct()
    {
        $this->_con = $this->Conn();//Connection to be used. [Config folder]
        $this->_table_name = 'personal';//Database table name
        $this->_pk_name    = 'id_Persona';// (Case sensitive!!!)
        $this->_class_name = 'Personal';//Object class name.
    }
    public function selectTop($limit,$data)
    {
        $sql = "select * from personal where apellido_pat like ? order by id_persona desc limit ?";
        
        $params = array();
        $params[] = "%" . $data . "%";
        $params[] = $limit;
        
        $res = $this->SqlQuery($sql, $params);
        $class = $this->_class_name;
        foreach ($res as $row) {
            $this->_object_list[] = new $class($row);
        }
        return $this->_object_list;
    }
} 