<?php
class Session {

    //Get session values methods
    public static function userId()
    {
        return self::getSession("userId");
    }
    public static function username()
    {
        return self::getSession("username");
    }
    public static function fullName()
    {
        return self::getSession("fullname");
    }

    //Set session values methods
    public static function setUserId($data)
    {
        self::setSession("userId",$data);
    }
    public static function setUsername($data)
    {
        self::setSession("username",$data);
    }
    public static function setFullName($data)
    {
        self::setSession("fullname",$data);
    }

    public static function destroy()
    {
        if ( self::is_session_started() === FALSE ) session_start();
        session_destroy();
    }
    /**
     * Metodo para verificar si esta activa o no la session
     *
     * @return boolean
     */
    private static function is_session_started()
    {
        if ( php_sapi_name() !== 'cli' ) {
            if ( version_compare(phpversion(), '5.4.0', '>=') ) {
                return session_status() === PHP_SESSION_ACTIVE ? TRUE : FALSE;
            } else {
                return session_id() === '' ? FALSE : TRUE;
            }
        }
        return FALSE;
    }
    /**
     * Metodo para obtener el valor de una variable de session
     *
     * @param string $sessionID Identificador de la variable de sesion
     * @return string
     */
    private static function getSession($sessionID)  
    {
        if ( self::is_session_started() === FALSE ) session_start();
        if (isset($_SESSION[$sessionID])){
            return $_SESSION[$sessionID];
        }else
            return null;
    }
    /**
     * Metodo para establecer un valor a una variable en session
     *
     * @param string $sessionID Identificador de la variable de session
     * @param string $data Valor que se le asignara a la variable de session
     * @return void
     */
    private static function setSession($sessionID, $data)  
    {
        if ( self::is_session_started() === FALSE ) session_start();
        $_SESSION[$sessionID] = $data;
    }
}