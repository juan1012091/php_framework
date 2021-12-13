<?php
    require_once(dirname(__FILE__)."/../Root.php");
    Root::UseObjects('Result');
    Root::Session();
    /**
	* Generic class for multiple purposes
	*/
	class GenericBLL
	{
        
    }
    function ajaxExceptionHandler($e) {
        $result = new Result();        
        $result->error = true;
        $result->message = "<center><strong>Ha ocurrido un error. <br> Mensaje:</strong> [" . $e->getMessage() . "]</center>";
        $result->id = $e->getCode();
        echo json_encode($result);
    }
    function validateAction($request){
        Root::UseObjects($request);
        Root::UseDAL($request . 'DAL');
        $action = null;
        if (isset($_POST['action'])) {
            $action = $_POST["action"];
        }
        elseif (isset($_GET['action'])) {
            $action = $_GET["action"];
        }
        $action = validActionAndAjaxCall($request, $action);
        return $action;
    }
    function validActionAndAjaxCall($request, $action){
        //---------Added 2017/07/22-------------
        //Evita que se ejecuten metodos con la misma accion
        //si estos son llamados desde otro lugar
        $parturl = $_SERVER['REQUEST_URI'];
        if (strpos($parturl, $request) === false) {
            $action = "";
        }else{
            //TODO: Validate if the caller have a valid session.
        }
        //--------------------------------------------------------
        return $action;
    }
?>