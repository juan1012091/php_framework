<?php 
    class ConnectDB
    {
        public function Conn()
        {
            $DB_host = "localhost";
            $DB_user = "root";
            $DB_pass = "";
            $DB_name = "electrotecnia";

            $conn = '';
            try
            {
                 $conn = new PDO("mysql:host={$DB_host};dbname={$DB_name}",$DB_user,$DB_pass);
                 $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }
            catch(PDOException $e)
            {
                throw new Exception("No fue posible conectarse a la base de datos");
            }
            return $conn;
        }

        public function Conn2($database="default_database")
        {

            $DB_host = "server";
            $DB_user = "user";
            $DB_pass = "password";
            $DB_name = $database;

            $conn = '';
            try
            {
                //Ejemplo de conexion SQL Server
                $conn = new PDO( "sqlsrv:server=$DB_host ; Database=$DB_name", $DB_user, $DB_pass);  
                $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );  
            }
            catch(PDOException $e)
            {
                echo $e->getMessage();
                die();
            }
            return $conn;
        }
    }

?>