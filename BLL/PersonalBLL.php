<?php
require_once("GenericBLL.php");

$name = "Personal";
$action = validateAction($name);
class PersonalBLL extends PersonalDAL {
    //CRUD Ajax calls--------------------------------
    public function InsertAjax(){
        try
        {
            $object = new Personal();
            $object->nombre = $_POST["nombre"];
            $object->nombre_2 = $_POST["nombre_2"];
            $object->nombre_2 = $_POST["nombre_2"];
            $object->apellido_pat = $_POST["apellido_pat"];
            $object->apellido_mat = $_POST["apellido_mat"];
            $object->tipo_persona = $_POST["tipo_persona"];
            $object->activo = true;
            $id = $this->_insert($object);
            echo json_encode($id);
        }
        catch (Exception $e)
        {
            ajaxExceptionHandler($e);
        }
    }
    public function UpdateAjax(){
        try
        {
            $object = new Personal();
            $object->id_persona = $_POST["id"];
            $object->nombre = $_POST["nombre"];
            $object->nombre_2 = $_POST["nombre_2"];
            $object->nombre_2 = $_POST["nombre_2"];
            $object->apellido_pat = $_POST["apellido_pat"];
            $object->apellido_mat = $_POST["apellido_mat"];
            $object->tipo_persona = $_POST["tipo_persona"];
            $object->activo = true;
            $ar = $this->_update($object);
            echo json_encode($ar);
        }
        catch (Exception $e)
        {
            ajaxExceptionHandler($e);
        }
    }
    public function GetByIdAjax(){
        try
        {
            $id = $_POST['id'];
            $res = $this->_getById($id);
            echo json_encode($res);
        }
        catch (Exception $e)
        {
            ajaxExceptionHandler($e);
        }
    }
    public function GetAllAjax(){
        try
        {
            $list = $this->_getAll();
            echo json_encode($list);
        }
        catch (Exception $e)
        {
            ajaxExceptionHandler($e);
        }
    }
    public function DeleteAjax(){
        try
        {
            $id = $_POST['id'];
            $res = $this->_delete($id);
            echo json_encode($res);
        }
        catch (Exception $e)
        {
            ajaxExceptionHandler($e);
        }
    }
    //Custom methods can be declared here--------
    public function selectTop($limit,$data)
    {
        $objDAL = new PersonalDAL();
        return $objDAL->selectTop($limit,$data);
    }
}
//When the class is extended, the order matters.
//The  class must be declared first before be called. (Ajax)
if ($action != null) {
    $class = $name.'BLL';
    $method = $action.'Ajax';
    $objectBLL = new $class;
    $objectBLL->$method();
}