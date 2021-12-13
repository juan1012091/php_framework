<?php
    Root::DataBase();
	/**
	* Generic Class for multiple purposes
	*/
	class GenericDAL extends ConnectDB
	{
        public $_con = null;
        protected $_table_name  = null;
        protected $_pk_name      = null;
        protected $_pk_val      = null;
        protected $_class_name  = null;
        protected $_object_list = array();
        /**
         * Metodo para obtener el primer resultado de la consulta executada
         *
         * @param [string] $tableName Nombre de la tabla
         * @param [string] $idColumn Columna de la tabla
         * @param [mixed]  $value Valor con el que debe concidir la columna
         * @return array
         */
        public function SelectById($tableName, $idColumn, $value)
        {
            $sql = "SELECT * FROM ".$tableName." WHERE ".$idColumn." = ?";
            $stmt = $this->_con->prepare($sql);
            $stmt->bindValue(1,$value);
            $stmt->execute();
            $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $row[0];
        }
		/**
         * Select generico para obtener los valores de una tabla en especifico
         *
         * @param [string] $tableName Nombre de la tabla
         * @param [string] $condition Condicion a la cual aplicar a la consulta
         * @param array $array //Arreglo clave valor que debe concidir con las condiciones
         * @return array
         */
		public function GenericSelect($tableName, $condition=null, $array = array())
		{
            $sqlSelect = '';
            if ($condition == null) {
                $sqlSelect   = $this->getSelectSql($tableName);
            }else{
                $sqlSelect   = $this->getSelectSql($tableName,$condition);
            }
            $res = $this->dbBoundQuery($sqlSelect, $array,'s');
            return $res;
        }
        /**
         * Funcion para realizar una consulta desde una cadena sql
         *
         * @param [string] $sqlQuery
         * @param [array] $conditionParams
         * @return array
         */
        public function SqlQuery($sqlQuery, $conditionParams = null)
		{
            $res = $this->dbBoundQuery($sqlQuery, $conditionParams,'s');
            return $res;
		}
		/*
		 * Update generico para la actualizacion de datos.
		 * @param array  $array     Arreglo d tipo clave valor donde se especifican los campos y valores que se van a actualizar.
		 * @param string $condition   Condicion para actualizar los datos.
		 */
		public function GenericUpdate($array,$condition)
		{
            $buildParams = $this->getParams($array);
            $table       = $this->_table_name;
            $sqlUpdate   = $this->getUpdateSql($table, $buildParams, $condition);
            $array[$this->_pk_name] = $this->_idVal; 
            $res         = $this->dbBoundQuery($sqlUpdate, $array,'u');
            return $res;
		}
		/*
		 * Insert generico para la insercion de datos.
		 * @param array $array     Arreglo de tipo clave valor en el cual la clave es el campo y la llave es el valor del campo respectivamente.
		 */
		public function GenericInsert($array)
		{
            $buildParams = $this->getParams($array);
            $table       = $this->_table_name;
            $sqlInsert   = $this->getInsertSql($table, $buildParams);
            $res         = $this->dbBoundQuery($sqlInsert, $array,'i');
            return $res;
		}
		public function GenericDelete($tableName, $condition, $array = array())
        {
            if ($condition == null) {
                $deleteSql   = $this->getDeleteSql($tableName);
            }else{
                $deleteSql   = $this->getDeleteSql($tableName,$condition);
            }
            $res = $this->dbBoundQuery($deleteSql, $array,'u');
            return $res;
        }
        
        protected function getParams($array)
        {
            $buildParams = '';
            if (!is_array($array) || !count($array)) 
            { 
                return $buildParams; 
            } 
            foreach ($array as $key => $value) {
                $buildParams.='`'.$key.'`=?, ';
            }
            $buildParams = trim($buildParams,', ');
            return $buildParams;
        }
        protected function getInsertSql($table, $buildParams)
        {
            $sql = '';
            $sql = 'INSERT INTO ' . $table . ' SET ' . $buildParams;
            return $sql;
        }
        protected function getUpdateSql($table, $buildParams,$condition)
        {
            $sql = '';
            $sql = 'UPDATE ' . $table . ' SET ' . $buildParams . ' WHERE ' . $condition;
            return $sql;
        }
        protected function getSelectSql($table, $condition = NULL)
        {
            $sql = '';
            if ($condition == null){
                $sql = 'SELECT * FROM ' . $table;
            }else{
                $sql = 'SELECT * FROM ' . $table . ' WHERE ' . $condition;
            }
            return $sql;
        }
        protected function getDeleteSql($table, $condition = NULL)
        {
            $sql = '';
            if ($condition == null){
                $sql = 'DELETE FROM ' . $table;
            }else{
                $sql = 'DELETE FROM ' . $table . ' WHERE ' . $condition;
            }
            return $sql;
        }
        protected function dbBoundQuery($sql, $values, $queryType)
        {
            try
            {
                $con = $this->_con;

                $stmt = $con->prepare($sql);
                $i = 0;
                $bind = true;
                if(is_array($values)){
                    foreach($values as $value) {
                        if (is_object($value)) {
                            throw new Exception("Value cannot be an object. " . serialize($value));
                        }
                            $i++;
                            if(is_int($value))
                            {
                                $param = PDO::PARAM_INT;
                            }
                            elseif(is_numeric($value))
                            {
                                $param = PDO::PARAM_STR;
                            }
                            elseif(is_bool($value))
                            {
                                $param = PDO::PARAM_BOOL;
                            }
                            elseif(is_null($value))
                            {
                                $param = PDO::PARAM_NULL;
                            }
                            elseif(is_string($value))
                            {
                                $param = PDO::PARAM_STR;
                            }else {
                                $bind = false;
                            }
                            if($bind) $stmt->bindValue($i,$value,$param);
                    }
                }
                $res = $stmt->execute();
                switch($queryType)
                {
                    case 'u'://update or delete
                        $res = $stmt->rowCount();
                        break;
                    case 'i'://insert
                        $res = $con->lastInsertId();
                        break;
                    case 's'://select
                        $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        break;
                }
                $con = null;
                return $res;
            }
            catch(PDOException $e)
            {
                $con2 = $this->_con;
                $sqlLog = "INSERT INTO log_database(error_desc, sql_string, stack_trace) VALUES(?, ?, ?)";
                $stmt2 = $con2->prepare($sqlLog);
                $stmt2->bindValue(1,$e->getMessage(),PDO::PARAM_STR);
                $stmt2->bindValue(2,$stmt->queryString,PDO::PARAM_STR);
                $stmt2->bindValue(3,$e->getTraceAsString(),PDO::PARAM_STR);
                $stmt2->execute();
                $idError = $con2->lastInsertId();
                $con2 = null;
                $message = "Error en base de datos. Codigo: " . $idError;
                throw new Exception($message);
            }
            catch(Exception $e){
                throw new Exception($e->getMessage());
            }
        }

        //Added by JGRG 2017/02/06
        protected function buildInsertArray($object)
        {
            //Convertir objeto a un arreglo
            $array = json_decode(json_encode($object), true);
            //Obtener valor de llave primaria
            $this->_idVal = $array[$this->_pk_name];
            //Eliminar llave primaria del arreglo ya que esta no se inserta ni se actualiza
            unset($array[$this->_pk_name]);
            return $array;
        }
        //Generic CRUD Methods --------------------------------------------------
        public function _insert($object)
        {
            $arrayData = $this->buildInsertArray($object);
            return $this->GenericInsert($arrayData);
        }
        public function _update($object)
        {
            $arrayData = $this->buildInsertArray($object);
            if ($this->_idVal != -1)
            {
                return $this->GenericUpdate($arrayData, $this->_pk_name . ' = ?');
            }else{
                return null;
            }

        }
        public function _getById($id)
        {
            if (is_numeric($id)) {
                $row = null;
                $row = $this->SelectById($this->_table_name,$this->_pk_name, $id);
                $class = $this->_class_name;
                $newData = new $class($row);
                return $newData;
            }else {
                return null;
            }
            
        }
        public function _getAll()
        {
            $res = null;
            $res = $this->GenericSelect($this->_table_name);
            $class = $this->_class_name;
            foreach ($res as $row) {
                $this->_object_list[] = new $class($row);
            }
            return $this->_object_list;
        }
        public function _delete($id){
            if (is_numeric($id)) {
                $res = null;
                $array = array($id);
                $res = $this->GenericDelete($this->_table_name,$this->_pk_name."= ?",$array);
                return $res;
            }else {
                return null;
            }
        }
	}
 ?>